{include file='admin_header.tpl'}
<center>

{if $updated}
<table class="songtable" cellspacing=0 width="75%">
<tr><td class="songheader">Profile Update</td></tr>
{assign var='curclass' value='songmain'}
<tr><td align="center" class="{$curclass}">Changes Saved!</td></tr>
</table><br />
{/if}

<form enctype="multipart/form-data" action="profile.php" method="POST">
<input type="hidden" name="doUpdate" value="1">
<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">Your Profile</td></tr>

{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}">Display Name:</td><td valign="top" class="{$curclass}"><input name="profile[DispName]" value="{$profile.DispName}"></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}">Profile Picture:</td><td valign="top" class="{$curclass}">
{if $profile.Picture}
<img src="{$config.upload_url}{$profile.Picture}" border=0><br />
<input type="checkbox" name="deletePic" value="1">Delete current picture; or,<br />
{/if}
Upload new picture: <input name="uploadPic" type="file"></td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}">Email: (Only enter if you want it publicly displayed!)</td><td valign="top" class="{$curclass}"><input name="profile[Email]" value="{$profile.Email}"></td></tr>
{assign var='curclass' value='songalt'}
<tr><td valign="top" align="right" class="{$curclass}">Profile Text:</td><td valign="top" class="{$curclass}"><textarea name="profile[Profile]" rows=7 cols=50 wrap="virtual">{$profile.Profile}</textarea></td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}">Show Playlist Link In Profile:</td><td valign="top" class="{$curclass}"><input type="checkbox" name="profile[PublicPlaylist]" value="1"{if $profile.PublicPlaylist} CHECKED{/if}></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}">Enable Profile:</td><td valign="top" class="{$curclass}"><input type="checkbox" name="profile[Status]" value="1"{if $profile.Status} CHECKED{/if}></td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}">Submit:</td><td valign="top" class="{$curclass}"><input type="submit" value="Update Profile"></td></tr>

</table></form>
</center><br />
{include file='admin_footer.tpl'}