{include file='admin_header.tpl'}
<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>WebRequest System Information</th>
	</tr>
</thead>

<tbody>
{assign var='curclass' value='songmain'}
{foreach from=$stats item=stat}
	<tr>
		<td class="text-right" width="25%">{$stat.Name}:</td>
		<td>{$stat.Value}</td>
	</tr>
{/foreach}
</tbody>

</table>
</div>
{include file='admin_footer.tpl'}