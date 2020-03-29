<?php
	require("./core.inc.php"); 
	
	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		print "Invalid ID, or no ID passed!";
		exit;
	}
  
  $res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE ID=\"".$db->escape($_GET['id'])."\" AND Seen=1");
  if ($arr = $db->fetch_assoc($res)) {
	  $smarty->assign("song",$arr);
	  $smarty->assign("title","Song Information");
	  $smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display("songinfo.tpl");    
  } else {
		print "Invalid Song ID!";
  }
  $db->free_result($res);
?>