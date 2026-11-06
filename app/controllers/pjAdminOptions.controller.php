<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminOptions extends pjAdmin
{
	public function pjActionCopy()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if (isset($_POST['calendar_id']) && (int) $_POST['calendar_id'] > 0 && isset($_POST['tab_id']) && (int) $_POST['tab_id'] > 0)
			{
				$pjOptionModel = pjOptionModel::factory();
				
				$src = $pjOptionModel->where('t1.foreign_id', $_POST['calendar_id'])->where('t1.tab_id', $_POST['tab_id'])->findAll()->getData();
				$src_pair = $pjOptionModel->getDataPair('key', 'value');
				$pjOptionModel->begin();
				foreach ($src as $option)
				{
					$pjOptionModel
						->reset()
						->where('foreign_id', $this->getForeignId())
						->where('`key`', $option['key'])
						->limit(1)
						->modifyAll(array('value' => $option['value']));
				}
				$pjOptionModel->commit();
				
				$fields = array();
				if ((int) $_POST['tab_id'] === 2) {
					set_time_limit(300);
					pjUtil::pjActionGenerateImages($this->getForeignId(), $src_pair);
				}

				if (!empty($fields))
				{
					$pjMultiLangModel = pjMultiLangModel::factory();
					
					$src = $pjMultiLangModel
						->where('t1.model', 'pjCalendar')
						->where('t1.foreign_id', $_POST['calendar_id'])
						->whereIn('t1.field', $fields)
						->findAll()->getData();

					$pjMultiLangModel->begin();
					foreach ($src as $item)
					{
						$item['id'] = NULL;
						unset($item['id']);
						$item['foreign_id'] = $this->getForeignId();
							
						$pjMultiLangModel->prepare(sprintf(
							"INSERT INTO `%s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`)
							VALUES (NULL, :foreign_id, :model, :locale, :field, :content)
							ON DUPLICATE KEY UPDATE `content` = :content", $pjMultiLangModel->getTable())
						)->exec($item);
					}
					$pjMultiLangModel->commit();
				}
			}
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || ($this->isOwner() && $this->getUserId() == $this->getForeignId()))
		{
			if (!isset($_GET['tab']) || (int) $_GET['tab'] <= 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&tab=1");
			}
			
			if (isset($_GET['cid']))
			{
				$this->setForeignId($_GET['cid']);
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=pjActionIndex&tab=" . $_GET['tab']);
			}
			
			if (isset($_GET['tab']) && in_array((int) $_GET['tab'], array(5,6)))
			{
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
				
				$arr = array();
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($this->getForeignId(), 'pjCalendar');
				$this->set('arr', $arr);
				
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				if ($_GET['tab'] == 6)
				{
					$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				}
			} elseif (isset($_GET['tab']) && in_array((int) $_GET['tab'], array(10))) {
				$this->set('arr', pjLimitModel::factory()->where('t1.calendar_id', $this->getForeignId())->findAll()->getData());
			} else {
				$tab_id = isset($_GET['tab']) && (int) $_GET['tab'] > 0 ? (int) $_GET['tab'] : 1;
				$arr = pjOptionModel::factory()
					->where('foreign_id', $this->getForeignId())
					->where('tab_id', $tab_id)
					->orderBy('t1.order ASC')
					->findAll()
					->getData();
				$this->set('arr', $arr);
				
				$tmp = $this->models['Option']->reset()->where('foreign_id', $this->getForeignId())->findAll()->getData();
				$o_arr = array();
				foreach ($tmp as $item)
				{
					$o_arr[$item['key']] = $item;
				}
				$this->set('o_arr', $o_arr);
				
				$this->appendJs('jquery.miniColors.min.js', PJ_THIRD_PARTY_PATH . 'mini_colors/');
				$this->appendCss('jquery.miniColors.css', PJ_THIRD_PARTY_PATH . 'mini_colors/');
				
				if ($tab_id == 1)
				{
					$calendar_arr = pjCalendarModel::factory()->find($this->getForeignId())->getData();
					if (!empty($calendar_arr))
					{
						$calendar_arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($calendar_arr['id'], 'pjCalendar');
						$this->set('calendar_arr', $calendar_arr);
					}
					
					if ((int) $this->option_arr['o_multi_lang'] === 1)
					{
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
						
						$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
						$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
					}
					$this->set('user_arr', pjUserModel::factory()->orderBy('t1.name ASC')->findAll()->getData());
				}
			}
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
		
	public function pjActionInstall()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.title')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->orderBy('t1.sort ASC')->findAll()->getData();
			$this->set('locale_arr', $locale_arr);
					
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPreview()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionSaveModern()
	{
		$this->setAjax(true);
		
		if (!$this->isXHR() || !$this->isLoged())
		{
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
		}
		if (!($this->isAdmin() || $this->isEditor() || ($this->isOwner() && $this->getUserId() == $this->getForeignId())))
		{
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101));
		}
		
		$json = '';
		if (!isset($_POST['reset']) && isset($_POST['settings']) && is_string($_POST['settings']))
		{
			$settings = pjAppController::getModernSettings($_POST['settings']);
			if (!empty($settings))
			{
				$json = json_encode($settings);
			}
		}
		
		$pjOptionModel = pjOptionModel::factory();
		$exists = $pjOptionModel->where('foreign_id', $this->getForeignId())->where('`key`', 'o_modern_colors')->findCount()->getData();
		if ((int) $exists > 0)
		{
			$pjOptionModel->reset()->where('foreign_id', $this->getForeignId())->where('`key`', 'o_modern_colors')->limit(1)->modifyAll(array('value' => $json));
		} else {
			$pjOptionModel->reset()->setAttributes(array(
				'foreign_id' => $this->getForeignId(),
				'key' => 'o_modern_colors',
				'tab_id' => 2,
				'value' => $json,
				'label' => '',
				'type' => 'string',
				'order' => 0,
				'is_visible' => 0,
				'style' => ''
			))->insert();
		}
		
		pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || ($this->isOwner() && $this->getUserId() == $this->getForeignId()))
		{
			if (isset($_POST['options_update']))
			{
				if (isset($_POST['tab']) && in_array($_POST['tab'], array(5, 6)))
				{
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $this->getForeignId(), 'pjCalendar');
					}
				} elseif (isset($_POST['tab']) && in_array($_POST['tab'], array(10))) {
					$pjLimitModel = pjLimitModel::factory();
					
					$pjLimitModel->where('calendar_id', $this->getForeignId())->eraseAll();
					$pjLimitModel->begin();
					$haystack = array();
					$dates = array();
					$overlaping = false;
					if (isset($_POST['date_from'], $_POST['date_to']) && !empty($_POST['date_from']))
					{
						foreach ($_POST['date_from'] as $k => $v)
						{
							if (empty($_POST['date_from'][$k]) || empty($_POST['date_to'][$k]) ||
								(empty($_POST['min_nights'][$k]) && empty($_POST['max_nights'][$k])) ||
								(!empty($_POST['min_nights'][$k]) && !empty($_POST['max_nights'][$k]) && (int) $_POST['min_nights'][$k] > (int) $_POST['max_nights'][$k])
							)
							{
								continue;
							}
							
							$overlap = false;
							$date_from = strtotime($_POST['date_from'][$k]);
							$date_to = strtotime($_POST['date_to'][$k]);
							foreach ($dates as $item)
							{
								if ($item['date_from'] <= $date_to && $item['date_to'] >= $date_from)
								{
									$overlap = true;
									$overlaping = true;
									break;
								}
							}
							if ($overlap)
							{
								continue;
							}
	
							$needle = $_POST['date_from'][$k] . "_" . $_POST['date_to'][$k];
							if (in_array($needle, $haystack))
							{
								continue;
							}
							array_push($haystack, $needle);
							array_push($dates, array('date_from' => strtotime($_POST['date_from'][$k]), 'date_to' => strtotime($_POST['date_to'][$k])));
							
							$pjLimitModel
								->reset()
								->set('calendar_id', $this->getForeignId())
								->set('date_from', pjUtil::formatDate($_POST['date_from'][$k], $this->option_arr['o_date_format']))
								->set('date_to', pjUtil::formatDate($_POST['date_to'][$k], $this->option_arr['o_date_format']))
								->set('min_nights', $_POST['min_nights'][$k])
								->set('max_nights', $_POST['max_nights'][$k])
								->insert()
							;
						}
					}
					$pjLimitModel->commit();
				} else {
					$OptionModel = pjOptionModel::factory();
					$OptionModel
						->where('foreign_id', $this->getForeignId())
						->where('type', 'bool')
						->where('tab_id', $_POST['tab'])
						->modifyAll(array('value' => '1|0::0'));
					
					$uniform = array(
						'o_email_new_reservation_subject', 'o_email_new_reservation', 'o_email_reservation_cancelled_subject',
						'o_email_reservation_cancelled', 'o_email_password_reminder_subject', 'o_email_password_reminder',
						'o_sms_new_reservation', 'o_sms_reservation_cancelled'
					);
						
					foreach ($_POST as $key => $value)
					{
						if (preg_match('/value-(string|text|int|float|enum|color|bool)-(.*)/', $key) === 1)
						{
							list(, $type, $k) = explode("-", $key);
							if (!empty($k))
							{
								$OptionModel->reset();
								if (!in_array($k, $uniform))
								{
									$OptionModel->where('foreign_id', $this->getForeignId())->limit(1);
								}
								$OptionModel
									->where('`key`', $k)
									->modifyAll(array(
										'value' => $value
									));
							}
						}
					}
				}
				
				if (isset($_POST['tab']) && $_POST['tab'] == 1)
				{
					if (isset($_POST['user_id']))
					{
						pjCalendarModel::factory()->set('id', $this->getForeignId())->modify(array('user_id' => $_POST['user_id']));
					}
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $this->getForeignId(), 'pjCalendar');
					}
				}
				
				if (isset($_POST['tab']) && in_array($_POST['tab'], array(2)))
				{
					set_time_limit(300);
					$data = pjOptionModel::factory()->getAllPairs($this->getForeignId());
					pjUtil::pjActionGenerateImages($this->getForeignId(), $data);
				}

				if (isset($_POST['tab']))
				{
					switch ($_POST['tab'])
					{
						case '1':
							$err = 'AO01';
							break;
						case '2':
							$err = 'AO02';
							break;
						case '3':
							$err = 'AO03';
							break;
						case '4':
							$err = 'AO04';
							break;
						case '5':
							$err = 'AO05';
							break;
						case '6':
							$err = 'AO06';
							break;
						case '7':
							$err = 'AO07';
							break;
						case '8':
							$err = 'AO08';
							break;
						case '10':
							$err = !$overlaping ? 'AO10' : 'AO11';
							break;
					}
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=" . @$_POST['next_action'] . "&tab=" . @$_POST['tab'] . "&err=$err");
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>