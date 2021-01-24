{include file='header.tpl'}
<center>
<table class="songtable" cellspacing=0 width="75%">
{if $cursong}
<tr><td class="songheader">Currently Playing Song{if $config.StreamDJ != ""} - {$config.StreamDJ}{/if}</td>{if $config.enable_ratings}<td class="songheader narrow">Rating</td>{/if}<td class="songheader narrow">Album</td><td class="songheader narrow" align="right">Time</td></tr>
<tr>
	<td class="songmain">{songinfolink id=$cursong.ID} {songdisp song=$cursong}</td>
	{if $config.enable_ratings}<td valign="middle" class="songmain nobr">{rating song=$cursong}</td>{/if}
	<td class="songmain nobr">{$cursong.Album}</td>
	<td class="songmain nobr" align="right">{timedisp len=$cursong.SongLen}</td>
</tr>
{/if}

{if $reqcnt > 0}
<tr><td class="songheader">Upcoming songs</td>{if $config.enable_ratings}<td class="songheader">Rating</td>{/if}<td class="songheader">Album</td><td class="songheader" align="right">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$requests item=song}
	<tr>
		<td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song}</td>
		{if $config.enable_ratings}<td valign="middle" class="songmain nobr">{rating song=$song}</td>{/if}
		<td class="{$curclass} nobr">{$song.Album}</td>
		<td class="{$curclass} nobr" align="right">{timedisp len=$song.SongLen}</td>
	</tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
{/if}

<tr><td class="songheader">Most recently played songs</td>{if $config.enable_ratings}<td class="songheader">Rating</td>{/if}<td class="songheader">Album</td><td class="songheader" align="right">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$lastplayed item=song}
	<tr>
		<td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song}</td>
		{if $config.enable_ratings}<td valign="middle" class="{$curclass} nobr">{rating song=$song}</td>{/if}
		<td class="{$curclass} nobr">{$song.Album}</td>
		<td class="{$curclass} nobr" align="right">{timedisp len=$song.SongLen}</td>
	</tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}

{if $toprequestscnt > 0}
<tr><td class="songheader">Top 10 most requested songs</td>{if $config.enable_ratings}<td class="songheader">Rating</td>{/if}<td class="songheader">Album</td><td class="songheader" align="right">Time</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$toprequests item=song}
	<tr>
		<td class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song} [times requested: {$song.RequestedInHistory}]</td>
		{if $config.enable_ratings}<td valign="middle" class="{$curclass} nobr">{rating song=$song}</td>{/if}
		<td class="{$curclass} nobr">{$song.Album}</td>
		<td class="{$curclass} nobr" align="right">{timedisp len=$song.SongLen}</td>
	</tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
{/if}

{if $toprequesterscnt > 0}
<tr><td class="songheader" colspan="{if $config.enable_ratings}2{else}1{/if}">Top 10 requesters</td><td class="songheader nobr" colspan="2" align="right">Number of Requests</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$toprequesters item=user}
	<tr><td class="{$curclass}" colspan="{if $config.enable_ratings}2{else}1{/if}">{$user.Nick}</td><td class="{$curclass}" align="right" colspan="2">{$user.Count}</td></tr>
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