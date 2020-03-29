{include file='header.tpl'}
<center>

{if $djcnt < 1}
<table class="songtable" width="50%" cellspacing=0>
<tr><td class="songheader">Request System Status</td></tr>
<tr><td align="center" class="songmain">No DJs Found!</td>
</table>
{/if}

{assign var='curclass' value='songmainvert'}
{foreach from=$djs item=dj}
	<table class="songtable" width="75%" cellspacing=0>
	<tr><td class="songheader" colspan={if $dj.Picture}2{else}1{/if}><a href="dj.php?dj={$dj.ID}" class="songheaderlink">{$dj.DispName}</a>{if $dj.IsCurDJ} {onairimg}{/if}</td></tr>
	<tr>
	{if $dj.Picture}
	<td width="1" class="{$curclass}"><img src="{$dj.PictureURL}" border=0></td>
	{/if}
	<td align="left" width="100%" class="{$curclass}">{$dj.ProfileShort}
	{if $dj.Email or $dj.PublicPlaylist}<br /><br />{/if}
	{if $dj.Email}<a href="mailto:{$dj.Email}?subject=WebRequest%20Profile">Email this DJ...</a><br />{/if}
	{if $dj.PublicPlaylist}<a href="browse.php?pl={$dj.Username}">Browse this DJ's playlist...</a><br />{/if}
	</td>
	</tr></table><br />
	{if $curclass == "songmainvert"}
		{assign var='curclass' value='songaltvert'}	
	{else}
		{assign var='curclass' value='songmainvert'}
	{/if}
{/foreach}

{if $pages > 1}
{if $page > 1}<a href="djs.php" title="First">&lt;&lt;</a> <a href="djs.php?page={$page-1}" title="Previous">&lt;</a> - {/if}
Page {$page} of {$pages}
{if $page < $pages} - <a href="djs.php?page={$page+1}" title="Next">&gt;</a> <a href="djs.php?page={$pages}" title="Last">&gt;&gt;</a>{/if}
{/if}

</center><br />
{include file='footer.tpl'}
