<?php
include __DIR__.'/../libraries/define.php';
class Test_mypage extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	/***　取引できないordre_id
	* order_id が存在しない
	* order_id が仮売上
	*/
	public function test_mypage_cancel()
	{
		$this->load->model('Credit');
		$result = $this->Credit->alter_tran('150223-043635-17100');
		//var_dump($result);
		if($result->isErrorOccurred()){
			$message = $this->Credit->getAlterErrorMessages($result);
			var_dump($message);
		}else{
			var_dump($result);
		}
	}
	
	/*** cancel
	* productが取得できこと
	* クレジットの取消が出来る事
	*/
	public function test_mypage_product()
	{
		$this->load->model('Order');
		$result = $this->Order->get_by_id_with_detail(48);
		echo '<pre>'; print_r($result);echo '</pre>';
	}
	
}
