<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	
	<?php pjUtil::printNotice(__('infoCalendarsTitle', true), __('infoCalendarsDesc', true)); ?>
	
	<div class="b10">
		<?php
		if($controller->isAdmin())
		{ 
			?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
				<input type="hidden" name="controller" value="pjAdminCalendars" />
				<input type="hidden" name="action" value="pjActionCreate" />
				<input type="submit" class="pj-button" value="<?php __('btnAddCalendar'); ?>" />
			</form>
			<?php
		} 
		?>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
		<div class="float_right t5"></div>
		<br class="clear_both" />
	</div>

	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.currentCalendarId = <?php echo $controller->getForeignId(); ?>;
	pjGrid.isAdmin = <?php echo $controller->isAdmin() ? 'true' : 'false'; ?>;
	pjGrid.queryString = "";
	var myLabel = myLabel || {};
	myLabel.settings = "<?php __('tabSettings'); ?>";
	myLabel.edit = "<?php __('lblEdit'); ?>";
	myLabel.delete = "<?php __('lblDelete'); ?>";
	myLabel.installPreview = "<?php __('menuInstallPreview'); ?>";
	myLabel.viewCalendar = "<?php __('lblViewCalendar'); ?>";
	myLabel.more = "<?php __('lblMore'); ?>";
	myLabel.user = "<?php __('lblUser'); ?>";
	myLabel.id = "<?php __('lblID'); ?>";
	myLabel.calendar = "<?php __('lblCalendarName'); ?>";
	myLabel.deleteSelected = "<?php __('delete_selected'); ?>";
	myLabel.deleteConfirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>