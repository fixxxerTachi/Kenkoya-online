<?php
include __DIR__.'/../libraries/define.php';
class Test_temp_zone extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Temp_zone');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}

	public function test_temp()
	{
		$temp_zones = $this->Temp_zone->get_short_names();
		var_dump($temp_zones);
	}
}
