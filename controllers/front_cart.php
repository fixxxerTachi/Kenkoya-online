<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_mail.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';
include __DIR__.'/../libraries/sendmail.php';

class Front_cart extends CI_Controller{
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
		//$this->load->model('Advertise_image');
		$this->load->model('Master_quantity');
		$this->load->model('Cart');
		//$this->load->model('Order');
		//$this->load->model('Mail_template');
		$this->load->model('Customer');
		//$this->load->model('Tax');
		//$this->load->model('Admin_mail');
		//$this->load->model('Master_days');
		//$this->load->model('Master_takuhai_hours');
		$this->load->model(array('Information','Top10','Recommend','Category','Advertise_product','Banner'));
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['customer'] = $this->session->userdata('customer') ? $this->session->userdata('customer'): $this->session->userdata('no-member');
		$this->data['informations'] = $this->Information->show_list_front();
		$this->data['top10'] = $this->Top10->show_list_side_top10(5);
		$this->data['recommend'] = $this->Recommend->show_list_side_recommend(5);
		$this->data['categories'] = $this->Category->show_list();
		$this->data['banner'] = $this->Banner->get_image_name();
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function input_cart()
	{
		if($this->input->post('submit'))
		{
			$carts=array();
			reset($carts);
			if($this->session->userdata('carts')){
				$carts = $this->session->userdata('carts');
			}
			$product_id = $this->input->post('product_id');
			$sale_price = $this->input->post('sale_price');
			$ad_id = $this->input->post('advertise_id');
			$qt = $this->input->post('quantity');
			$product_code = $this->input->post('product_code');
			$branch_code = $this->input->post('branch_code');
			////カートに入る商品が販売可能商品かどうか,商品別販売可能期間かどうか
			$this->load->model('Advertise_product');
			if(!$this->Advertise_product->check_on_sale($product_id) || !$this->Advertise_product->validate_sale_period($product_id))
			{
				$product = $this->Advertise_product->get_by_id($product_id);
				$this->session->set_flashdata('success',"申し訳ございません。現在【{$product[0]->product_name}】はお取扱いしていない商品です");
				return redirect(base_url('front_cart/show_cart'));
			}
			
			$cart = $this->Cart;
			$cart->product_id = $product_id;
			$cart->advertise_id = $ad_id;
			$cart->sale_price = $sale_price;
			$cart->branch_code = $branch_code;
			$cart->product_code = $product_code;
			$cart->quantity = $qt;
			//$form_data->quantity = $qt;
			$carts[] = serialize($cart);
			$this->session->set_userdata('carts',$carts);
			$this->session->set_flashdata('success','カートに入れました');
			return redirect(site_url('front_cart/show_cart'));
				//ajaxで遷移してきたとき販売期間がいなどの商品が登録されるようなばあいエラーを表示させる
				/*
				if($this->input->is_ajax_request()){
					echo json_encode(array('success'));
				}else{
					$this->session->set_flashdata('success','カートに入れました');
					return redirect(base_url('front_cart/show_cart'));
				}*/
		}
	}
	
	public function empty_cart()
	{
		if($this->session->userdata('carts')){
			$this->session->unset_userdata('carts');
			$this->session->set_flashdata('success','カートを空にしました');
			return redirect(base_url('front_cart/show_cart'));
		}
		return redirect(base_url('front_cart/show_cart'));
	}
	
	public function show_cart()
	{
		$this->data['h2title'] = 'カートの中身を見る';
		$this->data['title'] = 'カート';
		$carts = $this->session->userdata('carts');
		if(empty($carts)){
			$carts = null;
		}else{
			$list_product = array();
			foreach($carts as $cart){
				$c = unserialize($cart);
				$product = $this->Advertise->get_product_by_id_with_product($c->product_id);
				//数量を追加する
				$product->quantity = $c->quantity;
				$list_product[] = $product;
			}
		
		$this->data['list_product'] = $list_product;
		}
		$this->data['success_message']  = $this->session->flashdata('success');
		$this->load->view('front_cart/show_cart',$this->data);
	}
	
