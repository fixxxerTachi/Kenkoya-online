<?php
class Master_expire{
	public $year;
	public $month;
	public $date;
	public function __construct()
	{
		$this->date = new DateTime();
		$this->year = $this->date->format('Y');
		$this->short_year = $this->date->format('y');
	}
	
	public function set_expire_month()
	{
		$arr = array();
		//$arr[0] = '';
		for($i=1; $i <= 12; $i++){
			$key = sprintf("%02d",$i);
			$arr[$key] = sprintf("%02d",(int)$i);
		}
		return $arr;
	}

	public function set_expire_year()
	{
		$arr = array();
		$start_year = (int)$this->short_year;
		$end_year = $start_year + 20;
		$j = 0;
		for($i = $start_year; $i <= $end_year; $i++){
			$year = $this->year + $j;
			$key = sprintf("%02d",$i);
			$arr[$key] = sprintf("%02d",(int)$year);
			$j++;
		}
		return $arr;
	}
}