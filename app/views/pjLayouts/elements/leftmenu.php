<?php
if (pjObject::getPlugin('pjOneAdmin') !== NULL && $controller->isAdmin())
{
	$controller->requestAction(array('controller' => 'pjOneAdmin', 'action' => 'pjActionMenu'));
}
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionDashboard" class="<?php echo $_GET['controller'] == 'pjAdminCalendars' && $_GET['action'] == 'pjActionDashboard' ? 'menu-focus' : NULL; ?>"><span class="menu-availability">&nbsp;</span><?php __('menuAvailability'); ?></a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionIndex" class="<?php
			echo (in_array($_GET['controller'], array('pjAdminCalendars')) && $_GET['action'] != 'pjActionDashboard')
				|| ($_GET['controller'] == 'pjAdminOptions' && (isset($_GET['tab']) && in_array($_GET['tab'], array(1,2))))
			? 'menu-focus' : NULL; ?>"><span class="menu-calendars">&nbsp;</span><?php __('menuCalendars'); ?></a></li>
		<?php
		if ($controller->isAdmin())
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionNotifications'))) ||
			in_array($_GET['controller'], array('pjBackup', 'pjLocale'))
			? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionInstall' ? 'menu-focus' : NULL; ?>"><span class="menu-install">&nbsp;</span><?php __('menuInstallPreview'); ?></a></li>
			<?php
		}
		if ($controller->isEditor() || $controller->isOwner())
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionProfile' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuProfile'); ?></a></li>
			<?php
		}
		?>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogout"><span class="menu-logout">&nbsp;</span><?php __('menuLogout'); ?></a></li>
	</ul>
</div>
<div class="leftmenu-bottom"></div>