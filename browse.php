<?php
	@require("./core.inc.php");
	
	if (empty($config['db_table'])) {
		$smarty->assign("config", $config);	
	  $smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display("nodj.tpl");
	  exit;
	}	
  
  $limit = isset($_GET['limit']) ? intval($_GET['limit']):50;
  if ($limit < 1) {
  	$limit = 50;
  } else if ($limit > 100) {
  	$limit = 100;
  }

  $page = isset($_GET['page']) ? intval($_GET['page']):0;
  if ($page < 0) {
  	$page = 0;
  }

 	$f = isset($_GET['f']) ? (string)$_GET['f']:'';
 	if (!in_array($f, array("Title", "Artist", "Album", "Genre", "FN"))) {
		$f = "FN";
 	}
 	 	
 	$mode = isset($_GET['m']) ? (string)$_GET['m']:'';
 	$letter = isset($_GET['l']) ? trim($_GET['l']):'';
 	if ($letter != 'num') {
 		$letter = substr($letter, 0, 1);
 	}
  
  $s = isset($_GET['s']) ? (string)$_GET['s']:'';
  if (!empty($s)) {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE `".$f."` LIKE '%".$db->escape(str_replace(array(" ","*"),"%",$s))."%' AND Seen=1 ORDER BY ".$f." ASC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE `".$f."` LIKE '%".$db->escape(str_replace(array(" ","*"),"%",$s))."%' AND Seen=1";
  } else if ($mode == "artist" && !empty($letter)) {
	  if ($letter == 'num') {
	  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Artist NOT REGEXP '^[[:alpha:]]' AND Artist!='' AND Seen=1 ORDER BY Artist ASC LIMIT ".($page*$limit).", ".$limit;
	  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Artist NOT REGEXP '^[[:alpha:]]' AND Artist!='' AND Seen=1";
	  } else {
	  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Artist LIKE '".$db->escape($_GET['l'])."%' AND Seen=1 ORDER BY Artist ASC LIMIT ".($page*$limit).", ".$limit;
	  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Artist LIKE '".$db->escape($_GET['l'])."%' AND Seen=1";
	  }
  } else if ($mode == "album" && !empty($letter)) {
	  if ($letter == 'num') {
	  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Album NOT REGEXP '^[[:alpha:]]' AND Album!='' AND Seen=1 ORDER BY Album ASC LIMIT ".($page*$limit).", ".$limit;
	  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Album NOT REGEXP '^[[:alpha:]]' AND Album!='' AND Seen=1";
	  } else {
	  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Album LIKE '".$db->escape($letter)."%' AND Seen=1 ORDER BY Album ASC LIMIT ".($page*$limit).", ".$limit;
	  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Album LIKE '".$db->escape($letter)."%' AND Seen=1";
	  }
  } else if ($mode == "newest") {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY mTime DESC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  } else if ($mode == "oldest") {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY mTime ASC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  } else if ($mode == "mostpop") {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY PlayCount DESC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  } else if ($mode == "mostreq") {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY ReqCount DESC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  } else if ($mode == "bestrated") {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY Rating DESC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  } else {
  	$query = "SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY FN ASC LIMIT ".($page*$limit).", ".$limit;
  	$query2 = "SELECT COUNT(*) AS Count FROM `".$config['db_table']."` WHERE Seen=1";
  }
  $res = $db->query($query);
  $res2 = $db->query($query2);
  $arr2 = $db->fetch_assoc($res2);
  $count = $arr2['Count'];
  $pages = ceil($count / $limit);
  
  $sd = array(
  	"limit" => $limit,
  	"page" => $page,
  	"pages" => $pages,
  	"s" => xencode($s),
  	"f" => $f,
  	"l" => xencode($letter),
  	"m" => xencode($mode),
  	"first" => ($page*$limit)+1,
  	"last" => ($page*$limit)+$db->num_rows($res),
  	"count" => $count
  );
  $smarty->assign("search",$sd);
  
  $found = array();
  while ($arr = $db->fetch_assoc($res)) {
  	$arr['RevRating'] = 5 - $arr['Rating'];
 		array_push($found, $arr);
  }
  $smarty->assign("found",$found);    
  $smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display("browse.tpl");
?>