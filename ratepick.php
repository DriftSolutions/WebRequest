<?php
	@require("./core.inc.php");
	
	if (empty($config['db_table']) || !$config['cur_dj_selected'] || !$config['allow_rate'] || !$config['enable_ratings']) {
		die("You can only rate songs from the active DJ!");
	}
	
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	  $res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE ID=\"".$db->escape($_GET['id'])."\"");
	  if ($db->num_rows($res)) {
	  	$song = $db->fetch_assoc($res);	
			$smarty->assign("song", $song);
		  $smarty->assign("db_queries",$db->GetQueriesCount());
		  $smarty->display("ratepick.tpl");    
		} else {
			die("Invalid Song ID!");
		}  		
	} else {
		die("Invalid Song ID, or no ID passed!");
	}
?>