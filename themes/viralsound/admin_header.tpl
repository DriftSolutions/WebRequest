<html>
<head>
<title>{$title|default:"Web Request Admin Panel - Logged in as $user"}</title>
<link rel="stylesheet" href="{$config.base_url}themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center>
	<img src="{$config.base_url}images/logowhite.png" alt="ShoutIRC.com Auto DJ Request System" title="ShoutIRC.com Auto DJ Request System" border=0><br />
	[<a href="{$config.base_url}index.php">Back to Playlist</a>]
	[<a href="index.php">Admin Panel Home</a>]
	[<a href="upload.php">Create/Update Playlist</a>]
	{if $config.allow_djprofiles}[<a href="profile.php">Create/Update Profile</a>]{/if}
	{if $config.allow_musicup}[<a href="musicup.php">Upload Music</a>]{/if}
	[<a href="client.php">Bot Controls</a>] [<a href="index.php?logout=1">Logout</a>]
	{if $userinfo.Status <= 2}
  [<a href="update.php">Update WebRequest</a>]
	{/if}
</center><br />
