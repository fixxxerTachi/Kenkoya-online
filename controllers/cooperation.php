<?php
include __DIR__.'/../libraries/define.php';
class Cooperation extends CI_Controller {
	public $data;
	public function __construct()
	{
		parent::__construct();
		$this->data = new StdClass();
		$this->load->helper(array('form'));
		$this->load->library('session');
		$this->data->customer = $this->session->userdata('customer');
		$this->data->cart_count = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$this->data->title = '宅配スーパー健康屋 | 会社概要';
		$this->load->view('cooperation/index',$this->data);
	}
	
	public function show_policy()
	{
		$this->data->title = '宅配スーパー健康屋 | プライバシーポリシー';
		$this->load->view('cooperation/privacy_policy', $this->data);
	}
}