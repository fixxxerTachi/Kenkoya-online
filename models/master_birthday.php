<?php
class Master_birthday{
	public $year;
	public $month;
	public $day;
	public $date;
	public function __construct()
	{
		$this->date = new DateTime();
		$this->year = $this->date->format('Y');
	}
	
	public function set_year()
	{
		$year = (int)$this->year;
		$start_year = $year - 100;
		$arr = array();
		$arr[''] = '';
		for($i = $start_year; $i < $year - 13; $i++){
			$arr[$i] = $i;
		}
		return $arr;
	}
	
	public function set_month()
	{
		$arr = array();
		$arr[''] = '';
		for($i=1; $i <= 12; $i++){ $arr[$i] = $i; }
		return $arr;
	}
	
	public function set_day()
	{
		$arr = array();
		$arr[''] = '';
		for($i=1; $i <= 31; $i++){ $arr[$i] = $i; }
		return $arr;
	}
	
	public function set_expire_year()
	{
		$arr = array();
		$start_year = (int)$this->year;
		$end_year = $start_year + 20;
		for($i = $start_year; $i <= $end_year; $i++){
			$arr[$i] = $i;
		}
		return $arr;
	}
}