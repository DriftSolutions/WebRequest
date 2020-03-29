<?php
	@require("./core.inc.php");
	
	if (!$config['allow_djprofiles']) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("notenabled.tpl");
  	exit;		
	}

	if (!isset($_REQUEST['dj']) || !is_numeric($_REQUEST['dj'])) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("notenabled.tpl");
  	exit;		
	}

  $query = "SELECT * FROM `".DJ_TABLE."` WHERE ID=\"".$db->escape($_REQUEST['dj'])."\" AND Status=1";
	$res = $db->query($query);
	if ($db->num_rows($res) > 0) {
		$arr = $db->fetch_assoc($res);
	 	if ($arr['Picture']) {
	 		$arr['PictureURL'] = $config['upload_url'].$arr['Picture'];
	 	}
	  $smarty->assign("dj", $arr);
	  $smarty->assign("db_queries",$db->GetQueriesCount());	  
	  $smarty->display("dj.tpl");
	} else {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("notenabled.tpl");
	}
?>