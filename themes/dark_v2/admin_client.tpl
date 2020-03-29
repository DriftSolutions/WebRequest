{include file='admin_header.tpl'}
{$script}
<div class="container">
<table class="{$table_class}">
<thead>
	<tr>
		<th colspan=2 class="text-center">Simple IRCBot Client</th>
	</tr>
</thead>

<tbody>

{assign var='curclass' value='songmain'}
<tr>
	<th class="text-center" width="50%">Server Status</th>
	<th class="text-center" width="50%">Stream Status</th>
</tr>

<tr>
	<td id="server_status" class="text-center"></td>
	<td class="text-center">
		<span id="stream_status_1" class="nobr"></span><br />
		<span id="stream_status_2" class="nobr"></span>
	</td>
</tr>

<tr>
	<td class="text-center">AutoDJ Commands</td>
	<td class="text-center">IRCBot Commands</td>
</tr>

<tr>
	<td class="text-center">
		<input type="button" class="btn btn-primary btn-block" value="Play" onClick="javascript:doCommand(50); return false;"><br />
		<input type="button" class="btn btn-primary btn-block" value="Next" onClick="javascript:doCommand(51); return false;"><br />
		<input type="button" class="btn btn-primary btn-block" value="Force Off" onClick="javascript:doCommand(49); return false;"><br />
		<input type="button" class="btn btn-primary btn-block" value="Reload" onClick="javascript:doCommand(52); return false;"><br />
	</td>
	<td class="text-center">
		<input type="button" class="btn btn-primary btn-block" value="Get Current DJ" onClick="javascript:doCommand(18); return false;"><br />
		<input type="button" class="btn btn-primary btn-block" value="Toggle Spam" onClick="javascript:doCommand(32); return false;"><br />
		<input type="button" class="btn btn-warning btn-block" value="Rehash" onClick="javascript:doCommand(38); return false;"><br />
		<input type="button" class="btn btn-warning btn-block" value="Restart" onClick="javascript:doCommand(35); return false;"><br />
		<input type="button" class="btn btn-danger btn-block" value="Die" onClick="javascript:doCommand(33); return false;"><br />
	</td>
</tr>
 
</tbody>

</table>
</div>
{include file='admin_footer.tpl'}