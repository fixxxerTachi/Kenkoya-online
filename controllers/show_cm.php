<?php
class Show_cm extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = new StdClass();
		$this->load->model(
			array(
				'Banner',
				'Bread',
			)
		);
		$this->load->helper(array('form'));
		$this->load->library(array('session'));
		$this->data->cart_count = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
		$this->data->customer = $this->session->userdata('customer');
	}
	
	
	public function index()
	{
		$this->data->title = '宅配スーパー健康屋　| CM';
		$this->data->h2title = '宅配スーパー健康屋 CMの一覧';
		$bread = $this->Bread;
		$bread->text = '宅配スーパー健康屋CM';
		$this->data->breads = $this->Bread->create_bread($bread);
		$this->load->view('show_cm/index',$this->data);
	}	
}