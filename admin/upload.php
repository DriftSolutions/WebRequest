<?php
	require("./admincore.inc.php");
	
	$have_zlib = function_exists('gzuncompress') ? true:false;
	$have_simplexml = function_exists('simplexml_load_file') ? true:false;
	
	function let_to_num($v){ //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
	    $l = substr($v, -1);
	    $ret = substr($v, 0, -1);
	    switch(strtoupper($l)){
	    case 'P':
	        $ret *= 1024;
	    case 'T':
	        $ret *= 1024;
	    case 'G':
	        $ret *= 1024;
	    case 'M':
	        $ret *= 1024;
	    case 'K':
	        $ret *= 1024;
	        break;
	    }
	    return $ret;
	}
	
	function GetTableCreationSQL($nick) {
		return "CREATE TABLE `".$nick."` (`ID` int( 10 ) unsigned NOT NULL, `Path` varchar( 255 ) NOT NULL , `FN` varchar( 255 ) NOT NULL , `mTime` int( 11 ) NOT NULL , `LastPlayed` int( 11 ) NOT NULL , `PlayCount` int( 11 ) NOT NULL , `LastReq` int( 11 ) NOT NULL , `ReqCount` int( 11 ) NOT NULL , `Title` varchar( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , `Artist` varchar( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , `Album` varchar( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , `Genre` varchar( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , `SongLen` int( 11 ) NOT NULL , `Seen` tinyint( 4 ) NOT NULL , `Rating` tinyint( 4 ) NOT NULL , `RatingVotes` int( 11 ) NOT NULL , `IsPlaying` tinyint( 4 ) NOT NULL , PRIMARY KEY ( `ID` )) ENGINE = MYISAM DEFAULT CHARSET = latin1;";
	}

	if ($_FILES['smdfile']['name']) {		
	  $smarty->display( "admin_upload_exec_header.tpl");	  
	  function DispLine($msg) {
	  	global $smarty;
	  	$smarty->assign("msg", $msg);
			$smarty->display("admin_upload_exec_line.tpl");
	  }
	  
	  if ($_FILES['smdfile']['tmp_name'] && is_uploaded_file($_FILES['smdfile']['tmp_name'])) {
	  	set_time_limit(0);
	  	$data = @file_get_contents($_FILES['smdfile']['tmp_name']);
	  	if ($data && strlen($data)) {
	  		$error = false;
	  		if (substr($data, 0, 4) == "SMDZ") {
	  			DispLine("Detecting file type: Compressed SMD");
	  			if ($have_zlib) {	  				
		  			$compLen = unpack("V", substr($data, 4, 4));
		  			$compLen = $compLen[1];
		  			$fullLen = unpack("V", substr($data, 8, 4));
		  			$fullLen = $fullLen[1];
		  			$data = substr($data, 16);//remove the 16 byte SMDZ header
						$data = @gzuncompress($data, $fullLen);
						if ($data !== FALSE) {
							DispLine("Decompressed successfully! ($compLen => $fullLen)");
						} else {
		  				$error = true;
		  				DispLine("Error decompressing your playlist! Make sure the file has not been tampered with...");	
						}
	  			} else {
	  				$error = true;
	  				DispLine("Error: zlib is not supported on this system. Contact your system administrator and tell them to load the PHP zlib extension!");	
	  			}	  			
	  		} else {
	  			DispLine("Detecting file type: Uncompressed SMD");
	  		}
	  		if (!$error) {
	  			$table = $db->escape($userinfo['Username']);
	  			$query = "SHOW TABLES LIKE '".$table."'";
	  			$res = $db->query($query);
	  			if ($db->num_rows($res) == 0) {
	  				DispLine("Creating your playlist table...");
	  				if ($db->query(GetTableCreationSQL($table)) === FALSE) {
	  					$error = true;
	  					DispLine("Error creating table!");
	  				}
	  			}
	  			if (!$error) {
	  				//DispLine("\"".$data."\"");
						try {
						  $xml = @new SimpleXMLElement($data);
						} catch (Exception $e) {
						  DispLine("Error parsing XML! (".$e->getMessage().")");
						  if ($config['Debug']) {
						  	var_dump($e);
						  }
						  $error = true;
						}
						if (!$error) {
							$ver = $xml['Version'];
							if ($ver == 1) {
								DispLine("SMD Version: $ver");
								DispLine("Songs listed: ".$xml->Info['NumSongs']." for a duration of ".duration2($xml->Info['TotalLength']));
								DispLine("Processing songs... (this may take a while)");
								flush();

  							$query = "UPDATE `".$table."` SET Seen=0";
				  			$res = $db->query($query);
								
								foreach($xml->Songs->Song as $song) {
					  			$insert = array(
					  				"ID" => $song['ID'],
					  				"SongLen" => $song['SongLen'],
					  				"mTime" => $song['mTime'],
					  				"Path" => $song['Path'],
					  				"FN" => $song['FN'],
					  				"Artist" => $song['Artist'],
					  				"Title" => $song['Title'],
					  				"Album" => $song['Album'],
					  				"Genre" => $song['Genre'],
					  				"Seen" => 1
					  			);					  			
					  			
	  							$query = "SELECT ID FROM `".$table."` WHERE ID=".$song['ID'];
					  			$res = $db->query($query);
						  		if ($db->num_rows($res) > 0) {
						  			$db->update($table, $insert);
						  		} else {
						  			$db->insert($table, $insert);
						  		}						  		
								}
								
						  	DispLine("Processing complete!");																					
							} else {
								DispLine("Error: Unknown/unsupport SMD version!");
							}
							//var_dump($xml);
						}
	  			}
	  		}
	  	} else {
	  		DispLine("Error uploading file: error reading file");
	  	}
	  } else {
	  	DispLine("Error uploading file: ".$_FILES['smdfile']['error']);
	  }
	  
	 	$smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display( "admin_upload_exec_footer.tpl");
	} else {
		$table = $db->escape($userinfo['Username']);
		if ($_GET['action'] == "wipe") {
			$query = "DROP TABLE `".$table."`";
			$db->query($query);
		}
		
		$smarty->assign("have_zlib", $have_zlib);
		if ($have_simplexml) {
			$smarty->assign('missingsDeps', '');
		} else {
			$smarty->assign('missingsDeps', 'Error: SimpleXML is not enabled. Contact your system administrator for help');
		}
		
		$query = "SHOW TABLES LIKE '".$table."'";
		$res = $db->query($query);
		$smarty->assign('have_djtable', $db->num_rows($res) > 0 ? 1:0);
		if ($db->num_rows($res) > 0) {
			$query = "SELECT COUNT(*) AS Count FROM `".$table."` WHERE Seen=1";
			$res = $db->query($query);			
			$arr = $db->fetch_assoc($res);
			$smarty->assign('songcount', $arr['Count']);			
		}
			
		$max_upload_size = min(let_to_num(ini_get('post_max_size')), let_to_num(ini_get('upload_max_filesize')));
		$fu = ini_get('file_uploads');
		if (!strcasecmp($fu, "on") || $fu == "1") {
			$smarty->assign("max_upload_size_bytes", $max_upload_size." bytes");
			$smarty->assign("max_upload_size_mb", round($max_upload_size/(1024*1024), 2)."MB");	
		} else {
			$smarty->assign("max_upload_size_bytes", "File uploads disabled, contact your administrator for help");
			$smarty->assign("max_upload_size_mb", "File uploads disabled, contact your administrator for help");	
		}
		
		$ping_url_normal = $config['base_url']."admin/ping.php";
		$smarty->assign("ping_url_normal",$ping_url_normal);
		if (!strncasecmp($config['base_url'], "https://", 8)) {
			$ping_url_sam = "https://".$userinfo['Username'].":".$userinfo['Pass']."@".substr($config['base_url'], 8)."admin/ping.php";
		} else if (!strncasecmp($config['base_url'], "http://", 7)) {
			$ping_url_sam = "http://".$userinfo['Username'].":".$userinfo['Pass']."@".substr($config['base_url'], 7)."admin/ping.php";
		} else {
			$ping_url_sam = "Unknown URL Type!";
		}		
		$smarty->assign("ping_url_sam",$ping_url_sam);
		
	 	$smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display( "admin_upload_index.tpl");
	}

?>