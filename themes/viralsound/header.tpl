<html>
<head>
<title>{$title|default:"Auto DJ Playlist"}</title>
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center>
	<img src="images/logowhite.png" alt="ShoutIRC.com Auto DJ Request System" title="ShoutIRC.com Auto DJ Request System" border=0><br />
	[<a href="index.php">Currently Playing</a>] [<a href="browse.php">Browse Playlist</a>]{if $config.allow_djprofiles} [<a href="djs.php">Our DJs</a>]{/if}
</center><br />
