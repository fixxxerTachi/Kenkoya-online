<?php
class Master_controllers{
	public $controllers;
	public function __construct()
	{
		$this->controllers = array(
			'order'=>'受注管理',
			'customer'=>'会員管理',
			'contents'=>'コンテンツ管理',
			'advertise'=>'広告管理',
			'products'=>'商品管理',
			'admin'=>'管理メニュー',
			
		);
	}
}