<?php
	require("./admincore.inc.php");
	
	if (!$config['allow_djprofiles']) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("admin_notenabled.tpl");
  	exit;		
	}
		
	$res = $db->query("SELECT * FROM ".DJ_TABLE." WHERE Username='".$db->escape($userinfo['Username'])."'");
	$create = true;
	if ($profile = $db->fetch_assoc($res)) {
		$create = false;
	} else {
		$profile = array("Username" => $userinfo['Username'], "DispName" => $userinfo['Username']);
	}
	
	if ($_REQUEST['doUpdate'] == 1) {
		foreach($_REQUEST['profile'] as $k => $v) {
			$profile[$k] = $v;
		}
		if ($_REQUEST['deletePic'] == 1) {
			$profile['Picture'] = "";
		}
		if (!isset($_REQUEST['profile']['PublicPlaylist'])) {
			$profile['PublicPlaylist'] = 0;
		}
		if (!isset($_REQUEST['profile']['Status'])) {
			$profile['Status'] = 0;
		}
		if (!empty($_FILES['uploadPic']['tmp_name'])) {
			$src = $_FILES['uploadPic']['tmp_name'];
			$fn = $userinfo['Username'].strrchr($_FILES['uploadPic']['name'], ".");
			$dest = $config['upload_path'].$fn;
			if (@move_uploaded_file($src, $dest) !== FALSE) {
				$profile['Picture'] = $fn;
			} else {
				$smarty->assign("error", "Error moving uploaded image (permissions problem?)");
			}
		}
		$profile['TimeStamp'] = time();
		if ($create) {
			$profile['Created'] = $profile['TimeStamp'];
			$db->insert(DJ_TABLE, $profile);
		} else {
			$db->update(DJ_TABLE, $profile);
		}
		$smarty->assign("updated", 1);
	}
	
	$smarty->assign("profile", $profile);
		
 	$smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display( "admin_profile_index.tpl");

?>