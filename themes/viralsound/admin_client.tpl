{include file='admin_header.tpl'}
{$script}
<center>
<table class="songtableborder" cellspacing=0 width="50%">
<tr><td colspan=2 class="songheader" align="center">Simple IRCBot Client</td></td></tr>

{assign var='curclass' value='songmain'}
<tr><td align="center" class="{$curclass}" width="50%">Server Status</td><td align="center" class="{$curclass}" width="50%">Stream Status</td></tr>

{assign var='curclass' value='songalt'}
<tr><td id="server_status" rowspan=2 align="center" class="{$curclass}vert"></td><td align="center" id="stream_status_1" class="{$curclass}"></td></tr>
<tr><td align="center" id="stream_status_2" class="{$curclass}"></td></tr>

{assign var='curclass' value='songmain'}
<tr><td align="center" class="{$curclass}">AutoDJ Commands</td><td align="center" class="{$curclass}">IRCBot Commands</td></tr>

{assign var='curclass' value='songalt'}
<tr><td align="center" class="{$curclass}vert">
<input type="button" value="Play" onClick="javascript:doCommand(50); return false;"><br />
<input type="button" value="Next" onClick="javascript:doCommand(51); return false;"><br />
<input type="button" value="Force Off" onClick="javascript:doCommand(49); return false;"><br />
<input type="button" value="Reload" onClick="javascript:doCommand(52); return false;"><br />
</td><td align="center" class="{$curclass}vert">
<input type="button" value="Get Current DJ" onClick="javascript:doCommand(18); return false;"><br />
<input type="button" value="Toggle Spam" onClick="javascript:doCommand(32); return false;"><br />
<input type="button" value="Rehash" onClick="javascript:doCommand(38); return false;"><br />
<input type="button" value="Restart" onClick="javascript:doCommand(35); return false;"><br />
<input type="button" value="Die" onClick="javascript:doCommand(33); return false;"><br />
</td></tr>
 
</table>
</center><br />
{include file='admin_footer.tpl'}