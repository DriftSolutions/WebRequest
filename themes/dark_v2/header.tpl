<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title|default:"Auto DJ Playlist"}</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/{$bootstrap_version}/darkly/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css">
</head>
<body>

{if $show_menu}
<div class="container text-center">
	<img src="images/logowhite.png" alt="ShoutIRC.com Auto DJ Request System" title="ShoutIRC.com Auto DJ Request System" border="0">
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
        <li><a href="index.php">Current Status</a></li>
        <li><a href="browse.php">Browse Playlist</a></li>
				{if $config.allow_djprofiles}<li><a href="djs.php">Meet Our DJs</a></li>{/if}
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
{/if}