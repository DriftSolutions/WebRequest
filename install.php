<?php
	error_reporting(E_ALL & ~E_NOTICE);
	if (version_compare(PHP_VERSION, '8.1.0') >= 0) {
		mysqli_report(MYSQLI_REPORT_OFF);
	}
	session_start();
	print "<html>\n";
	print "<head>\n";
	print "\t<title>Web Request Installer</title>\n";
	print "</head>\n";
	print "<body bgcolor=\"#333333\" text=\"#FFFFFF\" link=\"#2285ab\" alink=\"#3399FF\" vlink=\"#2285ab\">\n";

	function install_line_ok($desc,$status) {
		print "<tr><td>&nbsp;".$desc."&nbsp;</td><td><font color=\"#009000\">&nbsp;".$status."&nbsp;</font></td></tr>\n";
	}
	function install_line_warn($desc,$status,$ext="") {
		print "<tr><td>&nbsp;".$desc."&nbsp;</td><td><font color=\"#FFFF00\">&nbsp;".$status."&nbsp;</font></td></tr>\n";
		if (strlen($ext)) {
			print "<tr><td colspan=2>".$ext."</td></tr>\n";
		}
	}
	function install_line_err($desc,$status,$ext="") {
		print "<tr><td>&nbsp;".$desc."&nbsp;</td><td><font color=\"#FF0000\">&nbsp;".$status."&nbsp;</font></td></tr>\n";
		if (strlen($ext)) {
			print "<tr><td colspan=2>".$ext."</td></tr>\n";
		}
	}
	function install_line_question($desc,$code) {
		print "<tr><td>&nbsp;".$desc."&nbsp;</td><td>&nbsp;".$code."&nbsp;</td></tr>\n";
	}
	function iif($blah,$ret1,$ret2="") {
		if ($blah) { return $ret1; }
		return $ret2;
	}
	function wipe_session() {
		if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), '', time()-86400, '/');
		}
		session_destroy();
	}
	function install_dir_check($dir,$writable) {
		if (is_dir("./".$dir)) {
			install_line_ok("Does ".$dir." exist?", "OK");
		} else {
			if (@mkdir("./".$dir, 0777) == TRUE && is_dir("./".$dir)) {
				install_line_ok("Does ".$dir." exist?", "Created");
			} else {
				install_line_err("Does ".$dir." exist?", "No, could not create","On a Windows server, simply create a new folder an name it ".$dir." in the WebRequest folder<br />\nOn *nix, cd to the folder and type mkdir ".$dir.".");
				return false;
			}
		}
		if ($writable) {
			if (is_writable("./".$dir)) {
				install_line_ok("Is ".$dir." writable?", "OK");
			} else {
				if (@chmod("./".$dir, 0777) == TRUE && is_writable("./".$dir)) {
					install_line_ok("Is ".$dir." writable?", "Changed Permissions");
				} else {
					install_line_err("Is ".$dir." writable?", "No, could not chmod","On a Windows server, I don't know what to tell you.<br />\nOn *nix, cd to the folder and type chmod 777 ".$dir);
					return false;
				}
			}
		}
		return true;
	}
	$themes = array();
	if ($h = @opendir("./themes")) {
		while ($file = readdir($h)) {
			if (strcmp($file,"default") && substr($file,0,1) != "." && is_dir("./themes/".$file)) {
				$themes[] = $file;
			}
		}
		sort($themes);
		closedir($h);
	}
	function install_old_image_check($file, $hash) {
		global $themes;
		foreach ($themes as $t) {
			$ffn = "./themes/".$t."/images/".$file;
			if (is_file($ffn) && hash_file("sha256", $ffn) == $hash) {
				if (@unlink($ffn) == TRUE || (@chmod($ffn, 0777) == TRUE && @unlink($ffn) == TRUE)) {
					install_line_ok("Old template image '".substr($ffn,2)."' detected.", "Deleted");
				} else {
					install_line_warn("Old template image '".substr($ffn,2)."' detected.", "Could not delete");
				}
			}
		}
		return true;
	}
	function is_vector(&$array) {
	  $next = 0;
	  foreach ($array as $k => $v) {
	    if ($k !== $next) {
	      return false;
	    }
	    $next++;
	  }
	  return true;
	}
	function EscapeForConfig($str) {
		return str_replace(array("\\","\"","\'"),array("\\\\","\\\"","\\\'"),$str);
	}
	function CheckDBTable($table, $sql) {
		global $link;
		print "Checking for existance of '".$table."': ";
		$res = @mysqli_query($link, "SELECT COUNT(*) FROM `".$table."`");
		if (!$res) {//table does not exist...
			@mysqli_query($link, $sql);
 			$res = @mysqli_query($link, "SELECT COUNT(*) FROM `".$table."`");
 			if ($res) {//table exists now...
 				print "<font color=\"green\">Created</font><br />";
 				return true;
 			} else {
 				print "<font color=\"red\">ERROR! Number: ".mysqli_errno($link)." -> ".mysqli_error($link)."</font><br />";
 				print "Please check your settings and try again...";
 				return false;
 			}
		} else {
			print "OK<br />";
			return true;
		}
	}
	function CheckDBTableField($table, $field, $sql) {
		global $link;
		print "Checking for existance of '".$table.".".$field."': ";
		$res = @mysqli_query($link, "SHOW COLUMNS IN `".$table."` LIKE \"".$field."\"");
		if (@mysqli_num_rows($res) == 0) { //field does not exist...
			@mysqli_query($link, "ALTER TABLE `".$table."` ADD ".$sql);
 			$res = @mysqli_query($link, "SELECT COUNT(*) FROM `".$table."`");
 			if ($res) {//field added
 				print "<font color=\"green\">Created</font><br />";
 				return true;
 			} else {
 				print "<font color=\"red\">ERROR! Number: ".mysqli_errno($link)." -> ".mysqli_error($link)."</font><br />";
 				print "Please check your settings and try again...";
 				return false;
 			}
		} else {
			print "OK<br />";
			return true;
		}
	}

	$step = isset($_REQUEST['step']) ? $_REQUEST['step'] : 1;
	$link = null;

	switch ($step) {
		default:
		case "1":
			foreach($_SESSION as $key => $val) {
				unset($_SESSION[$key]);
			}
			print "Welcome to the WebRequest Installer...<br />\n";
			print "Now, let's see what the situation is like here...<br /><br />\n";
			print "<table bgcolor=\"#444444\" border=1>\n";
			while (1) {
				if (!function_exists('mysqli_errno')) {
					install_line_err("Is MySQLi extension loaded?", "PHP MySQLi extension missing!");
					break;
				}

				if (is_file("./config.inc.php")) {
					install_line_ok("Does config.inc.php exist?", "OK");
					if (is_file("./config.inc.php")) {
						if (isset($config)) {
							unset($config);
						}
						@include("./config.inc.php");
						if (isset($config)) {
							foreach($config as $key => $val) {
								$_SESSION[$key] = $val;
							}
							install_line_ok("Imported existing configuration values...", "OK");
						}
					}
				} else {
					if (@touch("./config.inc.php")) {
						install_line_ok("Does config.inc.php exist?", "Created");
					} else {
						install_line_err("Does config.inc.php exist?", "No, could not create","On a Windows server, simply create an empty text file and rename it to config.inc.php in the WebRequest folder<br />\nOn *nix, cd to the folder and type touch config.inc.php. While you are there, you should also do a chmod 777 config.inc.php for the next step in this installer.");
						break;
					}
				}
				if (is_writable("./config.inc.php")) {
					install_line_ok("Is config.inc.php writable?", "OK");
				} else {
					if (@chmod("./config.inc.php", 0777)) {
						install_line_ok("Is config.inc.php writable?", "Changed Permissions");
					} else {
						install_line_err("Is config.inc.php writable?", "No, could not chmod","On a Windows server, I don't know what to tell you.<br />\nOn *nix, cd to the folder and type chmod 777 config.inc.php");
						break;
					}
				}
				if (!install_dir_check("templates_c", true)) { break; }
				if (!install_dir_check("configs", true)) { break; }
				if (!install_dir_check("cache", true)) { break; }
				if (!install_dir_check("uploads", true)) { break; }
				if (!install_old_image_check("request.png", "bc59a1aa127e6a3ffcea65b2c51c06d3a2cf32c09724f049c94c9cc581df4cfc")) { break; }
				if (!install_old_image_check("info.png", "ff9c48d8c2d063932c7aadd5e15ddfdc76b7111bf0715f3a192bba26df2c531c")) { break; }
				if (!install_old_image_check("star.png", "9d3d2d2933fa0190f4ded95fabb5bde04bd1bbb0f040a8de93aeb0deda699b73")) { break; }
				if (!install_old_image_check("remrate.png", "d04ecfc93ff86c44f6fc39e35945e3d8a7648ba8fcd97a2635920df2e88893b3")) { break; }
				if (!install_old_image_check("disstar.png", "907d0e05e49018c97c198b9b0369cc2691d02d866ef4bc51337742d2a312c6d5")) { break; }
				if (!install_old_image_check("onair.png", "1d00e36e66af00b1e59e1a6ded6ebb9e08f55cba53f37e8175be6ec12028f75a")) { break; }
				break;
			}
			print "</table><br />\n";
			print "Continue to the <a href=\"install.php?step=2\">next step</a>.";
			break;

		case "2":
			print "Step 2 - Collecting information<br />\n";
			print "Tell me a little about your setup...<br /><br />\n";
			print "<form action=\"install.php\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"3\">";
			print "<table bgcolor=\"#444444\" border=1>\n";

			//set some defaults if needed

			if (!$_SESSION['base_path']) { $_SESSION['base_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/";	}
			if (!$_SESSION['base_url']) { $_SESSION['base_url'] = "http://".iif($_SERVER["SERVER_NAME"],$_SERVER["SERVER_NAME"],$_SERVER["SERVER_ADDR"]).dirname($_SERVER["PHP_SELF"])."/";	}
			if (!$_SESSION['db_host']) { $_SESSION['db_host'] = "localhost"; }
			if (!$_SESSION['db_port']) { $_SESSION['db_port'] = 3306; }
			if (!$_SESSION['db_user']) { $_SESSION['db_user'] = "root";	}
			if (!$_SESSION['db_database']) { $_SESSION['db_database'] = "ShoutIRC";	}
			if (!$_SESSION['db_table']) { $_SESSION['db_table'] = "AutoDJ";	}
			if (!isset($_SESSION['db_tabprefix'])) { $_SESSION['db_tabprefix'] = "adj_";	}
			if (!$_SESSION['ircbot_host']) { $_SESSION['ircbot_host'] = "localhost"; }
			if (!$_SESSION['ircbot_port']) { $_SESSION['ircbot_port'] = 10000; }
			if (!$_SESSION['ircbot_user']) { $_SESSION['ircbot_user'] = "WebUser";	}
			if (!isset($_SESSION['mintimeperrequest'])) { $_SESSION['mintimeperrequest'] = "60";	}
			if (!isset($_SESSION['getinfo'])) { $_SESSION['getinfo'] = true;	}
			if (!isset($_SESSION['enable_ratings'])) { $_SESSION['enable_ratings'] = true;	}
			if (!isset($_SESSION['allow_rate'])) { $_SESSION['allow_rate'] = true;	}
			if (!isset($_SESSION['allow_dedication'])) { $_SESSION['allow_dedication'] = false;	}
			if (!isset($_SESSION['dnsbl_enable'])) { $_SESSION['dnsbl_enable'] = false;	}
			if (!isset($_SESSION['show_toprequests'])) { $_SESSION['show_toprequests'] = false;	}
			if (!isset($_SESSION['show_toprequesters'])) { $_SESSION['show_toprequesters'] = false;	}
			if (!isset($_SESSION['allow_djprofiles'])) { $_SESSION['allow_djprofiles'] = false;	}
			if (!isset($_SESSION['djs_per_page'])) { $_SESSION['djs_per_page'] = 4;	}
			if (!isset($_SESSION['allow_musicup'])) { $_SESSION['allow_musicup'] = 0;	}
			if (!isset($_SESSION['theme'])) { $_SESSION['theme'] = 'dark_v2';	}

			print "<tr><td colspan=2 align=\"center\">Script Information</td></tr>\n";
			install_line_question("What is your administrative email?<br />(Error notices, etc., will be sent to it)", "<input name=\"admin_email\" size=70 value=\"".$_SESSION['admin_email']."\">");
			install_line_question("What folder is this script installed in?<br />(Make sure you keep the ending slash)", "<input name=\"base_path\" size=70 value=\"".$_SESSION['base_path']."\">");
			install_line_question("What is the URL to the folder this script is installed in?<br />(Make sure you keep the ending slash)", "<input name=\"base_url\" size=70 value=\"".$_SESSION['base_url']."\">");

			print "<tr><td colspan=2 align=\"center\">Database Information</td></tr>\n";
			install_line_question("What is the hostname of your MySQL server?", "<input name=\"db_host\" size=25 value=\"".$_SESSION['db_host']."\">");
			install_line_question("What is the port of your MySQL server?", "<input name=\"db_port\" size=6 value=\"".$_SESSION['db_port']."\">");
			install_line_question("What is the username to log in to your MySQL server?", "<input name=\"db_user\" size=25 value=\"".$_SESSION['db_user']."\">");
			install_line_question("What is the password to log in to your MySQL server?", "<input name=\"db_pass\" size=25 value=\"".$_SESSION['db_pass']."\">");
			install_line_question("What database should I use your MySQL server?<br />(Note: This should be the same database that contains your AutoDJ-generated table!)", "<input name=\"db_database\" size=25 value=\"".$_SESSION['db_database']."\">");
			install_line_question("What table is your AutoDJ set to use?", "<input name=\"db_table\" size=25 value=\"".$_SESSION['db_table']."\">");
			install_line_question("What should I prefix tables generated with this script with?", "<input name=\"db_tabprefix\" size=25 value=\"".$_SESSION['db_tabprefix']."\">");

			print "<tr><td colspan=2 align=\"center\">IRCBot Information</td></tr>\n";
			install_line_question("What is the hostname/IP IRCBot is running on?", "<input name=\"ircbot_host\" size=25 value=\"".$_SESSION['ircbot_host']."\">");
			install_line_question("What is the port IRCBot is listening on?", "<input name=\"ircbot_port\" size=6 value=\"".$_SESSION['ircbot_port']."\">");
			install_line_question("What username should I use to log in to IRCBot?", "<input name=\"ircbot_user\" size=25 value=\"".$_SESSION['ircbot_user']."\">");
			install_line_question("What password should I use to log in to IRCBot?", "<input name=\"ircbot_pass\" size=25 value=\"".$_SESSION['ircbot_pass']."\">");

			print "<tr><td colspan=2 align=\"center\">Script Options</td></tr>\n";
			$code = "<select name=\"theme\">";
			foreach ($themes as $file) {
				if (strcmp($file,"default") && substr($file,0,1) != "." && is_dir("./themes/".$file)) {
					$code .= "<option value=\"".$file."\"".iif($_SESSION['theme'] == $file," SELECTED","").">".$file."</option>";
				}
			}
			$code .= "</select>";
			install_line_question("Which theme should I use?", $code);
			install_line_question("A user should be able to make one request per this many seconds? (0 for no limits)", "<input name=\"mintimeperrequest\" size=6 value=\"".$_SESSION['mintimeperrequest']."\">");
			$code = "<select name=\"getinfo\">";
			if ($_SESSION['getinfo']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Attempt to get band/album pictures/info from ShoutIRC.com?", $code);


			$code = "<select name=\"allow_djprofiles\">";
			if ($_SESSION['allow_djprofiles']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Enable DJ profiles?", $code);
			install_line_question("How many DJs per page should be shown on the DJ profiles page? (0 for no limits)", "<input name=\"djs_per_page\" size=6 value=\"".$_SESSION['djs_per_page']."\">");

			$code = "<select name=\"show_toprequests\">";
			if ($_SESSION['show_toprequests']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Display top 10 requested songs?", $code);

			$code = "<select name=\"show_toprequesters\">";
			if ($_SESSION['show_toprequesters']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Display top 10 requesters?", $code);

			$code = "<select name=\"enable_ratings\">";
			if ($_SESSION['enable_ratings']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Display song ratings?", $code);

			$code = "<select name=\"allow_rate\">";
			if ($_SESSION['allow_rate']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Allow users to rate songs from within the playlist?", $code);

			$code = "<select name=\"allow_dedication\">";
			if ($_SESSION['allow_dedication']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Allow users to enter dedication text (live DJs only)?", $code);

			$code = "<select name=\"dnsbl_enable\">";
			if ($_SESSION['dnsbl_enable']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Do you want to check IPs against DNS blacklists?<br />This will help prevent people using proxies, etc., from sending in requests.", $code);

			$code = "<select name=\"allow_musicup\">";
			if ($_SESSION['allow_musicup']) {
				$code .= "<option value=0>NO</option>";
				$code .= "<option value=1 selected>YES</option>";
			} else {
				$code .= "<option value=0 selected>NO</option>";
				$code .= "<option value=1>YES</option>";
			}
			$code .= "</select>";
			install_line_question("Allow DJs to upload music via the admin panel? (requires +g user flag)", $code);

			print "<tr><td colspan=2 align=\"center\">Submit Information</td></tr>\n";
			install_line_question("", "<input type=\"submit\" value=\"Continue to the next step...\">");

			print "</table>\n";
			//print "Continue to the <a href=\"install.php?step=3\">next step</a>.";
			break;

		case "3":
			print "Step 3 - Confirm your information<br />\n";
			print "Make sure this all looks OK to you, if any of it is incorrect, please press Back in your browser and correct it.<br /><br />\n";
			print "<form action=\"install.php\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"4\">";
			print "<table bgcolor=\"#444444\" border=1>\n";

			foreach ($_POST as $key => $val) {
				if ($key != "step") {
					$_SESSION[$key] = $val;
				}
			}

			print "<tr><td colspan=2 align=\"center\">Script Information</td></tr>\n";
			install_line_question("What is your administrative email?", $_SESSION['admin_email']);
			install_line_question("What folder is this script installed in?<br />(Make sure you keep the ending slash)", $_SESSION['base_path']);
			install_line_question("What is the URL to the folder this script is installed in?<br />(Make sure you keep the ending slash)", $_SESSION['base_url']);

			print "<tr><td colspan=2 align=\"center\">Database Information</td></tr>\n";
			install_line_question("What is the hostname of your MySQL server?", $_SESSION['db_host']);
			install_line_question("What is the port of your MySQL server?", $_SESSION['db_port']);
			install_line_question("What is the username to log in to your MySQL server?", $_SESSION['db_user']);
			install_line_question("What is the password to log in to your MySQL server?", $_SESSION['db_pass']);
			install_line_question("What database should I use your MySQL server?<br />(Note: This should be the same database that contains your AutoDJ-generated table!)", $_SESSION['db_database']);
			install_line_question("What table is your AutoDJ set to use?", $_SESSION['db_table']);
			install_line_question("What should I prefix tables generated with this script with?", $_SESSION['db_tabprefix']);

			print "<tr><td colspan=2 align=\"center\">IRCBot Information</td></tr>\n";
			install_line_question("What is the hostname/IP IRCBot is running on?", $_SESSION['ircbot_host']);
			install_line_question("What is the port IRCBot is listening on?", $_SESSION['ircbot_port']);
			install_line_question("What username should I use to log in to IRCBot?", $_SESSION['ircbot_user']);
			install_line_question("What password should I use to log in to IRCBot?", $_SESSION['ircbot_pass']);

			print "<tr><td colspan=2 align=\"center\">Script Options</td></tr>\n";
			install_line_question("Which theme should I use?", $_SESSION['theme']);
			install_line_question("A user should be able to make one request per this many seconds? (0 for no limits)", $_SESSION['mintimeperrequest']);
			install_line_question("Attempt to get band/album pictures/info from ShoutIRC.com?", iif($_SESSION['getinfo'],"YES","NO"));
			install_line_question("Enable DJ profiles?", iif($_SESSION['allow_djprofiles'],"YES","NO"));
			install_line_question("Display top 10 requested songs?", iif($_SESSION['show_toprequests'],"YES","NO"));
			install_line_question("Display top 10 requesters?", iif($_SESSION['show_toprequesters'],"YES","NO"));
			install_line_question("Display song ratings?", iif($_SESSION['enable_ratings'],"YES","NO"));
			install_line_question("Allow users to rate songs from within the playlist?", iif($_SESSION['allow_rate'],"YES","NO"));
			install_line_question("Allow users to enter dedication text (live DJs only)?", iif($_SESSION['allow_dedication'],"YES","NO"));
			install_line_question("Do you want to check IPs against DNS blacklists?<br />This will help prevent people using proxies, etc., from sending in requests.", iif($_SESSION['dnsbl_enable'],"YES","NO"));
			install_line_question("Allow DJs to upload music via the admin panel? (requires +g user flag)", iif($_SESSION['allow_musicup'],"YES","NO"));

			print "<tr><td colspan=2 align=\"center\">Confirm Information</td></tr>\n";
			install_line_question("", "<input type=\"submit\" value=\"All information looks correct, continue to the next step...\">");

			print "</table>\n";
			//print "Continue to the <a href=\"install.php?step=3\">next step</a>.";
			break;

		case "4":
			print "Step 4 - Final testing and set up...<br /><br />\n";
			print "Attempting to connect to MySQL: ";
			$link = new mysqli($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pass'], '', $_SESSION['db_port']);
 			if (mysqli_connect_error()) {
 				print "Error connecting to MySQL server! Error Number: ".mysqli_connect_errno()." -> ".mysqli_connect_error()."<br />";
 				print "Please check your settings and try again...";
 				break;
 			} else {
 				print "OK<br />";
 			}

 			print "Attempting to set database '".$_SESSION['db_database']."' active: ";
 			if (!@mysqli_select_db($link, $_SESSION['db_database'])) {
 				print "ERROR! Number: ".mysqli_errno($link)." -> ".mysqli_error($link)."<br />";
 				print "Attempting to create database '".$_SESSION['db_database']."'...<br />";
 				@mysqli_query($link, "CREATE DATABASE IF NOT EXISTS ".$_SESSION['db_database']);
	 			print "Attempting to set database '".$_SESSION['db_database']."' active: ";
	 			if (!@mysqli_select_db($link, $_SESSION['db_database'])) {
	 				print "ERROR! Number: ".mysqli_errno($link)." -> ".mysqli_error($link)."<br />";
	 				print "Please check your settings and try again...";
	 				break;
	 			} else {
	 				print "OK<br />";
	 			}
 			} else {
 				print "OK<br />";
 			}

 			$table = $_SESSION['db_tabprefix']."Requests";
 			if (!CheckDBTable($table, "CREATE TABLE IF NOT EXISTS `".$table."` (`ID` int(11) NOT NULL auto_increment, `IP` varchar(255) NOT NULL default '', `Expire` int(11) NOT NULL default '0', PRIMARY KEY  (`ID`));")) { break; }
 			$table = $_SESSION['db_tabprefix']."Users";
 			if (!CheckDBTable($table, "CREATE TABLE IF NOT EXISTS `".$table."` (`ID` int( 11 ) NOT NULL AUTO_INCREMENT, `Username` varchar( 255 ) NOT NULL DEFAULT '', `Pass` varchar( 255 ) NOT NULL DEFAULT '', `Status` tinyint( 4 ) NOT NULL DEFAULT 0, `Flags` INT UNSIGNED NOT NULL DEFAULT 0, `LastSeen` int( 11 ) NOT NULL DEFAULT 0, `TimeStamp` int( 11 ) NOT NULL DEFAULT 0, `Theme` varchar( 255 ) NOT NULL DEFAULT '', PRIMARY KEY ( `ID` ) , UNIQUE KEY `Username` ( `Username` ));")) { break; }
 			if (!CheckDBTableField($table, "Flags", "`Flags` INT UNSIGNED NOT NULL DEFAULT 0")) { break; }
 			$table = $_SESSION['db_tabprefix']."Config";
 			if (!CheckDBTable($table, "CREATE TABLE IF NOT EXISTS `".$table."` (`ID` int( 11 ) NOT NULL AUTO_INCREMENT, `UserID` int( 11 ) DEFAULT 0, `Name` varchar( 255 ) NOT NULL, `Value` varchar( 255 ) NOT NULL DEFAULT '' , PRIMARY KEY ( `ID` ), UNIQUE KEY `UserID` ( `UserID` , `Name` ));")) { break; }
 			$table = $_SESSION['db_tabprefix']."DNSBL";
 			if (!CheckDBTable($table, "CREATE TABLE IF NOT EXISTS `".$table."` (`ID` int( 11 ) NOT NULL AUTO_INCREMENT, `TimeStamp` int( 11 ) NOT NULL DEFAULT 0, `IP` varchar( 32 ) NOT NULL DEFAULT '', `Result` tinyint NOT NULL DEFAULT 0, PRIMARY KEY ( `ID` ));")) { break; }
 			$table = $_SESSION['db_tabprefix']."DJ";
 			if (!CheckDBTable($table, "CREATE TABLE IF NOT EXISTS `".$table."` (`ID` int( 11 ) NOT NULL AUTO_INCREMENT, `Status` TINYINT DEFAULT 0, `IsCurDJ` TINYINT DEFAULT 0, `Created` int( 11 ) NOT NULL DEFAULT 0, `TimeStamp` int( 11 ) NOT NULL DEFAULT 0, `Username` VARCHAR(255) NOT NULL DEFAULT '', `DispName` VARCHAR(255) NOT NULL DEFAULT '', `Email` VARCHAR(255) NOT NULL DEFAULT '', `Profile` TEXT, `Tagline` VARCHAR(255) DEFAULT '', `Picture` VARCHAR(255) DEFAULT '', `PublicPlaylist` TINYINT DEFAULT 0, PRIMARY KEY ( `ID` ));")) { break; }
 			if (!CheckDBTableField($table, "IsCurDJ", "`IsCurDJ` TINYINT DEFAULT 0")) { break; }

 			print "Opening config.inc.php for write access: ";
 			$fp = @fopen("./config.inc.php", "wb");
 			if ($fp === FALSE) {
 				print "Error opening config.inc.php!<br />";
 				print "Please check your settings and try again...";
 				break;
 			} else {
 				print "OK<br />";
 			}

 			print "Writing configuration...<br />\n";
			fwrite($fp, "<?php\r\nif (!defined('CONFIG_INC_PHP')) {\r\n");
			fwrite($fp, "\tdefine('CONFIG_INC_PHP','INCLUDED');\r\n");
			fwrite($fp, "\tunset(\$config);\r\n\r\n");

			ksort($_SESSION);
			foreach($_SESSION as $key => $val) {
				if (is_array($val)) {
					if (is_vector($val)) {
						fwrite($fp, "\t\$config['".$key."'] = array(");
						$tmp="";
						foreach($val as $val2) {
							if (strlen($tmp)) {
								$tmp .= ", ";
							}
							$tmp .= "\"".EscapeForConfig($val2)."\"";
						}
						fwrite($fp, $tmp.");\r\n");
					} else {
						fwrite($fp, "\t\$config['".$key."'] = array(");
						$tmp="";
						foreach($val as $key2 => $val2) {
							if (strlen($tmp)) {
								$tmp .= ", ";
							}
							$tmp .= "\"".$key2."\" => \"".EscapeForConfig($val2)."\"";
						}
						fwrite($fp, $tmp.");\r\n");
					}
				} elseif (is_numeric($val)) {
					fwrite($fp, "\t\$config['".$key."'] = ".$val.";\r\n");
				} else {
					fwrite($fp, "\t\$config['".$key."'] = \"".EscapeForConfig($val)."\";\r\n");
					if ($key == "base_path") {
						fwrite($fp, "\t\$config['upload_path'] = \"".EscapeForConfig($val."uploads".DIRECTORY_SEPARATOR)."\";\r\n");
					} else if ($key == "base_url") {
						fwrite($fp, "\t\$config['upload_url'] = \"".EscapeForConfig($val)."uploads/\";\r\n");
					}
				}
			}

			fwrite($fp, "\r\n}// !defined()\r\n?>");
 			print "Closing config.inc.php<br />";
 			print "All done, after you delete install.php you should be <a href=\"index.php\">ready to go!</a>";
			break;
	}

	print "</body>\n</html>";
	//print "Now you can continue on to <a href=\"index.php\">index.php</a>";
?>