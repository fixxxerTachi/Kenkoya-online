<?php
class Master_days{
	public $jdays;
	public $edays;
	public function __construct()
	{
		$this->jdays = array(
			0=>'日曜日',
			1=>'月曜日',
			2=>'火曜日',
			3=>'水曜日',
			4=>'木曜日',
			5=>'金曜日',
			6=>'土曜日',
		);
		$this->edays = array(
			0=>'Sunday',
			1=>'Monday',
			2=>'Tuesday',
			3=>'Wednesday',
			4=>'Thursday',
			5=>'Friday',
			6=>'Saturday',
		);
}
	
}