<?php
class Test_base_info extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->model('Base_info');
	}
	
	public function index()
	{
		$info = $this->Base_info->get_info();
		var_dump($info);
		var_dump($this->Base_info->shop_name);
	}
}
