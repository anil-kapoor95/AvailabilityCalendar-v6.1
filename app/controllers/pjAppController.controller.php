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
	

	/**
	 * Modern: default design tokens (must match app/web/css/themes/modern.css).
	 * Colour tokens are the keys of the "coral" palette plus the availability status colours.
	 */
	public static function getModernPalettes()
	{
		return array(
			'coral' => array('Sunset Coral', array('page' => '#fbf0ea', 'card' => '#ffffff', 'soft' => '#f4dccf', 'line' => '#f1e0d8', 'accent' => '#e8836b', 'heading' => '#4a2a22', 'body' => '#674c45', 'muted' => '#7d6660', 'link' => '#ae3c22', 'btn' => '#c94e33', 'btn_text' => '#ffffff', 'btn_hover' => '#b73f24', 'btn2' => '#ffffff', 'btn2_text' => '#ae3c22')),
			'sage' => array('Sage', array('page' => '#f1f4ec', 'card' => '#ffffff', 'soft' => '#dde6d0', 'line' => '#e3eadb', 'accent' => '#8fa876', 'heading' => '#2f3a26', 'body' => '#505a49', 'muted' => '#697163', 'link' => '#4d6837', 'btn' => '#5f7d45', 'btn_text' => '#ffffff', 'btn_hover' => '#4d6837', 'btn2' => '#ffffff', 'btn2_text' => '#4d6837')),
			'ocean' => array('Ocean', array('page' => '#edf4fa', 'card' => '#ffffff', 'soft' => '#d3e5f4', 'line' => '#e0ecf6', 'accent' => '#4f93c9', 'heading' => '#1b3550', 'body' => '#3f556c', 'muted' => '#5b6e81', 'link' => '#245f93', 'btn' => '#2a6fa8', 'btn_text' => '#ffffff', 'btn_hover' => '#1f5a8c', 'btn2' => '#ffffff', 'btn2_text' => '#245f93')),
			'lavender' => array('Lavender', array('page' => '#f4f0fa', 'card' => '#ffffff', 'soft' => '#e3d9f2', 'line' => '#ebe4f5', 'accent' => '#9a7fd0', 'heading' => '#33284f', 'body' => '#544a6b', 'muted' => '#6c6480', 'link' => '#5b4396', 'btn' => '#6b4fb0', 'btn_text' => '#ffffff', 'btn_hover' => '#59409a', 'btn2' => '#ffffff', 'btn2_text' => '#5b4396')),
			'forest' => array('Forest', array('page' => '#eef3ef', 'card' => '#ffffff', 'soft' => '#d2e2d7', 'line' => '#e0eae3', 'accent' => '#3f8a5f', 'heading' => '#16301f', 'body' => '#3b5143', 'muted' => '#576a5e', 'link' => '#1a5c3b', 'btn' => '#1f6b44', 'btn_text' => '#ffffff', 'btn_hover' => '#17573a', 'btn2' => '#ffffff', 'btn2_text' => '#1a5c3b')),
			'gold' => array('Gold', array('page' => '#fbf6e9', 'card' => '#ffffff', 'soft' => '#f2e6bf', 'line' => '#f2ead0', 'accent' => '#d6a52a', 'heading' => '#3d3112', 'body' => '#5c5238', 'muted' => '#736b54', 'link' => '#855f05', 'btn' => '#946a08', 'btn_text' => '#ffffff', 'btn_hover' => '#7d5805', 'btn2' => '#ffffff', 'btn2_text' => '#855f05')),
			'rose' => array('Rose', array('page' => '#fbeff3', 'card' => '#ffffff', 'soft' => '#f6d6e0', 'line' => '#f6e2e9', 'accent' => '#e0708f', 'heading' => '#4a2130', 'body' => '#674551', 'muted' => '#7d5f6a', 'link' => '#a52e56', 'btn' => '#c23b66', 'btn_text' => '#ffffff', 'btn_hover' => '#a82f56', 'btn2' => '#ffffff', 'btn2_text' => '#a52e56')),
			'mint' => array('Mint', array('page' => '#ebf7f4', 'card' => '#ffffff', 'soft' => '#cdeee6', 'line' => '#dcf2ed', 'accent' => '#3fb8a0', 'heading' => '#103a33', 'body' => '#365a54', 'muted' => '#53716c', 'link' => '#0f6a5a', 'btn' => '#13806d', 'btn_text' => '#ffffff', 'btn_hover' => '#0e6a5a', 'btn2' => '#ffffff', 'btn2_text' => '#0f6a5a'))
		);
	}

	public static function getModernStatus()
	{
		return array('free' => '#dcefe6', 'free_text' => '#256a50', 'booked' => '#f3cfc4', 'booked_text' => '#a53a22', 'pending' => '#fbe5b5', 'pending_text' => '#8a5a00', 'past' => '#f6f0ec', 'past_text' => '#b8a8a1');
	}

	/**
	 * Modern: fonts the admin can choose from (key => array(label, CSS stack, Google Fonts family or NULL)).
	 */
	public static function getModernFonts()
	{
		return array(
			'poppins' => array('Poppins', "'Poppins',system-ui,sans-serif", 'Poppins'),
			'inter' => array('Inter', "'Inter',system-ui,sans-serif", 'Inter'),
			'dm_sans' => array('DM Sans', "'DM Sans',system-ui,sans-serif", 'DM+Sans'),
			'open_sans' => array('Open Sans', "'Open Sans',system-ui,sans-serif", 'Open+Sans'),
			'playfair' => array('Playfair Display', "'Playfair Display',Georgia,serif", 'Playfair+Display'),
			'georgia' => array('Georgia', "Georgia,'Times New Roman',serif", NULL),
			'system' => array('System UI (no external font)', "system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif", NULL)
		);
	}

	public static function getModernRadii()
	{
		return array(
			'sharp' => array('8px', '6px', '6px', '6px'),
			'soft' => array('18px', '12px', '10px', '12px'),
			'round' => array('26px', '999px', '14px', '50%')
		);
	}

	public static function getModernShadows()
	{
		return array(
			'none' => 'none',
			'soft' => '0 10px 30px color-mix(in srgb, var(--modern-heading) 10%, transparent)',
			'lifted' => '0 18px 44px color-mix(in srgb, var(--modern-heading) 22%, transparent)'
		);
	}

	public static function getModernDefaults()
	{
		$p = self::getModernPalettes();
		return array(
			'palette' => 'coral',
			'colors' => array_merge($p['coral'][1], self::getModernStatus()),
			'font' => 'poppins',
			'fs' => 14,
			'radius' => 'soft',
			'shadow' => 'soft'
		);
	}

	/**
	 * Modern: validates the saved JSON and returns ONLY the valid overrides (empty array = use CSS defaults).
	 * Every value is checked against a strict whitelist, so the result is safe to print inside a stylesheet.
	 */
	public static function getModernSettings($json)
	{
		$out = array();
		if (!is_string($json) || $json === '')
		{
			return $out;
		}
		$arr = json_decode($json, true);
		if (!is_array($arr))
		{
			return $out;
		}
		$defaults = self::getModernDefaults();
		if (isset($arr['palette']) && is_string($arr['palette']))
		{
			$palettes = self::getModernPalettes();
			if ($arr['palette'] === '' || isset($palettes[$arr['palette']]))
			{
				$out['palette'] = $arr['palette'];
			}
		}
		if (isset($arr['colors']) && is_array($arr['colors']))
		{
			foreach ($defaults['colors'] as $k => $default)
			{
				if (isset($arr['colors'][$k]) && is_string($arr['colors'][$k]) && preg_match('/^#[0-9a-fA-F]{6}$/', $arr['colors'][$k]))
				{
					$out['colors'][$k] = strtolower($arr['colors'][$k]);
				}
			}
		}
		if (isset($arr['font']) && is_string($arr['font']) && array_key_exists($arr['font'], self::getModernFonts()))
		{
			$out['font'] = $arr['font'];
		}
		if (isset($arr['fs']) && is_numeric($arr['fs']) && in_array((int) $arr['fs'], array(13, 14, 15, 16), true))
		{
			$out['fs'] = (int) $arr['fs'];
		}
		if (isset($arr['radius']) && is_string($arr['radius']) && array_key_exists($arr['radius'], self::getModernRadii()))
		{
			$out['radius'] = $arr['radius'];
		}
		if (isset($arr['shadow']) && is_string($arr['shadow']) && array_key_exists($arr['shadow'], self::getModernShadows()))
		{
			$out['shadow'] = $arr['shadow'];
		}
		return $out;
	}

	/**
	 * Modern: Google Fonts @import line for the effective font ('' when a system font is used).
	 */
	public static function getModernFontsImport($settings)
	{
		$cfg = array_merge(self::getModernDefaults(), is_array($settings) ? $settings : array());
		$fonts = self::getModernFonts();
		if (isset($fonts[$cfg['font']]) && $fonts[$cfg['font']][2] !== NULL)
		{
			return "@import url('https://fonts.googleapis.com/css2?family=" . $fonts[$cfg['font']][2] . ":wght@400;500;600;700&display=swap');\n";
		}
		return '';
	}

	/**
	 * Modern: builds the "<selector>{--modern-*:...}" block from validated settings.
	 */
	public static function getModernCssBlock($settings, $selector)
	{
		if (empty($settings))
		{
			return '';
		}
		$lum = function ($hex) {
			$c = array();
			foreach (array(1, 3, 5) as $i)
			{
				$v = hexdec(substr($hex, $i, 2)) / 255;
				$c[] = $v <= 0.03928 ? $v / 12.92 : pow(($v + 0.055) / 1.055, 2.4);
			}
			return 0.2126 * $c[0] + 0.7152 * $c[1] + 0.0722 * $c[2];
		};
		$vars = array();
		if (isset($settings['colors']))
		{
			foreach ($settings['colors'] as $k => $v)
			{
				$vars[] = '--modern-' . str_replace('_', '-', $k) . ':' . $v;
			}
		}
		$fonts = self::getModernFonts();
		if (isset($settings['font']))
		{
			$vars[] = '--modern-font-head:' . $fonts[$settings['font']][1];
			$vars[] = '--modern-font-body:' . $fonts[$settings['font']][1];
		}
		if (isset($settings['fs']))
		{
			$vars[] = '--modern-fs:' . (int) $settings['fs'] . 'px';
		}
		if (isset($settings['radius']))
		{
			$radii = self::getModernRadii();
			$r = $radii[$settings['radius']];
			$vars[] = '--modern-r:' . $r[0];
			$vars[] = '--modern-rb:' . $r[1];
			$vars[] = '--modern-ri:' . $r[2];
			$vars[] = '--modern-cell:' . $r[3];
		}
		if (isset($settings['shadow']))
		{
			$shadows = self::getModernShadows();
			$vars[] = '--modern-shadow:' . $shadows[$settings['shadow']];
		}
		return empty($vars) ? '' : "\n/* Modern customization */\n" . $selector . "{" . join(';', $vars) . ";}\n";
	}

	/**
	 * Modern: returns the stylesheet (defaults + the saved customization) for one container.
	 */
	public static function getModernCss($option_arr, $selector, $file)
	{
		$settings = self::getModernSettings(isset($option_arr['o_modern_colors']) ? $option_arr['o_modern_colors'] : '');
		$css = '';
		ob_start();
		@readfile(PJ_CSS_PATH . 'themes/' . $file);
		$string = ob_get_contents();
		ob_end_clean();
		if ($string !== FALSE && $string !== '')
		{
			$css = str_replace('[calendarContainer]', $selector, $string);
		}
		return $css . self::getModernCssBlock($settings, $selector);
	}

	/**
	 * Modern: the "--modern-*" block of ONE calendar row of the all-calendars tables (admin overview, front availability).
	 * A Modern calendar uses its own palette, font, radius and shadow; a Classic calendar uses its own classic colours (square cells).
	 */
	public static function getModernRowCss($option_arr, $selector)
	{
		$cfg = self::getModernDefaults();
		unset($cfg['palette']);
		if (isset($option_arr['o_theme']) && $option_arr['o_theme'] == 'modern')
		{
			$settings = self::getModernSettings(isset($option_arr['o_modern_colors']) ? $option_arr['o_modern_colors'] : '');
			foreach ($settings as $k => $v)
			{
				if ($k == 'palette')
				{
					continue;
				}
				if ($k == 'colors')
				{
					$cfg['colors'] = array_merge($cfg['colors'], $v);
				} else {
					$cfg[$k] = $v;
				}
			}
		} else {
			foreach (array('free' => 'available', 'booked' => 'booked', 'pending' => 'pending', 'past' => 'past') as $token => $name)
			{
				$bg = isset($option_arr['o_background_' . $name]) ? $option_arr['o_background_' . $name] : '';
				$fg = isset($option_arr['o_color_' . $name]) ? $option_arr['o_color_' . $name] : '';
				if (is_string($bg) && preg_match('/^#[0-9a-fA-F]{6}$/', $bg))
				{
					$cfg['colors'][$token] = strtolower($bg);
				}
				if (is_string($fg) && preg_match('/^#[0-9a-fA-F]{6}$/', $fg))
				{
					$cfg['colors'][$token . '_text'] = strtolower($fg);
				}
			}
			$cfg['radius'] = 'sharp';
		}
		return self::getModernCssBlock($cfg, $selector);
	}

	/**
	 * Modern: the Google Fonts @import lines (unique) needed by all calendars that use the Modern design.
	 */
	public static function getModernFontsImportAll($option_arr_list)
	{
		$out = array();
		foreach ($option_arr_list as $option_arr)
		{
			if (isset($option_arr['o_theme']) && $option_arr['o_theme'] == 'modern')
			{
				$line = self::getModernFontsImport(self::getModernSettings(isset($option_arr['o_modern_colors']) ? $option_arr['o_modern_colors'] : ''));
				if ($line !== '' && !in_array($line, $out))
				{
					$out[] = $line;
				}
			}
		}
		return join('', $out);
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