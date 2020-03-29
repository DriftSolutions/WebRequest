<?php
	require("./admincore.inc.php"); 

	$stats = array();	
	$stats[] = array("Name" => "WebRequest Version", "Value" => _WEBREQUEST_VERSION);
	$stats[] = array("Name" => "PHP Version", "Value" => phpversion());
	$stats[] = array("Name" => "SAPI Module", "Value" => php_sapi_name());
	$stats[] = array("Name" => "Zend Engine Version", "Value" => zend_version());
	$stats[] = array("Name" => "Database Type", "Value" => $db->GetDBType());
	
	$info = $db->GetModuleInfo();
	$mstat = "";
	foreach ($info as $key => $val) {
		$mstat .= $key.": ".$val."<br />";
	}
	$stats[] = array("Name" => "Database Status", "Value" => $mstat);
	
	$smarty->assign("stats", $stats);
  
  $smarty->assign("db_queries",$db->GetQueriesCount());
  $smarty->display("admin_index.tpl");    
?>