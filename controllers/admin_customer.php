<?php
/*
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/csv.php';
*/
class Admin_customer extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','my_validation','my_mail'));
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Customer_info');
		$this->load->model('Master_mail_magazine');
		$this->load->model('Master_hour');
		$this->load->model('Personal');
		$this->load->model('Master_show_flag');
		$this->load->model('Master_shop');
		$this->load->model('Master_change_info');
		$this->load->model('Master_takuhai_hours');
		$this->load->model('Cource');
		$this->load->model('Customer_history');
		$this->load->model('Order');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->load->model('Admin_login');
		$this->data['user'] = $this->Admin_login->check_login();
		$this->load->model('Admin_urls');
		if(!empty($this->data['user'])){
			$this->Admin_urls->get_user_site_menu($this->data['user']->id,$this);
			$this->Admin_urls->is_access_url($this->data['user']->id,$this);
		}
	}
	
	public function index()
	{
		$this->data['h2title'] = 'メニューを選択';
		$this->load->view('index_admin',$this->data);
	}
	
	public function add_customer()
	{
		$this->data['h2title'] = '会員新規登録';
		$this->data['message'] = '下記の情報を入力して登録ボタンを押してください';
		$this->data['shops'] = $this->Master_shop->array_lists(TRUE);
		$form_data = $this->Customer;
		/*
		$sess_data = $this->session->userdata('result');
		$this->session->unset_userdata('result');
		if($sess_data == FALSE){
			$this->data['no_area_data'] = '';
		}elseif($sess_data == 'no_data'){
			$this->data['no_area_data'] = 'エリアマスタに登録されていません';
		}else{
			$form_data->zipcode = $sess_data->zipcode;
			$form_data->prefecture = $sess_data->prefecture;
			$form_data->address1 = $sess_data->city . $sess_data->address;
		}
		*/
		$this->data['form_data'] = $form_data;
		$this->data['cource_list'] = $this->Cource->show_list_for_dropdown(0,TRUE);
		/*
		if($this->input->post('post_zip')){
			$zipcode = $this->input->post('zipcode');
			$address  = $this->Customer->get_area_by_zip($zipcode);
			$input_data=array(
				'cource_id'=>$this->input->post('cource_id'),
				'code'=>$this->input->post('code'),
				'zipcode'=>$this->input->post('zipcode'),
				'name'=>$this->input->post('name'),
				'furigana'=>mb_convert_kana($this->input->post('furigana'),'KCs'),
				'email'=>$this->input->post('email'),
				'tel'=>$this->input->post('tel'),
				'tel2'=>$this->input->post('tel2'),
				'birthday'=>$this->input->post('birthday'),
				//'point'=>$this->input->post('point'),
				//'rank'=>$this->input->post('rank'),
				//'bank_name'=>$this->input->post('bank_name'),
				//'type_account'=>$this->input->post('type_account'),
				//'account_number'=>$this->input->post('account_number'),
			);
			$form_data= (object)$input_data;
			if(!empty($address)){
				$form_data->zipcode=$address->zipcode;
				$form_data->address1=$address->prefecture.$address->city.$address->address;
				$form_data->shop_code=$address->shop_code;
				$form_data->cource_code=$address->cource_code;
			}else{
				$form_data->shop_code = '';
				$form_data->cource_code = '';
				$form_data->address1 = '';
				$form_data->no_area_data = '登録されてません';
			}
			$form_data=(object)$form_data;
			$this->data['form_data'] = $form_data;
		}
		*/
		if($this->input->post('submit')){
			/*
			if($this->input->post('birthday')){
				$birthday = new DateTime();
				$birthday = $birthday->format('Y-m-d');
			}else{
				$birthday = '';
			}*/
			$this->my_validation->validation_add_customer();
			$this->form_validation->set_rules('code','顧客コード','callback_code_check');
			$this->form_validation->set_rules('tel','電話番号','callback_tel_check');
			$this->form_validation->set_rules('tel2','携帯電話番号','callback_tel_check');
			$this->form_validation->set_rules('email','メールアドレス','callback_email_check');
			$this->my_validation->validation_message();
			if($this->form_validation->run() === FALSE)
			{
				$this->data['form_data'] = (object)$this->input->post();
			}
			else
			{
				$input_data = array(
					//'shop_code'=>$this->input->post('shop_code'),
					'cource_id'=>$this->input->post('cource_id'),
					'code'=>$this->input->post('code'),
					'name' => $this->input->post('name'),
					'furigana' => mb_convert_kana($this->input->post('furigana'),'KCs'),
					'email' =>$this->input->post('email'),
					'zipcode' => $this->input->post('zipcode'),
					//'prefecture' => $this->input->post('prefecture'),
					'address1' => $this->input->post('address1'),
					'address2' => $this->input->post('address2'),
					'tel' => $this->input->post('tel'),
					'tel2'=> $this->input->post('tel2'),
					'birthday' => $birthday,
					//'point'=>$this->input->post('point'),
					//'rank'=>$this->input->post('rank'),
					//'bank_name'=>$this->input->post('bank_name'),
					//'type_account'=>$this->input->post('type_account'),
					//'account_number'=>$this->input->post('account_number'),
					'create_date' => date('Y-m-d H:i:s'),
				);
				$db_data = $input_data;
				$this->Customer->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_customer'));
			}		
		}
		$this->data['succsss_message'] = $this->session->flashdata('success');
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->load->view('admin_customer/add_customer.php',$this->data);
	}
	public function code_length($str)
	{
		return $this->my_validation->code_length($str);
	}
	public function zipcode_length($str)
	{
		return $this->my_validation->zipcode_length($str);
	}
	public function email_check($str)
	{
		return $this->my_validation->email_check($str);
	}
	public function tel_check($str)
	{
		return $this->my_validation->tel_check($str);
	}
	public function code_check($str)
	{
		return $this->my_validation->code_check($str);
	}
	public function kana_check($str)
	{
		return $this->my_validation->kana_check($str);
	}
	public function birthday_format_check($str)
	{
		return $this->my_validation->birthday_format_check($str);
	}
	
	public function list_customer()
	{
		$this->data['h2title'] = '会員検索';
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		$form_data = array(
			'shop_id'=>0,
			'cource_id'=>3,
			'code'=>'',
			'name'=>'',
			'address1'=>'',
			'address2'=>'',
			'tel'=>'',
		);
		//$this->data['shops'] = $this->Master_area->list_area;
		$this->data['shops'] = $this->Master_shop->array_lists(TRUE);
		$this->data['selected'] = '';
		$cource_list = $this->Cource->show_list_for_dropdown();
		$cource_list[3] = '---';
		$this->data['cource_list'] = $cource_list;
		if($this->input->post('search'))
		{
			$form_data = array(
				'shop_id'=>$this->input->post('shop_id'),
				'cource_id'=>$this->input->post('cource_id'),
				'name'=> $this->input->post('name'),
				'address1'=>$this->input->post('address1'),
				'address2'=>$this->input->post('address2'),
				'code'=>$this->input->post('code'),
				'tel'=>$this->input->post('tel'),
			);
			$form_data = (object)$form_data;
			$this->data['selected'] = $form_data->shop_id;
			$this->data['result'] = $this->Customer->show_list_conditions($form_data);
		}
		else
		{
			$offset = $this->uri->segment(5);
			$config['uri_segment'] = 5;
			$config['per_page'] = 20;
			$config['base_url'] = site_url('admin_customer/list_customer') . '/-/-/';
			$this->data['result'] = $this->Customer->show_list_where($config['per_page'],$offset);
			$config['total_rows'] = $this->Customer->num_rows();
			$config['last_link'] = false;
			$config['first_link'] = false;
			$config['next_link'] = '次へ';
			$config['prev_link'] = '前へ';
			$config['cur_tag_open'] = '<span class="current">';
			$config['cur_tag_close'] = '</span>';
			$this->pagination->initialize($config);
			$this->data['links'] =  $this->pagination->create_links();
		}
		$this->data['form_data'] = (object)$form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/list_customer.php',$this->data);
	}
	
	public function detail_customer()
	{
		$id = $this->uri->segment(3);
		$detail_result = $this->Customer->get_by_id($id);
		//$this->data['shops'] = $this->Master_area->list_area;
		$this->data['detail_result'] = $detail_result;
		$this->data['h2title'] = "{$this->data['detail_result']->name}さんの詳細情報";
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['orders'] = $orders;
		$this->load->view('admin_customer/detail_customer',$this->data);
	}
	
	public function list_order()
	{
		$customer_id = $this->uri->segment(3);
		$customer = $this->Customer->get_by_id($customer_id);
		$orders = $this->Order->get_by_customer_id($customer_id,null,5);
		$form_data = new StdClass();
		$form_data->start_date = '';
		$form_data->end_date = '';
		if($this->input->post('submit'))
		{
			$start = $this->input->post('start_date');
			$end = $this->input->post('end_date');
			$form_data->start_date = $start;
			$form_data->end_date = $end;
			$orders = $this->Order->get_by_customer_id($customer_id, $form_data);
		}
		$count = count($orders);
		for($i = 0; $i < $count; $i++)
		{
			$orders[$i]->details = $this->Order->get_detail_by_order_id($orders[$i]->id);
		}
		$this->data['form_data'] = $form_data;
		$this->data['h2title'] = $customer->name . ' 様の受注履歴';
		$this->data['orders'] = $orders;
		$this->load->model('Master_payment');
		$this->load->model('Master_order_status');
		$this->load->model('Master_takuhai_hours');
		$this->load->model('Mst_paid_flag');
		$this->data['paid_flags'] = $this->Mst_paid_flag->array_lists();
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->load->view('admin_customer/list_order',$this->data);
	}
	
	public function cancel()
	{
		try{
			$this->load->model('Master_payment');
			//$customer = $this->_checklogin($this->data['customer']);
			$customer_id = $this->uri->segment(4);
			$customer = $this->Customer->get_by_id($customer_id);
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
				return redirect("admin_customer/list_order/{$customer_id}");
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
						$this->Order->db->trans_begin();
						$this->Order->update($order_id,$db_data);
						$this->Order->update_order_detail_flag($result,$db_data);
						if($this->Order->db->trans_status() !== FALSE)
						{
							$this->Order->db->trans_commit();
							$result->cancel = $this->data['h2title'];
							$send_result = $this->my_mail->send_mail_change_order($customer,$result[0],$this->data['h2title']);
							$this->session->set_flashdata('success','注文をキャンセルしました');
							return redirect("admin_customer/list_order/{$customer_id}");
						}
						else
						{
							$this->Order->db->trans_rollback();
							$this->session->set_flashdata('error','キャンセル登録に失敗しました');
						}
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
		$this->load->view('admin_customer/cancel',$this->data);
	}
	
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

	public function edit_customer()
	{
		$this->data['h2title'] = '会員情報の変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$this->data['shops'] = $this->Master_shop->array_lists(TRUE);
		$id = $this->uri->segment(3);
		$result= $this->Customer->get_by_id($id);
		$this->data['cource_list'] = $this->Cource->show_list_for_dropdown($result->shop_id,TRUE);
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
		//add_validateion
			$this->my_validation->validation_add_customer();
			$this->my_validation->validation_message();
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$this->input->post();
			}else{
				if(!$this->Customer->check_email($this->input->post('email'),TRUE,$id))
				{
					$this->session->set_flashdata('error','メールアドレスは既に登録されています。');
					return redirect("admin_customer/edit_customer/{$id}");
				}
				if(!$this->Customer->check_tel($this->input->post('tel'),TRUE,$id)
					|| !$this->Customer->check_tel($this->input->post('tel2'),TRUE,$id))
				{
					$this->session->set_flashdata('error','電話番号は既に登録されています。');
					return redirect("admin_customer/edit_customer/{$id}");
				}
				if(!$this->Customer->check_code($this->input->post('code'),TRUE,$id))
				{
					$this->session->set_flashdata('error','顧客コードはすでに登録されています');
					return redirect("admin_customer/edit_customer/{$id}");
				}
				$input_data = array(
					'cource_id'=>$this->input->post('cource_id'),
					'code'=>$this->input->post('code'),
					'name' => $this->input->post('name'),
					'furigana' => $this->input->post('furigana'),
					'email' =>$this->input->post('email'),
					'zipcode' => $this->input->post('zipcode'),
					'address1' => $this->input->post('address1'),
					'address2' => $this->input->post('address2'),
					'tel' => $this->input->post('tel'),
					'tel2' => $this->input->post('tel2'),
					'birthday' => $this->input->post('birthday'),
				);
				$db_data = $input_data;
				//変更前情報の格納
				$old = $this->Customer_history;
				$count = $old->get_count();
				$old_data = array(
					1 => $result->name,
					2 => $result->furigana,
					3 => $result->zipcode,
					4 => $result->address1 . $result->address2,
					5 => $result->tel,
					6 => $result->tel2,
					7 => $result->email,
					10=> $result->birthday,
					11=> $result->cource_id,
					12=> $result->code,
				);
				$this->Customer->db->trans_begin();
				$this->Customer->update($id, $db_data);
				$admin = $this->session->userdata('admin');
				foreach($old_data as $k => $v)
				{
					$old->customer_id = $id;
					$old->customer_code = $result->code;
					$old->username = $admin->username;
					$old->item_id = $k;
					$old->content = $v;
					$old->save();
				}
				if($this->Customer->db->trans_status() == FALSE)
				{
					$this->Customer->db->rollback();
					$this->data['error_message'] = '保存に失敗しました';
				}
				else
				{
					$this->Customer->db->trans_commit();
					$this->session->set_flashdata('success','登録しました');
					redirect(site_url("admin_customer/detail_customer/{$id}"));
				}
			}
		}
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->load->view('admin_customer/add_customer',$this->data);
	}
		
	public function delete_customer()
	{
		$id = $this->uri->segment(3);
		$this->Customer->delete($id);
		$this->Personal->delete_with_user_id($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(site_url('/admin_customer/list_customer'));
	}
	
	public function add_personal()
	{
		$this->data['h2title'] = '会員詳細情報登録';
		$id = $this->uri->segment(3);
		$result = $this->Customer->get_by_id($id);
		$customer = $result[0];
		$form_data = $this->Personal;
		$this->data['form_data'] = $form_data;
		$this->data['merumaga_select'] = $this->Master_mail_magazine->merumaga_select;

		if($this->input->post('submit')){
			$input_data = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'point' => $this->input->post('point'),
				'rank' => $this->input->post('rank'),
				'bank_name' => $this->input->post('bank_name'),
				'type_account' => $this->input->post('type_account'),
				'account_number' => $this->input->post('account_number'),
				'mail_magazine' => $this->input->post('mail_magazine'),
				'user_id' => $id,
				'create_date'=>date('Y-m-d H:i:s'),
			);
			$this->form_validation->set_rules('password','パスワード','required');
		//add_validation
			if($this->form_validation->run() === FALSE){
			
			}else{
				$db_data = $input_data;
				$this->Personal->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_customer'));
			}
		}
		$this->data['message'] = "{$customer->familyname} {$customer->firstname}様の情報";
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/add_personal.php',$this->data);
	}
	
	public function list_personal()
	{
		$this->data['h2title'] = '会員詳細情報閲覧';
		$this->data['result']  = $this->Customer->show_list();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/list_personal.php',$this->data);
	}
	
	public function detail_personal()
	{
		$this->data['h2title'] = '会員詳細情報';
		$this->data['merumaga_select'] = $this->Master_mail_magazine->merumaga_select;
		$id = $this->uri->segment(3);
		$result = $this->Customer->get_by_id($id);
		$this->data['detail_result'] = $result[0];
		$this->load->view('admin_customer/detail_personal.php',$this->data);
	}
	
	public function edit_personal()
	{
		$this->data['h2title'] = '会員詳細情報変更';
		$this->data['message'] = '内容を修正して登録ボタンを押して下さい';
		$this->data['merumaga_select'] = $this->Master_mail_magazine->merumaga_select;
		$id = $this->uri->segment(3);
		$result = $this->Personal->get_by_user_id($id);
		$result = $result[0];
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
			$input_data = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'point' => $this->input->post('point'),
				'rank' => $this->input->post('rank'),
				'bank_name' => $this->input->post('bank_name'),
				'type_account' => $this->input->post('type_account'),
				'account_number' => $this->input->post('account_number'),
				'mail_magazine' => $this->input->post('mail_magazine'),
			);
			$db_data = $input_data;
			$this->Personal->update_with_user_id($id,$db_data);
			$this->session->set_flashdata('success','登録しました');
			redirect(site_url('/admin_customer/list_personal'));
		}
		$this->load->view('admin_customer/add_personal',$this->data);		
	}
	
	public function add_personal_payment()
	{
		$this->data['h2title']  = '会員お支払情報変更';
		$id = $this->uri->segment(3);
		$result = $this->Customer->get_by_id($id);
		$customer = $result;
		$this->data['form_data'] = $customer;
		if($this->input->post('submit')){
			$input_data = array(
				'bank_name' => $this->input->post('bank_name'),
				'type_account' => $this->input->post('type_account'),
				'account_number' => $this->input->post('account_number'),
			);
			$this->form_validation->set_rules('bank_name','銀行名','required');
			$this->form_validation->set_rules('type_account','種別','required');
			$this->form_validation->set_rules('account_number','口座番号','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data = (object)$input_data;
				$this->data['form_data'] = $form_data;
			}else{
				$db_data = $input_data;
				$this->Customer->update($id,$db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_personal'));
			}
		}
		$this->data['message'] = "{$customer->name}様のお支払情報登録";
		$this->load->view('admin_customer/add_personal_payment',$this->data);		
	}
	
	public function download_personal_login_info()
	{
		$this->data['h2title'] = '会員ログイン情報ダウンロード';
		$this->data['message'] = '会員ログイン情報をダウンロードする場合はdownloadボタンをクリックしてください';
		$id = $this->uri->segment(3);
		$this->data['customer'] = (object)array('id' => $id);

		$this->load->view('admin_customer/download_personal_login_info',$this->data);
	}
	
	public function add_personal_login_info()
	{
		$id = $this->uri->segment(3);
		$customer = $this->Customer->get_by_id($id);
		$name = $customer[0]->name;
		$username = $customer[0]->code;
		$password = uniqid();
		$db_data = array(
			'username' => $username,
			'password' => sha1($password),
		);
		$result = $this->Customer->update($id,$db_data);
		try{		
//			$downloadCsvDir = 'download_csv/';
//			$filename = 'test' . date('Y-m-d-H-i-s') . '.csv';	
//			$makeCsvFilename = $downloadCsvDir . $filename;
			//ファイル名にディレクトリを含めるとダウンロードされるときファイル名に変換される
			$filename = 'cusomer_' . $username . '.csv';
			$csv = new Csv();
			$result = array(array($name,$username,$password));
			$csv->setData($result);
			$csv->getCsvMs($filename);
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv->getCsvMs('php://output');
			exit();
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		$this->session->set_flashdata('success','登録しました');
		redirect(site_url('/admin_customer/list_personal'));
	}
	
	public function add_personal_mail_magazine()
	{
		$this->data['h2title'] = 'メールマガジン登録変更';
		$id = $this->uri->segment(3);
		$result = $this->Customer->get_by_id($id);
		$customer = $result;
		$this->data['form_data'] = $customer;
		$this->data['merumaga_select'] = $this->Master_mail_magazine->merumaga_select;
		if($this->input->post('submit')){
			$input_data = array(
				'mail_magazine'=>$this->input->post('mail_magazine'),
			);
			$this->Customer->update($id ,$input_data);
			$this->session->set_flashdata('success','登録しました');
			redirect(site_url('/admin_customer/list_personal'));
		}
		$this->data['message'] = "{$customer->name}様の情報";
		$this->load->view('admin_customer/add_personal_mail_magazine',$this->data);
	}
	
	public function upload()
	{
		$this->data['h2title'] = '会員一括登録';
		$this->data['db'] = getDb();
		$this->data['message'] = 'csvファイルをアップロードして下さい';
		
		if(isset($_FILES['csvfile'])){
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
//var_dump($csv);exit;
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate')){
					$dbh->query('TRUNCATE takuhai_customers');
				}
				$stmt = $dbh->prepare('insert into takuhai_customers(
					shop_code,
					cource_code,
					code,
					name,
					furigana,
					birthday,
					tel,
					tel2,
					email,
					zipcode,
					address1,
					address2,
					member_flag,
					new_member_flag,
					create_date
				) values(
					:shop_code,
					:cource_code,
					:code,
					:name,
					:furigana,
					:birthday,
					:tel,
					:tel2,
					:email,
					:zipcode,
					:address1,
					:address2,
					:member_flag,
					:new_member_flag,
					:create_date
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':shop_code',(int)$row[0]);
					$stmt->bindValue(':cource_code',(int)$row[2]);
					$stmt->bindValue(':code',$row[5]);
					$stmt->bindValue(':name',$row[6]);
					$stmt->bindValue(':furigana',$row[7]);
					$stmt->bindValue(':birthday','');
					$stmt->bindValue(':tel',$row[11]);
					$stmt->bindValue(':tel2',$row[12]);
					$stmt->bindValue(':email',$row[0].$row[2]);
					$stmt->bindValue(':zipcode',$row[8]);
					$stmt->bindValue(':address1',$row[9]);
					$stmt->bindValue(':address2',$row[10]);
					$stmt->bindValue(':member_flag',1);
					$stmt->bindValue(':new_member_flag',0);
					$stmt->bindValue(':create_date',$row[19]);
					$stmt->execute();
					$error_info=$stmt->errorInfo();
					$error_arr[] = $stmt->errorInfo();
					if($error_info[0] != '00000'){
						$this->data['error_message'] = 'データベースの登録に失敗しました';
						break;
					}
				}
				$counter = 0;
				foreach($error_arr as $err){
					if($err[0] == '00000'){
						$counter += 1;
					}
				}
				//$this->data['db_message']= "{$counter} 件データベースに登録しました";
				$this->session->set_flashdata('success',"{$counter} 件データベースに登録しました");
				redirect(site_url('/admin_customer/upload'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/admin_upload_customer.php',$this->data);
	}
	
	public function add_info()
	{
		try
		{
			$this->data['h2title'] = '会員お知らせ登録・一覧';
			/*** おしらせ一覧取得情報 ***/
			$this->data['result'] = $this->Customer_info->show_list_sorted();
			$this->data['show_flag'] = $this->Master_show_flag->show_flag;
			$detail_flag = $this->uri->segment(3);
			$id = $this->uri->segment(4);
			if($detail_flag)
			{
				$detail_result = $this->Customer_info->get_by_id($id);
				$this->data['detail_flag'] = $detail_flag;
				$this->data['detail_result'] = $detail_result;
			}
			if($this->input->post('change_order'))
			{
				$this->Customer_info->db->trans_begin();
				foreach($this->data['result'] as $obj)
				{
					$sort_num = $this->input->post("sort_order{$obj->id}");
					$db_data = array('sort_order'=>$sort_num);
					$this->Customer_info->update($obj->id,$db_data);
				}
				if($this->db->trans_status() === FALSE)
				{
					$this->Customer_info->db->trans_rollback();
					throw new Exception('登録に失敗しました');
				}
				else
				{
					$this->Customer_info->db->trans_commit();
					$this->session->set_flashdata('success','表示順を変更しました');
					return redirect(site_url('admin_customer/add_info'));
				}
			}
			/*** ここまでお知らせ一覧取得 ***/
			$form_data = $this->Customer_info;
			$this->data['hour_list'] = $this->Master_hour->hour;
			$this->data['form_data'] = $form_data;
			if($this->input->post('submit')){
				$input_data = array(
					'title' => $this->input->post('title'),
					'contents' => $this->input->post('contents'),
					'start_datetime' => $this->input->post('start_date') . ' ' . $this->input->post('start_time'),
					'end_datetime' => $this->input->post('end_date') . ' ' . $this->input->post('end_time'),
				);
				$this->form_validation->set_rules('title','タイトル','required');
				if($this->form_validation->run() === FALSE){
					$this->data['error_message'] = '未入力項目があります';
					$form_data = (object)$input_data;
					$this->data['form_data'] = $form_data;
				}else{
					$db_data = $input_data;
					$db_data['create_date'] = date('Y-m-d H:i:s');
					$result = $this->Customer_info->save($db_data);
					$this->session->set_flashdata('success','登録しました');
					return redirect(site_url('/admin_customer/add_info'));
				}
			}
			$this->data['success_message'] = $this->session->flashdata('success');
			$this->load->view('admin_customer/add_customer_info',$this->data);
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('success',$e->getMessage());
			return redirect(site_url('admin_customer/list_info'));
		}
	}
	
	public function list_info()
	{
		$this->data['h2title'] = '会員お知らせ一覧';
		$this->data['result'] = $this->Customer_info->show_list_sorted();
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$detail_flag = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		
		if($detail_flag){
			$detail_result = $this->Customer_info->get_by_id($id);
			$this->data['detail_flag'] = $detail_flag;
			$this->data['detail_result'] = $detail_result;
		}
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data=array('sort_order'=>$sort_num);
				$this->Customer_info->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(site_url('/admin_customer/list_info'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/list_customer_info',$this->data);
	}
	
	public function edit_customer_info()
	{
		$this->data['h2title'] = '会員お知らせ変更';
		$this->data['message'] = '内容を修正して登録ボタンを押して下さい';
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['result'] = $this->Customer_info->show_list_sorted();
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$id = $this->uri->segment(3);
		$result = $this->Customer_info->get_by_id($id);
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
			$input_data = array(
				'title' => $this->input->post('title'),
				'contents' => $this->input->post('contents'),
				'start_datetime' => $this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime' => $this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			$this->form_validation->set_rules('title','タイトル','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data = (object)$input_data;
				$this->data['form_data'] = $form_data;
			}else{
				$db_data = $input_data;
				$result = $this->Customer_info->update($id,$db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_info'));
			}
		}
		$this->load->view('admin_customer/add_customer_info',$this->data);
	}

	public function delete_customer_info()
	{
		$id = $this->uri->segment(3);
		$this->Customer_info->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(site_url('/admin_customer/add_info'));
	}
	public function change_show_flag_customer_info()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Customer_info->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_customer/add_info');
	}
	
	public function show_new_member()
	{
		$this->data['h2title'] = 'Web新規会員登録一覧';
		$this->data['shops'] = $this->Master_area->list_select;
		$this->data['selected'] = '';
		$shop_code = '';
		if($this->input->post('shop_code') != ''){
			$shop_code = $this->input->post('shop_code');
			$this->data['selected'] = $shop_code;
		}	
		$result = $this->Customer->get_new_member($shop_code,1);
		$this->data['result'] = $result;
		if($this->input->post('download'))
		{
			try{
				$filename = 'new_member_' . $shop_code . date('ymdhi') . '.csv';
				$csv = new Csv();
				$csv->setData($result);
				$csv->getCsvMs($filename);
				//new_member_flag = 0にする
				$this->Customer->update_new_member_flag($result);
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $filename);
				$csv->getCsvMs('php://output');
				exit();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		$this->load->view('admin_customer/show_new_member',$this->data);
	}
	
	public function show_change_member()
	{
		$this->data['h2title'] = 'Web会員変更情報取得一覧';
		$this->data['shops'] = $this->Master_area->list_select;
		$this->data['selected'] = '';
		$this->data['info'] = $this->Master_change_info->info;
		$shop_code = '';
		if($this->input->post('shop_code') != ''){
			$shop_code = $this->input->post('shop_code');
			$this->data['selected'] = $shop_code;
		}	
		$result = $this->Customer->get_change_member($shop_code);
		$this->data['result'] = $result;
		if($this->input->post('download'))
		{
			try{
				if(empty($result)){ throw new PDOException('登録されていません'); }
				$filename = 'new_member_' . $shop_code . date('ymdhi') . '.csv';
				$csv = new Csv();
				$csv->setData($result);
				$csv->getCsvMs($filename);
				//chanage_info をnullにする
				$this->Customer->update_change_member_flag($result);
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $filename);
				$csv->getCsvMs('php://output');
				exit();
			}catch(PDOException $e){
				$this->data['error_message'] =  $e->getMessage();
			}
		}
		$this->load->view('admin_customer/show_change_member',$this->data);
	}
	
	
	
}