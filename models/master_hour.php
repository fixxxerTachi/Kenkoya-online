<?php
class Master_hour{
	public $hour;
	public function __construct()
	{
		$this->hour = array();
		for($i = 0; $i < 24; $i++){
			$i = sprintf('%02d',$i);
			$this->hour[(string)$i . ':00:00'] = $i;
		}
		//$this->hour['23:59:59']='終日';
	}
	
}