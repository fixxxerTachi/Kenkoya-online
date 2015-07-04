<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_flag.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';

class Front_advertise extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination'));
		$this->load->model('Advertise');
		$this->load->model('Category');
		$this->load->model('Product');
		$this->load->model('Allergen');
		$this->load->model('Master_on_sale');
		$this->load->model('Advertise_image');
		$this->load->model('Master_quantity');
		$this->load->model('Cart');
		$this->load->model('Banner');
		$this->load->model(array('Information','Top10','Recommend','Category','Advertise_product'));
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['customer'] = $this->session->userdata('customer');
		$this->data['informations'] = $this->Information->show_list_front();
		$this->data['top10'] = $this->Top10->show_list_side_top10(5);
		$this->data['recommend'] = $this->Recommend->show_list_side_recommend(5);
		$this->data['categories'] = $this->Category->show_list();
		$this->data['banner'] = $this->Banner->get_image_name();
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$this->data['h2title'] = '宅配スーパー健康屋 商品チラシ一覧';
		$this->data['title'] = '商品チラシ一覧';
		$result = $this->Advertise->show_advertise_with_image();
		$this->data['result'] = $result;
		$this->load->view('front_advertise/index',$this->data);
	}
	
	public function detail_advertise()
	{
		$ad_id = $this->uri->segment(3);
		if(!is_numeric($ad_id)){ throw new Excepiton('no numeric');}
		$advertise = $this->Advertise->get_by_id($ad_id);
		$result = $this->Advertise_image->show_list_by_advertise($ad_id);
		$this->data['result'] = $result;
		$this->data['h2title'] = $advertise->title;
		$this->data['title'] = $advertise->title;
		$this->load->view('front_advertise/detail_advertise',$this->data);
	}
	
	public function detail_advertise_test()
	{
		$ad_id = $this->uri->segment(3);
		if(!is_numeric($ad_id)){ throw new Excepiton('no numeric');}
		$advertise = $this->Advertise->get_by_id($ad_id);
		$result = $this->Advertise_image->show_list_by_advertise($ad_id);
		$this->data['result'] = $result;
		$this->data['h2title'] = $advertise->title;
		$this->load->view('front_advertise/detail_advertise_test',$this->data);
	}
	
	public function list_product()
	{
		$this->data['h2title'] = '商品リスト';
		if(!$advertise_id = $this->uri->segment(3))
		{
			show_404();
		}
		$advertise = $this->Advertise->get_by_id_with_advertise_image($advertise_id,True);
//echo '<pre>';print_r($advertise);echo '</pre>';
		if(!$this->_is_release($advertise->end_datetime))
		{
			show_404();
		}
		$products = $this->Advertise->get_products_by_advertise_id_with_image($advertise_id);
		$this->data['products'] = $products;
		$this->data['advertise'] = $advertise;
		$this->data['ad_id'] = $advertise_id;
		$this->load->view('front_advertise/list_product',$this->data);
	}
	
	public function detail_product()
	{
//var_dump($this->session->userdata('carts'));
		$carts=array();
		if($this->session->userdata('carts')){
			$carts = $this->session->userdata('carts');
		}
		$ad_id = $this->uri->segment(4);
		$product_id = $this->uri->segment(3);
		$ad_result = $this->Advertise->get_by_id($ad_id);
		$product_result = $this->Advertise->get_product_by_id_with_product($product_id);
		if($this->input->post('submit')){
			$qt = $this->input->post('quantity');
			$cart = $this->Cart;
			$cart->product_id = $product_id;
			$cart->advertise_id = $ad_id;
			$cart->quantity = $qt;
			$form_data->quantity = $qt;
			$carts[] = serialize($cart);
			$this->session->set_userdata('carts',$carts);
			$this->session->set_flashdata('success','カートに入れました');
			return redirect("front_advertise/detail_product/{$product_id}/{$ad_id}/{$qt}");
		}
		//$this->data['ad_result'] = $ad_result[0];
		$quantity = $this->uri->segment(5);
		$this->data['quantity'] = $quantity;
		$this->data['result'] = $product_result[0];
		$this->data['select_quantity'] = $this->Master_quantity->quantity;
		$this->data['allergen'] = $this->Product->get_allergen_by_id($product_result[0]->product_id);
		$this->data['h2title'] = "{$ad_result->title}: 商品情報の詳細";
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_advertise/detail_product.php',$this->data);
	}
	
	private function _is_release($end_str){
		$today = new DateTime();
		$today = $today->format('Y-m-d');
		$endday = new DateTime($end_str);
		$endday = $endday->format('Y-m-d');
		return $today <= $endday;
	}
	
	public function show_page()
	{
		$ad_id = $this->uri->segment(3);
		$start_page = $this->uri->segment(4);
		$end_page = ($this->uri->segment(5)) ?: 0;
		if(!empty($end_page) && !is_numeric($end_page)){ throw new Exception('no numeric');}
		$result = $this->Advertise_product->get_imageinfo_by_ad_page($ad_id,$start_page,$end_page);
		$this->data['result'] = $result;
		$this->load->view('front_advertise/show_page',$this->data);
	}
	
	public function show_item()
	{
		$image_group = $this->uri->segment(3);
		$advertise_id = $this->uri->segment(4);
		$result = $this->Advertise_product->get_product_by_image_name($image_group,$advertise_id);
		$this->data['select_quantity'] = $this->Master_quantity->quantity;
		$this->data['result'] = $result;
		$this->load->view('front_advertise/show_item',$this->data);
	}
	
	public function get_image_info()
	{
		$ad_id = $this->uri->segment(3);
		$start_page = $this->uri->segment(4);
		$end_page = $this->uri->segment(5);
		if(empty($end_page)){
			$end_page = $start_page;
		}
		if(!is_numeric($ad_id) && !is_numeric($start_page) && !is_numeric($end_page)){
			throw new Exception('no numeric value');
		}
		$result = $this->Advertise_product->get_imageinfo_by_ad_page($ad_id,$start_page,$end_page);
		header( 'Content-Type: application/json' );
		echo(json_encode($result));
	}
	
}
