<?php
//include __DIR__.'/../libraries/define.php';
//include __DIR__.'/../libraries/define_mail.php';
//include __DIR__.'/../libraries/common.php';
//include __DIR__.'/../libraries/sendmail.php';
//include __DIR__.'/../libraries/csv.php';

class Front_area extends CI_Controller{
	public $data = array();
	//public $deliver_possible_day = '+3 days';
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','my_class','encrypt'));
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Area');
		$this->load->model('Master_area');
		$this->load->model('Bread');
		$this->load->model('Cource');
		$this->data['customer'] = $this->session->userdata('customer');
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function search_area()
	{
		$this->data['h2title'] = '宅配スーパー健康屋：配達可能エリア検索';
		$this->data['title']  = '配達可能エリア検索';
		$form_data = $this->Customer;
		$this->data['result'] = null;
		if($this->input->post('search_zip')){
			$form_data = array(
				'zipcode'=>$this->input->post('zipcode1').$this->input->post('zipcode2'),
			);
			$form_data = (object)$form_data;
			if(!$this->validation_zip($form_data->zipcode) == FALSE){
				//郵便番号が正しく入力されていたらエリアマスタからデータを取り出して反映
				$result = $this->Customer->get_area_by_zip($form_data->zipcode);
				$is_area = $this->_is_area($result,$form_data->zipcode);
				$this->data['is_area'] = $is_area;
				$this->data['result'] = $result;
			}else{
				//郵便番号の入力にエラーがある場合
				$this->data['error_message'] = '郵便番号は正しく入力してください';
			}
			$form_data->zipcode1 = $this->input->post('zipcode1');
			$form_data->zipcode2 = $this->input->post('zipcode2');
		}
		$this->data['form_data'] = $form_data;
		//bread
		$bread = $this->Bread;
		$bread->link = base_url('takuhai_service');
		$bread->text = '宅配サービスとは';
		$bread1 = new StdClass();
		$bread1->text = '宅配サービス利用可能エリア検索';
		$this->data['breads'] = $this->Bread->create_bread($bread,$bread1);
		$this->load->view('front_area/search_area',$this->data);
	}
	
	public function show_area(){
		$kana = array(
			'ア'=>array('ｱ','ｲ','ｳ','ｴ','ｵ'),
			'カ'=>array('ｶ','ｷ','ｸ','ｹ','ｺ'),
			'サ'=>array('ｻ','ｼ','ｽ','ｾ','ｿ'),
			'タ'=>array('ﾀ','ﾁ','ﾂ','ﾃ','ﾄ'),
			'ナ'=>array('ﾅ','ﾆ','ﾇ','ﾈ','ﾉ'),
			'ハ'=>array('ﾊ','ﾋ','ﾌ','ﾍ','ﾎ'),
			'マ'=>array('ﾏ','ﾐ','ﾑ','ﾒ','ﾓ'),
			'ヤ'=>array('ﾔ','ﾕ','ﾖ'),
			'ラ'=>array('ﾗ','ﾘ','ﾙ','ﾚ','ﾛ'),
			'ワ'=>array('ﾜ'),
		);
		$area_id = $this->uri->segment(3);
		$city = $this->uri->segment(4);
		if(empty($area_id)){ return redirect(base_url('area/search_area')); }
		$area_name = $this->Master_area->list_area_id[(int)$area_id];
		$cities = $this->Area->list_area_by_area_id($area_id);
		if(!empty($city)){
			$result = $this->Area->list_area_by_area($city);
			$this->data['result'] = $result;
			$this->data['city_name']= urldecode($city);
		}
		$this->data['h2title'] = "{$area_name}の無料配達エリア";
		$this->data['title'] = "{$area_name}の配達エリア";
		$this->data['cities'] = $cities;
		$this->data['area_id'] = $area_id;
		$this->data['kana'] = $kana;
		$this->data['model'] = $this->Area;
		//bread
		$bread = $this->Bread;
		$bread->link = base_url('takuhai_service');
		$bread->text = '宅配サービスとは';
		$bread1 = new StdClass();
		$bread1->link = base_url('area/search_area');
		$bread1->text = '宅配サービス利用可能エリア検索';
		$bread2 = new StdClass();
		$bread2->text = $this->data['h2title'];
		$this->data['breads'] = $this->Bread->create_bread($bread,$bread1,$bread2);
		$this->load->view('front_area/show_area',$this->data);
	}
	