	public function change_quantity()
	{
		$this->data['h2title'] = 'カートの商品数量変更';
		$this->data['title'] = '商品数量変更';
		$key = $this->uri->segment(3);
		$from = $this->uri->segment(4);
		$carts = $this->session->userdata('carts');
		if(empty($carts))
		{
			return redirect(site_url('front_cart/show_cart'));
		}
		$item = unserialize($carts[$key]);
		$product_result = $this->Advertise->get_product_by_id_with_product($item->product_id);
		if($this->input->post('submit')){
			$qt = $this->input->post('quantity');
			$item->quantity = $qt;
			$carts[$key] = serialize($item);
			$this->session->set_userdata('carts',$carts);
			$this->session->set_flashdata('success','数量を変更しました');
			if($from == 'confirm'){
				return redirect('front_order/confirm_order');
			}
			return redirect(site_url('front_cart/show_cart'));
		}
		$this->data['quantity'] = $item->quantity;
		$this->data['select_quantity'] = $this->Master_quantity->quantity;
		$this->data['result'] = $product_result;
		$this->load->view('front_cart/detail_product',$this->data);
	}
	
	public function delete_item()
	{
		$this->data['h2title'] = 'カートから商品を削除';
		$key = $this->uri->segment(3);
		$from = $this->uri->segment(4);
		$carts = $this->session->userdata('carts');
		unset($carts[$key]);
		$carts = array_values($carts);
		$this->session->set_userdata('carts',$carts);
		$this->session->set_flashdata('success','カートから削除しました');
		if($from == 'confirm'){
			return redirect('front_order/confirm_order');
		}
		return redirect(site_url('front_cart/show_cart'));
	}
	
