{if $show_menu}
<div class="container text-center">
	Admins and DJs: <a href="admin">Log in here...</a> (user/pass is the same as your RadioBot user/pass)<br />
	Copyright 2007-{$smarty.now|date_format:"%Y"} ShoutIRC.com (except as noted below) and is licensed under <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">GPL v2.0</a>.<br />
	Rating and pagination graphics by <a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">famfamfam.com</a> (<a href="http://creativecommons.org/licenses/by/2.5/" target="_blank">CC BY 2.5</a>).<br />
	Template engine is provided by <a href="http://www.smarty.net/" target="_blank">Smarty</a>.<br />
	<font size=-1>Queries used: {$db_queries}</font>
</div>
{/if}
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/{$bootstrap_version}/js/bootstrap.min.js"></script>
<script src="themes/{$config.theme}/theme.js"></script>
</body></html>
