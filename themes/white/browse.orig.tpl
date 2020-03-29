{include file='header.tpl'}
<center>
<form action="browse.php?pl={$config.db_table}" method="get">
<input name="pl" type="hidden" value="{$config.db_table}">
<table width="75%">
<tr><td valign="middle" colspan="2">Search for: <input name="s" value="{$search.s}"> in 
<select name="f">
	<option value="FN"{if $search.f == FN} SELECTED{/if}>Filename</option>
	<option value="Title"{if $search.f == Title} SELECTED{/if}>Title</option>
	<option value="Artist"{if $search.f == Artist} SELECTED{/if}>Artist</option>
	<option value="Album"{if $search.f == Album} SELECTED{/if}>Album</option>
</select><input type="submit" value="Go!"></td></tr>
<tr><td align="right" width="0">Common Searches:</td><td>
[<a href="browse.php?pl={$config.db_table}">All Songs</a>]
[<a href="browse.php?pl={$config.db_table}&m=mostpop">Most Popular</a>]
[<a href="browse.php?pl={$config.db_table}&m=mostreq">Most Requested</a>]
{if $config.enable_ratings}[<a href="browse.php?pl={$config.db_table}&m=bestrated">Highest Rated</a>] {/if}
[<a href="browse.php?pl={$config.db_table}&m=newest">Newest</a>]
[<a href="browse.php?pl={$config.db_table}&m=oldest">Oldest</a>]
</td></tr>
<tr><td align="right" width="0">Or browse by Artist:</td><td>
[<a href="browse.php?pl={$config.db_table}&m=artist&l=A">A</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=B">B</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=C">C</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=D">D</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=E">E</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=F">F</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=G">G</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=H">H</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=I">I</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=J">J</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=K">K</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=L">L</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=M">M</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=N">N</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=O">O</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=P">P</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=Q">Q</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=R">R</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=S">S</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=T">T</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=U">U</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=V">V</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=W">W</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=X">X</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=Y">Y</a>]
[<a href="browse.php?pl={$config.db_table}&m=artist&l=Z">Z</a>]
<tr><td align="right" width="0">Or browse by Album:</td><td>
[<a href="browse.php?pl={$config.db_table}&m=album&l=A">A</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=B">B</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=C">C</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=D">D</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=E">E</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=F">F</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=G">G</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=H">H</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=I">I</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=J">J</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=K">K</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=L">L</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=M">M</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=N">N</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=O">O</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=P">P</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=Q">Q</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=R">R</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=S">S</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=T">T</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=U">U</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=V">V</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=W">W</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=X">X</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=Y">Y</a>]
[<a href="browse.php?pl={$config.db_table}&m=album&l=Z">Z</a>]
</td></tr>
</table></form>

<table class="songtable" cellspacing=0 width="75%">
<tr><td class="songheader">Browse playlist...</td>{if $config.cur_dj_selected}<td class="songheader narrow">Request</td>{/if}{if $config.enable_ratings}<td class="songheader narrow">Rating</td>{/if}<td class="songheader narrow">Album</td><td class="songheader narrow" align="right">Time</td></tr>
<tr><td class="songheader" colspan={if $config.enable_ratings}5{else}4{/if} align="center">
	{if $search.page > 0}
		<a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><img src="{getimage fn=results_first.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="First Page" title="First Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><img src="{getimage fn=results_prev.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Previous Page" title="Previous Page"></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><img src="{getimage fn=results_next.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Next Page" title="Next Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><img src="{getimage fn=results_last.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Last Page" title="Last Page"></a>
	{/if}
</td></tr>
{assign var='curclass' value='songmain'}
{foreach from=$found item=song}
	<tr><td valign="middle" class="{$curclass}">{songinfolink id=$song.ID} {songdisp song=$song}</td>
	{if $config.cur_dj_selected}<td valign="middle" class="{$curclass} nobr">{requestlink id=$song.ID}</td>{/if}
	{if $config.enable_ratings}<td valign="middle" class="{$curclass} nobr">{rating song=$song}</td>{/if}
	<td valign="middle" class="{$curclass} nobr">{$song.Album}</td>
	<td valign="middle" class="{$curclass} nobr" align="right">{timedisp len=$song.SongLen}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
<tr><td class="songheader" colspan={if $config.enable_ratings}5{else}4{/if} align="center" valign="middle">
	{if $search.page > 0}
		<a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><img src="{getimage fn=results_first.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="First Page" title="First Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><img src="{getimage fn=results_prev.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Previous Page" title="Previous Page"></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><img src="{getimage fn=results_next.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Next Page" title="Next Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><img src="{getimage fn=results_last.png}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Last Page" title="Last Page"></a>
	{/if}
</td></tr>
</table>
</center><br />
{include file='footer.tpl'}