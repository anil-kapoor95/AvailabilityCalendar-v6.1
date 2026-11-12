<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFront extends pjAppController
{
	public $defaultCaptcha = 'PHPJabbersCaptcha';
	
	public $defaultLocale = 'front_locale_id';
	
	public $defaultCalendar = 'ABCalendar';
	
	public function __construct()
	{
		$this->setLayout('pjActionFront');
		
		self::allowCORS();
	}
	
	public function afterFilter()
	{
		$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC')->findAll()->getData();
		
		$this->set('locale_arr', $locale_arr);
	}
	
	public function beforeFilter()
	{
		if (isset($_GET['cid']) && (int) $_GET['cid'] > 0)
		{
			$OptionModel = pjOptionModel::factory();
			$this->option_arr = $OptionModel->getPairs($_GET['cid']);
			$this->set('option_arr', $this->option_arr);
			$this->setTime();
		}
		
		if (!isset($_SESSION[$this->defaultLocale]))
		{
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->setLocaleId($locale_arr[0]['id']);
			}
		}
		if(isset($_GET['locale']) && (int) $_GET['locale'] > 0 && (!isset($_SESSION[$this->defaultLocale]) || (isset($_SESSION[$this->defaultLocale]) && (int) $_GET['locale'] != $_SESSION[$this->defaultLocale] )))
		{
			$_SESSION[$this->defaultLocale] = (int) $_GET['locale'];
		}
		
		if (!in_array($_GET['action'], array('pjActionLoadCss', 'pjActionLoadAvailabilityCss')))
		{
			if(isset($_GET['locale']) && (int) $_GET['locale'] > 0 && ($_GET['action'] == 'pjActionLoad' || $_GET['action'] == 'pjActionLoadAvailability'))
			{
				$this->loadSetFields(true);
			}else{
				$this->loadSetFields();
			}
		}
	}
	
	public function beforeRender()
	{
		if (isset($_GET['iframe']))
		{
			$this->setLayout('pjActionIframe');
		}
	}
	
	public function pjActionCaptcha()
	{
		$this->setAjax(true);
		header("Cache-Control: max-age=3600, private");
		$Captcha = new pjCaptcha(PJ_WEB_PATH . 'obj/Anorexia.ttf', $this->defaultCaptcha, 6);
		$Captcha->setImage(PJ_IMG_PATH . 'button.png');
		$Captcha->init(isset($_GET['rand']) ? $_GET['rand'] : null);
		exit;
	}
	
	public function pjActionCheckCaptcha()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			echo @$_SESSION[$this->defaultCaptcha] === strtoupper($_GET['captcha']) ? 'true' : 'false';
		}
		exit;
	}

	public function pjActionLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['locale_id']))
			{
				$this->pjActionSetLocale($_GET['locale_id']);
				$this->loadSetFields(true);
			}
		}
		exit;
	}
	
	private function pjActionSetLocale($locale)
	{
		if ((int) $locale > 0)
		{
			$_SESSION[$this->defaultLocale] = (int) $locale;
		}
		return $this;
	}
	
	public function pjActionGetLocale()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
	}
	
	public function pjActionLoadAvailabilityCss()
	{
		header("Content-Type: text/css; charset=utf-8");
	
		$pjOptionModel = pjOptionModel::factory();
		$calendar_arr = pjCalendarModel::factory()->findAll()->getData();
		
		# Modern: the first calendar that uses it provides the design of the whole availability table
		$modern_opts = NULL;
		foreach ($calendar_arr as $calendar)
		{
			$option_arr = $pjOptionModel->reset()->getPairs($calendar['id']);
			if (isset($option_arr['o_theme']) && $option_arr['o_theme'] == 'modern')
			{
				$modern_opts = $option_arr;
				break;
			}
		}
		if (!is_null($modern_opts))
		{
			# @import must be the first rule of the stylesheet (fonts of all Modern calendars)
			$row_arr = array();
			foreach ($calendar_arr as $calendar)
			{
				$row_arr[$calendar['id']] = $pjOptionModel->reset()->getPairs($calendar['id']);
			}
			echo pjAppController::getModernFontsImportAll(array_merge(array($modern_opts), $row_arr));
		}
	
		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		$arr = array(
				array('file' => 'ABCalendar.Availability.css', 'path' => PJ_CSS_PATH),
				array('file' => 'ABCalendar.Availability.txt', 'path' => PJ_CSS_PATH),
				array('file' => 'perfect-scrollbar.min.css', 'path' => $dm->getPath('pj_perfect_scrollbar') . 'css/'),
		);
		foreach ($arr as $item)
		{
			ob_start();
			@readfile($item['path'] . $item['file']);
			$string = ob_get_contents();
			ob_end_clean();
				
			if ($string !== FALSE)
			{
				echo str_replace(
						array('../img/', '[background_nav]'),
						array(PJ_IMG_PATH, '#187c9a'),
						$string) . "\n";
			}
		}
	
		if (!is_null($modern_opts))
		{
			echo pjAppController::getModernCss($modern_opts, '.abAvailability', 'modern.availability.css');
			# every calendar row (and its name) uses the appearance of its own calendar
			foreach ($row_arr as $cid => $row_options)
			{
				echo pjAppController::getModernRowCss($row_options, '.abAvailability .abCal-id-' . (int) $cid . ', .abAvailability .abCal-t-' . (int) $cid);
			}
			exit;
		}
	
		$arr = $calendar_arr;
	
		ob_start();
		@readfile(PJ_CSS_PATH . 'availability.txt');
		$string = ob_get_contents();
		ob_end_clean();
	
		foreach ($arr as $calendar)
		{
			$option_arr = $pjOptionModel->reset()->getPairs($calendar['id']);
			if ($string !== FALSE && isset($option_arr['o_background_available']))
			{
				echo str_replace(
					array(
						'[calendarContainer]',
						'[URL]',
						'[cell_width]',
						'[cell_height]',
						'[background_available]',
						'[c_background_available]',
						'[background_booked]',
						'[c_background_booked]',
						'[background_empty]',
						'[background_month]',
						'[background_past]',
						'[background_pending]',
						'[c_background_pending]',
						'[background_select]',
						'[background_weekday]',
						'[border_inner]',
						'[border_inner_size]',
						'[border_outer]',
						'[border_outer_size]',
						'[color_available]',
						'[color_booked]',
						'[color_legend]',
						'[color_month]',
						'[color_past]',
						'[color_pending]',
						'[color_weekday]',
						'[font_family]',
						'[font_family_legend]',
						'[font_size_available]',
						'[font_size_booked]',
						'[font_size_legend]',
						'[font_size_month]',
						'[font_size_past]',
						'[font_size_pending]',
						'[font_size_weekday]',
						'[font_style_available]',
						'[font_style_booked]',
						'[font_style_legend]',
						'[font_style_month]',
						'[font_style_past]',
						'[font_style_pending]',
						'[font_style_weekday]'
					),
					array(
						'.abCal-id-' . $calendar['id'],
						PJ_INSTALL_URL,
						43,
						31,
						$option_arr['o_background_available'],
						str_replace('#', '', $option_arr['o_background_available']),
						$option_arr['o_background_booked'],
						str_replace('#', '', $option_arr['o_background_booked']),
						$option_arr['o_background_empty'],
						$option_arr['o_background_month'],
						$option_arr['o_background_past'],
						$option_arr['o_background_pending'],
						str_replace('#', '', $option_arr['o_background_pending']),
						$option_arr['o_background_select'],
						$option_arr['o_background_weekday'],
						$option_arr['o_border_inner'],
						$option_arr['o_border_inner_size'],
						$option_arr['o_border_outer'],
						$option_arr['o_border_outer_size'],
						$option_arr['o_color_available'],
						$option_arr['o_color_booked'],
						$option_arr['o_color_legend'],
						$option_arr['o_color_month'],
						$option_arr['o_color_past'],
						$option_arr['o_color_pending'],
						$option_arr['o_color_weekday'],
						$option_arr['o_font_family'],
						$option_arr['o_font_family_legend'],
						$option_arr['o_font_size_available'],
						$option_arr['o_font_size_booked'],
						$option_arr['o_font_size_legend'],
						$option_arr['o_font_size_month'],
						$option_arr['o_font_size_past'],
						$option_arr['o_font_size_pending'],
						$option_arr['o_font_size_weekday'],
						$option_arr['o_font_style_available'],
						$option_arr['o_font_style_booked'],
						$option_arr['o_font_style_legend'],
						$option_arr['o_font_style_month'],
						$option_arr['o_font_style_past'],
						$option_arr['o_font_style_pending'],
						$option_arr['o_font_style_weekday']
					),
					$string
				);
			}
		}
	
		exit;
	}
	
	public function pjActionLoadCss()
	{
		$arr = array(
				array('file' => 'ABCalendar.css', 'path' => PJ_CSS_PATH),
				array('file' => 'ABFonts.min.css', 'path' => PJ_CSS_PATH)
		);
		header("Content-Type: text/css; charset=utf-8");
		$modern = isset($this->option_arr['o_theme']) && $this->option_arr['o_theme'] == 'modern';
		if ($modern)
		{
			# @import must be the first rule of the stylesheet
			echo pjAppController::getModernFontsImport(pjAppController::getModernSettings(isset($this->option_arr['o_modern_colors']) ? $this->option_arr['o_modern_colors'] : ''));
		}
		foreach ($arr as $item)
		{
			ob_start();
			@readfile($item['path'] . $item['file']);
			$string = ob_get_contents();
			ob_end_clean();
				
			if ($string !== FALSE)
			{
				echo str_replace(
						array('../img/', '../fonts/'),
						array(PJ_IMG_PATH, PJ_FONT_PATH),
						$string) . "\n";
			}
		}
	
		if ($modern)
		{
			echo pjAppController::getModernCss($this->option_arr, '#abWrapper_' . (int) $_GET['cid'], 'modern.css');
			exit;
		}
		
		ob_start();
		@readfile(PJ_CSS_PATH . 'ABCalendar.txt');
		$string = ob_get_contents();
		ob_end_clean();
	
		if ($string !== FALSE && isset($this->option_arr['o_show_week_numbers']))
		{
			echo str_replace(
				array(
					'[calendarContainer]',
					'[URL]',
					'[cell_width]',
					'[cell_height]',
					'[background_available]',
					'[c_background_available]',
					'[background_booked]',
					'[c_background_booked]',
					'[background_empty]',
					'[background_month]',
					'[background_nav]',
					'[background_nav_hover]',
					'[background_past]',
					'[background_pending]',
					'[c_background_pending]',
					'[background_select]',
					'[background_weekday]',
					'[border_inner]',
					'[border_inner_size]',
					'[border_outer]',
					'[border_outer_size]',
					'[color_available]',
					'[color_booked]',
					'[color_legend]',
					'[color_month]',
					'[color_past]',
					'[color_pending]',
					'[color_weekday]',
					'[font_family]',
					'[font_family_legend]',
					'[font_size_available]',
					'[font_size_booked]',
					'[font_size_legend]',
					'[font_size_month]',
					'[font_size_past]',
					'[font_size_pending]',
					'[font_size_weekday]',
					'[font_style_available]',
					'[font_style_booked]',
					'[font_style_legend]',
					'[font_style_month]',
					'[font_style_past]',
					'[font_style_pending]',
					'[font_style_weekday]'
				),
				array(
					'#abWrapper_' . $_GET['cid'],
					PJ_INSTALL_URL,
					number_format((100 / ((int) $this->option_arr['o_show_week_numbers'] === 1 ? 8 : 7)), 2, '.', ''),
					number_format(100 / 8, 2, '.', ''),
					$this->option_arr['o_background_available'],
					str_replace('#', '', $this->option_arr['o_background_available']),
					$this->option_arr['o_background_booked'],
					str_replace('#', '', $this->option_arr['o_background_booked']),
					$this->option_arr['o_background_empty'],
					$this->option_arr['o_background_month'],
					$this->option_arr['o_background_nav'],
					$this->option_arr['o_background_nav_hover'],
					$this->option_arr['o_background_past'],
					$this->option_arr['o_background_pending'],
					str_replace('#', '', $this->option_arr['o_background_pending']),
					$this->option_arr['o_background_select'],
					$this->option_arr['o_background_weekday'],
					$this->option_arr['o_border_inner'],
					$this->option_arr['o_border_inner_size'],
					$this->option_arr['o_border_outer'],
					$this->option_arr['o_border_outer_size'],
					$this->option_arr['o_color_available'],
					$this->option_arr['o_color_booked'],
					$this->option_arr['o_color_legend'],
					$this->option_arr['o_color_month'],
					$this->option_arr['o_color_past'],
					$this->option_arr['o_color_pending'],
					$this->option_arr['o_color_weekday'],
					$this->option_arr['o_font_family'],
					$this->option_arr['o_font_family_legend'],
					$this->option_arr['o_font_size_available'],
					$this->option_arr['o_font_size_booked'],
					$this->option_arr['o_font_size_legend'],
					$this->option_arr['o_font_size_month'],
					$this->option_arr['o_font_size_past'],
					$this->option_arr['o_font_size_pending'],
					$this->option_arr['o_font_size_weekday'],
					$this->option_arr['o_font_style_available'],
					$this->option_arr['o_font_style_booked'],
					$this->option_arr['o_font_style_legend'],
					$this->option_arr['o_font_style_month'],
					$this->option_arr['o_font_style_past'],
					$this->option_arr['o_font_style_pending'],
					$this->option_arr['o_font_style_weekday']
				),
				$string
			);
		}
		exit;
	}
	
	public function pjActionLoadAvailability()
	{
		header("Content-Type: text/javascript; charset=utf-8");
	
		if ((isset($_GET['year']) && preg_match('/^(19|20)\d{2}$/', $_GET['year'])) === FALSE)
		{
			$arr = pjCalendarModel::factory()
				->select("t1.*, t2.value AS `o_timezone`")
				->join('pjOption', "t2.foreign_id=t1.id AND t2.key='o_timezone'", 'inner')
				->orderBy('t1.id ASC')
				->findAll()
				->limit(1)
				->getDataIndex(0);
	
			if ($arr !== FALSE && isset($arr['o_timezone']))
			{
				pjFront::pjActionSetTime((int) (strpos($arr['o_timezone'], '::') !== false ? substr($arr['o_timezone'], strrpos($arr['o_timezone'], '::') + 2) : $arr['o_timezone']));
			}
		}
	}
	
	private static function pjActionSetTime($timezone)
	{
		$offset = $timezone / 3600;
		if ($offset > 0)
		{
			$offset = "-".$offset;
		} elseif ($offset < 0) {
			$offset = "+".abs($offset);
		} elseif ($offset === 0) {
			$offset = "+0";
		}
	
		pjAppController::setTimezone('Etc/GMT' . $offset);
		if (strpos($offset, '-') !== false)
		{
			$offset = str_replace('-', '+', $offset);
		} elseif (strpos($offset, '+') !== false) {
			$offset = str_replace('+', '-', $offset);
		}
		pjAppController::setMySQLServerTime($offset . ":00");
	
		return;
	}
	
	public function pjActionGetAvailability()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$locale = isset($_GET['locale']) && !empty($_GET['locale']) ? (int) $_GET['locale'] : $this->pjActionGetLocale();
				
			$pjCalendarModel = pjCalendarModel::factory()
				->select("t1.*, t2.content AS `title`, t3.value AS `o_bookings_per_day`, t4.value AS `o_timezone`")
				->join('pjMultiLang', "t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='$locale'", 'left outer')
				->join('pjOption', "t3.foreign_id=t1.id AND t3.key='o_bookings_per_day'", 'left outer')
				->join('pjOption', "t4.foreign_id=t1.id AND t4.key='o_timezone'", 'left outer');
			$pjDateModel = pjDateModel::factory();
			$pjOptionModel = pjOptionModel::factory();
			$arr = $pjCalendarModel->orderBy('t1.id ASC')->findAll()->getData();
				
			$last_timezone = NULL;
			foreach ($arr as $k => $calendar)
			{
				# Set timezone per calendar
				if (isset($calendar['o_timezone']) && $last_timezone != $calendar['o_timezone'])
				{
					# Cache timezone
					$last_timezone = $calendar['o_timezone'];
					# the option is stored as "list|of|values::selected" - use the selected value only (otherwise the first one, UTC-12, was used)
					$tz_value = strpos($calendar['o_timezone'], '::') !== false ? substr($calendar['o_timezone'], strrpos($calendar['o_timezone'], '::') + 2) : $calendar['o_timezone'];
			
					pjFront::pjActionSetTime((int) $tz_value);
				}
	
				list($Y, $n) = explode("-", date("Y-n"));
				$year = isset($_GET['year']) && !empty($_GET['year']) ? (int) $_GET['year'] : $Y;
				$month = isset($_GET['month']) && !empty($_GET['month']) ? (int) $_GET['month'] : $n;
	
				$arr[$k]['date_arr'] = $pjDateModel->getDates(
						$calendar['id'],
						date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)),
						date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year)),
						$pjOptionModel->reset()->getPairs($calendar['id'])
				);
			}
											
			$this->set('arr', $arr);
		}
	}
	
	public function pjActionGetCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($this->option_arr['o_timezone']))
			{
				if (!isset($_GET['month']) && !isset($_GET['year']))
				{
					list($m, $y) = explode("-", date("n-Y"));
				} else {
					$m = (int) $_GET['month'];
					$y = (int) $_GET['year'];
				}
	
				$ABCalendar = new pjABCalendar();
				$ABCalendar
					->setWeekTitle(__('lblWeekTitle', true, false))
					->setShowNextLink((int) $_GET['view'] > 1 ? false : true)
					->setShowPrevLink((int) $_GET['view'] > 1 ? false : true)
					->setPrevLink("")
					->setNextLink("")
					->set('calendarId', $_GET['cid'])
					->set('reservationsInfo', pjDateModel::factory()
						->getDates(
								$_GET['cid'],
								date("Y-m-d", mktime(0, 0, 0, $m, 1, $y)),
								date("Y-m-d", mktime(23, 59, 59, $m + $_GET['view'], 0, $y)),
								$this->option_arr
						)
					)
					->set('options', $this->option_arr)
					->set('weekNumbers', (int) $this->option_arr['o_show_week_numbers'] === 1 ? true : false)
					->setStartDay($this->option_arr['o_week_start'])
					->setDayNames(__('day_names', true))
					->setWeekDays(__('days', true))
					->setMonthNames(__('months', true))
					->setLocale(pjRegistry::getInstance()->get('fields'));
				
				$this->set('ABCalendar', $ABCalendar);
			}
		}
	}
	
	public function pjActionImage()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	
		$w = isset($_GET['width']) && (int) $_GET['width'] > 0 ? intval($_GET['width']) : 100;
		$h = isset($_GET['height']) && (int) $_GET['height'] > 0 ? intval($_GET['height']) : 100;
	
		$width = $w * 10;
		$height = $h * 10;
	
		$image = imagecreatetruecolor($width, $height);
		if (function_exists('imageantialias'))
		{
			imageantialias($image, true);
		}
		$backgroundColor = pjUtil::html2rgb($_GET['color1']);
		$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
		imagefill($image, 0, 0, $color);
	
		if (isset($_GET['color2']) && !empty($_GET['color2']))
		{
			if ($_GET['color1'] == $_GET['color2'])
			{
				$backgroundColor = pjUtil::html2rgb('ffffff');
				$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
	
				$values = array(
					0, $height-2,
					$width-2, 0,
					$width, 0,
					$width, 1,
					1, $height,
					0, $height,
					0, $height-1
				);
				imagefilledpolygon($image, $values, 7, $color);
			} else {
				$backgroundColor = pjUtil::html2rgb($_GET['color2']);
				$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
				$values = array(
					$width,  0,  
					$width,  $height,
					0, $height,
					$width,  0
				);
				imagefilledpolygon($image, $values, 4, $color);
			}
		}
		$new_image = imagecreatetruecolor($w, $h);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $w, $h, $width, $height);

		header('Content-Type: image/jpeg');
		imagejpeg($new_image, null, 100);
		imagedestroy($image);
		imagedestroy($new_image);
		exit;
	}
	
	public function pjActionLoad()
	{
		header("Content-Type: text/javascript; charset=utf-8");
		if (isset($_GET['locale']) && (int) $_GET['locale'] > 0)
		{
			$this->loadSetFields(true);
		}
	}
	
	public function pjActionLoadCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			
		}
	}
	
	public function pjActionLoadAvail()
	{
		$this->setAjax(true);
	}
	
	public function isXHR()
	{
		return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
	}
	
	static protected function allowCORS()
	{
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
		header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
		header("Access-Control-Allow-Origin: $origin");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With");
	}
}
?>