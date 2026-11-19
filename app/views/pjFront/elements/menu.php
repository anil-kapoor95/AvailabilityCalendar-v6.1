<?php
$hasReferer = isset($_GET['index']) && (int) $_GET['index'] > 0;
if ((
		(in_array($_GET['action'], array('pjActionGetCalendar')) && isset($_GET['view']) && (int) $_GET['view'] === 1)
		|| in_array($_GET['action'], array('pjActionGetAvailability'))
	)
	&& isset($_GET['locale'])
	&& (int) $_GET['locale'] > 0
	&& !$hasReferer
	)
{
	
} else {
	?>
	<div class="abMenu">
		<div class="abErrorMessage" style="display: none" data-msg="<?php echo htmlentities(pjAppController::jsonEncode(__('front_err', true))); ?>"></div>
		<?php
		if ($hasReferer)
		{
			if($_GET['action'] == 'pjActionGetCalendar')
			{
				?><a href="#" class="abReturnToAvailability">&laquo;&nbsp;<?php __('front_back_to_calendars'); ?></a><?php
			}
		}
		if ($_GET['action'] == 'pjActionGetCalendar' && isset($_GET['view']) && (int) $_GET['view'] > 1)
		{
			?>
			<ul class="abMenuNav abMenuList">
				<li class="abMenuNavPrev"><a href="#" class="abCalendarLinkMonth" data-cid="<?php echo $_GET['cid']; ?>" data-year="<?php echo $prev_year; ?>" data-month="<?php echo $prev_month; ?>"><i class="fa fa-chevron-left"></i></a></li>
				<li class="abMenuNavNext"><a href="#" class="abCalendarLinkMonth" data-cid="<?php echo $_GET['cid']; ?>" data-year="<?php echo $next_year; ?>" data-month="<?php echo $next_month; ?>"><i class="fa fa-chevron-right"></i></a></li>
			</ul>
			<?php
		}
		if (!isset($_GET['locale']) || (int) $_GET['locale'] <= 0)
		{
			if (isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && !empty($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
			{
				?>
				<?php
				$ab_lang_cur = NULL;
				foreach ($tpl['locale_arr'] as $locale)
				{
					if ((int) $controller->pjActionGetLocale() === (int) $locale['id']) { $ab_lang_cur = $locale; break; }
				}
				if (!$ab_lang_cur) { $ab_lang_cur = $tpl['locale_arr'][0]; }
				$ab_lang_code = strtoupper(preg_replace('/^.*[-_]/', '', (string) $ab_lang_cur['language_iso']));
				?>
				<details class="abLang">
					<summary class="abLangCurrent" title="<?php echo pjSanitize::html($ab_lang_cur['title']); ?>">
						<img src="<?php echo PJ_INSTALL_URL . 'core/framework/libs/pj/img/flags/' . $ab_lang_cur['file'] ?>" alt="" />
						<span class="abLangCode"><?php echo pjSanitize::html($ab_lang_code); ?></span>
						<span class="abLangCaret" aria-hidden="true"></span>
					</summary>
					<ul class="abLangMenu">
						<?php
						foreach ($tpl['locale_arr'] as $locale)
						{
							$is_cur = (int) $locale['id'] === (int) $ab_lang_cur['id'];
							?><li<?php echo $is_cur ? ' class="active"' : NULL; ?>><a href="#" class="abSelectorLocale<?php echo $is_cur ? ' abLocaleFocus' : NULL; ?>" data-id="<?php echo $locale['id']; ?>" title="<?php echo pjSanitize::html($locale['title']); ?>"><img src="<?php echo PJ_INSTALL_URL . 'core/framework/libs/pj/img/flags/' . $locale['file'] ?>" alt="" /><span><?php echo pjSanitize::html($locale['title']); ?></span></a></li><?php
						}
						?>
					</ul>
				</details>
				<?php
			}
		}
		?>
	</div>
	<?php
}
?>