<?php
include __DIR__.'/../libraries/define.php';
class Test_my_bank extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('My_bank');
	}

	public function index()
	{
		var_dump($this->My_bank);
	}

}
