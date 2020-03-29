<?php
	require("./core.inc.php");

	$is_js = (isset($_REQUEST['js']) && $_REQUEST['js'] == 1) ? true:false;
	if ($is_js) {
		header('Content-Type: text/javascript');
	}
	$rate_error = true;
	function this_die($msg) {
		global $is_js,$smarty,$db,$rate_error;
		if ($is_js) {
			print "alert(".json_encode((string)$msg).");\n";
			if (!$rate_error) {
				print "location.reload();\n";
			}
			exit;
		} else {
			$smarty->assign("msg", $msg);
		  $smarty->assign("db_queries",$db->GetQueriesCount());
		  $smarty->display("rate.tpl");    
			exit;
		}		
	}
	
	if (empty($config['db_table']) || !$config['cur_dj_selected'] || !$config['allow_rate'] || !$config['enable_ratings']) {
		this_die("You can only rate songs from the active DJ!");
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
	
	$rating = isset($_REQUEST['rating']) ? $_REQUEST['rating']:'x';
	if (!is_numeric($rating)) {
		this_die("Invalid rating #!");
	}
	$rating = intval($rating);
	 
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
		$msg = RateSong($fn,$_SERVER['REMOTE_ADDR'],$rating);
		if (stristr($msg, "Thank you for your song rating") !== FALSE) {
			$rate_error = false;
			$smarty->assign("rate_error", 0);
		} else {
			if (stristr($msg, "unknown command code") !== FALSE) {
				$msg = "Your IRCBot is too old to understand this command, time to update it!";
			}
			$smarty->assign("rate_error", 1);
		}
		this_die($msg);
	} else {
		this_die("You are banned from the WebRequest system!");
	}
?>