{include file='admin_header.tpl'}
<center>

<form enctype="multipart/form-data" action="musicup.php" method="POST">
<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">Upload Music (Max per-file size: {$max_file_size_mb} / Max combined size: {$max_upload_size_mb})</td></tr>

{assign var='curclass' value='songmain'}
{section name=upform start=1 loop=10 step=1}
	<tr><td valign="top" align="right" class="{$curclass}" width="25%">Music File:</td><td valign="top" class="{$curclass}"><input type="file" name="musfile[]"></td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/section}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Upload:</td><td valign="top" class="{$curclass}"><input type="submit" value="Upload File(s)"></td></tr>
</table></form>
<br />
<form enctype="multipart/form-data" action="musicup.php" method="POST">
<table class="songtable" cellspacing=0 width="75%">
<tr><td colspan=2 class="songheader">Download music from URLs</td></tr>
{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">URLs (one per line):</td><td valign="top" class="{$curclass}"><textarea cols=80 rows=10 wrap="virtual" name="musurl"></textarea></td></tr>
{assign var='curclass' value='songalt'}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Upload:</td><td valign="top" class="{$curclass}"><input type="submit" value="Download File(s)"></td></tr>
	
</table></form>
</center><br />
{include file='admin_footer.tpl'}