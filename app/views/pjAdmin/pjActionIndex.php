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
	
	$months = __('months', true);
	$days = __('days', true);
	?>
	<div class="clear_left t20 overflow">
		<div class="float_left black pt15">
			<span class="gray"><?php echo ucfirst(__('lblDashLastLogin', true)); ?>:</span>
			<?php
			list($month_index, $other) = explode("_", date("n_d, Y H:i", strtotime($_SESSION[$controller->defaultUser]['last_login'])));
			printf("%s %s", $months[$month_index], $other);
			?>
		</div>
		<div class="float_right overflow">
		<?php
		list($hour, $day, $month_index, $other) = explode("_", date("H:i_w_n_d, Y"));
		?>
			<div class="dashboard_date">
				<abbr><?php echo @$days[$day]; ?></abbr>
				<?php printf("%s %s", $months[$month_index], $other); ?>
			</div>
			<div class="dashboard_hour"><?php echo $hour; ?></div>
		</div>
	</div>
	<?php
}
?>