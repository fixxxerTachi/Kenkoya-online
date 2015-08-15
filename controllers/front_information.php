<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
class Front_information extends CI_Controller {
	public $data;
	public $breads;
	public function __construct()
	{
		parent::__construct();
		$this->data = new StdClass();
		$this->load->model(
			array(
				'Information',
				'Top10',
				'Recommend',
				'Category',
				'Advertise_product',
				'Master_quantity',
				'Mainvisual',
				'Banner',
			)
		);
		$this->load->helper(array('form'));
		$this->load->library('session');
		$this->data->informations = $this->Information->show_list_front();
		$this->data->top10 = $this->Top10->show_list_side_top10(5);
		$this->data->recommend = $this->Recommend->show_list_side_recommend(5);
		$this->data->categories = $this->Category->show_list();
		$this->data->mainvisual = $this->Mainvisual->get_image_name();
		$this->data->banner = $this->Banner->get_image_name();
		$this->data->customer = $this->session->userdata('customer');
		$this->data->count_cart = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
		$home = new StdClass();
		$home->link = base_url();
		$home->text = 'home';
		$this->breads = array();
		$this->breads[] = $home;
	}
	
	public function index()
	{
		$info_id = $this->uri->segment(3);
		if(empty($info_id)){
			$info_id = null;
		}else{
			if(!is_numeric($info_id)){
				throw new Exception('no numeric');
			}
		}
		$info = $this->Information->get_by_id($info_id,true);
		$info = $info[0];
		$this->data->title = 'おしらせ';
		$this->data->h2title = $info->title;
		$this->data->info = $info;
		//bread
		$bread1 = new StdClass();
		$bread1->link = base_url();
		$bread1->text = 'お知らせ';
		$bread2 = new StdClass();
		$bread2->text = $info->title;
		$this->breads[] = $bread1;
		$this->breads[] = $bread2;
		$this->data->breads = $this->breads;
		$this->load->view('front_information/index',$this->data);
	}
}