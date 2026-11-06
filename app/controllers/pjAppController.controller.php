<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjController
{
	public $models = array();

	/**
	 * Generate (or return existing) CSRF token for the current session.
	 */
	public static function getCsrfToken()
	{
		if (empty($_SESSION['csrf_token']))
		{
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		return $_SESSION['csrf_token'];
	}

	/**
	 * Validate CSRF token (admin requests).
	 */
	public static function validateCsrfToken()
	{
		$token = '';
		if (!empty($_POST['csrf_token'])) {
			$token = $_POST['csrf_token'];
		} elseif (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
			$token = $_SERVER['HTTP_X_CSRF_TOKEN'];
		}
		if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string) $token))
		{
			header('HTTP/1.1 403 Forbidden');
			exit('Invalid CSRF token.');
		}
	}
	
	public $option_arr = array();

	public $defaultLocale = 'admin_locale_id';
	
	public $defaultCalendarId = 'admin_calendar_id';
	
	public $defaultFields = 'fields';
	
	public $defaultFieldsIndex = 'fields_index';
	
	protected function loadSetFields($force=FALSE, $locale_id=NULL, $fields=NULL)
	{
		if (is_null($locale_id))
		{
			$locale_id = $this->getLocaleId();
		}
	
		if (is_null($fields))
		{
			$fields = $this->defaultFields;
		}
	
		$registry = pjRegistry::getInstance();
		if ($force
				|| !isset($_SESSION[$this->defaultFieldsIndex])
				|| (isset($this->option_arr) && $_SESSION[$this->defaultFieldsIndex] != $this->option_arr['o_fields_index'])
				|| !isset($_SESSION[$fields])
				|| empty($_SESSION[$fields]))
		{
			pjAppController::setFields($locale_id);
	
			# Update session
			if ($registry->is('fields'))
			{
				$_SESSION[$fields] = $registry->get('fields');
			}
			$_SESSION[$this->defaultFieldsIndex] = $this->option_arr['o_fields_index'];
		}
	
		if (isset($_SESSION[$fields]) && !empty($_SESSION[$fields]))
		{
			# Load fields from session
			$registry->set('fields', $_SESSION[$fields]);
		}
	
		return TRUE;
	}
	
	public static function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }

	public static function setMySQLServerTime($offset="-0:00")
    {
		pjAppModel::factory()->prepare("SET SESSION time_zone = :offset;")->exec(compact('offset'));
    }
    
	public function setTime()
	{
		if (isset($this->option_arr['o_timezone']))
		{
			$offset = $this->option_arr['o_timezone'] / 3600;
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
		}
	}
    
    public function beforeFilter()
    {
		if (strtoupper(@$_SERVER['REQUEST_METHOD']) === 'POST')
		{
			$_csrf_ctrl = isset($_GET['controller']) ? $_GET['controller'] : '';
			$_csrf_act = isset($_GET['action']) ? $_GET['action'] : '';
			if (strpos($_csrf_ctrl, 'pjAdmin') === 0
				&& !in_array($_csrf_act, array('pjActionLogin', 'pjActionForgot', 'pjActionLogout', 'pjActionExportFeed'), true)) {
				pjAppController::validateCsrfToken();
			}
		}

    	if (!in_array($_GET['controller'], array('pjFront', 'pjInstaller')))
    	{
    		$pjCalendarModel = pjCalendarModel::factory();
    		if ($this->isOwner())
    		{
    			$pjCalendarModel->where('t1.user_id', $this->getUserId());
    		}
	    	$calendars = $pjCalendarModel
				->select('t1.*, t2.content AS `name`')
				->join('pjMultiLang', "t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
				->orderBy('t1.id ASC')
				->findAll()->getDataPair('id');
			$this->set('calendars', $calendars);
			
			if ($this->getForeignId() === false && count($calendars) > 0)
			{
				$keys = array_keys($calendars);
				$this->setForeignId($keys[0]);
			}
    	}
		
    	$this->appendJs('jquery.min.js', PJ_THIRD_PARTY_PATH . 'jquery/');
    	$baseDir = defined("PJ_INSTALL_PATH") ? PJ_INSTALL_PATH : null;
    	$dm = new pjDependencyManager($baseDir, PJ_THIRD_PARTY_PATH);
    	$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
    	$this->appendJs('jquery-migrate.min.js', $dm->getPath('jquery_migrate'), FALSE, FALSE);
    	$this->appendJs('pjAdminCore.js');
    	$this->appendCss('reset.css');
    		
    	$this->appendJs('js/jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
    	$this->appendCss('css/smoothness/jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
    		
    	$this->appendCss('pj-all.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
    	$this->appendCss('admin.css');
		
    	if ($_GET['controller'] != 'pjInstaller')
		{
			$this->models['Option'] = pjOptionModel::factory();
			$this->option_arr = $this->models['Option']->getPairs($this->getForeignId());
			$this->set('option_arr', $this->option_arr);
			$this->setTime();
			if (!isset($_SESSION[$this->defaultLocale]))
			{
				$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
				if (count($locale_arr) === 1)
				{
					$_SESSION[$this->defaultLocale] = $locale_arr[0]['id'];
				}
			}
			if (!in_array($_GET['action'], array('pjActionPreview')))
			{
				$this->loadSetFields();
			}
		}
		
    }
    
    public function getForeignId()
	{
		if (isset($_SESSION[$this->defaultCalendarId]))
		{
			return $_SESSION[$this->defaultCalendarId];
		}
		return false;
	}
	
	public function setForeignId($calendar_id)
	{
		$_SESSION[$this->defaultCalendarId] = (int) $calendar_id;
		return $this;
	}
	
    public static function setFields($locale)
    {
    	if(isset($_SESSION['lang_show_id']) && (int) $_SESSION['lang_show_id'] == 1)
		{
			$fields = pjMultiLangModel::factory()
				->select('CONCAT(t1.content, CONCAT(":", t2.id, ":")) AS content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}else{
			$fields = pjMultiLangModel::factory()
				->select('t1.content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}
		$registry = pjRegistry::getInstance();
		$tmp = array();
		if ($registry->is('fields'))
		{
			$tmp = $registry->get('fields');
		}
		$arrays = array();
		foreach ($fields as $k => $v)
		{
			if (strpos($k, '_ARRAY_') !== false)
			{
				list($prefix, $suffix) = explode("_ARRAY_", $k);
				if (!isset($arrays[$prefix]))
				{
					$arrays[$prefix] = array();
				}
				$arrays[$prefix][$suffix] = $v;
			}
		}
		require PJ_CONFIG_PATH . 'settings.inc.php';
		$fields = array_merge($tmp, $fields, $settings, $arrays);
		$registry->set('fields', $fields);
    }

    public static function jsonDecode($str)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->decode($str);
	}
	
	public static function jsonEncode($arr)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->encode($arr);
	}
	
	public static function jsonResponse($arr)
	{
		header("Content-Type: application/json; charset=utf-8");
		echo pjAppController::jsonEncode($arr);
		exit;
	}

	public function isEditor()
	{
		return $this->getRoleId() == 2;
	}

	public function isOwner()
	{
		return $this->getRoleId() == 3;
	}
	
	public function isPriceReady()
	{
		return $this->isAdmin() || $this->isOwner();
	}
	
	public function isPeriodReady()
	{
		return $this->isAdmin() || $this->isOwner();
	}
	
	public function isInvoiceReady()
	{
		return $this->isAdmin() || $this->isEditor() || $this->isOwner();
	}
	
	public function isCountryReady()
	{
		return $this->isAdmin();
	}
	
	public function isOneAdminReady()
	{
		return $this->isAdmin();
	}

	public function getLocaleId()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : false;
	}
	public function setLocaleId($locale_id)
	{
		$_SESSION[$this->defaultLocale] = (int) $locale_id;
	}
	public function pjActionCheckInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
		$folders = array('app/web/upload');
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$result['status'] = 'ERR';
				$result['code'] = 101;
				$result['text'] = 'Permission requirement';
				$result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
			}
		}
		
		return $result;
	}
	
	public function pjActionAfterInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$id = pjCalendarModel::factory()->set('user_id', 1)->insert()->getInsertId();
		if ($id !== false && (int) $id > 0)
		{
			pjMultiLangModel::factory()->saveMultiLang(array(
				1 => array('name' => 'Calendar 1'),
				2 => array('name' => 'Kalender 1'),
				3 => array('name' => 'Calendario 1')
			), $id, 'pjCalendar');
			
			$pjOptionModel = pjOptionModel::factory();
			$pjOptionModel->init($id);
			
			$data = $data = $pjOptionModel->reset()->getAllPairs($id);
			pjUtil::pjActionGenerateImages($id, $data);
		}
		
		return array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded');
	}
}
?>