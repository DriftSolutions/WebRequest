{include file='header.tpl'}
<div class="container">
<table class="{$table_class}">
<tbody>

{if $cursong}
		<tr><th>Currently Playing Song{if $config.StreamDJ != ""} - {$config.StreamDJ}{/if}</th>{if $config.enable_ratings}<th>Rating</th>{/if}<th>Album</th><th class="text-right">Time</th></tr>
		<tr>
			<td>{songinfolink id=$cursong.ID} {songdisp song=$cursong}</td>
			{if $config.enable_ratings}<td>{rating song=$cursong}</td>{/if}
			<td>{$cursong.Album}</td>
			<td class="text-right">{timedisp len=$cursong.SongLen}</td>
		</tr>
{/if}

{if $reqcnt > 0}
			<tr>
				<th>Upcoming songs</th>
				{if $config.enable_ratings}<th>Rating</th>{/if}
				<th>Album</th><th class="text-right">Time</th>
			</tr>
{foreach from=$requests item=song}
	<tr>
		<td>{songinfolink id=$song.ID} {songdisp song=$song}</td>
		{if $config.enable_ratings}<td>{rating song=$song}</td>{/if}
		<td>{$song.Album}</td>
		<td class="text-right">{timedisp len=$song.SongLen}</td>
	</tr>
{/foreach}
{/if}

<tr><th>Most recently played songs</th>{if $config.enable_ratings}<th>Rating</th>{/if}<th>Album</th><th class="text-right">Time</th></tr>
{assign var='curclass' value='songmain'}
{foreach from=$lastplayed item=song}
	<tr>
		<td>{songinfolink id=$song.ID} {songdisp song=$song}</td>
		{if $config.enable_ratings}<td>{rating song=$song}</td>{/if}
		<td>{$song.Album}</td>
		<td class="text-right">{timedisp len=$song.SongLen}</td>
	</tr>
{/foreach}

{if $toprequestscnt > 0}
<tr><th>Top 10 most requested songs</th>{if $config.enable_ratings}<th>Rating</th>{/if}<th>Album</th><th class="text-right">Time</th></tr>
{assign var='curclass' value='songmain'}
{foreach from=$toprequests item=song}
	<tr>
		<td>{songinfolink id=$song.ID} {songdisp song=$song} [times requested: {$song.RequestedInHistory}]</td>
		{if $config.enable_ratings}<td>{rating song=$song}</td>{/if}
		<td>{$song.Album}</td>
		<td class="text-right">{timedisp len=$song.SongLen}</td>
	</tr>
{/foreach}
{/if}

{if $toprequesterscnt > 0}
<tr><th colspan="{if $config.enable_ratings}2{else}1{/if}">Top 10 requesters</th><th class="text-right" colspan="2">Number of Requests</th></tr>
{foreach from=$toprequesters item=user}
	<tr><td colspan="{if $config.enable_ratings}2{else}1{/if}">{$user.Nick}</td><td class="text-right" colspan="2">{$user.Count}</td></tr>
{/foreach}
{/if}

</tbody>
</table>
</div>
{include file='footer.tpl'}