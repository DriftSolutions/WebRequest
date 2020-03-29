<?php
	@require("./core.inc.php");
	
	if (!$config['allow_djprofiles']) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("notenabled.tpl");
  	exit;		
	}

  $query = "SELECT COUNT(*) AS Count FROM `".DJ_TABLE."` WHERE Status=1";
  $res = $db->query($query);
  $arr = $db->fetch_assoc($res);
  $totdjcnt = $arr['Count'];
  $smarty->assign("totdjcnt",$totdjcnt);
  
	if ($totdjcnt == 0) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("notenabled.tpl");
  	exit;		
	}  
  
  $per_page = $config['djs_per_page'];
  if ($per_page <= 0) { $per_page = $totdjcnt; }
  $num_pages = ceil($totdjcnt/$per_page);
  $page = 1;
  if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] >= 1 && $_REQUEST['page'] <= $num_pages) {
	  $page = (int)$_REQUEST['page'];
  }
  $start = ($page-1)*$per_page;
  $query = "SELECT * FROM `".DJ_TABLE."` WHERE Status=1 ORDER BY IsCurDJ DESC, DispName ASC LIMIT ".$start.", ".$per_page;
	$res = $db->query($query);
  $smarty->assign("page",$page);
  $smarty->assign("pages",$num_pages);
  $smarty->assign("djcnt",$db->num_rows($res));
  
  $djs = array();
  while ($arr = $db->fetch_assoc($res)) {
  	if ($arr['Picture']) {
  		$arr['PictureURL'] = $config['upload_url'].$arr['Picture'];
  	}
  	if (strlen($arr['Profile']) > 140) {
  		$arr['ProfileShort'] = substr($arr['Profile'],0,140)."... (<a href=\"dj.php?dj=".$arr['ID']."\">read more</a>)";
  	} else {
  		$arr['ProfileShort'] = $arr['Profile'];
  	}
  	array_push($djs, $arr);
  }
  $smarty->assign("djs", $djs);  
	$smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display("djs.tpl");
?>