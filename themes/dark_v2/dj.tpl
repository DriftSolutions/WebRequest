{include file='header.tpl'}
<div class="container">
	<div class="well text-center">
	
		<h2>{$dj.DispName}</h2>
		{if $dj.Picture}
			<img src="{$dj.PictureURL}" border=0><br />
		{/if}
		{if $dj.Email}[<a href="mailto:{$dj.Email}?subject=WebRequest%20Profile">Email Me</a>] {/if}
		{if $dj.PublicPlaylist}[<a href="browse.php?pl={$dj.Username}">Browse my playlist...</a>]{/if}
		<br /><br />

		{$dj.Profile|nl2br}
	</div>
</div>
{include file='footer.tpl'}
