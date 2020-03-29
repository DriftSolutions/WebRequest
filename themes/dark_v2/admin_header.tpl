<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title|default:"Web Request Admin Panel - Logged in as $user"}</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/{$bootstrap_version}/darkly/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../themes/{$config.theme}/style.css" type="text/css">
</head>
<body>

<div class="container text-center">
	<img src="../images/logowhite.png" alt="ShoutIRC.com Auto DJ Request System" title="ShoutIRC.com Auto DJ Request System" border="0">
</div>

<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="{$config.base_url}">Back to Playlist</a></li>
        <li><a href="index.php">Admin Panel Home</a></li>
        <li><a href="upload.php">Create/Update Playlist</a></li>
				{if $config.allow_djprofiles}<li><a href="profile.php">Create/Update Profile</a></li>{/if}
				{if $config.allow_musicup}<li><a href="musicup.php">Upload Music</a></li>{/if}
        <li><a href="client.php">Bot Controls</a></li>
        <li><a href="index.php?logout=1">Logout</a></li>
        <li><a href="update.php">WebRequest Version</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
