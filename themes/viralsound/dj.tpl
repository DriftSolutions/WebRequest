{include file='header.tpl'}
<center>

<h2>{$dj.DispName}</h2>
{if $dj.Picture}
	<img src="{$dj.PictureURL}" border=0><br />
{/if}
{if $dj.Email}[<a href="mailto:{$dj.Email}?subject=WebRequest%20Profile">Email Me</a>] {/if}
{if $dj.PublicPlaylist}[<a href="browse.php?pl={$dj.Username}">Browse my playlist...</a>]{/if}
<br /><br />

<table class="songtable" width="75%" cellspacing=0>
	<tr><td class="songheader">DJ Profile</td></tr>
	<tr><td align="left" class="songmain">{$dj.Profile|nl2br}</td></tr>
</tr></table>

</center><br />
{include file='footer.tpl'}
