<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/define_mail.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/csv.php';
include __DIR__.'/../libraries/define_flag.php';

class Mypage extends CI_Controller{
	public $data = array();
	public $deliver_possible_day = '+3 days';
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','encrypt'));
		$this->load->library('my_mail');
		$this->load->library('my_validation');
		$this->load->library('my_class');
		$this->load->helper('form');
		if(!$this->session->userdata('customer')){
			return redirect('front_customer/login_action/mypage');
		}
		$this->load->model('Address');
		$this->load->model('Customer');
		$this->load->model('Area');
		$this->load->model('Master_days');
		$this->load->model('Customer_info');
		$this->load->model('Master_mail_magazine');
		$this->load->model('Master_hour');
		$this->load->model('Master_birthday');
		$this->load->model('Order');
		$this->load->model('Master_cancel');
		$this->load->model('Master_quantity');
		$this->load->model('Mail_template');
		$this->load->model('Master_order_status');
		$this->load->model('Master_takuhai_hours');
		$this->load->model('Credit');
		$this->load->model('Master_payment');
		$this->load->model('Bread');
		$this->load->model('Master_address');
		$this->load->model('Mst_paid_flag');
		$this->data['customer'] = $this->session->userdata('customer');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$this->data['h2title'] = "{$this->data['customer']->name}様　マイページ";
		$this->data['title'] = 'マイページ';
		$information = $this->Customer_info->show_list_front();
		$this->data['information'] = $information;
		if(!empty($no_mail_message)){
			$this->data['no_mail_message'] = $no_mail_message;
		}
		$this->data['point'] = $this->Customer->get_point($this->data['customer']);
		//bread
		$bread = $this->Bread;
		$bread->text = 'マイページ';
		$this->data['breads'] = $this->Bread->create_bread($bread);
		$this->load->view('mypage/mypage_top',$this->data);
	}
	
	public function mypage_account()
	{
		$this->data['title'] = '会員情報の編集';
		$customer = $this->_checklogin($this->data['customer']);
//var_dump($this->session->userdata('customer'));
		$this->data['h2title'] = 'マイページ：会員情報の編集';
		//$sess_customer = $this->session->userdata('customer');
		$customer = $this->Customer->get_by_username($customer);
		$form_data = $customer;
		$form_data->zipcode1 = substr($form_data->zipcode,0,3);
		$form_data->zipcode2 = substr($form_data->zipcode,3,6);
		$this->data['form_data'] = $form_data;
		$type = $this->uri->segment(3);
		//住所変更の場合注意事項があるので注意事項を表示
		/*
		if($type == 'address')
		{
			$agreed = $this->uri->segment(4);
			if(empty($agreed) || 'agreed' != $agreed){
				return redirect('mypage/address_notice');
			}
		}
		*/
		$this->data['type'] = $type;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('mypage/mypage_account',$this->data);
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
					//'new_member_flag'=>2,
					'change_info'=>'name',
				);
				$form_data['name'] = $this->my_class->convert_kana($form_data['name']);
				$form_data['furigana'] = $this->my_class->convert_space($form_data['furigana']);
				$this->form_validation->set_rules('name','お名前','required|max_length[50]');
				$this->form_validation->set_rules('furigana','フリガナ','required|max_length[100]|callback_kana_check');
				$this->validation_message();
				if(!$this->form_validation->run() == FALSE){
					$old_info = "{$customer->name}({$customer->furigana})";
					$new_info = "{$form_data['name']}({$form_data['furigana']})";
					$this->Customer->update($sess_customer->id,$form_data);
					$this->my_mail->send_mail_change_info($customer,$new_info,$old_info,'お名前の変更');
					$this->session->set_flashdata('success','お名前を変更しました');
					$this->Customer->re_session_userdata($sess_customer->id);
					return redirect("mypage/mypage_account/{$type}");
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
						$db_data = array(
							'email'=>$form_data->email,
							//'new_member_flag'=>2,							
							'change_info'=>'email',
							);
						$this->Customer->update($sess_customer->id,$db_data);
						$old_info = $customer->email;
						$new_info = $form_data->email;
						$this->my_mail->send_mail_change_info($customer,$new_info,$old_info,'メールアドレスの変更');
						$this->session->set_flashdata('success','メールアドレスを変更しました');
						$this->Customer->re_session_userdata($sess_customer->id);
						return redirect("mypage/mypage_account/{$type}");
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
					$form_data->address2 = '';
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
						$this->form_validation->set_rules('street','住所','required|max_length[255]');
					}else{
						$this->form_validation->set_rules('zipcode1','郵便番号','required|maxlength[3]');
						$this->form_validation->set_rules('zipcode2','郵便番号','required|maxlength[4]');
						$this->form_validation->set_rules('address1','住所','required|maxlength[255]');
					}
					$this->validation_message();
					if(!$this->form_validation->run() === FALSE){
						//エリア内
						if(!isset($this->data['no_address'])){
							$zipcode = str_replace('-','',$form_data->zipcode);
							$area = $this->Customer->get_area_by_zip($form_data->zipcode);
							$db_data = array(
								'zipcode'=>$zipcode,
								'pref_id'=>$this->Master_address->get_pref_id($zipcode),
								'address1'=>$form_data->prefecture.$form_data->address1.$form_data->street,
								'address2'=>$form_data->address2,
							//エリア内はshop_code,cource_codeをセットする
								'cource_code'=>$area->cource_code,
								'shop_code'=>$area->shop_code,
								//'new_member_flag'=>2,
								'change_info'=>'address',
								);
						 //非エリア内
						}else{
							$db_data = array(
								'zipcode'=>$form_data->zipcode,
								'pref_id'=>$this->Master_address->get_pref_id($form_data->zipcode),
								'address1'=>$form_data->address1,
								'address2'=>$form_data->address2,
								//'new_member_flag'=>2,
								'change_info'=>'address',
						//shop_code と　cource_codeを0セットする
								'shop_code'=>0,
								'cource_code'=>0,
							);
						}
						$this->Customer->update($sess_customer->id , $db_data);
						$new_customer = $this->Customer->get_by_username($customer);
						$old_zipcode = substr($customer->zipcode,0,3) . '-' . substr($customer->zipcode,3,6);
						$old_info = "〒{$old_zipcode}\n";
						$old_info .= "{$customer->address1}{$customer->address2}\n";
						//$old_info .= "電話番号: {$customer->tel}\n";
						$new_zipcode = substr($new_customer->zipcode,0,3) . '-' . substr($new_customer->zipcode,3,6);
						$new_info = "\n〒{$new_zipcode}\n";
						$new_info .= "{$new_customer->address1}{$new_customer->address2}\n";
						//$new_info .= "電話番号: {$form_data->tel}\n";
						$this->my_mail->send_mail_change_info($customer,$new_info,$old_info,'住所の変更');
						$this->session->unset_userdata('address');
						$this->Customer->re_session_userdata($sess_customer->id);
						$this->session->set_flashdata('success','住所を変更しました');
						return redirect("mypage/mypage_account/{$type}/agreed");
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
						'tel2'=>$form_data->tel2,
						//'new_member_flag'=>2,
						'change_info'=>'tel',
					);
					$this->Customer->update($sess_customer->id,$db_data);
					$old_info = "電話番号: {$customer->tel}\n";
					$old_info .= "携帯電話番号: {$customer->tel2}\n";
					$new_info = "電話番号: {$form_data->tel}\n";
					$new_info .= "携帯電話番号: {$form_data->tel2}\n";
					$this->my_mail->send_mail_change_info($customer, $new_info, $old_info, 'お電話番号の変更');
					$this->session->set_flashdata('success','お電話番号を変更しました');
					$this->Customer->re_session_userdata($sess_customer->id);
					return redirect("mypage/mypage_account/{$type}");
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
				$form_data = array(
					'mail_magazine'=>$maga,
					'new_member_flag'=>2,
					'change_info'=>'maga',
				);
				$this->Customer->update($sess_customer->id, $form_data);
				$this->session->set_flashdata('success','変更しました');
				$this->Customer->re_session_userdata($sess_customer->id);
				return redirect("mypage/mypage_account/{$type}");
			}
		}
		$this->data['form_data']  = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('mypage/mypage_change',$this->data);
	}
	
	public function change_auth()
	{
		$this->data['h2title'] = 'マイページ：会員情報の変更';
		$this->data['title'] = '会員情報変更';
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
						$db_data = array(
							'username'=>$form_data->username,
							'new_member_flag'=>2,
						);
						$this->Customer->update($sess_customer->id,$db_data);
						$this->session->set_flashdata('success','ユーザーIDを変更しました');
						$this->Customer->re_session_userdata($sess_customer->id);
						return redirect('mypage/change_auth/username');
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
							$db_data = array(
								'password'=>$this->encrypt->encode($form_data->password),
								'new_member_flag'=>2,
							);
							$this->Customer->update($sess_customer->id,$db_data);
							$this->session->set_flashdata('success','パスワードを変更しました');
							$this->Customer->re_session_userdata($sess_customer->id);
							return redirect('mypage/change_auth/password');
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
		$this->load->view('mypage/mypage_change_auth',$this->data);
	}
	
	public function mypage_order()
	{
		$this->data['title'] = 'ご注文履歴';
		$customer = $this->_checklogin($this->data['customer']);
		$this->data['h2title'] = 'ご注文履歴';
		$sess_customer = $this->session->userdata('customer');
		$orders  = $this->Order->get_by_customer_id($sess_customer->id,null,5);
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

		$this->data['paid_flags'] = $this->Mst_paid_flag->array_lists();
		$this->data['orders'] = $orders;
		$this->data['form_data'] = $form_data;
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->data['payments'] = $this->Master_payment->method;
		$this->load->view('mypage/mypage_order',$this->data);
	}
	
	/** 領収書の表示　**/
	public function receipt()
	{
		$this->load->model('Base_info');
		$base_info = $this->Base_info->get_base_info();
		if(!$order_id = $this->uri->segment(3))
		{
			return show_error('不正な画面操作です');
		}
		$this->data['title'] = '領収書の表示';
		$customer = $this->_checklogin($this->data['customer']);
		$order = $this->Order->get_by_id_customer((int)$order_id,$customer->id);
		$details = $this->Order->get_detail_by_order_id($order_id);
		$customer = $this->Customer->get_by_id($customer->id);
		$this->data['h2title'] = "{$customer->name} 様";
		$this->data['customer'] = $customer;
		$this->data['details'] = $details;
		$this->data['order'] = $order;
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['shop_info'] = $base_info;
		$this->load->view('mypage/reciept',$this->data);
	}
	
	public function select_address()
	{
		$form_data = new StdClass();
		if($form_data = $this->session->userdata('my_address'))
		{
			$form_data = $form_data;
		}else{
			$form_data = $this->Address;
		}
		$addresses = $this->Address->get_addresses($this->data['customer']);
		$this->data['title'] = '登録済みのお届け先';
		$this->data['h2title'] = '登録済みのお届け先';
		//$userdata = $this->Customer->get_by_username($this->data['customer']);
		if($this->input->post('submit'))
		{
			$form_data = $this->input->post();
			$form_data = (object)$form_data;
			$this->my_validation->add_address_validation_rules();
			if(!$this->form_validation->run() === FALSE){
				$this->session->set_userdata('my_address',$form_data);
				return redirect('mypage/confirm_address');
			}
		}
		$this->data['addresses'] = $addresses;
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->load->view('mypage/select_address',$this->data);
	}

	public function confirm_address()
	{
		if(!$form_data = $this->session->userdata('my_address')){
			return redirect('mypage/select_address');
		}
		if(!$form_data = $this->session->userdata('my_address')){
			return redirect('mypage/select_address');
		}
		$this->data['title'] = 'お届け先の入力内容の確認';
		$this->data['h2title'] = 'お届け先入力内容の確認';
		$this->data['form_data'] = $form_data;
		$this->data['button_message'] = 'この内容で登録する';
		$this->load->view('mypage/confirm_address',$this->data);
		if($this->input->post('submit')){
			$_POST = (array)$form_data;
			$this->my_validation->add_address_validation_rules();
			if(!$this->form_validation->run() === FALSE)
			{
				return redirect('mypage/add_address');
			}else{
				return redirect('mypage/select_address');
			}
		}
	}
	
	public function add_address()
	{
		if(!$data = $this->session->userdata('my_address'))
		{
			return redirect('mypage/select_address');
		}
		$customer_data = $this->Customer->get_by_username($this->data['customer']);
		$db_data = array(
			'customer_code'=>$customer_data->code,
			'customer_id'=>$customer_data->id,
			'name'=>$data->name,
			'furigana'=>$data->furigana,
			'zipcode'=>$data->zipcode1 . $data->zipcode2,
			'address1'=>$data->address1,
			'address2'=>$data->address2,
			'tel'=>$data->tel,
			'create_datetime' =>date('Y-m-d H:i:s'),
		);
		$id = $this->Address->save($db_data);
		if(!empty($id))
		{
			$this->session->unset_userdata('my_address');
		}else{
			$this->session->set_flashdata('error','登録に失敗しました');
		}
		return redirect('mypage/select_address');	
	}
	
	public function edit_address()
	{
		$address_id = $this->uri->segment(3);
		$this->Address->check_addres_id($address_id,'mypage/select_address');
		$this->data['addresses'] = $this->Address->get_addresses($this->data['customer']);
		$this->data['h2title'] = 'お届け先の編集';
		$this->data['title'] = 'お届け先の編集';
		$address = $this->Address->get_by_id_and_customer_id($address_id,$this->data['customer']);
		if(empty($address)){
			return redirect('mypage/select_address');
		}
		$form_data = $address;
		$form_data->zipcode1 = substr($address->zipcode,0,3);
		$form_data->zipcode2 = substr($address->zipcode,3,4);
		$form_data->address1 = $address->address1;
		$form_data->address2 = $address->address2;
		$this->data['form_data'] = $form_data;
		$this->data['edit_flag'] = TRUE;
		$this->load->view('mypage/select_address',$this->data);
		if($this->input->post('submit'))
		{
			$this->my_validation->add_address_validation_rules();
			if(!$this->form_validation->run() === FALSE)
			{
				$data = (object)$this->input->post();
				$db_data = array(
					'name'=> $data->name,
					'furigana'=> $data->furigana,
					'zipcode'=>$data->zipcode1 . $data->zipcode2,
					'address1'=>$data->address1,
					'address2'=>$data->address2,
					'tel'=>$data->tel,
				);
				$this->Address->update($address_id, $db_data);
			}
			return redirect('mypage/select_address');
		}				
	}
	
	public function del_address()
	{
		$address_id = $this->uri->segment(3);
		$this->Address->check_addres_id($address_id,'mypage/select_address');
		$this->data['addresses'] = $this->Address->get_addresses($this->data['customer']);
		$this->data['h2title'] = 'お届け先の削除';
		$this->data['title'] = 'お届け先の削除';
		$address = $this->Address->get_by_id_and_customer_id($address_id,$this->data['customer']);
		if(empty($address))
		{
			return redirect('mypage/select_address');
		}
		$this->data['form_data'] = $address;
		$this->data['button_message'] = 'このお知らせを削除する';
		if($this->input->post('submit')){
			$address_id = $this->input->post('address_id');
			$this->Address->delete($address_id);
			return redirect('mypage/select_address');
		}
		$this->load->view('mypage/del_address',$this->data);
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
	
	private function validation_message(){
		$this->form_validation->set_message('required','%sを入力してください');
		$this->form_validation->set_message('numeric','%sは数字で入力してください');
		$this->form_validation->set_message('max_length',mb_convert_encoding('%sは%s文字以内で入力してください','utf-8'));
		$this->form_validation->set_message('min_length',mb_convert_encoding('%sは%s文字以上で入力してください','utf-8'));
		$this->form_validation->set_message('valid_email',mb_convert_encoding('%sは正しい形式で入力してください','utf-8'));
	}

/**********************　中止　************************************************	
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
				$send_result = $this->my_mail->send_mail_change_order($customer,$result,'数量変更');
				$this->session->set_flashdata('success','数量を変更しました');
				return redirect('mypage/mypage_order');
			}
			$this->data['order'] = $result;
			$this->data['quantity'] = $this->Master_quantity->quantity;
			$this->load->view('mypage/change_quantity',$this->data);
		}catch(Exception $e){
			show_404();
			log_message($e->getMessage());
		}
	}
***************************************************************************************/

	public function cancel()
	{
		try{
			$customer = $this->_checklogin($this->data['customer']);
			//order情報取得
			$order_id = $this->uri->segment(3);
			$result = $this->Order->get_by_id($order_id);
			//$order_detail_id = $this->uri->segment(3);
			//$result = $this->Order->get_detail_by_id($order_detail_id);
			//detail_orderを取得
			$result = $this->Order->get_by_id_with_detail($order_id);
			//status_flagが0でなかったらキャンセルできない
			if($result[0]->status_flag != 0){
				$this->session->set_flashdata('error','キャンセルできません。');
				return redirect('mypage/mypage_order');
			}else{
				$this->data['h2title'] = 'ご注文のキャンセル';
				$this->data['title'] = 'ご注文のキャンセル';
				$this->data['label_message'] = 'キャンセルする';
				$this->data['button_message'] = 'キャンセル';
				$this->data['message'] = 'ご注文をキャンセルには、キャンセルするにチェックを入れてキャンセルボタンを押してください';
				if($this->input->post('submit')){
					if($this->input->post('cancel')){
						//クレジット取消処理
						if((int)$result[0]->payment == PAYMENT_CREDIT){
							$output = $this->Credit->alter_tran($result[0]->order_number);
							if($output->isErrorOccurred()){
								$message = $this->Credit->getAlterErrorMessages($output);
								throw new Exception($message[0]);
							}
						}
						$db_data = array(
							'status_flag'=>CANCELED
						);
						$this->Order->update($order_id,$db_data);
						$this->Order->update_order_detail_flag($result,$db_data);
						$result->cancel = $this->data['h2title'];
						$send_result = $this->my_mail->send_mail_change_order($customer,$result[0],$this->data['h2title']);
						$this->session->set_flashdata('success','注文をキャンセルしました');
						return redirect('mypage/mypage_order');
					}else{
						$this->data['error_message'] = 'キャンセルする場合はキャンセルするにチェックを入れてください。<br>
							キャンセルしない場合は、戻るボタンを押してください';
					}
				}
			}
			/* キャンセルを取り消す場合の処理　現在取引停止中
			if($result[0]->status_flag==2){
				$this->data['h2title'] = 'ご注文のキャンセルの取消';
				$this->data['title'] = 'ご注文のキャンセルの取消';
				$this->data['label_message'] = 'キャンセルの取消';
				$this->data['button_message'] = 'キャンセル取消';
				$this->data['message'] = 'キャンセルを取り消す場合は,キャンセル取消にチェックをいれてください。';
				if($this->input->post('submit')){
					if($this->input->post('cancel')){
						$db_data = array(
							'status_flag'=>0
						);
						$this->Order->update($order_id,$db_data);
						$this->Order->update_order_detail_flag($result,$db_data);
						$result->cancel = $this->data['h2title'];
						$send_result = $this->my_mail->send_mail_change_order($customer,$result,$this->data['h2title']);
						$this->session->set_flashdata('success','キャンセルを取り消しました');
						return redirect('mypage/mypage_order');
					}else{
						$this->data['error_message'] = 'キャンセルを取り消す場合はキャンセルの取消にチェックを入れてください。<br>
							取消しない場合は、戻るボタンを押してください';
					}
				}
			}
			*/
		}catch(Exception $e){
			//show_404();
			//log_message($e->getMessage());
			$this->data['error_message'] = $e->getMessage();
		}
		$this->data['order'] = $result;
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->load->view('mypage/cancel',$this->data);
	}
	
	public function information()
	{
		$this->data['h2title'] = '会員お知らせ一覧';
		$this->data['title'] = '会員おしらせ一覧';
		$this->data['informations'] = $this->Customer_info->show_list_front();
		$customer = $this->_checklogin($this->data['customer']);
		$id = $this->uri->segment(3);
		$info = $this->Customer_info->get_by_id($id);
		$this->data['info'] = $info;
		$this->load->view('mypage/information',$this->data);
	}
	
	public function address_notice()
	{
		$this->data['h2title'] = '住所変更にあたってのご注意';
		$this->data['title'] = '住所変更にあたってのご注意';
		$this->load->view('mypage/address_notice',$this->data);
	}
	
	//全角かたかなチェック
	public function kana_check($str)
	{
		if(preg_match("/^[ァ-ヶー　\s]+$/u", $str)) {
			return TRUE;
		}else{
			$this->form_validation->set_message('kana_check','%sは全角カタカナで入力してください');
			return FALSE;
		}
	}

	/*
	public function logout()
	{
		$this->session->unset_userdata('customer');
		$this->session->unset_userdata('login_user');
		$this->session->unset_userdata('address');
		$this->session->set_flashdata('success','ログアウトしました');
		return redirect('mypage/login_action/user_view');
	}

	
	private function validation_message(){
		$this->form_validation->set_message('required','%sを入力してください');
		$this->form_validation->set_message('numeric','%sは数字で入力してください');
		$this->form_validation->set_message('max_length','%sは%文字以内で入力してください');
	}
	*/
	/*
	private function validation_rules($no_member = False)
	{
		$this->form_validation->set_rules('name','お名前','required|max_length[50]');
		$this->form_validation->set_rules('furigana','フリガナ','required|max_length[100]');
		$this->form_validation->set_rules('email_confirm','メールアドレス','required|max_length[100]');
		$this->form_validation->set_rules('email','メールアドレス(確認用)','required|max_length[100]');
		$this->form_validation->set_rules('zipcode','郵便番号','required');
		$this->form_validation->set_rules('prefecture','県名','required|max_length[10]');
		$this->form_validation->set_rules('address1','住所','required|max_length[200]');
		$this->form_validation->set_rules('street','住所、番地','required|max_length[100]');
		$this->form_validation->set_rules('tel','電話番号','required|max_length[10]');
		$this->validation_message();
	}
	
	private function validation_login()
	{
		$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]');
		$this->form_validation->set_rules('password','パスワード','required|max_length[50]');
		$this->validation_message();
	}
	
	private function check_email($obj)
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
	
	public function username_check($str)
	{
		if($this->Customer->check_username($str)){
			return TRUE;
		}else{
			$this->form_validation->set_message('username_check','%sはすでに登録さてます');
			return FALSE;
		}
	}
	
	private function send_mail($data,$type)
	{
		$send_address = $this->Mail_template->send_address;
		$template_for_user = $this->Mail_template->get_by_id(ACCOUNT_CHANGE_FOR_USER);
		$template_for_admin = $this->Mail_template->get_by_id(ACCOUNT_CHANGE_FOR_ADMIN);
		$name = $data->name;
		$customer_id = $data->id;
		$url = site_url('mypage/mypage');
		if($type == 'name'){$content = 'お名前の変更';}
		if($type == 'mail'){$content = 'メールアドレスの変更';}
		if($type == 'address'){$content = '住所、電話番号の変更';}
		if($type == 'maga'){$content = 'メルマガ登録の変更';}
		if($type == 'username'){$content = 'ユーザーIDの変更';}
		if($type == 'password'){$content = 'パスワードの変更';}
		
		//for customer
		$mail_body = str_replace(array('{$name}','{$url}','{$content}'),array($name,$url,$content),$template_for_user->mail_body);
		$result1 = sendMail($data->email,$template_for_user->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		//for admin
		$mail_body = str_replace(array('{$name}','{$customer_id}','{$content}'),array($name,$customer_id,$content),$template_for_admin->mail_body);
		$result2 = sendMail($this->Mail_template->admin_address,$template_for_admin->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		return ($result1 && $result2);
	}
	
	private function send_mail_order($userdata,$orderdata,$newdata,$type)
	{
		$send_address = $this->Mail_template->send_address;
		$template_for_user = $this->Mail_template->get_by_id(ORDER_CHANGE_FOR_USER);
		$template_for_admin = $this->Mail_template->get_by_id(ORDER_CHANGE_FOR_ADMIN);
		$name = $userdata->name;
		$customer_id = $userdata->id;
		$url = site_url('mypage/mypage');
		if($type == 'quantity'){
			$content = 'ご注文商品数量変更';
			$content1 = "【受注番号】{$orderdata->order_number}\n {$orderdata->product_name}  変更前 {$orderdata->quantity} ⇒ 変更後　{$newdata->quantity}";
		}
		if($type == 'cancel'){
			$content = '注文キャンセル';
			$content1 = "【受注番号】{$orderdata->order_number}\n {$orderdata->product_name} 数量 {$orderdata->quantity} ⇒　キャンセルしました";
		}
		
		//for customer
		$mail_body = str_replace(array('{$name}','{$url}','{$content}','{$content1}'),array($name,$url,$content,$content1),$template_for_user->mail_body);
		$result1 = sendMail($userdata->email,$template_for_user->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		//for admin
		$mail_body = str_replace(array('{$name}','{$customer_id}','{$content}','{$content1}'),array($name,$customer_id,$content,$content1),$template_for_admin->mail_body);
		$result2 = sendMail($this->Mail_template->admin_address,$template_for_admin->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		return ($result1 && $result2);
	}
*/

}