{include file='header.tpl'}

{if $djcnt < 1}
<div class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">Request System Status</div>
	  <div class="panel-body">
	    No DJs Found!
	  </div>
	</div>
</div>

{else}

<div class="container">
	<div class="row djsrow">
{foreach from=$djs item=dj}
	<div class="col-xs-12 col-sm-6 col-md-4 equalize">
			<div class="text-center">
				{if $dj.Picture}<img src="{$dj.PictureURL}" border=0><br />{/if}
				<a href="dj.php?dj={$dj.ID}">DJ {$dj.DispName}</a>{if $dj.IsCurDJ} {onairimg}{/if}
			</div>
			{if $dj.ProfileShort}<br />{$dj.ProfileShort}{/if}
			{if $dj.Email or $dj.PublicPlaylist}<br /><br />{/if}
			{if $dj.Email}<a href="mailto:{$dj.Email}?subject=WebRequest%20Profile">Email this DJ...</a><br />{/if}
			{if $dj.PublicPlaylist}<a href="browse.php?pl={$dj.Username}">Browse this DJ's playlist...</a><br />{/if}
	</div>
{/foreach}
	</div>
</div>

{if $pages > 1}
<div class="container">
{if $page > 1}<a href="djs.php" title="First"><i class="fa fa-fast-backward"></i></a> <a href="djs.php?page={$page-1}" title="Previous"><i class="fa fa-backward"></i></a> - {/if}
Page {$page} of {$pages}
{if $page < $pages} - <a href="djs.php?page={$page+1}" title="Next"><i class="fa fa-forward"></i></a> <a href="djs.php?page={$pages}" title="Last"><i class="fa fa-fast-forward"></i></a>{/if}
</div>
{/if}

{/if}
{include file='footer.tpl'}
