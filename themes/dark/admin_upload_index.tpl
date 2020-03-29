{include file='admin_header.tpl'}
<center>

<form enctype="multipart/form-data" action="upload.php" method="POST">
<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">Playlist Upload</td></tr>

{assign var='curclass' value='songmain'}
{if $missingsDeps == ""}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Playlist File (.SMD/.SMZ):</td><td valign="top" class="{$curclass}"><input type="file" name="smdfile"><br />Max upload size: {$max_upload_size_mb}
{if $have_zlib == 0}
<br />zlib is not detected, SMZ support disabled! (You can still upload non-compressed SMD files though).
{/if}
</td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Upload:</td><td valign="top" class="{$curclass}"><input type="submit" value="Upload File"></td></tr>
{else}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Playlist File (.SMD/.SMZ):</td><td valign="top" class="{$curclass}">{$missingsDeps}</td></tr>
{/if}

</table>
<br />
<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">Playlist Status</td></tr>

{assign var='curclass' value='songmain'}
{if $have_djtable}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Playlist Status:</td><td valign="top" class="{$curclass}"><font color="green">OK ({$songcount} songs)</font></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Ping URL (for WebRequest plugin):</td><td valign="top" class="{$curclass}"><input type="text" value="{$ping_url_normal}" size=60></td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Ping URL (for SAM):</td><td valign="top" class="{$curclass}"><input type="text" value="{$ping_url_sam}" size=60></td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Wipe Playlist:</td><td valign="top" class="{$curclass}">[<a href="upload.php?action=wipe" onClick="javascript:return confirm('Are you sure you want to delete your playlist?');">Delete playlist</a>]</td></tr>
{else}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Playlist Status:</td><td valign="top" class="{$curclass}">Playlist has not been created yet</td></tr>
{/if}

</table></form>
</center><br />
{include file='admin_footer.tpl'}