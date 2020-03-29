{include file='admin_header.tpl'}
<div class="container">
<form enctype="multipart/form-data" action="upload.php" method="POST">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Playlist Upload</th>
	</tr>
</thead>

<tbody>
{if $missingsDeps == ""}
<tr>
	<td valign="top" class="text-right" width="25%">Playlist File (.SMD/.SMZ):</td>
	<td valign="top"><input type="file" name="smdfile"><br />Max upload size: {$max_upload_size_mb}
{if $have_zlib == 0}
<br />zlib is not detected, SMZ support disabled! (You can still upload non-compressed SMD files though).
{/if}
	</td>
</tr>
<tr>
	<td valign="top" class="text-right" width="25%">Upload:</td>
	<td valign="top"><input type="submit" class="btn btn-primary" value="Upload File"></td>
</tr>
{else}
<tr>
	<td valign="top" class="text-right" width="25%">Playlist File (.SMD/.SMZ):</td>
	<td valign="top">{$missingsDeps}</td>
</tr>
{/if}
</tbody>
</table></form>
</div>

<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Playlist Status</th>
	</tr>
</thead>

<tbody>
{if $have_djtable}
<tr><td valign="top" class="text-right" width="25%">Playlist Status:</td><td valign="top"><span class="text-success">OK ({$songcount} songs)</font></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" class="text-right" width="25%">Ping URL (for WebRequest plugin):</td><td valign="top"><input type="text" value="{$ping_url_normal}" size=60></td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" class="text-right" width="25%">Ping URL (for SAM):</td><td valign="top"><input type="text" value="{$ping_url_sam}" size=60></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" class="text-right" width="25%">Wipe Playlist:</td><td valign="top">[<a href="upload.php?action=wipe" onClick="javascript:return confirm('Are you sure you want to delete your playlist?');">Delete playlist</a>]</td></tr>
{else}
<tr><td valign="top" class="text-right" width="25%">Playlist Status:</td><td valign="top">Playlist has not been created yet</td></tr>
{/if}

</tbody>
</table>
</div>
{include file='admin_footer.tpl'}