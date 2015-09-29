<?php
include __DIR__.'/../libraries/define.php';
class Test_customer extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Customer');
		$this->load->model('Master_customer_item');
		$this->load->model('Customer_history');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function test_save_history()
	{
		//customerのカラム名の一覧を取得する
		$lists = $this->Master_customer_item->list_items();
		/*** test Master_customer_item::list_items ***/
		echo $this->unit->run($lists['email'],'7','Master_customer_item::list_items');
		//customer取得
		$customer_id = 501;
		$customer = $this->Customer->get_by_id(501);
		//変更するデータをセット
		$data = array(
			'email'=>'test_save_histroy8@test.com',
		);
		$h_customer = $this->Customer_history;
		$h_customer->customer_id = $customer_id;
		$h_customer->customer_code = $customer->code;
		$h_customer->item_id = $lists['email'];
		$h_customer->content = $data['email'];
		$h_customer->create_datetime = date('Y-m-d H:i:s');
		/** transaction start **/
		$this->db->trans_begin();
		$this->Customer->update($customer->id,$data);
		$this->Customer_history->save($h_customer);
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		/*** test Customer_history::save***/
			echo $this->unit->run('1','1','Customer_history::save');
		}
	}
}