	public function error_item()
	{
		$this->data['title'] = 'カートに入れることができません';
		$this->data['h2title'] = 'カートに入れることができません';
		$this->load->view('front_cart/error_item',$this->data);
	}

/*
	public function confirm_order()
	{
		$customer = $this->_checklogin($this->data['customer']);
		$this->data['h2title'] = 'ご注文の最終確認画面';
		$this->data['title'] = 'ご注文確認';
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		if(!$carts = $this->session->userdata('carts')){
			$this->session->set_flashdata('error','カートにはなにも入っていません');
			return redirect(base_url('front_cart/show_cart'));
		}
		if(!$order_info = $this->session->userdata('order_info')){
			$this->session->set_flashdata('error','配達情報が入力されていません');
			return redirect(base_url('front_customer/delivery_info'));
		}
		
		//会員はユーザーIDから情報を取り出す、非会員はセッションno-memberから情報をとりだす。
		if($customer->username != 'no-member'){
			$userdata = $this->Customer->get_by_username($customer);
			$param = '';	//戻り先のパラメータ
		}elseif($customer->username == 'no-member'){
			$userdata = new StdClass();
			$userdata = $customer;
			$param = '_no_member';	//戻り先のパラメータ
		}
		$this->data['param'] = $param;
		
		$form_data = array(
			'name'=>$userdata->name,
			'address1'=>$userdata->address1,
			'tel'=>$userdata->tel,
			'delivery_date'=>$order_info->delivery_date,
			'payment'=>$order_info->payment,
			'bank_name'=>$order_info->bank_name,
			'type_account'=>$order_info->type_account,
			'takuhai'=>$order_info->takuhai,
		);
		$form_data = (object)$form_data;
		//配達時間が渡されているときは表示する
		if(isset($order_info->delivery_hour)){
			$form_data->delivery_hour = $order_info->delivery_hour;
		}
		if(!empty($carts)){
			$list_product = array();
			$total_price = array();
			foreach($carts as $cart){
				$c = unserialize($cart);
				$product = $this->Advertise->get_product_by_id_with_product($c->product_id);
				//$product = $product[0];
				$product->quantity = $c->quantity;
				$product->subtotal = $product->sale_price * $c->quantity;
				$list_product[] = $product;
				$total_price[] = $product->subtotal;
			}
			$this->data['list_product'] = $list_product;
			$total_price = array_sum($total_price);
			$tax = $this->Tax->get_current_tax();
			$tax_price = floor($tax * $total_price);
			$total = $total_price + $tax_price;
			$point = floor($total_price / 1000);
			$this->data['point'] = $point;
			$this->data['total_price'] = $total_price;
			$this->data['tax'] = $tax;
			$this->data['tax_price'] = $tax_price;
			$this->data['total'] = $total;
		}
		$this->data['form_data'] = $form_data;
		$this->data['days'] = $this->Master_days->jdays;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_cart/confirm_order',$this->data);
	}
	
	public function order_process()
	{
		try{
			$customer = $this->_checklogin($this->data['customer']);
			if(!$carts = $this->session->userdata('carts')){
				$this->session->set_flashdata('error','カートにはなにも入っていません');
				return redirect('front_cart/show_cart');
			}
			
			if(!$order_info = $this->session->userdata('order_info')){
				$this->session->set_flashdata('error','配達情報が入力されていません');
				return redirect(base_url('front_customer/delivery_info'));
			}
			
			if($this->input->post('submit')){
				$now = date('ymd-His');
				if($customer->username != 'no-member'){
					$userdata = $this->Customer->get_by_username($customer);
					$data=array(
						'customer_id'=>$userdata->id,
						'shop_code'=>$userdata->shop_code,
						'customer_code'=>$userdata->code,
						'address'=>$userdata->address1,
						'cource_code'=>$userdata->cource_code,
						'order_number'=>$now,
						'create_date'=>date('Y-m-d H:i:s'),
					);
			//非会員はsessionからデータを取り出す 情報をdbに格納
					}else{
						//$userdata = $this->session->userdata('customer');
						$user_data = array(
							'name'=>$customer->name,
							'furigana'=>$customer->furigana,
							'tel'=>$customer->tel,
							'tel2'=>$customer->tel2,
							'email'=>$customer->email,
							'zipcode'=>$customer->zipcode,
							'address1'=>$customer->address1,
							'member_flag'=>0,
							'create_date'=>date('Y-m-d H:i:s'),
						);
						//customersに格納
						$user_id = $this->Customer->save($user_data);
						$data = array(
							'customer_id'=>$user_id,
							'shop_code'=>0,
							'address'=>$user_data['address1'],
							'cource_code'=>0,
							'order_number'=>$now,
							'create_date'=>date('Y-m-d H:i]s'),
						);
				}
			//transaction
				$db = $this->Order->db;
				$db->trans_begin();
				$this->Order->save($data);
				
				//order_detail　保存処理
				$last_id = $this->Order->last_insert_id();
				if(empty($last_id)){
					throw new Exception('cannot save order');
				}
				foreach($carts as $cart){
					$c = unserialize($cart);
					$db_data=array(
						'order_id'=>$last_id,
						'order_number'=>$now,
						'product_code'=>$c->product_code,
						'branch_code'=>$c->branch_code,
						'delivery_date'=>$order_info->delivery_date,
						'delivery_hour'=>$order_info->delivery_hour,
						'advertise_id'=>$c->advertise_id,
						'advertise_product_id'=>$c->product_id,
						'sale_price'=>$c->sale_price,
						'quantity'=>$c->quantity,
						'create_date'=>date('Y-m-d H:i:s')
					);
					$this->Order->save_order($db_data);
				}
				if($db->trans_status() === FALSE){
					$db->trans_rollback();
					throw new Exception('transation error order');
				}else{
					$db->trans_commit();
					//メール送信処理
					//会員はDBから情報を取得、非会員はそのままcustomerに情報があるはず
					if($customer->username != 'no-member'){
						$customer = $this->Customer->get_by_username($customer);
					}
					$order = $this->Order->get_by_id($last_id);
					$details = $this->Order->get_by_order_number($order->order_number);
					$text = '';
					$total = array();
					foreach($details as $item){
						if($item->delivery_date != '0000-00-00 00:00:00'){
							$date = new DateTime($item->delivery_date);
							$delivery_date = $date->format('Y年m月d日');
						}else{
							$delivery_date = '日付指定しない';
						}
						if($item->delivery_hour != 0){
							$delivery_hour = $this->Master_takuhai_hours->hours[$item->delivery_hour];
						}else{
							$delivery_hour = '時間指定しない';
						}
						$sale_price = number_format($item->sale_price);
						$text .= "{$item->product_name}     {$sale_price}円  数量:{$item->quantity}  配達予定日:{$delivery_date} {$delivery_hour}\n";
						$total[] = $item->sale_price * $item->quantity;
					}
					$total = array_sum($total);
					$tax_rate = $this->Tax->get_tax_by_date($order->create_date);
					$tax  = floor($total * $tax_rate);
					$total_price = $total + $tax;
					$text_items = new StdClass();
					$text_items->order_number = $order->order_number;
					$text_items->items  = $text;
					$text_items->total = $total;
					$text_items->tax = $tax;
					$text_items->total_price = $total_price;
					$result = $this->send_mail($customer,$text_items);
					$this->session->set_flashdata('success','注文を確定しました');
					$this->session->set_userdata('order',$order);
					return redirect(base_url('front_cart/complete'));
				}
			}
		}catch(Exception $e){
			log_message('error',$e->getMessage());
			show_404();
		}
	}
	
	public function complete()
	{
		$customer = $this->_checklogin($this->data['customer']);
		$this->data['h2tilte'] = 'ご注文完了';
		$this->data['title'] = '注文完了';
		$order = $this->session->userdata('order');
		$this->data['order_number'] = $order->order_number;
		$this->session->unset_userdata('order');
		$this->session->unset_userdata('carts');
		//$this->session->unset_userdata('order_price');
		$this->session->unset_userdata('order_info');
		$this->session->unset_userdata('no-member');
		$this->load->view('front_cart/complete',$this->data);
	}
		
	
	private function send_mail($customer = null , $obj = null)
	{
		//お客さま用メール送信処理
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(3);
		$name = $customer->name;
		//$address = '〒' . substr($customer->zipcode,0,3).'-'.substr($customer->zipcode,3,6)."\n".$customer->prefecture.$customer->address1.$customer->address2;
		$address = $customer->address1;
		$mypage_url = base_url('front_customer/mypage');
		$contact_url = base_url('front_contact');
		$mail_body = str_replace(
		array(
			'{{name}}',
			'{{order_number}}',
			'{{order_items}}',
			'{{total}}',
			'{{tax}}',
			'{{total_price}}',
			'{{address}}',
			'{{mypage_url}}',
			'{{contact_url}}'
		),array(
			$name,
			$obj->order_number,
			mb_convert_kana($obj->items,'K'),
			$obj->total,
			$obj->tax,
			$obj->total_price,
			$address,
			base_url('front_customer/mypage'),
			base_url('front_contact/'),
		),
		$mail_template->mail_body);
		$result = sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->Admin_mail->show_list();
			$admin_mail_template = $this->Mail_template->get_by_id(4);
			$admin_mail_body = str_replace(array('{{content}}'),array($mail_body),$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->Mail_template->sender);
			}
			if(in_array(false)){
				$admin_result = false;
			}else{
				$admin_result = true;
			}
		}
		return $admin_result;
	}
	
	private function _checklogin($customer){
		if(empty($customer)){
			$this->session->set_flashdata('success','ログインされていません');
			return redirect('front_customer/login_action');
		}else{
			return $customer;
		}
	}
*/
}
