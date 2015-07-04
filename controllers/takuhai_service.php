<?php
include __DIR__.'/../libraries/define.php';
class Takuhai_service extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = new StdClass();
		$this->load->model(
			array(
				'Bread',
				'Advertise',
			)
		);
		$this->load->helper(array('form'));
		$this->load->library(array('session'));
		$this->data->cart_count = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
		$this->data->customer = $this->session->userdata('customer');
	}
	
	
	public function index()
	{
		$this->data->title = '宅配スーパー健康屋　| 宅配サービスの案内';
		$bread = $this->Bread;
		$bread->text = '宅配サービスとは';
		$this->data->breads = $this->Bread->create_bread($bread);
		$this->load->view('takuhai_service/index',$this->data);
	}
	
	public function show_policy()
	{
		$this->data->title = '宅配スーパー健康屋 | 宅配サービスご利用規約';
		$this->data->h2title = '宅配スーパー健康屋 | 宅配サービスご利用規約';
		$bread1 = $this->Bread;
		$bread1->link = base_url('takuhai_service');
		$bread1->text = '宅配サービスとは';
		$bread2 = new StdClass();
		$bread2->text = '宅配サービスのご利用案内';
		$this->data->breads = $this->Bread->create_bread($bread1,$bread2);
		$this->load->view('takuhai_service/show_policy',$this->data);
	}	
}