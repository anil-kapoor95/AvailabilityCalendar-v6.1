<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOptionModel extends pjAppModel
{
	protected $primaryKey = NULL;
	
	protected $table = 'options';
	
	protected $schema = array(
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'key', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'tab_id', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'value', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'label', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'varchar', 'default' => 'string'),
		array('name' => 'order', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'is_visible', 'type' => 'tinyint', 'default' => 1),
		array('name' => 'style', 'type' => 'varchar', 'default' => 'string')
	);
	
	public static function factory($attr=array())
	{
		return new pjOptionModel($attr);
	}
	
	public function getAllPairs($foreign_id)
	{
		return $this->where('t1.foreign_id', $foreign_id)->findAll()->getDataPair('key', 'value');
	}
	
	public function getPairs($foreign_id)
	{
		$_arr = $this
			->where('t1.foreign_id', $foreign_id)
			->orWhere('t1.key', 'private_key')
			->findAll()->getData();
		$arr = array();
		foreach ($_arr as $row)
		{
			switch ($row['type'])
			{
				case 'enum':
				case 'bool':
					list(, $arr[$row['key']]) = explode("::", $row['value']);
					break;
				default:
					$arr[$row['key']] = $row['value'];
					break;
			}
		}
		return $arr;
	}

	public function init($calendar_id)
	{
		$data = array(
			array($calendar_id, 'o_week_start', 1, '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 4, 1, ''),
			array($calendar_id, 'o_date_format', 1, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d-m-Y', 'd.m.Y (25.09.2012)|m.d.Y (09.25.2012)|Y.m.d (2012.09.25)|j.n.Y (25.9.2012)|n.j.Y (9.25.2012)|Y.n.j (2012.9.25)|d/m/Y (25/09/2012)|m/d/Y (09/25/2012)|Y/m/d (2012/09/25)|j/n/Y (25/9/2012)|n/j/Y (9/25/2012)|Y/n/j (2012/9/25)|d-m-Y (25-09-2012)|m-d-Y (09-25-2012)|Y-m-d (2012-09-25)|j-n-Y (25-9-2012)|n-j-Y (9-25-2012)|Y-n-j (2012-9-25)', 'enum', 5, 1, ''),
			array($calendar_id, 'o_month_year_format', 1, 'Month Year|Month, Year|Year Month|Year, Month::Month Year', '', 'enum', 6, 1, ''),
			array($calendar_id, 'o_timezone', 1, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 7, 1, ''),
			array($calendar_id, 'o_show_week_numbers', 1, '1|0::1', '', 'bool', 9, 1, ''),
			array($calendar_id, 'o_show_legend', 1, '1|0::1', '', 'bool', 10, 1, ''),
			array($calendar_id, 'o_price_based_on', 1, 'days|nights::days', 'Days|Nights', 'enum', 11, 1, ''),
			array($calendar_id, 'o_send_email', 1, 'mail|smtp::mail', 'PHP mail()|SMTP', 'enum', 12, 0, ''),
			array($calendar_id, 'o_smtp_host', 1, '', '', 'string', 13, 0, ''),
			array($calendar_id, 'o_smtp_user', 1, '', '', 'string', 14, 0, ''),
			array($calendar_id, 'o_smtp_pass', 1, '', '', 'string', 15, 0, ''),
			array($calendar_id, 'o_smtp_port', 1, '25', '', 'int', 16, 0, ''),
			
			array($calendar_id, 'o_background_available', 2, '#80b369', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_booked', 2, '#da5350', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_empty', 2, '#f8f6f0', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_month', 2, '#248faf', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_nav', 2, '#187c9a', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_nav_hover', 2, '#116b86', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_past', 2, '#f2f0ea', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_pending', 2, '#f9ce67', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_background_weekday', 2, '#ffffff', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_border_inner', 2, '#e0dfde', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_border_inner_size', 2, '0|1|2|3|4|5|6|7|8|9::1', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_border_outer', 2, '#000000', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_border_outer_size', 2, '0|1|2|3|4|5|6|7|8|9::0', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_color_available', 2, '#ffffff', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_booked', 2, '#ffffff', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_legend', 2, '#676F71', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_month', 2, '#ffffff', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_past', 2, '#c5c6c7', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_pending', 2, '#ffffff', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_color_weekday', 2, '#737576', '', 'color', 1, 1, ''),
			array($calendar_id, 'o_font_family', 2, 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana::Arial', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_family_legend', 2, 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana::Arial', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_available', 2, '10|12|14|16|18|20|22|24|26|28|30::14', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_booked', 2, '10|12|14|16|18|20|22|24|26|28|30::14', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_legend', 2, '10|12|14|16|18|20|22|24|26|28|30::12', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_month', 2, '10|12|14|16|18|20|22|24|26|28|30::20', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_past', 2, '10|12|14|16|18|20|22|24|26|28|30::14', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_pending', 2, '10|12|14|16|18|20|22|24|26|28|30::14', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_size_weekday', 2, '10|12|14|16|18|20|22|24|26|28|30::12', '', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_available', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_booked', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_legend', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: normal', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_month', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: normal', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_past', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_pending', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			array($calendar_id, 'o_font_style_weekday', 2, 'font-weight: normal|font-weight: bold|font-style: italic|font-style: underline|font-weight: bold; font-style: italic::font-weight: normal', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 1, 1, ''),
			
			array($calendar_id, 'o_multi_lang', 99, '1|0::1', '', 'bool', '', 0, ''),
			array($calendar_id, 'o_fields_index', 99, 'd874fcc5fe73b90d770a544664a3775d', '', 'string', '', 0, '')
		);
		
		$this->setBatchFields(array('foreign_id', 'key', 'tab_id', 'value', 'label', 'type', 'order', 'is_visible', 'style'));
		$this->setBatchRows($data);
		$this->insertBatch();
	}
}
?>