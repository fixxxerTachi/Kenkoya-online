<?php
class Master_cancel{
	public $cancel;
	public function __construct()
	{
		$this->cancel = array(
			'2'=>'受付済',
			'1'=>'注文取消し',
			'0'=>'注文受付中',
		);
	}
	
}