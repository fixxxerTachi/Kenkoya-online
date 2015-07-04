<?php
class Master_mail_magazine{
	public $merumaga_select;
	public function __construct()
	{
		$this->merumaga_select = array(
			'1'=>'購読する',
			'0'=>'購読中止',
		);
	}
	
}