	public function result_area()
	{
		$this->data['h2title'] = '配達エリア検索結果';
		$this->data['title'] = '配達エリア検索結果';
		$zipcode = $this->uri->segment(3);
		if(!is_numeric($zipcode)){ throw new Exception('NO Numeric Value'); }
		$result = $this->Customer->get_area_by_zip($zipcode);
		$is_area = $this->_is_area($result,$zipcode);
		$this->data['is_area'] = $is_area;
		$this->data['result'] = $result;
		//bread
		$bread = $this->Bread;
		$bread->link = base_url('takuhai_service');
		$bread->text = '宅配サービスとは';
		$bread1 = new StdClass();
		$bread1->link = base_url('area/search_area');
		$bread1->text = '宅配サービス利用可能エリア検索';
		$area_id = $result->area_id;
		$area_name = $this->Master_area->list_area_id[(int)$area_id];
		$bread2 = new StdClass();
		$bread2->text = "{$area_name}の配達エリア";
		$bread2->link = base_url("area/show_area/{$area_id}/");
		$bread3 = new StdClass();
		$bread3->text  = $this->data['h2title'];
		$this->data['breads'] = $this->Bread->create_bread($bread,$bread1,$bread2,$bread3);
		$this->load->view('front_area/result_area',$this->data);
	}

	public function check_area()
	{
		$uri_flag = $this->uri->segment(3);
		$this->data['h2title'] = '宅配スーパー健康屋：配達可能エリア検索';
		$this->data['title']  = '無料配達可能エリア検索';
		$form_data = $this->Customer;
		$is_area = null;
		if($this->input->post('search_zip')){
			$form_data->zipcode1 = $this->my_class->convert_alpha($this->input->post('zipcode1'));
			$form_data->zipcode2 = $this->my_class->convert_alpha($this->input->post('zipcode2'));
			$form_data->zipcode = $form_data->zipcode1 . $form_data->zipcode2;
			if(!$this->validation_zip($form_data->zipcode) == FALSE)
			{
				//郵便番号が正しく入力されていたらエリアマスタからデータを取り出して反映
				$result = $this->Customer->get_area_by_zip($form_data->zipcode);
				$zipcode = $this->input->post('zipcode1').$this->input->post('zipcode2');
				$is_area = $this->_is_area($result,$zipcode);
				$this->data['result'] = $result;
			}
			else
			{
				//郵便番号の入力にエラーがある場合
				$this->data['error_message'] = '郵便番号は正しく入力してください';
			}
			$this->data['is_area'] = $is_area;
		}

		$member_link = new StdClass();
		
		//会員登録しない人　カートからのアクセス
		if($uri_flag == 'no-member')
		{
			//配達エリア内だったら会員登録をお勧めする。
			$member_link->recommend_text = '健康屋宅配サービスエリア内ですので会員登録していただくと商品を0円～配送できます。';
			$member_link->member_text = '会員登録する';
			$member_link->member_url = 'customer/show_policy/nav';
			$member_link->text = '会員登録せずに購入処理を進める';
			$member_link->url = 'customer/show_policy/no-member';
		//マイページ内の住所変更からのアクセス
		}
		else if($uri_flag == 'mypage')
		{
			$member_link->recommend_text = '健康屋宅配サービスエリア内ですので会員登録していただくと商品を0円～配送できます。';
			$member_link->member_text = '住所を変更する';
			$member_link->member_url = 'mypage/mypage_change/address';
			$member_link->text = '戻る';
			$member_link->url = 'mypage/mypage';
		//会員登録したいひと ナビゲーションリンクからのアクセス //param: navはいらないかも
		}
		else if($uri_flag == 'nav')
		{
			$member_link->member_text = '会員登録を進める';
			$member_link->member_url = 'customer/show_policy';
			$member_link->text = '戻る';
			$member_link->url = 'customer/login_action';
		//会員登録したいひと　カートからのアクセス
		}
		else
		{
			$member_link->member_text = '会員登録を進める';
			$member_link->member_url = 'customer/show_policy';
			$member_link->text = '戻る';
			$member_link->url = 'customer/login_action';
		}
		$this->data['member_link'] = $member_link;
		$this->data['form_data'] = $form_data;
		$this->load->view('front_area/check_area',$this->data);
	}

	private function validation_zip($zip)
	{
		if(empty($zip) || !is_numeric($zip)){
			return false;
		}elseif(mb_strlen($zip,'UTF-8') != 7){
			return false;
		}else{
			return true;
		}
	}

	private function _is_area($obj,$zipcode,$flag = FALSE)
	{
		//配達コースがセットされていたら
		if(!empty($obj) && $obj->cource_id != 0){
			$flag = TRUE;
			$obj->is_master_area = TRUE;
			$this->session->set_userdata('address',$obj);
		//配達コースがセットされていても配達圏外
		}elseif(!empty($obj) && $obj->cource_id == 0){
			$obj->is_master_area = TRUE;
			$this->session->set_userdata('address',$obj);
			$flag = FALSE;
		//配達コースマスタなし
		}elseif(empty($obj)){
			$newobj = new StdClass();
			$newobj->is_master_area = FALSE;
			$newobj->zipcode = $zipcode;
			$newobj->zipcode1 = substr($zipcode,0,3);
			$newobj->zipcode2 = substr($zipcode,3,4);
			$this->session->set_userdata('address',$newobj);
			$flag = FALSE;
		}
		return $flag;
	}

}
