<html>
<head>
<title>Song Information</title>
<link rel="stylesheet" href="themes/{$config.theme}/style.css" type="text/css" media="screen,projection">
</head>
<body>
<center>
<table class="songtable" cellspacing=0 width="100%">
{if $config.getinfo}
<tr><td class="songheader" colspan=3 width="100%" align="center">Song Information</td><td class="songheader" width=0 align="center">Image</td></tr>
<tr><td class="songmain" align="right">Song Name:</td><td colspan=2 class="songmain">{songdisp song=$song}</td><td valign="top" width=0 rowspan=10><img src="http://www.shoutirc.com/songpic.php?artist={$song.Artist|escape:'url'}&album={$song.Album|escape:'url'}" border=0 alt="Band/Album Image" title="Band/Album Image"></td></tr>
{else}
<tr><td class="songheader" colspan=3 width="100%" align="center">Song Information</td></tr>
<tr><td class="songmain" align="right">Song Name:</td><td colspan=2 class="songmain">{songdisp song=$cursong}</td></tr>
{/if}

<tr><td class="songalt" align="right">Title:</td><td class="songalt">{if $song.Title}{$song.Title}{else}<i>-Not Available-</i>{/if}</td><td align="right" class="songalt">{requestlink id=$song.ID}</td></tr>
<tr><td class="songmain" align="right">Artist:</td><td colspan=2 class="songmain">{if $song.Artist}{$song.Artist}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="songalt" align="right">Album:</td><td colspan=2 class="songalt">{if $song.Album}{$song.Album}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="songmain" align="right">Genre:</td><td colspan=2 class="songmain">{if $song.Genre}{$song.Genre}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="songalt" align="right">Filename:</td><td colspan=2 class="songalt">{$song.FN}</td></tr>
<tr><td class="songmain" align="right">Song Length:</td><td colspan=2 class="songmain">{timedisp len=$song.SongLen}</td></tr>
<tr><td class="songalt" align="right">Play Count:</td><td class="songalt" colspan=2>{$song.PlayCount}</td></tr>
<tr><td class="songmain" align="right"><nobr>Times Requested:</nobr></td><td colspan=2 class="songmain">{$song.ReqCount}</td></tr>
<tr><td class="songalt" align="right">Song ID:</td><td colspan=2 class="songalt">{$song.ID}</td></tr>

</table>
</center>
</body>
</html>
