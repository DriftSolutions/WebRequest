<?php
	require("./core.inc.php");
	
	$days = 14;
	$limit = 10;
	$reqtab = $config['db_table']."_RequestHistory";
  
  //$res = $db->query("SELECT * FROM `".$reqtab."` WHERE TimeStamp > ".(time() - ($days * 86400))." LIMIT $limit");
  $res = $db->query("SELECT SongID,COUNT(*) AS Count FROM `".$reqtab."` WHERE TimeStamp > ".(time() - ($days * 86400))." GROUP BY SongID ORDER BY Count DESC LIMIT $limit");
  $stats = array();
  while ($arr = $db->fetch_assoc($res)) {
 		$stats[$arr['SongID']]++;
  }
  arsort($stats, SORT_NUMERIC);
  
  foreach ($stats as $id => $count) {
  	$res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE ID=".$id);
  	if ($arr = $db->fetch_assoc($res)) {
  		print "relay ".$arr['Path'].$arr['FN']."\r\n";
  	}
  }
  
?>