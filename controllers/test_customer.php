<?php
include __DIR__.'/../libraries/define.php';
class Test_customer extends CI_Controller {
	public $data;
	public $no_user;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Customer');
		$this->load->model('Master_customer_item');
		$this->load->model('Master_shop');
		$this->load->model('Customer_history');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->no_user = $this->Customer;
		
	}
	
	public function index()
	{
		$limit = 20;
		$offset = 1;
		$customers = $this->Customer->show_list_where($limit,$offset);
		echo '<pre>'; print_r($customers); echo '</pre>';
		$shops = $this->Master_shop->array_lists(true);
		echo '<pre>'; print_r($shops); echo '</pre>';
		$old = $this->Customer_history;
		$count = $old->get_count();
		echo $count;
		$before_tel = '0767772888';
		$after_tel = '0767772889';
		$customer_id = 110;
		/* Customer::check_tel 電話番号が存在したらfalseを返す*/
		//customer_idがセットされている場合はそのIDを除外する。
		echo $this->unit->run($this->Customer->check_tel($before_tel,true,$customer_id),true,'check_tel');
		//customer_idがセットされていない場合はその顧客も含める
		echo $this->unit->run($this->Customer->check_tel($after_tel,false,$customer_id),false,'check_tel');
		$customer = new Customer();
		$customer->username='tatick.tic.tic.tic.tock@gmail.com';
		var_dump($this->Customer->get_code($customer));

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

	public function test_check_tel()
	{
		$tel = $this->uri->segment(3);
		if(empty($tel))
		{
			header('Content-Type:text/html;Charset="utf-8"');
			echo '電話番号のパラメータを入力してください';
		}
		else
		{
			var_dump($this->Customer->check_tel($tel));
		}
	}
	
	public function test_validation()
	{
		//メール重複チェック
		$this->no_user->email = 'tekihout@hotei.com';
		$yamada = $this->Customer->get_by_id(528);
		echo $this->unit->run($this->Customer->check_email($this->no_user->email),TRUE,'check_email');
		echo $this->unit->run($this->Customer->check_email($yamada->email),FALSE,'check_email');
		//電話番号重複チェック
		$this->no_user->tel = '45678913789';
		$this->no_user->tel2 = '12345679';
		echo $this->unit->run($this->Customer->check_tel($this->no_user->tel,TRUE),'check_tel');
		echo $this->unit->run($this->Customer->check_tel($this->no_user->tel2,TRUE),'check_tel');
		echo $this->unit->run($this->Customer->check_tel($yamada->tel),FALSE,'check_tel');
		echo $this->unit->run($this->Customer->check_tel($yamada->tel2),FALSE,'check_tel');
		//コード重複チェック
		$this->no_user->code = '000000';
		echo $this->unit->run($this->Customer->check_code($this->no_user->code),TRUE,'check_code');
		echo $this->unit->run($this->Customer->check_code($yamada->code),FALSE,'check_code');
		//ユーザー名重複チェック
		$this->no_user->username = NULL;
		echo $this->unit->run($this->Customer->check_username($this->no_user->username),TRUE,'check_usernaem');
		echo $this->unit->run($this->Customer->check_username($yamada->username),FALSE,'check_username');
	}
}
