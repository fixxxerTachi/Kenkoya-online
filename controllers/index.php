<?php
include __DIR__.'/../libraries/define.php';
class Index extends CI_Controller {
	public $data;
	
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
				'Bread',
			)
		);
		$this->load->helper(array('form'));
		$this->load->library(array('session','pagination'));
		$this->data->informations = $this->Information->show_list_front();
		$this->data->top10 = $this->Top10->show_list_side_top10(5);
		$this->data->recommend = $this->Recommend->show_list_side_recommend(10);
		$this->data->categories = $this->Category->show_list();
		//$this->data->mainvisual = $this->Mainvisual->get_image_name();
		$this->data->mainvisual = $this->Mainvisual->get_images();
		$this->data->banner = $this->Banner->get_image_name();
		$this->data->customer = $this->session->userdata('customer');
		$this->data->cart_count = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$this->load->library('useragent');

//echo '<pre>';print_r($this->data->recommend);echo '</pre>';
		$this->data->title = '宅配スーパー健康屋とは';
		/* pc view */
		//$this->load->view('index',$this->data);
		/* mobile view */
		$this->data->agent = $this->useragent->set();
		if($this->data->agent != 'others'){
			$this->load->view('mobile/index',$this->data);
		}else{
			$this->load->view('index',$this->data);
		}

	}
	
	public function category()
	{
		try{
			$category_id = $this->uri->segment(3);
			$offset = $this->uri->segment(4);
			if(empty($offset)){
				$offset = 0;
			}
			$category = $this->Category->get_category_by_id($category_id);
			$count = $this->Advertise_product->all_data($category_id);
			//pagination
			$config['uri_segment'] = 4;
			$config['base_url'] = base_url("index/category/{$category_id}");
			$config['total_rows'] = $count;
			$config['per_page'] = 20;
			$config['next_link'] = false;
			$config['prev_link'] = false;
			$config['first_link'] = '1...';
			$config['first_tag_open'] = '<span class="first_tag">';
			$config['first_tag_close'] = '</span>';
			$config['last_tag_open'] = '<span class="last_tag">';
			$config['last_tag_close'] = '</span>';
			$config['cur_tag_open'] = '<span class="current">';
			$config['cur_tag_close'] = '</span>';
			$last_page = ceil($count/$config['per_page']);
			$config['last_link'] = "...{$last_page}";
			$this->pagination->initialize($config);
			$result = $this->Advertise_product->show_list_by_category($category_id,$config['per_page'],$offset);
			$this->data->links = $this->pagination->create_links();
			$this->data->result = $result;
			$this->data->category = $category;
			$this->data->title = $category->show_name . 'のカテゴリ';
			$this->data->h2title = $category->show_name . 'の商品一覧';
			$this->data->h2icon = 'category';
			//bread
			$bread = $this->Bread;
			$bread->text = $this->data->h2title;
			$this->data->breads = $this->Bread->create_bread($bread);
			$this->load->view('front_product/category',$this->data);
		}catch(Exception $e){
			show_404();
		}
	}
	
	public function search_product()
	{
		$product_name = $this->input->get('product');
		$result = $this->Advertise_product->get_by_name($product_name);
		$this->data->result = $result;
		$this->data->title = $product_name . 'の商品一覧';
		$this->data->h2title = $product_name . 'の商品一覧';
		$this->data->h2icon = 'product';
		//bread
		$bread = $this->Bread;
		$bread->text = $this->data->h2title;
		$this->data->breads = $this->Bread->create_bread($bread);
		$this->load->view('front_product/category',$this->data);
	}
	
	public function detail_product()
	{
		try{
			$id = $this->uri->segment(3);
			if(!is_numeric($id)){
				throw new Exception('no decimal');
			}
			$result = $this->Advertise_product->get_by_product_id($id);
			$this->data->row= $result;
			$this->data->title = '商品詳細';
			$this->data->h2title = "{$result->product_name}";
			$this->data->select_quantity = $this->Master_quantity->quantity;
			//bread
			$category_id = $result->category_id;
			$category = $this->Category->get_category_by_id($category_id);
			$bread1 = $this->Bread;
			$bread1->link = base_url("index/category/{$category_id}");
			$bread1->text = $category->show_name . 'の商品一覧';
			$bread2 = new StdClass();
			$bread2->text = $this->data->h2title;
			$this->data->breads = $this->Bread->create_bread($bread1,$bread2);
			$this->load->view('front_product/detail_product',$this->data);
		}catch(Exception $e){
			show_404();
		}
	}
	
	public function buy_flow()
	{
		$this->data->title = 'ご購入の流れ';
		//bread
		$bread = $this->Bread;
		$bread->text = $this->data->title;
		$this->data->breads = $this->Bread->create_bread($bread);
		$this->load->view('front_product/buy_flow',$this->data);
	}
	
	public function guidance()
	{
		$this->data->title = '宅配スーパー健康屋 | ご利用の案内';
		$this->data->h2title = '宅配スーパー健康屋 | ご利用の案内';
		$this->load->view('takuhai_service/guidance',$this->data);
	}
	
	/* 画像の表示　show_image::displayに記述
	public function show_image($product_code, $size)
	{
		$img = ImageCreateFromJpeg('images/products/ak' . $product_code . '.jpg');
		$width = ImageSx($img);
		$height = ImageSy($img);
		$out = ImageCreateTrueColor($size, $size);
		ImageCopyResampled($out,$img,0,0,0,0,$width/2,$height/2,$width,$height);
		header('Content-Type: image/jpeg');
		imagejpeg($out);
	}
	*/
	
	public function information()
	{
		$id = $this->uri->segment(3);
		$info = $this->Information->get_by_id($id, True);
		$this->data->h2title = $info->title;
		$this->data->title = $info->title;
		$this->data->info = $info;
		$this->load->view('information/index',$this->data);
	}
	
	public function notation()
	{
		$this->data->title = '特定商取引に関する法律に基づく表示';
		$this->data->h2title = '特定商取引に関する法律に基づく表示';
		$this->load->view('notation/index',$this->data);
	}
	
	public function show_policy()
	{
		$this->data->title = '宅配スーパー健康屋 | プライバシーポリシー';
		$this->load->view('cooperation/privacy_policy',$this->data);
	}
	
	public function takuhai_service()
	{
		$this->data->title = '宅配スーパー健康屋　| 宅配スーパー健康屋とは';
		$this->load->view('takuhai/index',$this->data);
	}
	
	public function show_charge()
	{
		$this->load->model('Takuhai_charge');
		$this->data->title = '宅急便送料';
		$blocks = $this->Takuhai_charge->get_block_name();
//var_dump($blocks);
		$this->data->blocks = $blocks;
		$classes = $this->Takuhai_charge->get_charge_class();
		$this->data->classes = $classes;
		$prefnames = $this->Takuhai_charge->get_prefname_by_block_id(2);
//echo '<pre>';print_r($prefnames);echo '</pre>';
		$this->data->Takuhai_charge = $this->Takuhai_charge;
		$charge_class = $this->Takuhai_charge->get_charge_class();
//var_Dump($charge_class);
		$charge_price = $this->Takuhai_charge->get_charge_price_by_temp_id(2,2);
//var_dump($charge_price);
		$this->load->view('takuhai_charge/index',$this->data);
	}

}