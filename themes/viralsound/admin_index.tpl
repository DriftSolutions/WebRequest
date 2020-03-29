{include file='admin_header.tpl'}
<center>
<table class="songtable" cellspacing=0 width="75%">

<tr><td colspan=2 class="songheader">WebRequest System Information</td></td></tr>
 
{assign var='curclass' value='songmain'}
{foreach from=$stats item=stat}
	<tr><td valign="middle" align="right" class="{$curclass}" width="25%">{$stat.Name}:</td><td valign="middle" class="{$curclass}">{$stat.Value}</td></tr>
	{if $curclass == "songmain"}
		{assign var='curclass' value='songalt'}	
	{else}
		{assign var='curclass' value='songmain'}
	{/if}
{/foreach}
 
</table>
</center><br />
{include file='admin_footer.tpl'}