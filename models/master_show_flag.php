<?php
class Master_show_flag{
	public $show_flag;
	public $show_flag_kenkoya;
	
	public function __construct()
	{
		$this->show_flag = array(
			'1'=>'公開',
			'0'=>'非公開',
		);
		
		$this->show_flag_kenkoya = array(
			'1'=>'全て公開',
			'2'=>'健康屋のみ公開',
			'0'=>'非公開',
		);	
	}
}