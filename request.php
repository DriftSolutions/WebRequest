<?php
	@require("./core.inc.php"); 
	
	$is_js = (isset($_REQUEST['js']) && $_REQUEST['js'] == 1) ? true:false;
	if ($is_js) {
		header('Content-Type: text/javascript');
	}
	function this_die($msg) {
		global $is_js,$smarty,$db;
		if ($is_js) {
			print "alert(".json_encode((string)$msg).");\n";
			exit;
		} else {
			$smarty->assign("msg", $msg);
		  $smarty->assign("db_queries",$db->GetQueriesCount());
		  $smarty->display("request.tpl");    
			exit;
		}		
	}
	
	if (empty($config['db_table']) || !$config['cur_dj_selected']) {
		this_die("You can only request from the active DJ!");
	}
	
	if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		$res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE ID=\"".$db->escape($_REQUEST['id'])."\"");
		if ($arr = $db->fetch_assoc($res)) {
  		$fn = $arr['FN'];
		} else {
			this_die("Invalid Song ID!");
		}  		
	} else if (!empty($_REQUEST['fn'])) {	
		$fn = $_REQUEST['fn'];
	} else {
		this_die("Invalid ID, or no ID passed!");
	}
	 
	$query = "DELETE FROM `".REQ_TABLE."` WHERE Expire < ".time();
	$db->query($query);
	
	$wl = false;
	if (isset($config['whitelist'])) {		
		foreach($config['whitelist'] as $ip) {
			if (!strncasecmp($ip,$_SERVER['REMOTE_ADDR'],strlen($ip))) {
				$wl = true;
				break;
			}
		}
	}
	
	$bl = false;
	if (isset($config['blacklist'])) {		
		foreach($config['blacklist'] as $ip) {
			if (!strncasecmp($ip,$_SERVER['REMOTE_ADDR'],strlen($ip))) {
				$bl = true;
				break;
			}
		}
	}	
	
	if (!$bl) {
		$query = "SELECT * FROM `".REQ_TABLE."` WHERE IP=\"".$db->escape($_SERVER['REMOTE_ADDR'])."\"";
		$res = $db->query($query);
		if ($db->num_rows($res) == 0 || $wl) {
			$dedication = isset($_POST['dedication']) ? $_POST['dedication']:'';
			if (!$config['allow_dedication'] || isset($_POST['dedication'])) {
				$req = $fn;
				if (!empty($dedication)) {
					$req .= " - Dedication text: ".$dedication;
				}
				$msg = RequestSong($req);
				if (stristr($msg, "thank you")) {
					$insert = array(
						"IP" => $_SERVER['REMOTE_ADDR'],
						"Expire" => (time()+$config['mintimeperrequest'])
					);
					$db->insert(REQ_TABLE, $insert);
					
					if ($config['real_dj'] && isset($_GET['id']) && is_numeric($_GET['id'])) {
						//since this playlist isn't AutoDJ's, we have to keep the LastReq and ReqCount up to date ourselves
						$query = "UPDATE `".$config['db_table']."` SET LastReq=".time().", ReqCount=ReqCount+1 WHERE ID=\"".$db->escape($_GET['id'])."\"";
						$db->query($query);
					}
				}				
			} else {
				$smarty->assign("fn", $fn);
			  $smarty->assign("db_queries",$db->GetQueriesCount());
			  $smarty->display("request_ded.tpl");    				
			  exit;
			}
			this_die($msg);
		} else {
			this_die("You can only request 1 song per ".$config['mintimeperrequest']." seconds.");
		}
	} else {
		this_die("You are banned from the WebRequest system!");
	}
?>