<?php
include __DIR__.'/../libraries/define.php';
class Test_cart extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Cart');
		$this->load->model('Advertise_product');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function test_on_sale()
	{
		$product_id = 2004;
		$row = $this->Advertise_product->check_on_sale($product_id);
		var_dump($row);
		$result = $this->Advertise_product->validate_sale_period($product_id);
		var_dump($result);
	}	
}
