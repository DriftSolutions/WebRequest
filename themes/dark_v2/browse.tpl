{include file='header.tpl'}
<div class="container" style="margin-bottom: 15px;">
	<div class="row">
		<div class="col-xs-6 col-sm-3 text-right">
			Search for:
		</div>
		<div class="col-xs-6 col-sm-9">
			<form action="browse.php" method="get" class="form-inline">
			<input name="pl" type="hidden" value="{$config.db_table}">
			<input type="text" class="form-control input-sm" name="s" value="{$search.s}"> in 
			<select name="f" class="form-control input-sm">
				<option value="FN"{if $search.f == FN} SELECTED{/if}>Filename</option>
				<option value="Title"{if $search.f == Title} SELECTED{/if}>Title</option>
				<option value="Artist"{if $search.f == Artist} SELECTED{/if}>Artist</option>
				<option value="Album"{if $search.f == Album} SELECTED{/if}>Album</option>
			</select>
			<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> Go!</button>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 col-sm-3 text-right">
			Common Searches:
		</div>
		<div class="col-xs-6 col-sm-9">
			[<a href="browse.php?pl={$config.db_table}">All Songs</a>]
			[<a href="browse.php?pl={$config.db_table}&m=mostpop">Most Popular</a>]
			[<a href="browse.php?pl={$config.db_table}&m=mostreq">Most Requested</a>]
			{if $config.enable_ratings}[<a href="browse.php?pl={$config.db_table}&m=bestrated">Highest Rated</a>] {/if}
			[<a href="browse.php?pl={$config.db_table}&m=newest">Newest</a>]
			[<a href="browse.php?pl={$config.db_table}&m=oldest">Oldest</a>]
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 col-sm-3 text-right">
			Or browse by Artist:
		</div>
		<div class="col-xs-6 col-sm-9">
			<a href="browse.php?pl={$config.db_table}&m=artist&l=A">A&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=B">B&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=C">C&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=D">D&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=E">E&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=F">F&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=G">G&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=H">H&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=I">I&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=J">J&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=K">K&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=L">L&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=M">M&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=N">N&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=O">O&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=P">P&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=Q">Q&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=R">R&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=S">S&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=T">T&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=U">U&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=V">V&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=W">W&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=X">X&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=Y">Y&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=Z">Z&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=artist&l=num">Other</a>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6 col-sm-3 text-right">
			Or browse by Album:
		</div>
		<div class="col-xs-6 col-sm-9">
			<a href="browse.php?pl={$config.db_table}&m=album&l=A">A&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=B">B&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=C">C&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=D">D&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=E">E&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=F">F&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=G">G&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=H">H&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=I">I&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=J">J&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=K">K&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=L">L&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=M">M&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=N">N&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=O">O&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=P">P&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=Q">Q&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=R">R&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=S">S&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=T">T&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=U">U&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=V">V&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=W">W&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=X">X&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=Y">Y&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=Z">Z&nbsp;
			<a href="browse.php?pl={$config.db_table}&m=album&l=num">Other</a>
		</div>
	</div>
</div>	

<div class="container">
<table class="{$table_class}">
<tbody>

<tr><th>Browse playlist...</th>{if $config.cur_dj_selected}<th class="text-center">Request</th>{/if}{if $config.enable_ratings}<th class="text-center">Rating</th>{/if}<th class="text-center">Album</th><th class="text-right">Time</td></tr>
<tr><th colspan={if $config.enable_ratings}5{else}4{/if} class="text-center">
	{if $search.page > 0}
		<a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><i class="fa fa-fast-backward"></i></a> <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><i class="fa fa-backward"></i></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><i class="fa fa-forward"></i></a> <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><i class="fa fa-fast-forward"></i></a>
	{/if}
</th></tr>
{foreach from=$found item=song}
	<tr><td>{songinfolink id=$song.ID} {songdisp song=$song}</td>
	{if $config.cur_dj_selected}<td class="text-center">{requestlink id=$song.ID}</td>{/if}
	{if $config.enable_ratings}<td class="text-center nobr">{rating song=$song}</td>{/if}
	<td>{$song.Album}</td>
	<td class="text-right">{timedisp len=$song.SongLen}</td></tr>
{/foreach}
<tr><th colspan={if $config.enable_ratings}5{else}4{/if} class="text-center">
	{if $search.page > 0}
		<a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page=0"><i class="fa fa-fast-backward"></i></a> <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page-1}"><i class="fa fa-backward"></i></a> &middot;
	{/if}
	Showing results {$search.first}-{$search.last} of {$search.count}...
	{if $search.last < $search.count}
		&middot; <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.page+1}"><i class="fa fa-forward"></i></a> <a href="browse.php?pl={$config.db_table}&s={$search.s}&f={$search.f}&l={$search.l}&m={$search.m}&limit={$search.limit}&page={$search.pages-1}"><i class="fa fa-fast-forward"></i></a>
	{/if}
</th></tr>
</tbody>
</table>
</div>
{include file='footer.tpl'}