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
	$locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : NULL;
	if (is_null($locale))
	{
		foreach ($tpl['lp_arr'] as $v)
		{
			if ($v['is_default'] == 1)
			{
				$locale = $v['id'];
				break;
			}
		}
	}
	if (is_null($locale))
	{
		$locale = @$tpl['lp_arr'][0]['id'];
	}
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	?>
	
	<?php 
	pjUtil::printNotice(__('infoAddCalendarTitle', true), __('infoAddCalendarDesc', true));
	if(count($tpl['lp_arr']) > 1)
	{
		?>
		<div class="multilang"></div>
		<?php
	} 
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionCreate" method="post" id="frmCreateCalendar" class="form pj-form" autocomplete="off">
		<input type="hidden" name="calendar_create" value="1" />
		<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
		<p>
			<label class="title"><?php __('lblUser'); ?></label>
			<span class="inline_block">
				<select name="user_id" id="user_id" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach ($tpl['user_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<?php
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('lblName'); ?>:</label>
				<span class="inline_block">
					<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w300<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" />
					<?php
					if(count($tpl['lp_arr']) > 1)
					{
						?>
						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
						<?php
					} 
					?>
				</span>
			</p>
			<?php
		}
		?>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
			<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminCalendars&action=pjActionIndex';" />
		</p>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
	<?php
	if(count($tpl['lp_arr']) > 1)
	{ 
		?>
		(function ($) {
			$(function() {
				$(".multilang").multilang({
					langs: <?php echo $tpl['locale_str']; ?>,
					flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
					tooltip: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet faucibus enim.",
					select: function (event, ui) {
						// Callback, e.g. ajax requests or whatever
					}
				});
				$(".multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
			});
		})(jQuery_1_8_2);
		<?php
	} 
	?>
	</script>
	<?php
}
?>