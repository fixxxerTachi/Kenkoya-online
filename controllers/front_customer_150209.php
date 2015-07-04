<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_mail.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/csv.php';

class Front_customer extends CI_Controller{
	public $data = array();
	public $deliver_possible_day = '+3 days';
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','myclass','encrypt'));
		$this->load->helper('form');
		$this->load->helper('captcha');
		$this->load->model('Customer');
		$this->load->model('Area');
		$this->load->model('Master_days');
		$this->load->model('Customer_info');
		$this->load->model('Master_mail_magazine');
		$this->load->model('Master_hour');
		$this->load->model('Master_birthday');
		$this->load->model('Order');
		$this->load->model('Master_order_status');
		$this->load->model('Master_quantity');
		$this->load->model('Master_area');
		$this->load->model('Bread');
		$this->load->model('Captcha');
		$this->load->model('Master_type_account');
		$this->load->model('Mail_template');
		$this->load->model('Admin_mail');
		$this->load->model('Cource');
		$this->load->model('Master_takuhai_hours');
		$this->load->model('Order_info');
		$this->data['customer'] = $this->session->userdata('customer');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
//var_dump($this->session->userdata('customer'));
//var_dump($this->session->userdata('no-member'));
	}
	
	public function index()
	{
		$this->data['h2title'] = '';
		$this->load->view('index',$this->data);
	}
	
	public function login_action()
	{
		$customer = $this->session->userdata('customer');
		$ref = $this->uri->segment(3);
		$this->data['h2title'] = 'ログイン';
		$this->data['title'] = 'ログイン';
		$form_data=array(
			'username'=>'',
			'password'=>'',
			'member'=>'1',
		);
		//usernameがsessionにあったらフォームに表示
		if(!empty($customer)){
			$form_data['username'] = $customer->username;
		}
		$this->validation_login();
		//ログイン処理
		if($this->input->post('submit')){
			/*$post_ref = $this->input->post('ref');*/
			if(!$this->form_validation->run() === FALSE){
				$form_data = array(
					'username'=>$this->input->post('username'),
					'password'=>$this->input->post('password'),
					'member'=>$this->input->post('member'),
				);
				$form_data = (object)$form_data;
				//ログイン処理　session('customer')に obj->id ,username,name,emailを格納
				if(!$this->Customer->check_login_customer($form_data) == FALSE){
				//mypageからの遷移はmypageに移動
					if($ref == 'mypage'){
						return redirect('front_customer/mypage');
					}
				//カートからの遷移はdelivery_infoに移動
					if($ref == 'process'){
						return redirect('front_customer/delivery_info');
					}
				//それ以外はトップページへ移動
					return redirect(base_url('/'));
				}else{
					$this->data['error_message'] = 'ログインに失敗しました';
				}
			}
		}
		//会員登録処理、非会員処理
		if($this->input->post('save_member')){
			$param = $this->input->post('member');
			if($param == '1'){ redirect(base_url('front_customer/check_area'));}
			if($param == '0'){ redirect(base_url('front_customer/check_area/no-member'));}
		}
		$this->data['form_data'] = (object)$form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/login',$this->data);
	}
	
	public function check_area()
	{
		$uri_flag = $this->uri->segment(3);
		$this->data['h2title'] = '宅配スーパー健康屋：無料配達可能エリア検索';
		$this->data['title']  = '無料配達可能エリア検索';
		$form_data = $this->Customer;
		$is_area = null;
		if($this->input->post('search_zip')){
			$form_data = array(
				'zipcode'=>$this->myclass->convert_alpha($this->input->post('zipcode1').$this->input->post('zipcode2')),
			);
			$form_data = (object)$form_data;
			if(!$this->validation_zip($form_data->zipcode) == FALSE){
				//郵便番号が正しく入力されていたらエリアマスタからデータを取り出して反映
				$result = $this->Customer->get_area_by_zip($form_data->zipcode);
				$zipcode = $this->input->post('zipcode1').$this->input->post('zipcode2');
				$is_area = $this->_is_area($result,$zipcode);
			}else{
				//郵便番号の入力にエラーがある場合
				$this->data['error_message'] = '郵便番号は正しく入力してください';
			}
			$form_data->zipcode1 = $this->input->post('zipcode1');
			$form_data->zipcode2 = $this->input->post('zipcode2');
			$this->data['is_area'] = $is_area;
			$this->data['result'] = $result;
		}

		$member_link = new StdClass();
		
		//会員登録しない人　カートからのアクセス
		if($uri_flag == 'no-member'){
			//配達エリア内だったら会員登録をお勧めする。
			$member_link->recommend_text = '健康屋宅配サービスエリア内ですので会員登録していただくと無料で商品を配送できます。';
			$member_link->member_text = '会員登録する';
			$member_link->member_url = 'front_customer/show_policy/nav';
			$member_link->text = '会員登録せずに購入処理を進める';
			$member_link->url = 'front_customer/show_policy/no-member';
		//マイページ内の住所変更からのアクセス
		}else if($uri_flag == 'mypage'){
			$member_link->recommend_text = '健康屋宅配サービスエリア内ですので会員登録していただくと無料で商品を配送できます。';
			$member_link->member_text = '住所を変更する';
			$member_link->member_url = 'front_customer/mypage_change/address';
			$member_link->text = '戻る';
			$member_link->url = 'front_customer/mypage';
		//会員登録したいひと ナビゲーションリンクからのアクセス //param: navはいらないかも
		}else if($uri_flag == 'nav'){
			$member_link->member_text = '会員登録を進める';
			$member_link->member_url = 'front_customer/show_policy';
			$member_link->text = '戻る';
			$member_link->url = 'front_customer/login_action';
		//会員登録したいひと　カートからのアクセス
		}else{
			$member_link->member_text = '会員登録を進める';
			$member_link->member_url = 'front_customer/show_policy';
			$member_link->text = '戻る';
			$member_link->url = 'front_customer/login_action';
		}
		$this->data['member_link'] = $member_link;
		$this->data['form_data'] = $form_data;
		$this->load->view('front_customer/check_area',$this->data);
	}
	
	public function search_area()
	{
		$this->data['h2title'] = '宅配スーパー健康屋：無料配達エリア検索';
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
		$bread->text = $this->data['title'];
		$this->data['breads'] = $this->Bread->create_bread($bread);
		$this->load->view('front_customer/search_area',$this->data);
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
		if(empty($area_id)){ return redirect(base_url('front_customer/search_area')); }
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
		$bread->link = base_url('front_customer/search_area');
		$bread->text = '配達可能エリア検索';
		$bread2 = new StdClass();
		$bread2->text  = $this->data['h2title'];
		$this->data['breads'] = $this->Bread->create_bread($bread,$bread2);
		$this->load->view('front_customer/show_area',$this->data);
	}
	
	public function result_area()
	{
		$this->data['h2title'] = '無料配達エリア検索結果';
		$this->data['title'] = '無料配達エリア検索結果';
		$zipcode = $this->uri->segment(3);
		if(!is_numeric($zipcode)){ throw new Exception('NO Numeric Value'); }
		$result = $this->Customer->get_area_by_zip($zipcode);
		$is_area = $this->_is_area($result,$zipcode);
		$this->data['is_area'] = $is_area;
		$this->data['result'] = $result;
		$bread = $this->Bread;
		$bread->link = base_url('front_customer/search_area');
		$bread->text = '配達可能エリア検索';
		$bread1 = new StdClass();
		$bread1->link = base_url('front_customer/search_area');
		$area_id = $result->area_id;
		$area_name = $this->Master_area->list_area_id[(int)$area_id];
		$bread1->text = "{$area_name}の配達エリア";
		$bread1->link = base_url("front_customer/show_area/{$area_id}/");
		$bread2 = new StdClass();
		$bread2->text  = $this->data['h2title'];
		$this->data['breads'] = $this->Bread->create_bread($bread,$bread1,$bread2);
		$this->load->view('front_customer/result_area',$this->data);
	}
	
	public function show_policy()
	{
		$no_member = $this->uri->segment(3);
		$h2title = '利用規約';
		$message = 'ご購入には「利用規約」への同意が必要です。 以下の内容をご確認の上、ご同意いただける場合「以上の利用規約に同意する」にチェックをして、「次へ進む」をクリックしてください。';
		$this->data['h2title']  = $h2title;
		$this->data['message'] = $message;
		$this->data['title'] = '健康屋利用規約';
//		$zipcode = $this->uri->segment(3);
		if($this->input->post('submit')){
			if(!$this->input->post('agree')){
				if(!$no_member){
					$this->data['error_message'] = '会員規約に同意する場合は「以上の会員規約に同意する」にチェックを入れて下さい';
				}else{
					$this->data['error_message'] = 'プライバシーポリシーに同意する場合には「以上のプライバシーポリシーに同意する」にチェックをいれてください';
				}
			}else{
//				header("Location: " . site_url('front_customer/add_customer/') . $zipcode);
				if(!$no_member){
					return redirect(base_url('front_customer/add_customer'));
				}else{
					if($no_member == 'nav'){
						return redirect(base_url('front_customer/add_customer/nav'));
					}
					return redirect(base_url('front_customer/add_customer/no-member'));
				}
			}
		}
		$this->data['no_member'] = $no_member;
		$this->load->view('front_customer/show_policy',$this->data);
	}
	
	public function add_customer()
	{
		$no_member = $this->uri->segment(3);
		if($no_member == 'no-member'){
			$this->data['h2title'] = 'お届け先情報入力';
			$this->data['title'] = 'お届け先情報入力';
			$this->data['message'] = 'お届け先情報を入力してください';
		}else{
			$this->data['h2title'] = '会員新規登録';
			$this->data['title'] = '会員登録';
			$this->data['message'] = 'ログインに必要なログインIDとパスワードを発行いたしますので、必要事項を入力してください。';
		}
		
		$this->data['birthday'] = $this->Master_birthday;
		if($this->session->userdata('customer_info')){
			//あとでこの処理を飛ばす
			$form_data = $this->session->userdata('customer_info');
		}else{
			$form_data = $this->Customer;
			$form_data->email_confirm = '';
			$form_data->user_email = '';
		}
		//郵便番号検索処理
		$address = $this->session->userdata('address');
		$this->data['is_master_area'] = $address->is_master_area;
		//エリアマスタに登録されているものはセッションから情報を表示
		if($address->is_master_area){
			$address = $this->session->userdata('address');
			$form_data->zipcode = $address->zipcode;
			$form_data->prefecture = $address->prefecture;
			$form_data->address = $address->city . $address->address;
		//エリアマスタに登録されてないものは郵便番号のみセッションに入っているので表示
		}else{
			$form_data->zipcode1 = $address->zipcode1;
			$form_data->zipcode2 = $address->zipcode2;
		}
		
		//お客様情報登録処理
		if($this->input->post('submit')){
			$form_data = $this->input->post();
			$form_data = (object)$form_data;
			//フリガナ変換
			$form_data->furigana = mb_convert_kana($this->input->post('furigana'),'KCs');
			
			$this->validation_rules();
			if($no_member != 'no-member'){
			}
			if($this->form_validation->run() === FALSE){
			}else{
				if($this->check_email_confirm($form_data) === FALSE){
					$this->data['error_message'] = 'メールアドレスとメールアドレス(確認用)が異なります';
				}else{
					//$db_data = $this->input->post();
					$db_data = (object)$form_data;
					//誕生日が入力が不完全だったら表示しないためのプラグ
					if(empty($year) || empty($month) || empty($day)){
						$db_data->birthday = FALSE;
					}
					
					$this->session->set_userdata('customer_info',$db_data);
					//$this->session->unset_userdata('address');
					if($no_member != 'no-member'){
					/*
						if($no_member == 'nav'){
							return redirect(base_url('front_customer/confirm_customer/nav'));
						}
						redirect(base_url('front_customer/confirm_customer'));
					*/
						return redirect(base_url('front_customer/confirm_customer'));
					}else{
						redirect(base_url('front_customer/confirm_customer/no-member'));
					}
				}
			}
		}
		$this->data['no_member'] = $no_member;
		$this->data['form_data'] = $form_data;
		$this->data['succsss_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/add_customer.php',$this->data);
	}
	
	public function confirm_customer()
	{
//var_dump($this->session->userdata('customer_info'));
		if(!$this->session->userdata('customer_info')){ return redirect('front_customer/add_customer'); }
		$no_member = $this->uri->segment(3);
		$form_data=$this->session->userdata('customer_info');
		//zipcodeがなくてzipcode1,2がある場合zipcodeにまとめる
		if(empty($form_data->zipcode) && !empty($form_data->zipcode1) && !empty($form_data->zipcode2)){
			$form_data->zipcode = $form_data->zipcode1 . $form_data->zipcode2;
		}
		//addressがある（エリア内の場合)address1にまとめる
		if(isset($form_data->address)){
			$form_data->address1 = $form_data->address . $form_data->address1;
		}
		if($no_member != 'no-member'){
			$this->data['button_message'] = '会員登録する';
			$this->data['h2title'] = '会員情報確認画面';
			$this->data['title'] = '会員情報確認';
		}else{
			$this->data['button_message'] = '購入処理を進める';
			$this->data['h2title'] = 'お届け先情報入力確認';
			$this->data['title'] = 'お届け先情報入力確認';
		}
		if($this->input->post('submit')){
				$db_data = array(
					'name'=>$form_data->name,
					'furigana'=>$form_data->furigana,
					'email'=>$form_data->email,
					'zipcode'=>str_replace('-','',$form_data->zipcode),
					'address1'=>$form_data->prefecture.$form_data->address1.$form_data->address2,
					'tel'=>$form_data->tel,
					'tel2'=>$form_data->tel2,
					'birthday'=>"{$form_data->year}/{$form_data->month}/{$form_data->day}",
				);
			$db_data=(object)$db_data;
			/*
			if(!empty($form_data->maga) || $form_data->maga == '1'){
				$db_data->mail_magazine = 1;
			}else{
				$db_data->mail_magazine = 0;
			}
			*/
			
			//会員登録の場合ユーザーIDとパスワードを発行してDB登録
			if($no_member != 'no-member'){
				//現在のcodeの最大値を取得する
				$maxcode = $this->Customer->get_max_usercode();
				
				$maxcode = ($maxcode >= 100000) ? $maxcode : 100000;
				//ユーザーIDはメールアドレスにする
				$username = $db_data->email;
				$password = uniqid(mt_rand(1,99));
				$db_data->username = $username;
				$db_data->password = $this->encrypt->encode($password);
				
				//配達情報
				$area = $this->Customer->get_area_by_zip($db_data->zipcode);
				if(!empty($area)){
					$db_data->shop_code = $area->shop_code;
					$db_data->cource_code = $area->cource_code;
					$db_data->code = $maxcode + 1;
				}else{
					$db_data->code = $maxcode + 1;
				}
				//保存する
				$this->Customer->save($db_data);
				//保存できたかどうか確かめてメール送信
				$customer = $this->Customer->get_by_username_and_password($db_data);
				if(!empty($customer)){
					$this->send_mail_login_info($db_data,$password);
				}
				$this->session->unset_userdata('customer_info');
				return redirect(base_url('front_customer/complete_register'));
			}else{
			//非会員の場合session->customer no-memberを登録
				$db_data->username = 'no-member';
				$db_data->id = null;
				$this->session->set_userdata('no-member',$db_data);
				$this->session->unset_userdata('customer_info');
				return redirect('front_customer/delivery_info_no_member');
			}
		}
		$this->data['no_member'] = $no_member;
		$this->data['form_data'] = $form_data;
		$this->load->view('front_customer/confirm_customer',$this->data);
	}
	
	public function complete_register()
	{
		$this->data['h2title'] = 'ユーザーID/パスワードを発行しました';
		$this->data['title'] = 'ユーザーID/パスワードを発行しました。';
		$this->load->view('front_customer/complete_send',$this->data);
	}
	
	public function delivery_info()
	{
		if(!$this->session->userdata('carts')){
			return redirect('front_cart/show_cart');
		}
		$this->data['h2title'] = 'お支払/ 配送情報登録';
		$this->data['title'] = '配送情報';
		$this->data['form_data'] = $this->Order_info;
		$customer = $this->_checklogin($this->data['customer']);
		$userdata = $this->Customer->get_by_username($customer);
		//エリア内の会員であるかどうか
		$is_area = $userdata->shop_code != 0 && $userdata->cource_code !=0;
		$this->data['is_area'] = $is_area;
		//エリア内は健康屋の配達可能日のリスト作成
		if($is_area){
			$deliver_day = $this->Cource->get_delivery_day($userdata);
			$select_days =$this->Area->delivery_select_date($deliver_day,$this->deliver_possible_day,$this,$this->Master_days->jdays);
			$this->data['select_days'] = $select_days;
			$this->data['cource_info'] = $deliver_day;
		}
		$takuhai_select_days  = $this->Area->delivery_select_date_for_takuhai($this->deliver_possible_day,$this);
		$this->data['select_days_takuhai'] = $takuhai_select_days;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['days'] = $this->Master_days->jdays;
		//戻り先のパラメータ
		$this->data['param'] = '';
		
		//userdataにお支払方法と、配達日を追加してorder_infoセッションに格納
		/*********************************************************
		**エリアの場合**
		param: select_date 　 宅配エリアコースで決められた宅配日
		**エリア外の場合**
		param: delivery    　 宅急便の場合日付を指定するか 0(指定しない) 1(指定する)
		param: select_date 　 宅急便の場合の宅配日    0(未選択) 2015-01-01
		param: delivery_hour 宅急便の場合の指定時間  0(指定しない) 1(午前中)
		param: takuhai      　エリア内で宅急便を希望する　'takuhai'
		************************************************************/
		if($this->input->post('submit')){
			$this->form_validation->set_rules('payment','お支払方法','required');
			$this->form_validation->set_message('required','%sを選択してください');
			//takuhaiはcheckboxなので初期値を設定
			if(!$this->form_validation->run() === FALSE){
				$order_info = new StdClass();
				$order_info->username = $customer->username;
				$order_info->bank_name = $userdata->bank_name;
				$order_info->type_account = $userdata->type_account;
				$order_info->payment = $this->input->post('payment');
				$order_info->delivery_date = 0;
				//エリア内の場合
				if($is_area){
					//エリア内で宅急便が指定されていない 健康屋での配達の場合
					if(!$this->input->post('takuhai')){
						$order_info->takuhai = FALSE;
						//エリア内の場合　健康屋の直近の配達日 日付指定されていなければfirst_dateが配達日
						$order_info->delivery_date = $select_days->first_date;
						//$order_info->shipping_method =　1;
						//エリア内の場合　健康屋の日付けが指定されいればdelivery_dateに格納
						if($this->input->post('select_date') != 0){
							$order_info->delivery_date = $this->input->post('select_date');
						}
						$order_info->delivery_hour = 0;
					//エリア内で宅配便が指定されている場合
					}elseif($this->input->post('takuhai') == 'takuhai'){
						$order_info->takuhai = TRUE;
						//日付け指定(delivery = 1)の場合
						if($this->input->post('delivery') == 1){
							$order_info->delivery_date = $this->input->post('takuhai_select_date');
						}
						//エリア内で宅配希望の場合 時間指定
						$order_info->delivery_hour = $this->input->post('delivery_hour');
					}
				//エリア外の場合
				}else{
					$order_info->takuhai  = TRUE;
					//エリア外の場合日付け指定(delivery = 1) で　日付が選択されている(select_date != 1)
					if($this->input->post('delivery') == 1){
						$order_info->delivery_date = $this->input->post('takuhai_select_date');
					}
					//時間指定
					$order_info->delivery_hour = $this->input->post('delivery_hour');
				}
				$this->session->set_userdata('order_info',$order_info);
				return redirect(base_url('front_cart/confirm_order'));
			}else{
				$this->data['form_data'] = (object)$this->input->post();
			}
		}
		//$this->session->set_userdata('order_info',$order_info);
		$this->data['success_message']  = $this->session->flashdata('success');
		$this->data['account'] = $this->Master_type_account->account;
		//$this->data['order_info'] = $order_info;
		$this->load->view('front_customer/delivery_info',$this->data);
	}
	
	public function delivery_info_no_member()
	{
		if(!$this->session->userdata('carts')){
			return redirect('front_cart/show_cart');
		}
		$this->data['h2title'] = 'お支払/ 配送情報登録';
		$this->data['title'] = '配送情報';
		$this->data['form_data'] = $this->Order_info;
		$this->data['is_area'] = FALSE;
		if(!$this->session->userdata('no-member')){
			return redirect('front_customer/login_action');
		}
		$userdata = $this->session->userdata('no-member');
		$select_days = $this->Area->delivery_select_date_for_takuhai($this->deliver_possible_day,$this);
		$this->data['select_days_takuhai'] = $select_days;
		$this->data['days'] = $this->Master_days->jdays;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['param'] = 'no-member';//戻り先のパラメータ
		
		if($this->input->post('submit')){
			$this->form_validation->set_rules('payment','お支払方法','required');
			$this->form_validation->set_message('required','%sを選択してください');
			if(!$this->form_validation->run() === FALSE){
				$order_info = new StdClass();
				$order_info->takuhai = TRUE;
				$order_info->username = 'no-member';
				$order_info->bank_name = '';
				$order_info->type_account = '';
				$order_info->payment = $this->input->post('payment');
				$order_info->delivery_date = 0;
				if($this->input->post('delivery') == 1){
					$order_info->delivery_date = $this->input->post('takuhai_select_date');
				}
				$order_info->delivery_hour = $this->input->post('delivery_hour');
				$order_info->shipping_method = '1';
				$this->session->set_userdata('order_info',$order_info);
				return redirect(base_url('front_cart/confirm_order'));
			}else{
				$this->data['form_data'] = (object)$this->input->post();
			}
		}
		//$this->session->set_userdata('order_info',$order_info);
		//$this->data['order_info'] = $order_info;
		$this->load->view('front_customer/delivery_info',$this->data);
	}
	
	/*
	public function confirm_order()
	{
		$this->data['h2title'] = 'ご注文の最終確認画面';
		$this->data['title'] = '注文確認';
		$userdata = $this->session->userdata('customer');
//var_dump($userdata);
		$form_data = $userdata;
		if(!empty($form_data->zipcode1)){
			$form_data->zipcode1='';
			$form_data->zipcode2='';
		}
		$this->data['form_data'] = $form_data;
		$this->load->view('front_customer/confirm_order',$this->data);
	}
	*/
	
	public function mypage()
	{
		$customer = $this->_checklogin($this->data['customer'],'mypage');
		if($this->Customer->check_email($customer->email)){
			$no_mail_message = '<a href="' . base_url('front_customer/add_mail') . '">メールアドレスが登録されていません。メールアドレスを登録して下さい。</a>';
		}
		$this->data['h2title'] = "{$customer->name}様　マイページ";
		$this->data['title'] = 'マイページ';
		$information = $this->Customer_info->show_list_front();
		$this->data['information'] = $information;
		if(!empty($no_mail_message)){
			$this->data['no_mail_message'] = $no_mail_message;
		}
		$this->data['point'] = $this->Customer->get_point($customer);
		$this->load->view('front_customer/mypage_top',$this->data);
	}
	
	public function mypage_account()
	{
		$this->data['title'] = '会員情報の編集';
		$customer = $this->_checklogin($this->data['customer']);
//var_dump($this->session->userdata('customer'));
		$this->data['h2title'] = 'マイページ：会員情報の編集';
		$sess_customer = $this->session->userdata('customer');
		$customer = $this->Customer->get_by_id($sess_customer->id);
		$form_data = $customer;
		$form_data->zipcode1 = substr($form_data->zipcode,0,3);
		$form_data->zipcode2 = substr($form_data->zipcode,3,6);
		$this->data['form_data'] = $form_data;
		$type = $this->uri->segment(3);
		$this->data['type'] = $type;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/mypage_account',$this->data);
	}
	
	
	public function mypage_change()
	{
		$this->data['title'] = '会員情報変更';
		$customer = $this->_checklogin($this->session->userdata('customer'));
		$this->data['h2title'] = 'マイページ：会員情報の変更';
		$type = $this->uri->segment(3);
		$sess_customer = $this->session->userdata('customer');
		$customer = $this->Customer->get_by_id($sess_customer->id);
		$customer = $customer;
		$form_data = $customer;
		$this->data['type'] = $type;
		if($type == 'name'){
			if($this->input->post('submit')){
				$form_data = array(
					'name'=>$this->input->post('name'),
					'furigana'=>$this->input->post('furigana'),
				);
				$this->form_validation->set_rules('furigana','フリガナ','required|max_length[100]');
				$this->form_validation->set_rules('name','お名前','required|max_length[100]');
				$this->validation_message();
				if(!$this->form_validation->run() == FALSE){
					$old_info = "{$customer->name}({$customer->furigana})";
					$new_info = "{$form_data['name']}({$form_data['furigana']})";
					$this->Customer->update($sess_customer->id,$form_data);
					$this->send_mail_change_info($customer,$new_info,$old_info,'お名前の変更');
					$this->session->set_flashdata('success','お名前を変更しました');
					$this->Customer->re_session_userdata($sess_customer->id);
					return redirect("front_customer/mypage_account/{$type}");
				}else{
					$form_data = (object)$form_data;
				}
			}
		}
		if($type == 'mail'){
			$form_data->email_confirm = $form_data->email;
			if($this->input->post('submit')){
				$form_data = array(
					'email_confirm'=>$this->input->post('email_confirm'),
					'email'=>$this->input->post('email'),
				);
				$form_data = (object)$form_data;
				$this->form_validation->set_rules('email','メールアドレス','required|max_length[50]');
				$this->validation_message();
				if(!$this->form_validation->run() == FALSE){
					if($form_data->email_confirm == $form_data->email){
						$db_data = array('email'=>$form_data->email);
						$this->Customer->update($sess_customer->id,$db_data);
						$old_info = $customer->email;
						$new_info = $form_data->email;
						$this->send_mail_change_info($customer,$new_info,$old_info,'メールアドレスの変更');
						$this->session->set_flashdata('success','メールアドレスを変更しました');
						$this->Customer->re_session_userdata($sess_customer->id);
						return redirect("front_customer/mypage_account/{$type}");
					}else{
						$this->data['error_message'] = '新しいメールアドレスと新しいメールアドレス（確認用）が異なります';
					}
				}else{
					$this->data['form_data'] = $form_data;
				}
			}
		}
		if($type == 'address'){
			try{
				if(!$address = $this->session->userdata('address')){
					throw new Exception('no-prameter');
				}
				$form_data = new StdClass();
				//エリアマスタに登録されている場合session　addressに住所がある
				if($address->is_master_area){
					//$form_data->zipcode = substr($address->zipcode,0,3) . '-' . substr($address->zipcode,3,6);
					$form_data->zipcode = $address->zipcode;
					$form_data->prefecture = $address->prefecture;
					$form_data->address1 = $address->city . $address->address;
					$form_data->street = '';
					$form_data->address2 = '';
				//エリアマスタにない場合zipcodeのみ情報が入っているno-addressフラグを立てる
				}else{
					$form_data->zipcode1 = $address->zipcode1;
					$form_data->zipcode2 = $address->zipcode2;
					$form_data->address1 = '';
					$this->data['no_address'] = True;
				}
				if($this->input->post('submit')){
					$form_data = array(
						'zipcode'=>$this->input->post('zipcode'),
						'zipcode1'=>$this->input->post('zipcode1'),
						'zipcode2'=>$this->input->post('zipcode2'),
						'prefecture'=>$this->input->post('prefecture'),
						'address1'=>$this->input->post('address1'),
						'street'=>$this->input->post('street'),
						'address2'=>$this->input->post('address2'),
					);
					$form_data = (object)$form_data;
					if(isset($this->data['no_address'])){
						$form_data->zipcode = $form_data->zipcode1 . $form_data->zipcode2;
					}
					if(!isset($this->data['no_address'])){
						$this->form_validation->set_rules('zipcode','郵便番号','required|max_length[8]');
						$this->form_validation->set_rules('prefecture','県名','required|max_length[10]');
						$this->form_validation->set_rules('street','番地','required|max_length[255]');
					}else{
						$this->form_validation->set_rules('zipcode1','郵便番号','required|maxlength[3]');
						$this->form_validation->set_rules('zipcode2','郵便番号','required|maxlength[4]');
						$this->form_validation->set_rules('address1','住所','required|maxlength[255]');
					}
					$this->validation_message();
					if(!$this->form_validation->run() === FALSE){
						if(!isset($this->data['no-address'])){
							$db_data = array(
								'zipcode'=>str_replace('-','',$form_data->zipcode),
								'address1'=>$form_data->prefecture.$form_data->address1 . $form_data->street . $form_data->address2,
							);
						}else{
							$db_data = array(
								'zipcode'=>$form_data->zipcode,
								'address1'=>$form_data->address1,
							);
						}
						$this->Customer->update($sess_customer->id , $db_data);
						$new_customer = $this->Customer->get_by_id($sess_customer->id);
						$old_zipcode = substr($customer->zipcode,0,3) . '-' . substr($customer->zipcode,3,6);
						$old_info = "〒{$old_zipcode}\n";
						$old_info .= "{$customer->address1}{$customer->address2}\n";
						//$old_info .= "電話番号: {$customer->tel}\n";
						$new_zipcode = substr($new_customer->zipcode,0,3) . '-' . substr($new_customer->zipcode,3,6);
						$new_info = "\n〒{$new_zipcode}\n";
						$new_info .= "{$new_customer->address1}\n";
						//$new_info .= "電話番号: {$form_data->tel}\n";
						$this->send_mail_change_info($customer,$new_info,$old_info,'住所の変更');
						$this->session->unset_userdata('address');
						$this->Customer->re_session_userdata($sess_customer->id);
						$this->session->set_flashdata('success','住所を変更しました');
						return redirect("front_customer/mypage_account/{$type}");
					}
				}
			}catch(Exception $e){
				show_404();
			}
		}
		
		if($type == 'tel'){
			if($this->input->post('submit')){
				$form_data = array(
					'tel'=>$this->input->post('tel'),
					'tel2'=>$this->input->post('tel2'),
				);
				$form_data = (object)$form_data;
				$this->form_validation->set_rules('tel','電話番号','required|max_length[14]');
				$this->validation_message();
				if(!$this->form_validation->run() === FALSE){
					$db_data = array(
						'tel'=>$form_data->tel,
						'tel2'=>$form_data->tel2
					);
					$this->Customer->update($sess_customer->id,$db_data);
					$old_info = "電話番号: {$customer->tel}\n";
					$old_info .= "携帯電話番号: {$customer->tel2}\n";
					$new_info = "電話番号: {$form_data->tel}\n";
					$new_info .= "携帯電話番号: {$form_data->tel2}\n";
					$this->send_mail_change_info($customer, $new_info, $old_info, 'お電話番号の変更');
					$this->session->set_flashdata('success','お電話番号を変更しました');
					$this->Customer->re_session_userdata($sess_customer->id);
					return redirect("front_customer/mypage_account/{$type}");
				}else{
					$form_data = (object)$form_data;
				}
			}
		}
		
		if($type == 'maga'){
			if($this->input->post('submit')){
				$maga = $this->input->post('maga');
				if(!empty($maga) || $maga == '1'){
					$maga = 1;
				}else{
					$maga = 0;
				}
				$form_data = array('mail_magazine'=>$maga);
				$this->Customer->update($sess_customer->id, $form_data);
				$this->session->set_flashdata('success','変更しました');
				$this->Customer->re_session_userdata($sess_customer->id);
				return redirect("front_customer/mypage_account/{$type}");
			}
		}
		$this->data['form_data']  = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/mypage_change',$this->data);
	}
	
	public function change_auth()
	{
		$type = $this->uri->segment(3);
		$sess_customer = $this->session->userdata('customer');
		$customer = $this->Customer->get_by_username($sess_customer);
		$this->data['type'] = $type;
		if($type == 'username'){
			$this->data['h2title'] = 'ユーザーIDの変更';
			$form_data = new StdClass();
			$form_data->current_username = $customer->username;
			$form_data->username = '';
			$this->data['email'] = $customer->email;
			if($this->input->post('submit'))
			{
				$this->form_validation->set_rules('current_pw','現在のパスワード','required|max_length[16]');
				$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]||callback_username_check');
				$this->validation_message();
				if(!$this->form_validation->run() === FALSE){
					$form_data = array(
						'current_pw'=>$this->input->post('current_pw'),
						'username'=>$this->input->post('username'),
						'current_username'=>$form_data->current_username,
					);
					$form_data=(object)$form_data;
					if($this->Customer->check_password($sess_customer,$customer,$form_data))
					{
						$db_data = array('username'=>$form_data->username);
						$this->Customer->update($sess_customer->id,$db_data);
						$this->session->set_flashdata('success','ユーザーIDを変更しました');
						$this->Customer->re_session_userdata($sess_customer->id);
						return redirect('front_customer/change_auth/username');
					}else{
						$this->data['error_message'] = 'パスワードが違います';
					}
				}
			}
		}
		if($type == 'password'){
			$this->data['h2title'] = 'パスワードの変更';
			$form_data = new StdClass();
			if($this->input->post('submit')){
				$this->form_validation->set_rules('current_pw','現在のパスワード','required|min_length[8]|max_length[100]');
				$this->form_validation->set_rules('pw_confirm','新しいパスワード','required|min_length[8]|max_length[16]');
				$this->form_validation->set_rules('new_pw','新しいパスワード(確認)','required|min_length[8]|max_length[16]');
				$this->validation_message();
				if(!$this->form_validation->run() === FALSE){
					$form_data = array(
						'current_pw'=>$this->input->post('current_pw'),
						'pw_confirm'=>$this->input->post('pw_confirm'),
						'password'=>$this->input->post('new_pw'),
					);
					$form_data = (object)$form_data;
					if($form_data->pw_confirm == $form_data->password){
						if($this->Customer->check_password($sess_customer,$customer,$form_data))
						{
							$db_data = array('password'=>$this->encrypt->encode($form_data->password));
							$this->Customer->update($sess_customer->id,$db_data);
							$this->session->set_flashdata('success','パスワードを変更しました');
							$this->Customer->re_session_userdata($sess_customer->id);
							return redirect('front_customer/change_auth/password');
						}else{
							$this->data['error_message'] = 'パスワードが違います';
						}
					}else{
						$this->data['error_message'] = '新しいパスワードと新しいパスワード（確認）が異なります';
					}
				}
			}
		}
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/mypage_change_auth',$this->data);
	}
	
	public function mypage_order()
	{
		$this->data['title'] = 'ご注文履歴';
		$customer = $this->_checklogin($this->data['customer']);
		$this->data['h2title'] = 'ご注文履歴';
		$sess_customer = $this->session->userdata('customer');
		$orders  = $this->Order->get_by_customer_id($sess_customer->id);
		$form_data = new StdClass();
		$form_data->start_date ='';
		$form_data->end_date = '';
		if($this->input->post('submit')){
			$start = $this->input->post('start_date');
			$end = $this->input->post('end_date');
			$form_data->start_date = $start;
			$form_data->end_date = $end;
			$orders = $this->Order->get_by_customer_id($sess_customer->id , $form_data);
		}
		//$order_details = $this->Order->get_detail_by_order_id(13);
		$count = count($orders);
		for($i=0; $i < $count; $i++){
			$orders[$i]->details = $this->Order->get_detail_by_order_id($orders[$i]->id);
		}
		$this->data['orders'] = $orders;
		$this->data['form_data'] = $form_data;
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_customer/mypage_order',$this->data);
	}
	
	public function change_quantity()
	{
		try{
			$this->data['title'] = 'ご注文数量変更';
			$customer = $this->_checklogin($this->data['customer']);
			$this->data['h2title'] = 'ご注文数量変更';
			$this->data['message'] = '数量を変更して変更ボタンを押してください';
			$order_detail_id = $this->uri->segment(3);
			$result = $this->Order->get_detail_by_id($order_detail_id);
			if($this->input->post('submit')){
				$quantity = $this->input->post('quantity');
				$db_data = array(
					'quantity'=>$quantity
				);
				$this->Order->update($order_detail_id , $db_data);
				//メール処理
				$result->new_quantity = $quantity;
				$send_result = $this->send_mail($customer,$result,'数量変更');
				$this->session->set_flashdata('success','数量を変更しました');
				return redirect('front_customer/mypage_order');
			}
			$this->data['order'] = $result;
			$this->data['quantity'] = $this->Master_quantity->quantity;
			$this->load->view('front_customer/change_quantity',$this->data);
		}catch(Exception $e){
			show_404();
			log_message($e->getMessage());
		}
	}
	
	public function cancel()
	{
		try{
			$customer = $this->_checklogin($this->data['customer']);
			$order_detail_id = $this->uri->segment(3);
			$result = $this->Order->get_detail_by_id($order_detail_id);
			if($result->status_flag==0){
				$this->data['h2title'] = 'ご注文のキャンセル';
				$this->data['title'] = 'ご注文のキャンセル';
				$this->data['label_message'] = 'キャンセルする';
				$this->data['button_message'] = 'キャンセル';
				$this->data['message'] = 'ご注文をキャンセルするにチェックを入れてキャンセルボタンを押してください';
				if($this->input->post('submit')){
					if($this->input->post('cancel')){
						$db_data = array(
							'status_flag'=>2
						);
						$this->Order->update($order_detail_id,$db_data);
						$result->cancel = $this->data['h2title'];
						$send_result = $this->send_mail($customer,$result,$this->data['h2title']);
						$this->session->set_flashdata('success','注文をキャンセルしました');
						return redirect('front_customer/mypage_order');
					}else{
						$this->data['error_message'] = 'キャンセルする場合はキャンセルするにチェックを入れてください。<br>
							キャンセルしない場合は、戻るボタンを押してください';
					}
				}
			}
			if($result->status_flag==2){
				$this->data['h2title'] = 'ご注文のキャンセルの取消';
				$this->data['title'] = 'ご注文のキャンセルの取消';
				$this->data['label_message'] = 'キャンセルの取消';
				$this->data['button_message'] = 'キャンセル取消';
				$this->data['message'] = 'キャンセルを取り消す場合はキャンセル取消にチェックをいれてください';
				if($this->input->post('submit')){
					if($this->input->post('cancel')){
						$db_data = array(
							'status_flag'=>0
						);
						$this->Order->update($order_detail_id,$db_data);
						$result->cancel = $this->data['h2title'];
						$send_result = $this->send_mail($customer,$result,$this->data['h2title']);
						$this->session->set_flashdata('success','キャンセルを取り消しました');
						return redirect('front_customer/mypage_order');
					}else{
						$this->data['error_message'] = 'キャンセルを取り消す場合はキャンセルの取消にチェックを入れてください。<br>
							取消しない場合は、戻るボタンを押してください';
					}
				}
			}
			$this->data['order'] = $result;
			$this->load->view('front_customer/cancel',$this->data);
		}catch(Exception $e){
			show_404();
			log_message($e->getMessage());
		}
	}
	
	
	public function logout()
	{
		$this->session->unset_userdata('customer');
		$this->session->unset_userdata('address');
		$this->session->set_flashdata('success','ログアウトしました');
		return redirect('front_customer/login_action');
	}

	
	private function validation_message(){
		$this->form_validation->set_message('required','%sを入力してください');
		$this->form_validation->set_message('numeric','%sは数字で入力してください');
		$this->form_validation->set_message('max_length',mb_convert_encoding('%sは%s文字以内で入力してください','utf-8'));
		$this->form_validation->set_message('min_length',mb_convert_encoding('%sは%s文字以上で入力してください','utf-8'));
		$this->form_validation->set_message('valid_email',mb_convert_encoding('%sは正しい形式で入力してください','utf-8'));
	}
	
	
	private function validation_rules($no_member = False)
	{
		$this->form_validation->set_error_delimiters('<p class="error">','</p>');
		$this->form_validation->set_rules('name','お名前','required|max_length[50]');
		$this->form_validation->set_rules('furigana','フリガナ','required|max_length[100]');
		$this->form_validation->set_rules('email_confirm','メールアドレス','required|max_length[100]|valid_email|callback_email_check');
		$this->form_validation->set_rules('email','メールアドレス(確認用)','required|max_length[100]|valid_email');
		//$this->form_validation->set_rules('zipcode','郵便番号','required');
		$this->form_validation->set_rules('zipcode1','郵便番号','required');
		$this->form_validation->set_rules('zipcode2','郵便番号','required');
		$this->form_validation->set_rules('prefecture','県名','required|max_length[10]');
		$this->form_validation->set_rules('address1','住所','required|max_length[200]');
		/*$this->form_validation->set_rules('street','住所、番地','required|max_length[100]');*/
		$this->form_validation->set_rules('tel','電話番号','required|max_length[15]');
		/*
		if($this->input->post('member')){
			$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]');
			$this->form_validation->set_rules('password','パスワード','required|max_length=[50]');
		}*/
		$this->validation_message();
	}
	
	private function validation_login()
	{
		$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]');
		$this->form_validation->set_rules('password','パスワード','required|max_length[50]');
		$this->validation_message();
	}
	
	
	//メールアドレスと確認用メールアドレスが同一かどうか確認する
	private function check_email_confirm($obj)
	{
		if($obj->email != $obj->email_confirm){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	private function validation_zip($zip)
	{
		if(empty($zip) || !is_numeric($zip)){
			return false;
		}else{
			return true;
		}
	}
	
	//ユーザー名の重複をチェック
	public function username_check($str)
	{
		if($this->Customer->check_username($str)){
			return TRUE;
		}else{
			$this->form_validation->set_message('username_check','%sはすでに登録されています');
			return FALSE;
		}
	}
	//メールアドレスの重複をチェック
	public function email_check($str)
	{
		if($this->Customer->check_email($str)){
			return TRUE;
		}else{
			$this->form_validation->set_message('email_check','%sはすでに登録されいるメールアドレスです');
			return FALSE;
		}
	}

	/*
	private function send_mail($customer = null , $order = null)
	{
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(1);
		$mail_template = $mail_template[0];
		$username = $customer->name;
		$title = $customer->address1;
		$contents = $order->order_number;
		$mail_body = str_replace(array('{$title}','{$username}','{$contents}'),array($title,$username,$contents),$mail_template->mail_body);
		$result = sendMail($customer->email,$mail_template->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		return $result;
	}
	*/
	
	public function member_policy()
	{
		$this->load->view('front_customer/member_policy');
	}
	
	public function no_member_policy()
	{
		$this->load->view('front_customer/no_member_policy');
	}
	
	public function logout_action()
	{
		$this->session->unset_userdata('customer');
		$this->session->set_flashdata('success','ログアウトしました');
		return redirect(base_url('front_customer/login_action/mypage'));
	}
	
	public function set_password()
	{
		//captacha画像の削除
		$path = 'images/captcha';
		$res_dir = opendir( $path );
		while( $file_name = readdir( $res_dir ) ){
			if(is_file($path.'/'.$file_name)) unlink($path.'/'.$file_name);
		}
		$this->data['h2title'] = 'パスワード再発行';
		$this->data['title'] = 'パスワード再発行';
		$vals = array(
			'word'=>mb_strimwidth(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,6),
			'img_path'=>'./images/captcha/',
			'img_url'=>base_url('images/captcha') . '/',
			'img_width'=>'150',
			'img_height'=>'30',
		);
		$cap = create_captcha($vals);
		$this->data['cap'] = $cap;
		$db_data=array(
			'captcha_time'=>$cap['time'],
			'ip_address'=>$this->input->ip_address(),
			'word'=>$cap['word'],
			'create_datetime'=>date('Y-m-d H:i:s'),
		);
		$this->Captcha->save($db_data);
		$captcha_id = $this->Captcha->db->insert_id();
		$this->data['captcha_id'] = $captcha_id;
		if($this->input->post('submit')){
			$username = $this->input->post('username');
			$tel = $this->input->post('tel');
			$name = $this->input->post('name');
			$name = trim(mb_convert_kana($name, "s", 'UTF-8'));
			$word = $this->input->post('word');
			$captcha_id = $this->input->post('captcha_id');
			$captcha = $this->Captcha->get_by_id($captcha_id);
			try{
				if(empty($username) || empty($tel) || empty($word) || empty($name)){
					throw new Exception('未入力項目があります');
				}
				if($word != $captcha->word){
					throw new Exception('画像の文字と入力文字が異なります');
				}
				
				//check_username　usernameが登録されていたらfalseを返す
				$customer = $this->Customer->check_username_tel_name($username,$tel,$name);
				//更新したらcaptcha_id,captacha_time,usernameのハッシュを返す
				//$this->Captcha->update($captcha_id,array('username'=>$username));
				
				//wordをハッシュ化
				$hashed_word = base64_encode($captcha->word);
				$this->send_mail_password($customer,$hashed_word);
				$flag = 'password';
				return redirect("front_customer/notice/{$flag}");
				
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
				$this->data['username']= $username;
			}
		}
		$this->load->view('front_customer/set_password',$this->data);
	}
	
	public function set_username()
	{
		$path = 'images/captcha';
		$res_dir = opendir($path);
		while($file_name = readdir( $res_dir )){
			if(is_file($path . '/' . $file_name)) unlink($path . '/' . $file_name);
		}
		$this->data['h2title'] = 'ユーザーID再発行';
		$this->data['title'] = 'ユーザーID再発行';
		$vals = array(
			'word'=>mb_strimwidth(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,6),
			'img_path'=>'./images/captcha' . '/',
			'img_url'=>base_url('images/captcha') . '/',
			'img_width'=>'150',
			'img_height'=>'30',
		);
		$cap = create_captcha($vals);
		$this->data['cap'] = $cap;
		$db_data=array(
			'captcha_time' => $cap['time'],
			'ip_address'=>$this->input->ip_address(),
			'word'=>$cap['word'],
			'create_datetime'=>date('Y-m-d H:i:s'),
		);
		$this->Captcha->save($db_data);
		$captcha_id = $this->Captcha->db->insert_id();
		$this->data['captcha_id'] = $captcha_id;
		if($this->input->post('submit')){
			$email = $this->input->post('email');
			$word = $this->input->post('word');
			$captcha_id = $this->input->post('captcha_id');
			$captcha = $this->Captcha->get_by_id($captcha_id);
			try{
				if(empty($email) || empty($word)){
					throw new Exception('未入力の項目があります');
				}
				if($word != $captcha->word){
					throw new Exception('画像の文字と入力文字が異なります。');
				}
				$customer = $this->Customer->get_by_email($email);
				//$hashed_word = base64_encode($captcha->word);
				$pre_username = uniqid(rand());
				if(!$this->Customer->check_username($pre_username)){
					throw new Exception('恐れ入ります。再度入力してください');
				}
				$db_data = array('username'=>$pre_username);
				$this->Customer->update_by_email($customer,$db_data);
				$this->send_mail_username($customer,$pre_username);
				$flag = 'username';
				return redirect("front_customer/notice/{$flag}");
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
		}
		$this->load->view('front_customer/set_username',$this->data);
	}
	
	public function notice()
	{
		$flag = $this->uri->segment(3);
		if(empty($flag)){ return show_404(); }
		if($flag == 'password'){
			$h2title = 'パスワード再設定メールを送信しました';
			$flag_str = 'パスワード';
		}
		if($flag == 'username'){
			$h2title = 'ユーザー名再設定メールを送信しました';
			$flag_str = 'ユーザー名';
		}
		$this->data['title'] = 'メールを送信しました';
		$this->data['h2title'] = $h2title;
		$this->data['flag'] = $flag;
		$this->load->view('front_customer/notice',$this->data);
	}
	
	public function reset_password()
	{
		try{
			$this->data['title'] = 'パスワードの再設定';
			$this->data['h2title'] = 'パスワードの再設定';
			$key = $this->input->get('key');
			$decoded = urldecode($key);
			$word = base64_decode($decoded);
			$ip_address = $this->input->ip_address();
			$result = $this->Captcha->check_word($word,$ip_address);
			$this->data['username'] = '';
			if(!$result){
				return show_404(); 
			}else{
				if($this->input->post('submit')){
					$this->form_validation->set_error_delimiters('<p class="error_validation">','</p>');
					$this->form_validation->set_rules('username','ユーザー名','required|max_length[50]');
					$this->form_validation->set_rules('password','パスワード','required|min_length[8]|max_length[16]');
					$this->form_validation->set_rules('password_confirm','パスワード(確認用)','required|min_length[8]|max_length[16]');
					$this->validation_message();
					$username = $this->input->post('username');
					$password = $this->input->post('password');
					$password_confirm = $this->input->post('password_confirm');
					$this->data['username'] = $username;
					if(!$this->form_validation->run() === FALSE){
						if($password_confirm != $password){
							throw new Exception('パスワードとパスワード(確認用)が異なります');
						}
						if($this->Customer->check_username($username)){
							throw new Exception('ユーザー名が登録されていません');
						}
						$db_data = array(
							'password'=>$this->encrypt->encode($password),
						);
						$this->Customer->update_by_username($username,$db_data);
						$this->session->set_flashdata('success','パスワードを登録しました');
						return redirect('front_customer/login_action');
					}
				}
			}
		}catch(Exception $e){
			$this->data['error_message'] = $e->getMessage();
		}
		$this->data['key'] = $key;
		$this->load->view('front_customer/reset_password',$this->data);
	}
	
	public function reset_username()
	{
		try{
			$this->data['title'] = 'ユーザー名の再設定';
			$this->data['h2title'] = 'ユーザー名の再設定';
			$key = $this->input->get('key');
			$decoded = urldecode($key);
			$word = base64_decode($decoded);
			$ip_address = $this->input->ip_address();
			$result = $this->Captcha->check_word($word,$ip_address);
			
		}catch(Exception $e){
			$this->data['error_message'] = $e->getMessage();
		}
	}
		
	public function add_mail()
	{
		$customer = $this->_checklogin($this->data['customer']);
		$this->data['h2title'] = 'メールアドレスの新規登録';
		$form_data = array(
			'email'=>'',
			'email_confirm'=>''
		);
		$this->data['form_data'] = (object)$form_data;
		if($this->input->post('submit')){
			$form_data = array(
				'email_confirm'=>$this->input->post('email_confirm'),
				'email'=>$this->input->post('email'),
			);
			$form_data = (object)$form_data;
			$this->form_validation->set_rules('email','メールアドレス','required|max_length[50]');
			$this->validation_message();
			if(!$this->form_validation->run() == FALSE){
				if($form_data->email_confirm == $form_data->email){
					$db_data = array('email'=>$form_data->email);
					$this->Customer->update($customer->id,$db_data);
					$this->session->set_flashdata('success','変更しました');
					return redirect("front_customer/mypage_account/{$type}");
				}else{
					$this->data['error_message'] = '新しいメールアドレスと新しいメールアドレス（確認用）が異なります';
				}
			}else{
				$this->data['form_data'] = $form_data;
			}
		}
		$this->load->view('front_customer/add_mail',$this->data);
	}
	
	public function renew_user(){
		$this->session->unset_userdata('renew_code');
		$this->data['h2title'] = 'ログイン';
		$this->data['title'] = 'ログイン';
		$this->form_validation->set_error_delimiters('<p class="error_validation">','</p>');
		//$this->form_validation->set_rules('code','ご登録番号','required|max_length[3]');
		$this->form_validation->set_rules('tel','電話番号','required|max_length[15]');
		$this->form_validation->set_message('required','%sが未入力です');
		$form_data = array(
			'code'=>'',
			'code1'=>'',
			'code2'=>'',
			'code3'=>'',
			'code4'=>'',
			'tel'=>'',
		);
		$form_data = (object)$form_data;
		if($this->input->post('submit')){
			$form_data = $this->input->post();
			$code = $this->input->post('code');
			$code3 = $this->input->post('code3');
			$tel = $this->input->post('tel');
			if(!$this->form_validation->run() == FALSE){
				if(!empty($code) && !empty($code3)){
					if($result = $this->Customer->check_customer_no_web($code,$code3,$tel)){
						$this->session->set_userdata('user_code',$result);
						return redirect('front_customer/renew_input');
					}else{
						$this->data['error_message'] = 'ご登録番号もしくはお電話番号が間違っています';
					}
				}else{
					$this->data['error_message'] = 'ご登録番号が未入力です';
				}
			}
		}
		$this->data['form_data'] = (object)$form_data;
		$this->load->view('front_customer/renew_user',$this->data);
	}
	
	public function renew_input(){
		$this->data['title'] = 'ログイン';
		try{
			if($user_code = $this->session->userdata('user_code')){
				$this->data['h2title'] = 'メールアドレス・パスワード登録';
				$this->data['tilte'] =' メールアドレス・パスワード登録';
				$this->data['username'] = $user_code;
				$this->form_validation->set_error_delimiters('<p class="error">','</p>');
				$this->form_validation->set_rules('email','メールアドレス','required|max_length[50]');
				$this->form_validation->set_rules('email_confirm','メールアドレス（確認用)','required|max_length[50]');
				$this->form_validation->set_rules('password','パスワード','required|min_length[8]|max_length[16]');
				$this->form_validation->set_rules('password_confirm','パスワード(確認用)','required|min_length[8]|max_length[16]');
				$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]|callback_username_check');
				$this->validation_message();
				$email = $this->input->post('email');
				$email_c = $this->input->post('email_confirm');
				$password = $this->input->post('password');
				$password_c = $this->input->post('password_confirm');
				$username = $this->input->post('username');
				if(!$this->form_validation->run() === FALSE){
					if($email != $email_c){
						throw new Exception('メールアドレスとメールアドレス(確認用)が異なります');
					}
					if($password != $password_c){
						throw new Exception('パスワードとパスワード（確認用）が異なります');
					}
					$data=array(
						'email'=>$email,
						'username'=>$username,
						'password'=>$this->encrypt->encode($password),
					);
					$this->Customer->update_by_code($user_code,$data);
					$this->session->unset_userdata('user_code');
					$this->session->set_flashdata('success','メールアドレスとパスワードを登録しました');
					return redirect('front_customer/login_action/mypage');
				}
			}else{
				return redirect('front_customer/renew_user');
			}
		}catch(Exception $e){
			$this->data['error_message'] = $e->getMessage();
		}
		$this->load->view('front_customer/renew_input',$this->data);
	}
	
	private function _checklogin($customer,$ref = ''){
		if(empty($customer)){
			if($ref == 'mypage'){
				$ref = 'mypage';
			}
			return redirect("front_customer/login_action/{$ref}");
		}else{
			return $customer;
		}
	}
	
	private function send_mail($customer = null , $obj = null,$kind=null)
	{
		//お客さま用メール送信処理
		$customer = $this->Customer->get_by_username($customer);
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(7);
		$url_mypage = base_url('front_customer/mypage');
		$url_contact = base_url('front_contact');
		//$name = $customer->name;
		//$address = '〒' . substr($customer->zipcode,0,3).'-'.substr($customer->zipcode,3,6)."\n".$customer->prefecture.$customer->address1.$customer->address2;
		//$title = $customer->address1;
		//$contents = $order->order_number;
		
		//新数量が設定されている　=　数量変更
		if(!empty($obj->new_quantity)){
			$change_content="{$obj->new_quantity}個に数量変更";
		}
		if(!empty($obj->cancel)){
			$change_content=$obj->cancel;
		}

		$mail_body = str_replace(
			array(
				'{{name}}',
				'{{customer_code}}',
				'{{order_number}}',
				'{{product_name}}',
				'{{quantity}}',
				'{{change_content}}',
				'{{mypage_url}}',
				'{{contact_url}}',
			),array(
				$customer->name,
				$customer->code,
				$obj->order_number,
				mb_convert_kana($obj->product_name,'K'),
				$obj->quantity,
				$change_content,
				$url_mypage,
				$url_contact,
			),
		$mail_template->mail_body);
		$result = sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->Admin_mail->show_list();
			$admin_mail_template = $this->Mail_template->get_by_id(8);
			$admin_mail_body = str_replace(
				array('{{code}}','{{name}}','{{kind}}','{{content}}'),
				array($customer->code,$customer->name,$kind,$mail_body),
			$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->Mail_template->sender);
			}
			if(in_array(false,$admin_result)){
				$admin_result = false;
			}else{
				$admin_result = true;
				return $admin_result;
			}
		}else{
			throw new Exception("send_mail failed file:{{__FILE__}} code:{{__LINE__}}");
		}
	}

	private function send_mail_change_info($customer = null , $new_info = null, $old_info=null,$kind=null)
	{
		//お客さま用メール送信処理
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(5);
		$url_mypage = base_url('front_customer/mypage');
		$url_contact = base_url('front_contact');
		$mail_body = str_replace(
			array(
				'{{name}}',
				'{{kind}}',
				'{{old_info}}',
				'{{new_info}}',
				'{{mypage_url}}',
				'{{contact_url}}',
			),array(
				$customer->name,
				$kind,
				$old_info,
				$new_info,
				$url_mypage,
				$url_contact,
			),
		$mail_template->mail_body);
		$result = sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->Admin_mail->show_list();
			$admin_mail_template = $this->Mail_template->get_by_id(6);
			$admin_mail_body = str_replace(
				array('{{code}}','{{name}}','{{kind}}','{{content}}'),
				array($customer->code,$customer->name,$kind,$mail_body),
			$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->Mail_template->sender);
			}
			if(in_array(false,$admin_result)){
				$admin_result = false;
			}else{
				$admin_result = true;
				return $admin_result;
			}
		}else{
			throw new Exception("send_mail failed file:{{__FILE__}} code:{{__LINE__}}");
		}
	}
	
	private function send_mail_password($customer,$hashed_word)
	{
		$encoded = urlencode($hashed_word);
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(11);
		$url_contact = base_url('front_contact');
		$url = base_url("front_customer/reset_password?key={$encoded}");
		$mail_body = str_replace(
			array('{{url}}','{{url_contact}}'),
			array($url,$url_contact),
			$mail_template->mail_body
		);
		$result = sendMail($customer->email, $mail_template->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
	}
	
	private function send_mail_username($customer,$pre_username)
	{
		//$encoded = urlencode($hashed_word);
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(12);
		$url_contact = base_url('front_contact');
		//$url = base_url("front_customer/reset_username?key={$encoded}");
		$mypage_url = base_url('front_customer/mypage');
		$mail_body = str_replace(
			array('{{mypage_url}}','{{url_contact}}','{{pre_username}}','{{name}}'),
			array($mypage_url,$url_contact,$pre_username,$customer->name),
			$mail_template->mail_body
		);
		$result = sendMail($customer->email, $mail_template->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
	}
	
	private function send_mail_login_info($obj,$password){
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(13);
		$url_contact = base_url('front_contact');
		$mypage_url = base_url('front_customer/mypage');
		$mail_body = str_replace(
			array(
				'{{mypage_url}}',
				'{{url_contact}}',
				'{{name}}',
				'{{username}}',
				'{{password}}',
				'{{zipcode}}',
				'{{address1}}',
				'{{tel}}',
				'{{email}}',
			),
			array(
				$mypage_url,
				$url_contact,
				$obj->name,
				$obj->username,
				$password,
				$obj->zipcode,
				$obj->address1,
				$obj->tel,
				$obj->email,
			),
			$mail_template->mail_body
		);
		$result = sendMail($obj->email,$mail_template->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
	}
	
	private function _is_area($obj,$zipcode,$flag = FALSE)
	{
		if(!empty($obj) && $obj->cource_code != 0){
			$flag = TRUE;
			$obj->is_master_area = TRUE;
			$this->session->set_userdata('address',$obj);
		}elseif(!empty($obj) && $obj->cource_code == 0){
			$obj->is_master_area = TRUE;
			$this->session->set_userdata('address',$obj);
			$flag = FALSE;
		}elseif(empty($obj)){
			$newobj = new StdClass();
			$newobj->is_master_area = FALSE;
			$newobj->zipcode1 = substr($zipcode,0,3);
			$newobj->zipcode2 = substr($zipcode,3,4);
			$this->session->set_userdata('address',$newobj);
			$flag = FALSE;
		}
		return $flag;
	}

}