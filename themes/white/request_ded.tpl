<html>
<head>
<title>Song Request</title>
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center>
<form action="request.php" method="post">
<input type=hidden name="fn" value="{$fn}">
<table class="songtable" cellspacing=0 width="100%">
<tr><td class="songheader" width="100%" align="center">Request Dedication</td></tr>
<tr><td class="songmain"><input type="text" name="dedication" value=""><input type="submit" value="Request Song"></td></tr>
</table>
</form>
</center>
</body>
</html>
