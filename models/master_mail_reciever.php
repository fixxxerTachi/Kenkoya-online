<?php
class Master_mail_reciever{
	public $reciever;
	public function __construct()
	{
		$this->reciever = array(
			'0'=>'管理者送付用',
			'1'=>'お客さま送付用',
		);
	}
	
}