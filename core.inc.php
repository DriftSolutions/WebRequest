<?php
	error_reporting(E_ALL & ~E_NOTICE);
	if (isset($config['Debug']) && $config['Debug']) {
		ini_set("display_errors", "on");
	}

	if (is_file("./install.php")) {
		if (is_file("./config.inc.php")) {
			print "Error: config.inc.php exists, but install.php has not been deleted!<br />";
			print "If you need to (re)configure the Web Request system, click <a href=\"install.php\">here</a>.<br />";
			print "Otherwise, delete install.php to begin using this script normally.";
		} else {
			header("Location: install.php");
			print "To set up the Web Request system, click <a href=\"install.php\">here</a>.";
			//include "./install.php";
		}
		exit;
	}

	require("config.inc.php");
	require('include/remote.client.inc.php');
	require("include/functions.inc.php");
	require("include/db.mysqli.inc.php");
	
	if (!$db->init($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_database'], $config['db_port'])) {
		die("FATAL: Error connecting to database!");
	}

	if ($config['dnsbl_enable']) {
		$ts = time() - 86400;
		$query = "DELETE FROM ".DNSBL_TABLE." WHERE `TimeStamp`<".$ts;
		$res = $db->query($query);
		$query = "SELECT * FROM ".DNSBL_TABLE." WHERE `IP`=\"".$db->escape($_SERVER['REMOTE_ADDR'])."\"";
		$res = $db->query($query);
		if ($arr = $db->fetch_assoc($res)) {
			if ($arr['Result'] == 1) {
				die("Access blocked, your IP ".$_SERVER['REMOTE_ADDR']." is listed in a DNSBL!");
			}
		} else {
			$insert = array("IP" => $_SERVER['REMOTE_ADDR'], "TimeStamp" => time());
			// This code modified from a sample posted on php.net
			$dnsbls = array('http.dnsbl.sorbs.net', 'socks.dnsbl.sorbs.net', 'misc.dnsbl.sorbs.net', 'smtp.dnsbl.sorbs.net', 'web.dnsbl.sorbs.net', 'old.spam.dnsbl.sorbs.net', 'sbl-xbl.spamhaus.org');
			$parts = explode('.', $_SERVER['REMOTE_ADDR']);
			$ip  = implode('.', array_reverse($parts)).'.';
			foreach($dnsbls as $bl) {
				$check = $ip.$bl;
				$res = @gethostbyname($check);
				if ($check != $res && $res != "") {
					$insert['Result'] = 1;
					$db->insert(DNSBL_TABLE, $insert);
					die("Access blocked, your IP ".$_SERVER['REMOTE_ADDR']." is listed in DNSBL ".$bl."!");
				}
			}
			
			$insert['Result'] = 0;
			$db->insert(DNSBL_TABLE, $insert);
		}
	}

	//now that the DB is loaded, load dynamic config variables
	LoadDynamicConfig();	
	
	if (!defined('ADMIN_CORE')) {
		//now, to find out who the active DJ is...
		if (!isset($config['LastCheck']) || !is_numeric($config['LastCheck'])) { $config['LastCheck'] = 0; }
		if ((time() - $config['LastCheck']) >= 60) {		
			SaveConfigValue("LastCheck", time());
			$tmpdj = "";
			$rc = new RemoteClient();
			if ($rc->Connect()) {
				$arr = $rc->SendCommandRecvReply(0x37); // first, we see if AutoDJ is playing
				if (count($arr) && $arr['cmd'] == 0xFE) {
					if (!strcmp($arr['data'], "playing")) {
						$tmpdj = $config['db_table'];
					} else {
						//nope, not AutoDJ. Let's see if a Live DJ is playing...
						$arr = $rc->SendCommandRecvReply(0x12);
						if (count($arr) && $arr['cmd'] == 0xFE) {
							$tmpdj = $arr['data'];
						}
					}
				} else {
					if (stristr($arr['data'], "unknown command code") !== FALSE) {
						$arr['data'] = "Your IRCBot is too old to understand this command, time to update it!";
					}					
					@mail($config['admin_email'], "WebRequest Error", MakeErrorReport("CurDJ Check 2 ".$arr['cmd'], $arr['data']));
				}
				$arr = $rc->SendCommandRecvReply(0x02); // request stream stats
				if (count($arr) && $arr['cmd'] == 0x13) {
					//see the STREAM_INFO struct at http://api.shoutirc.com for details
					SaveConfigValue("StreamTitle", trim(substr($arr['data'], 0, 64)));
					SaveConfigValue("StreamDJ", trim(substr($arr['data'], 64, 64)));
					$data = unpack("Vlisteners/Vpeak/Vmax", substr($arr['data'], 128, 12));
					SaveConfigValue("StreamListeners", $data['listeners']);
					SaveConfigValue("StreamPeak", $data['peak']);
					SaveConfigValue("StreamMax", $data['max']);
					/*
						int32 listeners;
	int32 peak;
	int32 max;
	*/
				}
				$rc->Disconnect();
			} else {
	  		@mail($config['admin_email'], "WebRequest Error", MakeErrorReport("CurDJ Check 1 ".$arr['cmd'], $rc->GetError()));
	  	}
			SaveConfigValue("CurDJ", $tmpdj);
			$query = "UPDATE ".DJ_TABLE." SET IsCurDJ=0 WHERE Username!=\"".$db->escape($tmpdj)."\"";
			$db->query($query);				
			$query = "UPDATE ".DJ_TABLE." SET IsCurDJ=1 WHERE Username=\"".$db->escape($tmpdj)."\"";
			$db->query($query);				
		}
	}
	
	$now = gmdate("D, d M Y H:i:s")." GMT";
	$exp = gmdate("D, d M Y H:i:s",(time() - (60 * 60 * 24)))." GMT";
	header("Date: $now");
	header("Expires: $exp");
	header("Last-Modified: $now");
	header("Content-Type: text/html; charset=UTF-8");
	
	$smarty = new Smarty();
	$smarty->setTemplateDir($config['base_path']."themes/".$config['theme']);
	$smarty->setCompileDir($config['base_path']."templates_c");
	$smarty->setConfigDir($config['base_path']."configs");
	$smarty->setCacheDir($config['base_path']."cache");

 	$smarty->assign("webreq_version", _WEBREQUEST_VERSION);
 	
	$smarty->registerPlugin('function', 'songdisp', 'songdisp');
	$smarty->registerPlugin('function', 'timedisp', 'timedisp');
	$smarty->registerPlugin('function', 'songinfolink', 'songinfolink');
	$smarty->registerPlugin('function', 'requestlink', 'requestlink');
	$smarty->registerPlugin('function', 'str_repeat', 'sm_str_repeat');
	$smarty->registerPlugin('function', 'rating', 'rating');
	$smarty->registerPlugin('function', 'onairimg', 'onairimg');
	$smarty->registerPlugin('function', 'getimage', 'getimage');

	if (!defined('ADMIN_CORE')) {
		$res = $db->query("SHOW TABLES LIKE '".$db->escape($config['CurDJ'])."'");
		if (strlen($config['CurDJ']) && $db->num_rows($res) > 0) {
			if ($config['db_table'] != $config['CurDJ']) {
				$config['real_dj'] = true;
				//only AutoDJ supports ratings
				$config['enable_ratings'] = 0;
				$config['allow_rate'] = 0;
				//and we have to keep track of IsPlaying ourself
				$query = "UPDATE `".$db->escape($config['CurDJ'])."` SET IsPlaying=0 WHERE (LastPlayed+SongLen)<".time();
				$db->query($query);
			} else {
				$config['real_dj'] = false;			
				//AutoDJ doesn't support dedications	
				$config['allow_dedication'] = 0;
			}
			$config['db_table'] = $db->escape($config['CurDJ']);
		} else {
			$config['CurDJ'] = "";
			$config['db_table'] = "";
		}
		
		if ($config['allow_djprofiles'] && !empty($_REQUEST['pl'])) {
			$res = $db->query("SELECT Username FROM ".DJ_TABLE." WHERE SHA1(Username)=SHA1('".$db->escape($_REQUEST['pl'])."') AND PublicPlaylist=1"); 
			if ($db->num_rows($res) > 0) {
				// user exists and has public playlist browsing on
				$res = $db->query("SHOW TABLES LIKE '".$db->escape($_REQUEST['pl'])."'");
				if ($db->num_rows($res) > 0) {
					// the playlist actually exists
					$config['db_table'] = $db->escape($_REQUEST['pl']);
				}
			}
		}
		
		if ($db->escape($config['CurDJ']) == $config['db_table']) {
			$config['cur_dj_selected'] = true;
		} else {
			$config['cur_dj_selected'] = false;
			$config['real_dj'] = false;			
			$config['allow_dedication'] = 0;
			$config['enable_ratings'] = 0;
			$config['allow_rate'] = 0;
		}

	}
	
 	$smarty->assign("config", $config);

	$fn = $config['base_path']."themes/".$config['theme']."/theme.inc.php";
	if (is_file($fn)) {
		include($fn);
	}
