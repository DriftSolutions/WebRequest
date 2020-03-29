{include file='admin_header.tpl'}
<center>

<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">Update WebRequest...</td></tr>

{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Current Version:</td><td valign="top" class="{$curclass}">{$webreq_version}</td></tr>
{assign var='curclass' value='songalt'}	
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Remote Version:</td><td valign="top" class="{$curclass}">{$remote_version}</td></tr>

{assign var='curclass' value='songmain'}
<tr><td valign="top" align="right" class="{$curclass}" width="25%">Update:</td><td valign="top" class="{$curclass}">
{if $needUpdate == 1}
[<a href="{$download_url}">Get WebRequest Update</a>]
{else}
You are up to date!
{/if}
</td></tr>

</table>
</center><br />
{include file='admin_footer.tpl'}