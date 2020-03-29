{assign var='show_menu' value='0'}
{include file='header.tpl'}
<div class="container">
<table class="{$table_class} table-condensed">
<tbody>
{if $config.getinfo}
<tr><th colspan=3 class="text-center">Song Information</th><th class="text-center narrow">Image</th></tr>
<tr><td class="text-right narrow">Song Name:</td><td colspan=2>{songdisp song=$song}</td><td valign="top" class="narrow" rowspan=10><img src="http://www.shoutirc.com/songpic.php?artist={$song.Artist|escape:'url'}&album={$song.Album|escape:'url'}" border=0 alt="Band/Album Image" title="Band/Album Image"></td></tr>
{else}
<tr><th colspan=3 class="text-center">Song Information</th></tr>
<tr><td class="text-right narrow">Song Name:</td><td colspan=2>{songdisp song=$cursong}</td></tr>
{/if}

<tr><td class="text-right narrow">Title:</td><td>{if $song.Title}{$song.Title}{else}<i>-Not Available-</i>{/if}</td><td align="right">{requestlink id=$song.ID}</td></tr>
<tr><td class="text-right narrow">Artist:</td><td colspan=2>{if $song.Artist}{$song.Artist}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="text-right narrow">Album:</td><td colspan=2>{if $song.Album}{$song.Album}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="text-right narrow">Genre:</td><td colspan=2>{if $song.Genre}{$song.Genre}{else}<i>-Not Available-</i>{/if}</td></tr>
<tr><td class="text-right narrow">Filename:</td><td colspan=2>{$song.FN}</td></tr>
<tr><td class="text-right narrow">Song Length:</td><td colspan=2>{timedisp len=$song.SongLen}</td></tr>
<tr><td class="text-right narrow">Play Count:</td><td colspan=2>{$song.PlayCount}</td></tr>
<tr><td class="text-right narrow"><nobr>Times Requested:</nobr></td><td colspan=2>{$song.ReqCount}</td></tr>
<tr><td class="text-right narrow">Song ID:</td><td colspan=2>{$song.ID}</td></tr>

</tbody>
</table>
</div>
{include file='footer.tpl'}