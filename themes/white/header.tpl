<html>
<head>
<title>{$title|default:"Auto DJ Playlist"}</title>
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center class="nobr">
	<img src="images/logo.png" alt="ShoutIRC.com Auto DJ Request System" title="ShoutIRC.com Auto DJ Request System" border=0><br />
	<a href="index.php"><img src="{getimage fn=head_playing.png}" alt="Currently Playing"></a> <a href="browse.php"><img src="{getimage fn=head_browse.png}" alt="Browse Playlist"></a>{if $config.allow_djprofiles} <a href="djs.php"><img src="{getimage fn=head_ourdjs.png}" alt="Our DJs"></a>{/if}
</center><br />
