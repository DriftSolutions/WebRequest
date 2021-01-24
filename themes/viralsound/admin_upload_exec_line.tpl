<tr><td valign="top" class="{$curclass}">{$msg}</td></tr>
{if $curclass == "songmain"}
	{assign var='curclass' value='songalt' scope='global'}	
{else}
	{assign var='curclass' value='songmain' scope='global'}
{/if}
