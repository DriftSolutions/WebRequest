<html>
<head>
<title>Rate Song</title>
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center>
<table class="songtable" cellspacing=0 width="100%">
<tr><td class="songheader" width="100%" align="center">Rating Status</td></tr>
<tr><td class="songmain">{$msg}</td></tr>
</table>
</center>
{if $rate_error == 0}{literal}
<script language="JavaScript">
window.close();
if (window.opener && !window.opener.closed) {
	window.opener.location.reload();
}
</script>
{/literal}{/if}
</body>
</html>
