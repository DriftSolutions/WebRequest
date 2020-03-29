<?php
	$alt_bad_login_text = "ERROR: Invalid user/pass";
	require("./admincore.inc.php");
	
	switch ($_GET['action']) {
		case "play":
			if ($_GET['client'] == "sam" && $_GET['fn']) {
				$_GET['id'] = sprintf("%u",CRC32(strtolower(str_replace("'","`",$_GET['fn']))));
			}
			if (isset($_GET['id']) && is_numeric($_GET['id'])) {
				$table = $db->escape($userinfo['Username']);
				$query = "SHOW TABLES LIKE '".$table."'";
				$res = $db->query($query);
				if ($db->num_rows($res) > 0) {
					$query = "UPDATE `".$table."` SET IsPlaying=0 WHERE ID!=\"".$db->escape($_GET['id'])."\"";
					$res = $db->query($query);
					$query = "UPDATE `".$table."` SET LastPlayed=".time().", PlayCount=PlayCount+1, IsPlaying=1 WHERE ID=\"".$db->escape($_GET['id'])."\"";
					$res = $db->query($query);
					print "OK: Ping received!";
				} else {
					print "ERROR: Your playlist doesn't exist ".$userinfo['Username']."!";
				}
			} else {
				print "ERROR: You must pass a song ID!";
			}
			break;
			
		default:
			print "ERROR: Unknown command!";
			break;
	}	

?>