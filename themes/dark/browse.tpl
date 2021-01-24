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
<a href="browse.php?pl={$config.db_table}&m=artist&l=A"><img src="images/letters_white/A.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=B"><img src="images/letters_white/B.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=C"><img src="images/letters_white/C.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=D"><img src="images/letters_white/D.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=E"><img src="images/letters_white/E.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=F"><img src="images/letters_white/F.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=G"><img src="images/letters_white/G.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=H"><img src="images/letters_white/H.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=I"><img src="images/letters_white/I.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=J"><img src="images/letters_white/J.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=K"><img src="images/letters_white/K.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=L"><img src="images/letters_white/L.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=M"><img src="images/letters_white/M.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=N"><img src="images/letters_white/N.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=O"><img src="images/letters_white/O.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=P"><img src="images/letters_white/P.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=Q"><img src="images/letters_white/Q.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=R"><img src="images/letters_white/R.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=S"><img src="images/letters_white/S.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=T"><img src="images/letters_white/T.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=U"><img src="images/letters_white/U.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=V"><img src="images/letters_white/V.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=W"><img src="images/letters_white/W.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=X"><img src="images/letters_white/X.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=Y"><img src="images/letters_white/Y.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=Z"><img src="images/letters_white/Z.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=artist&l=num"><img src="images/letters_white/other.png" title="Other songs starting with numbers or special characters." border=0></a>&nbsp;
<tr><td align="right" width="0">Or browse by Album:</td><td>
<a href="browse.php?pl={$config.db_table}&m=album&l=A"><img src="images/letters_white/A.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=B"><img src="images/letters_white/B.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=C"><img src="images/letters_white/C.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=D"><img src="images/letters_white/D.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=E"><img src="images/letters_white/E.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=F"><img src="images/letters_white/F.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=G"><img src="images/letters_white/G.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=H"><img src="images/letters_white/H.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=I"><img src="images/letters_white/I.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=J"><img src="images/letters_white/J.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=K"><img src="images/letters_white/K.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=L"><img src="images/letters_white/L.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=M"><img src="images/letters_white/M.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=N"><img src="images/letters_white/N.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=O"><img src="images/letters_white/O.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=P"><img src="images/letters_white/P.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=Q"><img src="images/letters_white/Q.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=R"><img src="images/letters_white/R.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=S"><img src="images/letters_white/S.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=T"><img src="images/letters_white/T.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=U"><img src="images/letters_white/U.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=V"><img src="images/letters_white/V.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=W"><img src="images/letters_white/W.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=X"><img src="images/letters_white/X.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=Y"><img src="images/letters_white/Y.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=Z"><img src="images/letters_white/Z.png" border=0></a>&nbsp;
<a href="browse.php?pl={$config.db_table}&m=album&l=num"><img src="images/letters_white/other.png" title="Other songs starting with numbers or special characters." border=0></a>&nbsp;
</td></tr>
</table></form>

<table class="songtable" cellspacing=0 width="75%">
<tr><td class="songheader">Browse playlist...</td>{if $config.cur_dj_selected}<td class="songheader narrow">Request</td>{/if}{if $config.enable_ratings}<td class="songheader narrow">Rating</td>{/if}<td class="songheader narrow">Album</td><td class="songheader narrow" align="right">Time</td></tr>
<tr><td class="songheader" colspan={if $config.enable_ratings}5{else}4{/if} align="center">
	{if $search.page > 0}
		<a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><img src="{getimage fn='results_first.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="First Page" title="First Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><img src="{getimage fn='results_prev.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Previous Page" title="Previous Page"></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><img src="{getimage fn='results_next.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Next Page" title="Next Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><img src="{getimage fn='results_last.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Last Page" title="Last Page"></a>
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
		<a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><img src="{getimage fn='results_first.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="First Page" title="First Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><img src="{getimage fn='results_prev.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Previous Page" title="Previous Page"></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><img src="{getimage fn='results_next.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Next Page" title="Next Page"></a><a class="songheaderlink" href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><img src="{getimage fn='results_last.png'}" style="vertical-align: middle; padding: 0px; margin: 0px;" border="0" alt="Last Page" title="Last Page"></a>
	{/if}
</td></tr>
</table>
</center><br />
{include file='footer.tpl'}
