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
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	?>
	<style type="text/css">
	#abWrapper_<?php echo $controller->getForeignId(); ?> {
		float: left;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> table.abCalendarTable{
		margin: 0 0 10px;
		height: 285px !important;
		width: 380px !important;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarMonth{
		height: 35px !important;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarMonthPrev a,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarMonthNext a{
		height: 35px !important;
		width: 40px !important;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarWeekDay,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarWeekNum,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarToday,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPending,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPast,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarEmpty,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarDate,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPendingNightsStart,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPendingNightsEnd,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarReservedNightsStart,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarReservedNightsEnd,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsReservedReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsReservedPending,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsPendingReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsPendingPending{
		height: 35px !important;
		width: 40px !important;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPending,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarPast,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsReservedReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsReservedPending,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsPendingReserved,
	#abWrapper_<?php echo $controller->getForeignId(); ?> td.abCalendarNightsPendingPending{
		cursor: pointer !important;
	}
	.ac-button:hover{
		color: <?php echo $tpl['option_arr']['o_color_past'];?> !important;
		background-color: <?php echo $tpl['option_arr']['o_background_past'];?> !important;
	}
	#abWrapper_<?php echo $controller->getForeignId(); ?> .abCalendarLinkDate{
		width: 100% !important;
		height: 100% !important;
		position: relative !important;
		padding: 0px !important;
	}
	
	#abWrapper_<?php echo $controller->getForeignId(); ?> .abCalendarLinkDateInner{
		width: 100% !important;
		padding: 22% 0 !important;
		top: 0px !important;
		left: 0px !important;
		position: absolute !important;
	}
	</style>
	<?php include PJ_VIEWS_PATH . 'pjLayouts/elements/calmenu.php'; ?>
	
	<?php pjUtil::printNotice(__('infoViewCalendarTitle', true), __('infoViewCalendarDesc', true)); ?>
	
	<div id="abWrapper_<?php echo $controller->getForeignId(); ?>">
		<div id="abCalendar_<?php echo $controller->getForeignId(); ?>">
		<?php include dirname(__FILE__) . '/pjActionGetCal.php'; ?>
		</div>
	</div>
	
	<div class="float_right w350 t10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionView" method="post" id="frmViewCalendar" class="form pj-form" autocomplete="off">
			<input type="hidden" name="view_calendar" value="1" />
			<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
			<input type="hidden" name="status" value="" />
			<p>
				<label class="title"><?php __('lblStartDate'); ?></label>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="start_date" class="pj-form-field pointer w90 required datepicker" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['start_date']) ? pjUtil::formatDate($_GET['start_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']) : NULL; ?>" data-format="<?php echo $tpl['calendar_format'];?>" />
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
				</span>
			</p>
			<p>
				<label class="title"><?php __('lblEndDate'); ?></label>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="end_date" class="pj-form-field pointer w90 required datepicker sameDate" data-mode="<?php echo $tpl['option_arr']['o_price_based_on'];?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-format="<?php echo $tpl['calendar_format'];?>" data-msg-sameDate="<?php __('lblSameDateMsg');?>"/>
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					<input type="hidden" name="dates" id="dates" value="0" />
				</span>
			</p>
			<?php
			$buttons = __('button_statuses', true); 
			?>
			<p>
				<label class="title">&nbsp;</label>
				<span class="overflow block">
					<input type="button" value="<?php echo $buttons['available']?>" class="ac-button b15" data-status="available" style="background-color: <?php echo $tpl['option_arr']['o_background_available']?>; color: <?php echo $tpl['option_arr']['o_color_available'];?>"/>
					<input type="button" value="<?php echo $buttons['pending']?>" class="ac-button b15" data-status="pending" style="background-color: <?php echo $tpl['option_arr']['o_background_pending']?>; color: <?php echo $tpl['option_arr']['o_color_pending'];?>"/>
					<input type="button" value="<?php echo $buttons['booked']?>" class="ac-button" data-status="booked" style="background-color: <?php echo $tpl['option_arr']['o_background_booked']?>; color: <?php echo $tpl['option_arr']['o_color_booked'];?>"/>
				</span>
			</p>
		</form>
	</div>
	<div class="clear_both"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.queryString = "";
	<?php
	if (!isset($_GET['time']))
	{
		?>pjGrid.queryString += "&time=<?php list($y, $n, $j) = explode("-", date("Y-n-j")); echo mktime(0, 0, 0, $n, $j, $y); ?>";<?php
	}
	?>
	var myLabel = myLabel || {};
	myLabel.today = "<?php echo pjUtil::formatDate(date("Y-m-d"), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>";
	myLabel.exportSelected = "<?php __('lblExportSelected'); ?>";
	myLabel.deleteSelected = "<?php __('lblDeleteSelected'); ?>";
	myLabel.deleteConfirmation = "<?php __('lblDeleteConfirmation'); ?>";
	myLabel.name = "<?php __('lblName'); ?>";
	myLabel.email = "<?php __('email'); ?>";
	myLabel.id = "<?php __('lblID'); ?>";
	myLabel.status = "<?php __('lblStatus'); ?>";
	myLabel.from = "<?php __('lblReservationFrom'); ?>";
	myLabel.to = "<?php __('lblReservationTo'); ?>";
	myLabel.amount = "<?php __('lblReservationAmount'); ?>";
	</script>
	<?php
}
?>