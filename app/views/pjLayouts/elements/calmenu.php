<div class="bold b10"><?php echo pjSanitize::html( @$tpl['calendars'][$controller->getForeignId()]['name'] ); ?></div>
<?php
$active = ' ui-tabs-active ui-state-active';
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminCalendars' && $_GET['action'] == 'pjActionView' ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionView"><?php __('tabCalendar'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminOptions' && isset($_GET['tab']) ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;tab=1"><?php __('tabSettings'); ?></a></li>
		<?php
		if($controller->isAdmin() && $_GET['controller'] == 'pjAdminCalendars' && $_GET['action'] == 'pjActionView') 
		{
			?><li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall&id=<?php echo $_GET['id']; ?>"><?php __('menuInstallPreview'); ?></a></li><?php
		}
		?>
	</ul>
</div>