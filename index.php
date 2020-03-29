<?php	
	require("./core.inc.php");
	
	if (empty($config['CurDJ'])) {
		$smarty->assign("config", $config);	
	  $smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display("nodj.tpl");
	  exit;
	}
  
  $res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE Seen=1 ORDER BY LastPlayed DESC LIMIT 11");
  $lastplayed = array();
  while (count($lastplayed) < 10 && $arr = $db->fetch_assoc($res)) {
  	$arr['RevRating'] = 5 - $arr['Rating'];
  	if (($arr['LastPlayed']+$arr['SongLen']) > time() && count($lastplayed) == 0) {
  		$smarty->assign("cursong",$arr);
  	} else {
  		array_push($lastplayed, $arr);
  	}
  }
  $smarty->assign("lastplayed",$lastplayed);

  $requests = array();
  $toprequests = array();
  $toprequesters = array();
  if (!$config['real_dj']) {
  	// only AutoDJ is so robotic that he honors all requests
	  $res = $db->query("SELECT * FROM `".$config['db_table']."` WHERE LastReq > LastPlayed AND Seen=1 ORDER BY LastReq ASC LIMIT 10");
	  while ($arr = $db->fetch_assoc($res)) {
			$arr['RevRating'] = 5 - $arr['Rating'];
 			array_push($requests, $arr);
  	}
  	
  	if ($config['show_toprequests']) {
		  $res = $db->query("SELECT SongID,COUNT(*) AS Count FROM `".$config['db_table']."_RequestHistory` GROUP BY SongID ORDER BY Count DESC LIMIT 20");
		  $cnt = 0;
		  while ($arr = $db->fetch_assoc($res)) {
		  	$res2 = $db->query("SELECT * FROM `".$config['db_table']."` WHERE ID='".$db->escape($arr['SongID'])."' AND Seen=1");
		  	if ($arr2 = $db->fetch_assoc($res2)) {
		  		$arr2['RequestedInHistory'] = $arr['Count'];
					$arr2['RevRating'] = 5 - $arr2['Rating'];
		  		array_push($toprequests, $arr2);		  		
		  		if (++$cnt >= 10) {
		  			break;
		  		}
		  	}
		  }
		}
	  
	  if ($config['show_toprequesters']) {
		  $res = $db->query("SELECT Nick,COUNT(*) AS Count FROM `".$config['db_table']."_RequestHistory` WHERE Nick!=\"".$db->escape($config['ircbot_user'])."\" GROUP BY Nick ORDER BY Count DESC LIMIT 10");
		  while ($arr = $db->fetch_assoc($res)) {
	  		array_push($toprequesters, $arr);
		  }
		}
  }
  $smarty->assign("requests",$requests);
  $smarty->assign("toprequests",$toprequests);
  $smarty->assign("toprequesters",$toprequesters);
  $smarty->assign("reqcnt",count($requests));
  $smarty->assign("toprequestscnt",count($toprequests));
  $smarty->assign("toprequesterscnt",count($toprequesters));
    
  $smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display("index.tpl");    
?>