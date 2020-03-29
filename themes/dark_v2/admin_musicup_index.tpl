{include file='admin_header.tpl'}
<div class="container">
<form enctype="multipart/form-data" action="musicup.php" method="POST">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Upload Music (Max per-file size: {$max_file_size_mb} / Max combined size: {$max_upload_size_mb})</th>
	</tr>
</thead>

<tbody>

{section name=upform start=1 loop=10 step=1}
<tr>
	<td valign="top" class="text-right" width="25%">Music File:</td>
	<td valign="top" class="{$curclass}"><input type="file" name="musfile[]"></td>
</tr>
{/section}

<tr>
	<td valign="top" class="text-right" width="25%">Upload:</td>
	<td valign="top"><input type="submit" class="btn btn-primary" value="Upload File(s)"></td>
</tr>

</tbody>
</table></form>
</div>

<div class="container">
<form enctype="multipart/form-data" action="musicup.php" method="POST">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Download music from URLs</th>
	</tr>
</thead>

<tbody>
<tr>
	<td valign="top" class="text-right" width="25%">URLs (one per line):</td>
	<td valign="top"><textarea cols=80 rows=10 wrap="virtual" name="musurl"></textarea></td>
</tr>
<tr>
	<td valign="top" class="text-right" width="25%">Upload:</td>
	<td valign="top"><input type="submit" class="btn btn-primary" value="Download File(s)"></td>
</tr>
	
</tbody>
</table></form>
</div>
{include file='admin_footer.tpl'}