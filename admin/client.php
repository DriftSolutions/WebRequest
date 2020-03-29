<?php
	require("./admincore.inc.php");
	ob_start();
?>
	<script language="JavaScript">
	function setStreamInfo(x,y) {
		var el = document.getElementById('stream_status_1');
		el.innerHTML = x;
		el = document.getElementById('stream_status_2');
		el.innerHTML = y;	
	}
	function setServerStatus(x) {
		var el = document.getElementById('server_status');
		el.innerHTML = x;
	}	
	function doSSupdate() {
		//setStreamInfo('MC Lars - iGeneration', 'Listeners: 1/64, Peak: 12');	
		var fileref=document.createElement('script');
		fileref.setAttribute("type","text/javascript");
		fileref.setAttribute("src", "scmd.php?mode=get_info");
		document.getElementsByTagName("head")[0].appendChild(fileref);		
	}
	function doCommand(code,parms) {
		var fileref=document.createElement('script');
		fileref.setAttribute("type","text/javascript");
		fileref.setAttribute("src", "scmd.php?cmd="+code+"&parms="+parms);
		document.getElementsByTagName("head")[0].appendChild(fileref);		
	}	
	function initSSupdates() {
		setServerStatus('Logged in as <?php print str_replace("'","`",$userinfo['Username']); ?>');
		setStreamInfo('Getting stream info...', '');	
		setInterval("doSSupdate()", 10000);
		doSSupdate();
	}
	if (window.addEventListener) {
		//NS/Firefox
		window.addEventListener("load", initSSupdates, false);
	} else if (window.attachEvent) {
		//IE5+
		window.attachEvent("onload", initSSupdates);
	} else {
		//fallback
		window.onload=initSSupdates();
	}
	</script>
<?php
	$script = ob_get_contents();
	ob_end_clean();
	
	$smarty->assign("script", $script);
  $smarty->assign("db_queries", $db->GetQueriesCount());
  $smarty->display("admin_client.tpl");

?>