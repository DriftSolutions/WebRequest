{include file='admin_header.tpl'}

{if $updated}
<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Profile Update</th>
	</tr>
</thead>

<tbody>
<tr>
	<td class="text-center">Changes Saved!</td>
</tr>
</tbody>

</table>
</div>
{/if}

<form enctype="multipart/form-data" action="profile.php" method="POST">
<input type="hidden" name="doUpdate" value="1">

<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>Your Profile</th>
	</tr>
</thead>

<tbody>

<tr>
	<td valign="top" class="text-right">Display Name:</td>
	<td valign="top"><input name="profile[DispName]" value="{$profile.DispName}"></td>
</tr>

<tr>
	<td valign="top" class="text-right">Profile Picture:</td>
	<td valign="top">
{if $profile.Picture}
<img src="{$config.upload_url}{$profile.Picture}" border=0><br />
<input type="checkbox" name="deletePic" value="1">Delete current picture; or,<br />
{/if}
Upload new picture: <input name="uploadPic" type="file">
	</td>
</tr>

<tr>
	<td valign="top" class="text-right">Email: (Only enter if you want it publicly displayed!)</td>
	<td valign="top"><input name="profile[Email]" value="{$profile.Email}"></td>
</tr>
<tr>
	<td valign="top" class="text-right">Profile Text:</td>
	<td valign="top"><textarea name="profile[Profile]" rows=7 cols=50 wrap="virtual">{$profile.Profile}</textarea></td>
</tr>
<tr>
	<td valign="top" class="text-right">Show Playlist Link In Profile:</td>
	<td valign="top"><input type="checkbox" name="profile[PublicPlaylist]" value="1"{if $profile.PublicPlaylist} CHECKED{/if}></td>
</tr>
<tr>
	<td valign="top" class="text-right">Enable Profile:</td>
	<td valign="top"><input type="checkbox" name="profile[Status]" value="1"{if $profile.Status} CHECKED{/if}></td>
</tr>
<tr>
	<td valign="top" class="text-right">Submit:</td>
	<td valign="top"><input type="submit" class="btn btn-primary" value="Update Profile"></td>
</tr>

</tbody>

</table></form>
</div>
{include file='admin_footer.tpl'}