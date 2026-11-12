<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminCalendars extends pjAdmin
{
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['calendar_create']))
			{
				$id = pjCalendarModel::factory($_POST)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
						->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
						->where('t2.file IS NOT NULL')
						->orderBy('t1.sort ASC')->findAll()->getData();
					
					$this->models['Option']->init($id);
					$err = 'ACR03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjCalendar');
					}
					
					$data = $this->models['Option']->reset()->getAllPairs($id);
					pjUtil::pjActionGenerateImages($id, $data);
				} else {
					$err = 'ACR04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminCalendars&action=pjActionIndex&err=$err");
			} else {
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $v)
				{
					$lp_arr[$v['id']."_"] = $v['file'];
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$this->set('user_arr', pjUserModel::factory()->orderBy('t1.name ASC')->findAll()->getData());
		
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminCalendars.js');
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
		
	public function pjActionDeleteCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$resp = array();
			if (pjCalendarModel::factory()->where('id', $_GET['id'])->where('id !=', $this->getForeignId())->limit(1)->eraseAll()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjCalendar')->where('foreign_id', $_GET['id'])->eraseAll();
				pjOptionModel::factory()->where('foreign_id', $_GET['id'])->eraseAll();
				pjDateModel::factory()->whereIn('calendar_id', $_GET['id'])->eraseAll();
				
				$this->pjActionDeleteImages($_GET['id']);
				
				$resp['code'] = 200;
			} else {
				$resp['code'] = 100;
			}
			pjAppController::jsonResponse($resp);
		}
		exit;
	}
	
	private function pjActionDeleteImages($cid)
	{
		$arr = array(
			PJ_UPLOAD_PATH . '%u_reserved_start.jpg',
			PJ_UPLOAD_PATH . '%u_reserved_end.jpg',
			PJ_UPLOAD_PATH . '%u_pending_pending.jpg',
			PJ_UPLOAD_PATH . '%u_reserved_pending.jpg',
			PJ_UPLOAD_PATH . '%u_pending_reserved.jpg',
			PJ_UPLOAD_PATH . '%u_reserved_reserved.jpg',
			PJ_UPLOAD_PATH . '%u_pending_start.jpg',
			PJ_UPLOAD_PATH . '%u_pending_end.jpg',
		);
		
		if (is_array($cid))
		{
			foreach ($cid as $id)
			{
				foreach ($arr as $img)
				{
					@unlink(sprintf($img, $id));
				}
			}
		} else {
			foreach ($arr as $img)
			{
				@unlink(sprintf($img, $cid));
			}
		}
	}
	
	public function pjActionDeleteCalendarBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjCalendarModel::factory()->whereIn('id', $_POST['record'])->where('id !=', $this->getForeignId())->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjCalendar')->whereIn('foreign_id', $_POST['record'])->where('foreign_id !=', $this->getForeignId())->eraseAll();
				pjOptionModel::factory()->whereIn('foreign_id', $_POST['record'])->where('foreign_id !=', $this->getForeignId())->eraseAll();
				$this->pjActionDeleteImages($_POST['record']);
				pjDateModel::factory()->whereIn('calendar_id', $_POST['record'])->where('calendar_id !=', $this->getForeignId())->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionGetCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjCalendarModel = pjCalendarModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCalendar' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->join('pjUser', "t3.id=t1.user_id", 'left');
			if ($this->isOwner())
			{
				$pjCalendarModel->where('t1.user_id', $this->getUserId());
			}
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjCalendarModel->escapeString($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjCalendarModel->where('t2.content LIKE', "%$q%");
			}
				
			$column = 'name';
			$direction = 'ASC';
			$allowed_columns = array('id', 'name', 'user_name');
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array($_GET['column'], $allowed_columns) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjCalendarModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjCalendarModel->select('t1.id, t1.user_id, t2.content AS name, t3.name AS user_name')
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
		
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminCalendars.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && ($this->isAdmin() || $this->isEditor() || ($this->isOwner() && $this->getUserId() == $this->getForeignId())))
		{
			$pjCalendarModel = pjCalendarModel::factory();
			if (!in_array($_POST['column'], $pjCalendarModel->getI18n()))
			{
				$pjCalendarModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjCalendar');
			}
		}
		exit;
	}

	private function __getCalendar($cid, $year, $month, $view=1)
	{
		
		$ABCalendar = new pjABCalendar();
		$ABCalendar
			->setShowNextLink((int) $view > 1 ? false : true)
			->setShowPrevLink((int) $view > 1 ? false : true)
			->setPrevLink("")
			->setNextLink("")
			->set('calendarId', $cid)
			->set('reservationsInfo', pjDateModel::factory()
				->getDates(
					$cid,
					date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)),
					date("Y-m-d", mktime(23, 59, 59, $month + $view, 0, $year)),
					$this->option_arr
				)
			)
			->set('options', $this->option_arr)
			->set('weekNumbers', (int) $this->option_arr['o_show_week_numbers'] === 1 ? true : false)
			->setStartDay($this->option_arr['o_week_start'])
			->setDayNames(__('day_names', true))
			->setMonthNames(__('months', true))
		;
		
		$this->set('ABCalendar', $ABCalendar);
	}
	
	public function pjActionGetCal()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$this->__getCalendar($_GET['cid'], $_GET['year'], $_GET['month']);
		}
	}
	
	public function pjActionView()
	{
		$this->checkLogin();
		
		$pjCalendarModel = pjCalendarModel::factory();
		$_arr = $pjCalendarModel->find($this->getForeignId())->getData();
		if (isset($_GET['id']) && (int) $_GET['id'] > 0)
		{
			$_arr = $pjCalendarModel->reset()->find($_GET['id'])->getData();
		}
		if ($this->isAdmin() || $this->isEditor() || ($this->isOwner() && $this->getUserId() == $_arr['user_id']))
		{
			if(isset($_POST['view_calendar']))
			{
				$start_date = strtotime(pjUtil::formatDate($_POST['start_date'], $this->option_arr['o_date_format']));
				$end_date = strtotime(pjUtil::formatDate($_POST['end_date'], $this->option_arr['o_date_format']));
				$pjDateModel = pjDateModel::factory();
				
				$start_date_arr = $pjDateModel->where('calendar_id', $this->getForeignId())->where('t1.date', date("Y-m-d", $start_date))->limit(1)->findAll()->getData();
				$end_date_arr = $pjDateModel->reset()->where('calendar_id', $this->getForeignId())->where('t1.date', date("Y-m-d", $end_date))->limit(1)->findAll()->getData();
				
				for ($i = $start_date; $i <= $end_date; $i += 86400)
				{
					$item = array();
					$item['calendar_id'] = $this->getForeignId();
					$item['date'] = date("Y-m-d", $i);
					
					$item['status_morning'] = $_POST['status'];
					$item['status_afternoon'] = $_POST['status'];
					if($this->option_arr['o_price_based_on'] == 'nights')
					{
						if($i == $start_date)
						{
							$item['status_morning'] = count($start_date_arr) == 1 ? $start_date_arr[0]['status_morning'] : 'available';
							$item['status_afternoon'] = $_POST['status'];
						}else if($i == $end_date){
							$item['status_morning'] = $_POST['status'];
							$item['status_afternoon'] = count($end_date_arr) == 1 ? $end_date_arr[0]['status_afternoon'] : 'available';
						}
					}
					$pjDateModel->prepare(sprintf(
							"INSERT INTO `%s` (`calendar_id`, `date`, `status_morning`, `status_afternoon`)
							VALUES (:calendar_id, :date, :status_morning, :status_afternoon)
							ON DUPLICATE KEY UPDATE `status_morning` = :status_morning, `status_afternoon` = :status_afternoon", $pjDateModel->getTable())
					)->exec($item);
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminCalendars&action=pjActionView&id=".$this->getForeignId()."&year=".date('Y', $start_date)."&month=".date('n', $start_date)."&err=ACR10");
			}else{
				if (isset($_GET['id']) && (int) $_GET['id'] > 0)
				{
					if ((int) pjCalendarModel::factory()->where('t1.id', $_GET['id'])->findCount()->getData() !== 1)
					{
						pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminCalendars&action=pjActionIndex");
					}
					$this->setForeignId($_GET['id']);
				}
				$year = date("Y");
				$month = date("n");
				if(isset($_GET['year']) && isset($_GET['month']))
				{
					$year = $_GET['year'];
					$month = $_GET['month'];
				}
				$this->__getCalendar($this->getForeignId(), $year, $month);
				
				$this->set('calendar_format', pjUtil::formatDate($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01', 'Y-m-d', $this->option_arr['o_date_format']));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendCss('index.php?controller=pjFront&action=pjActionLoadCss&cid=' . $this->getForeignId() . '&' . rand(1,99999), PJ_INSTALL_URL, true);
				$this->appendJs('pjAdminCalendars.js');
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDashboard()
	{
		$this->checkLogin();
	
		if ($this->isAdmin() || $this->isOwner() || $this->isEditor())
		{
			list($year, $month) = explode("-", date("Y-n"));
			$arr = $this->pjActionGetAvailability($year, $month);
			$this->set('arr', $arr);
				
			pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.title')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->orderBy('t1.sort ASC')->findAll()->getData();
			$this->set('locale_arr', $locale_arr);
				
			foreach ($arr as $calendar)
			{
				$this->appendCss('index.php?controller=pjAdminCalendars&action=pjActionLoadCss&cid=' . $calendar['id'], PJ_INSTALL_URL, true);
			}
			if ($this->getModernOptions() !== NULL)
			{
				$this->appendCss('index.php?controller=pjAdminCalendars&action=pjActionLoadModernCss', PJ_INSTALL_URL, true);
			}
			$this->appendJs('perfect-scrollbar.jquery.js', PJ_THIRD_PARTY_PATH . 'perfect_scrollbar/js/');
			$this->appendCss('perfect-scrollbar.min.css', PJ_THIRD_PARTY_PATH . 'perfect_scrollbar/css/');
			$this->appendJs('pjAdminCalendars.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetDashboard()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$this->set('arr', $this->pjActionGetAvailability($_GET['year'], $_GET['month']));
		}
	}
	
	private function pjActionGetAvailability($year, $month)
	{
		$pjCalendarModel = pjCalendarModel::factory()
			->select("t1.*, t2.content AS title, t3.value AS o_bookings_per_day")
			->join('pjMultiLang', "t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->join('pjOption', "t3.foreign_id=t1.id AND t3.key='o_bookings_per_day'", 'left outer');
		$pjDateModel = pjDateModel::factory();
		if ($this->isOwner())
		{
			$pjCalendarModel->where('t1.user_id', $this->getUserId());
		}
		$arr = $pjCalendarModel->orderBy('t1.id ASC')->findAll()->getData();
		foreach ($arr as $k => $calendar)
		{
			$arr[$k]['date_arr'] = $pjDateModel->getDates(
					$calendar['id'],
					date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)),
					date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year)),
					$this->models['Option']->reset()->getPairs($calendar['id'])
			);
		}
		return $arr;
	}
	
	/**
	 * Modern design: options of the first calendar that uses it (NULL when every calendar uses Classic).
	 * The administration "Availability" overview takes its look from that calendar.
	 */
	public function getModernOptions()
	{
		$row = pjOptionModel::factory()
			->where('t1.key', 'o_theme')
			->where('t1.value', 'classic|modern::modern')
			->orderBy('t1.foreign_id ASC')
			->limit(1)
			->findAll()
			->getDataIndex(0);
		if (empty($row))
		{
			return NULL;
		}
		return pjOptionModel::factory()->getPairs($row['foreign_id']);
	}
	
	public function pjActionLoadModernCss()
	{
		header("Content-Type: text/css; charset=utf-8");
		$option_arr = $this->getModernOptions();
		if ($option_arr !== NULL)
		{
			# every calendar row (and its name) uses the appearance of its own calendar
			$row_arr = array();
			$pjOptionModel = pjOptionModel::factory();
			foreach (pjCalendarModel::factory()->findAll()->getData() as $calendar)
			{
				$row_arr[$calendar['id']] = $pjOptionModel->reset()->getPairs($calendar['id']);
			}
			echo pjAppController::getModernFontsImportAll(array_merge(array($option_arr), $row_arr));
			echo pjAppController::getModernCss($option_arr, '.cal-container.modern-admin', 'modern.admin.css');
			foreach ($row_arr as $cid => $row_options)
			{
				echo pjAppController::getModernRowCss($row_options, '.cal-container.modern-admin .cal-id-' . (int) $cid . ', .cal-container.modern-admin .cal-t-' . (int) $cid);
			}
		}
		exit;
	}
	
	public function pjActionLoadCss()
	{
		$option_arr = pjOptionModel::factory()->getPairs($_GET['cid']);
	
		ob_start();
		@readfile(PJ_CSS_PATH . 'availability.txt');
		$string = ob_get_contents();
		ob_end_clean();
	
		header("Content-Type: text/css; charset=utf-8");
		if ($string !== FALSE)
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
							'.cal-id-' . $_GET['cid'],
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
		exit;
	}
}
?>