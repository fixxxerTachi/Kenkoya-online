<?php
class Master_customer_show_flag{
	public $show_flag;
	public function __construct()
	{
		$this->show_flag = array(
			'2'=>'会員のみ公開',
			'1'=>'公開',
			'0'=>'非公開',
		);
	}
}