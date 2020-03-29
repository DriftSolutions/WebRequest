{include file='admin_header.tpl'}
<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2>WebRequest Version</th>
	</tr>
</thead>

<tbody>

<tr>
	<td valign="top" class="text-right">Current Version:</td><td valign="top">{$webreq_version}</td>
</tr>
<tr>
	<td valign="top" class="text-right">Remote Version:</td>
	<td valign="top">{$remote_version}</td>
</tr>

<tr>
	<td valign="top" class="text-right">Update:</td>
	<td valign="top">
{if $needUpdate == 1}
[<a href="{$download_url}">Get WebRequest Update</a>]
{else}
You are up to date!
{/if}
	</td>
</tr>

</tbody>

</table>
</div>
{include file='admin_footer.tpl'}