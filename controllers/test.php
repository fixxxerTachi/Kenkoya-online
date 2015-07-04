<?php
include __DIR__.'/../libraries/define.php';
class Test extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Category');
		$this->load->model('Product');
		$this->load->model('Master_on_sale');
		$this->load->model('Allergen');
		$this->load->model('Recommend');
		$this->load->model('Advertise');
		$this->load->model('Advertise_product');
		$this->load->model('Customer');
		$this->load->helper('form');
		$this->load->model('Area');
		$this->load->model('Master_days');
		$this->load->model('Master_takuhai_hours');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function index()
	{
		//$result = $this->Product_image->resize('images/products/332879.jpg','022222',300,170);
		$arr=array(
			'username'=>'tachi',
			'usercode'=>'888',
		);
		$this->session->set_userdata($arr);
		var_dump($this->session->userdata('usercode'));
		$this->session->set_userdata('username','hoge');
		var_dump($this->session->userdata('username'));
		$this->session->set_userdata('cart','productname');
		//$this->session->unset_userdata('username');
		$obj = new StdClass();
		$obj->name = 'hoge';
		$obj->tel = '6666';
		$this->session->set_userdata('username',$obj);
		echo '<pre>';print_r($this->session->all_userdata());echo '</pre>';
		log_message('error',var_dump($obj));
		//$this->unit->run($result,'is_true');
	}
	
	public function sqlserver()
	{
		$dbh = new PDO("sqlsrv:Server=OWNER-PC\SQLEXPRESS;Database=sampleDB", "sa", "P@ssword");
		var_dump($dhb);
	}
	
	public function session()
	{
		echo '<pre>';print_r($this->session->all_userdata());echo '</pre>';
	}
	
	public function upload(){
			$this->data['h2title'] = '画像アップロード';
			if($this->input->post('submit')){
				if(!empty($_FILES['image']['name'])){
					$code = '';
					if($this->input->post('code')){
						$code = $this->input->post('code');
					}else{
						$image_name = explode('.',$_FILES['image']['name']);
						$code = $image_name[0];
					}
	//var_dump($code);exit;
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
							$result = $this->Product->resize(PRODUCT_IMAGE_PATH.$_FILES['image']['name'],$code,300);
							if($result){
									$this->session->set_flashdata('success','登録しました');
									redirect(base_url('test/upload'));
							}else{
								$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							}
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
							redirect(base_url('/test/upload'));
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						redirect(base_url('/test/upload'));
					}
				}
			}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('test/upload',$this->data);
	}
	
	public function advertise_item()
	{
		$result = $this->Advertise_product->get_product_by_image_name(201,4);
		echo '<pre>';print_r($result);echo '</pre>';
	}
	
	public function test_filter()
	{
		if($name = $this->input->post('name')){
			$this->data['name'] = $name;
			echo $this->security->get_csrf_token_name();
			echo $this->security->get_csrf_hash();
		}
		$this->load->view('test/test_filter',$this->data);
	
	}
	
	public function test_crone()
	{
		$db = $this->Ice_product->db;
		$date = new DateTime();
		$date = $date->format('YmdHis');
		$str = "updated";
		$db_data = array('comment' => $date);
		$db->where('id',1);
		$db->update('history',$db_data);
	}
	
	public function test_category()
	{
		$str = '野菜';
		$result = $this->Advertise_product->get_by_name($str);
		echo '<pre>';print_r($result);echo '</pre>';
	}
	
	public function test_generate_username()
	{
		$db = $this->Customer->db;
		$db->select('max(code) as maxcode')->from('customers');
		$result = $db->get()->row();
		var_dump($result);
		$max = (int)$result->maxcode;
		$pre_username = $max + 1;
		$password = uniqid(mt_rand(1,99));
		echo $password;
		
		echo '<pre>';print_r($this->Customer->get_area_by_zip(9291724));echo '</pre>';
	}
	
	public function test_delivery_day()
	{
		$date = $this->Area->delivery_select_date_for_takuhai('+6 day',$this);
		echo '<pre>'; print_r($date); echo '</pre>';
		//$result = $this->Area->delivery_select_date_for_takuhai('+6 day',$this,
	}
	
	public function test_no_member_save()
	{
		$user_data = array(
			'name'=>'nname',
			'furigana'=>'furigana',
			'tel'=>'tel',
			'tel2'=>'tel2',
			'email'=>'mailaddress',
			'zipcode'=>'050-5599-55577',
			'address1'=>'testnoaddress',
			'member_flag'=>0,
			'create_date'=>date('Y-m-d H:i:s'),
		);
		try{
			$id = $this->Customer->save($user_data);
			var_dump($id);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function get_delivery_hour(){
		echo $this->Master_takuhai_hours->hours[3];
	}

	public function test_birthday()
	{
		$this->load->model('Master_birthday');
		$year = $this->Master_birthday->set_expire_yea();
		var_dump($year);
	}
}
