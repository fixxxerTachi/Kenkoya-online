<?php
class Master_order_status{
	public $cancel;
	public $order_status;
	public function __construct()
	{
		$this->order_status = array(
			'0'=>'受付中',
			'1'=>'受付済み',
			'2'=>'ご注文取消し',
			'3'=>'出荷準備中',
			'4'=>'配送済み',
		);
		
	}
	
}