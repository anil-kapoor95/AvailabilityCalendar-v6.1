<?php
if (isset($tpl['ABCalendar']))
{
	$month = $year = array();

	if (isset($_GET['year']) && isset($_GET['month']))
	{
		$y = $_GET['year'];
		$m = $_GET['month'];
	} else {
		list($y, $m) = explode("-", date("Y-m"));
	}
	
	if (isset($_GET['view']) && (int) $_GET['view'] > 1)
	{
		$next_month = $m + (int) $_GET['view'] <= 12 ? $m + (int) $_GET['view'] : $m + (int) $_GET['view'] - 12;
		$next_year = $m + (int) $_GET['view'] <= 12 ? $y : $y + 1;
		$prev_month = $m - (int) $_GET['view'] >= 1 ? $m - (int) $_GET['view'] : $m - (int) $_GET['view'] + 12;
		$prev_year = $m - (int) $_GET['view'] >= 1 ? $y : $y - 1;
	}
}
include dirname(__FILE__) . '/elements/menu.php';
if (isset($tpl['ABCalendar']))
{
	$month[1] = intval($m);
	foreach (range(2, 12) as $i)
	{
		$month[$i] = ($month[1] + $i - 1) > 12 ? $month[1] + $i - 1 - 12 : $month[1] + $i - 1;
	}
	
	$year[1] = intval($y);
	foreach (range(2, 12) as $i)
	{
		$year[$i] = ($month[1] + $i - 1) > 12 ? $year[1] + 1 : $year[1];
	}	

	?> <div class="clearfix"> <?php 

	if (isset($_GET['view']))
	{
		
		switch ((int) $_GET['view'])
		{
			case 12:
				?>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[1], $year[1]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[2], $year[2]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[3], $year[3]); ?></div>
				</div>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[4], $year[4]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[5], $year[5]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[6], $year[6]); ?></div>
				</div>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[7], $year[7]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[8], $year[8]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[9], $year[9]); ?></div>
				</div>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[10], $year[10]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[11], $year[11]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[12], $year[12]); ?></div>
				</div>
				<?php
				break;
			case 6:
				?>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[1], $year[1]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[2], $year[2]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[3], $year[3]); ?></div>
				</div>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[4], $year[4]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[5], $year[5]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[6], $year[6]); ?></div>
				</div>
				<?php
				break;
			case 3:
				?>
				<div class="abRow">
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[1], $year[1]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[2], $year[2]); ?></div>
					<div class="abBox13"><?php echo $tpl['ABCalendar']->getMonthView($month[3], $year[3]); ?></div>
				</div>
				<?php
				break;
			case 1:
			default:
				echo $tpl['ABCalendar']->getMonthView($month[1], $year[1]);
				break;
		}
	} else {
		echo $tpl['ABCalendar']->getMonthView($month[1], $year[1]);
	}

	?> </div> <?php 
	
	if ((int) $tpl['option_arr']['o_show_legend'] === 1)
	{
		echo $tpl['ABCalendar']->getLegend($tpl['option_arr'], pjRegistry::getInstance()->get('fields'));
	}
}
?>