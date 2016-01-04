<?php
include __DIR__.'/../libraries/define.php';
class Test_validation extends CI_Controller {
	public $data;
	public $no_user;
	public $new_user;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test','my_validation'));
		$this->load->model('Customer');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->no_user = $this->Customer;
		$this->new_user = $this->Customer;
		
	}
	
	public function index()
	{
		//メール重複チェック
		$yamada = $this->Customer->get_by_id(528); //既に登録されているお客様
		$this->	new_user->email = 'tatick.tic.tic.tic.tock@gmail.com'; //会員登録していないお客様と重複している
		echo $this->unit->run($this->my_validation->email_check($yamada->email),FALSE,'email_check');
		echo $this->unit->run($this->my_validation->email_check($this->new_user->email),TRUE,'email_check');
		//電話番号重複チェック
		echo $this->unit->run($this->my_validation->tel_check($yamada->tel),FALSE,'tel_check');
		echo $this->unit->run($this->my_validation->tel_check($yamada->tel2),FALSE,'tel_check');
		$this->new_user->tel = '123456789';
		$this->new_user->tel2 = '789456123';
		echo $this->unit->run($this->my_validation->tel_check($this->new_user->tel),TRUE,'tel_check');
		echo $this->unit->run($this->my_validation->tel_check($this->new_user->tel2),TRUE,'tel_check');
		//コード重複チェック
		echo $this->unit->run($this->my_validation->code_check($yamada->code),FALSE,'code_check');
		//ユーザー名チェック
		echo $this->unit->run($this->my_validation->username_check($yamada->username),FALSE,'username_check');

	}
	
	public function test_customer_validation()
	{
		//メール重複チェック
		$yamada = $this->Customer->get_by_id(528);
		echo $this->unit->run($this->Customer->check_email($yamada->email),FALSE,'check_email');
		//電話番号重複チェック
		echo $this->unit->run($this->Customer->check_tel($yamada->tel),FALSE,'check_tel');
		echo $this->unit->run($this->Customer->check_tel($yamada->tel2),FALSE,'check_tel');
		//コード重複チェック
		echo $this->unit->run($this->Customer->check_code($yamada->code),FALSE,'check_code');
		//ユーザー名重複チェック
		echo $this->unit->run($this->Customer->check_username($yamada->username),FALSE,'check_username');
	}
}
