<tr><td valign="top" class="{$curclass}">{$msg}</td></tr>
{if $curclass == "songmain"}
	{assign var='curclass' value='songalt'}	
{else}
	{assign var='curclass' value='songmain'}
{/if}