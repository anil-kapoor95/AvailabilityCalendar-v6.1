<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjDateModel extends pjAppModel
{
	protected $primaryKey = null;

	protected $table = 'dates';

	protected $schema = array(
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'is_change_over', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'status_morning', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'status_afternoon', 'type' => 'enum', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjDateModel($attr);
	}
	
	public function getDates($calendar_id, $start, $end, $option_arr)
	{
		$arr = array();
		$map = array('available' => 1, 'booked' => 2, 'pending' => 3);
		$_arr = $this->reset()
			->where('t1.calendar_id', $calendar_id)
			->where("(t1.date BETWEEN '$start' AND '$end')")
			->findAll()
			->getData();
		
		foreach ($_arr as $k => $v)
		{
			$v['status_morning'] = $map[$v['status_morning']];
			$v['status_afternoon'] = $map[$v['status_afternoon']];
			$arr[$v['date']] = $v;
		}
		return $arr;
	}
}
?>