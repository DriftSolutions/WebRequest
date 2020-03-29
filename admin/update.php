<?php
	require("./admincore.inc.php");

	$needUpdate = 0;
	$ver = @file_get_contents("http://www.shoutirc.com/webreq2.version.txt");
	if ($ver === FALSE || $ver == "") {
		$ver = "Error getting remote version!";
	} else if (version_compare(_WEBREQUEST_VERSION, $ver) == -1) {
		$needUpdate = 1;
	}
	
	$smarty->assign("remote_version", $ver);
	$smarty->assign("download_url", "http://www.shoutirc.com/WebRequest-".$ver.".zip");
	$smarty->assign("needUpdate", $needUpdate);
	
 	$smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display("admin_update.tpl");

?>