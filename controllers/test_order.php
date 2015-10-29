<?php
include __DIR__.'/../libraries/define.php';
class Test_order extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Order');
		$this->load->model('Credit');
		$this->load->model('Customer');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function test_order_info()
	{
		$this->load->model('Credit');
		$order_number ='150223-084325-171000649';
		$card_info = $this->Credit->search_trade($order_number);
		//var_dump($card_no);
		$this->unit->run($card_info->cardNo, "************1111");
		echo $this->unit->report();
	}
	
	public function admin_order_list()
	{
		$this->load->model('Credit');
		$order_number = '150223-084325-171000649';
		$output = $this->Credit->alter_tran($order_number,'SALES');
		if($output->isErrorOccurred()){
			$messages = $this->Credit->getAlterErrorMessages($output);
			var_dump($messages);
		}else{
			var_Dump($output);
		}
	}
	
	public function admin_list_payment()
	{		
		//Orderの受付済みstatus_flag = 1を取得
		$result = $this->Order->get_recieved();
		$bool = function($result)
		{
			foreach($result as $row)
			{
				if($row->status_flag != 1)
				{
					return false;
				}
				if($row->csv_flag != 1)
				{
					return false;
				}
			}
			return true;
		};
		//配列が返ってstatus_flag =1 ans csv_flag=1 であること
		$this->unit->run($result,'is_array');
		$this->unit->run($bool($result),true);
		//実売上
		$message=$this->Order->change_shipped(array(63,64));
		if(!empty($message)) echo current($message);
		echo $this->unit->report();
	}
	
	public function show_address()
	{
		try
		{
			$order1 = $this->Order->get_by_id(1);
			$order2 = $this->Order->get_by_id(3);
			echo $this->Order->show_shipping_address($order1)['name'];
			echo $this->Order->show_shipping_address($order2)['address'];
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
}
