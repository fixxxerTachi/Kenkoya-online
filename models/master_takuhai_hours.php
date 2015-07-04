<?php
class Master_takuhai_hours{
	public $hours;
	public function __construct()
	{
		$this->hours = array(
			0=>'時間指定しない',
			1=>'午前中',
			2=>'12-14時',
			3=>'14-16時',
			4=>'16-18時',
			5=>'18-20時',
			6=>'20-21時',
		);
	}
}
