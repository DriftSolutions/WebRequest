{include file='header.tpl'}
<center>
<table class="songtable" cellspacing=0 width="75%">
{if $cursong}
<tr><td class="songheader">Currently Playing Song{if $config.StreamDJ != ""} - {$config.StreamDJ}{/if}</td><td class="songheader">Album</td><td class="songheader">Time</td></tr>
<tr><td class="songmain">{songinfolink id=$cursong.ID} {songdisp song=$cursong}</td><td class="songmain">{$cursong.Album}</td><td class="songmain" align="right">{timedisp len=$cursong.SongLen}</td></tr>
{/if}

{if $reqcnt > 0}
<tr><td class="songheader">Upcoming songs</td><td class="songheader">Album</td><td class="songheader">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$requests item=song}
	<tr><td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song}</td><td class="{$curclass}">{$song.Album}</td><td class="{$curclass}" align="right">{timedisp len=$song.SongLen}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
{/if}

<tr><td class="songheader">Most recently played songs</td><td class="songheader">Album</td><td class="songheader">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$lastplayed item=song}
	<tr><td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song}</td><td class="{$curclass}">{$song.Album}</td><td class="{$curclass}" align="right">{timedisp len=$song.SongLen}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}

{if $toprequestscnt > 0}
<tr><td class="songheader">Top 10 most requested songs</td><td class="songheader">Album</td><td class="songheader">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$toprequests item=song}
	<tr><td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song} [times requested: {$song.RequestedInHistory}]</td><td class="{$curclass}">{$song.Album}</td><td class="{$curclass}" align="right">{timedisp len=$song.SongLen}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
{/if}

{if $toprequesterscnt > 0}
<tr><td class="songheader" colspan=2>Top 10 requesters</td><td class="songheader">Number of Requests</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$toprequesters item=user}
	<tr><td class="{$curclass}" colspan=2>{$user.Nick}</td><td class="{$curclass}" align="right">{$user.Count}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
{/if}

</table>
</center><br />
{include file='footer.tpl'}