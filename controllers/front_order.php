<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_mail.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/csv.php';

class Front_order extends CI_Controller{
	public $data = array();
	public $deliver_possible_day = '+3 days';
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','myclass','encrypt'));
		$this->load->library('my_mail');
		$this->load->library('my_validation');
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Cart');
		$this->load->model('Tax');
		$this->load->model('Area');
		$this->load->model('Advertise');
		$this->load->model('Advertise_product');
		$this->load->model('Master_days');
		$this->load->model('Customer_info');
		//$this->load->model('Master_mail_magazine');
		$this->load->model('Master_hour');
		$this->load->model('Order');
		$this->load->model('Master_type_account');
		//$this->load->model('Mail_template');
		//$this->load->model('Admin_mail');
		$this->load->model('Cource');
		$this->load->model('Master_takuhai_hours');
		$this->load->model('Order_info');
		$this->load->model('Credit');
		$this->load->model('Master_payment');
		$this->load->model('Address');
		$this->load->model('Box');
		$this->load->model('Takuhai_charge');
		$this->load->model('Charge_kenkoya');
		$this->data['customer'] = $this->session->userdata('customer') ? $this->session->userdata('customer'): $this->session->userdata('no-member');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
//var_dump($this->session->userdata('customer'));
//var_dump($this->session->userdata('no-member'));
	}
	
	public function delivery_info()
	{
		try{
			if(!$carts = $this->session->userdata('carts')){
				return redirect('front_cart/show_cart');
			}
			$messages = array();
			//お届け日限定商品があるかどうか
			foreach($carts as $cart)
			{
				$c = unserialize($cart);
				$product = $this->Advertise_product->get_by_product_id($c->product_id);
				//お届け日限定商品がある場合、期間を表示する
				if(!empty($product->delivery_start_datetime) || !empty($product->delivery_end_datetime))
				{
					$sdate = new DateTime($product->delivery_start_datetime);
					$edate = new DateTime($product->delivery_end_datetime);
					if(!empty($sdate))
					{
						$startstr = $sdate->format('m月d日') . 'から';
					}else{
						$startstr = '';
					}
					if(!empty($edate))
					{
						$endstr = $edate->format('m月d日') . 'までに';
					}
					else
					{
						$endstr = '';
					}
					$messages[] = "{$product->product_name}は{$startstr}{$endstr}お届けする商品です。配送日指定する際はご注意ください。";
				}
			}
			if(count($messages)>0)
			{
				$this->data['error_message'] = $messages;
			}
			
			//sessionからorder_infoを取得
			$order_info = $this->session->userdata('order_info');
			//配達予定日の選択リストを取得
			$takuhai_select_days  = $this->Area->delivery_select_date_for_takuhai($this->deliver_possible_day,$this);
			
			$customer = $this->myclass->_checklogin($this->data['customer']);
			if($customer->username == 'no-member'){
				return redirect('front_order/delivery_info_no_member');
			}
			$this->data['h2title'] = 'お支払/ 配送情報登録';
			$this->data['title'] = '配送情報';
			
			//order_infoがあればorder_infoをセット
			if(!$order_info){
				$this->data['form_data'] = $this->Order_info;
			}else{
			//日付指定なし　delivery_date = 0 の場合
				if($order_info->delivery_date == 0){
					$order_info->delivery = 0;
				}else{
			//日付が指定されている場合
					$order_info->delivery = 1;
				}
			//delivery_date は　takuhai_select_daysのキー
				$order_info->takuhai_select_date = $order_info->delivery_date;
				$this->data['form_data'] = $order_info;
			}
			
			$userdata = $this->Customer->get_by_username($customer);
			//請求先住所を表示させるため$custoemr->address1に住所をセット
			$customer->address1 = $userdata->address1.$userdata->address2;
			$payments = $this->Master_payment->method;
			//配送先情報の表示
			$address= '';
			$destination = $this->session->userdata('destination');
			//別の配送先がある場合session::destination
			if(!empty($destination)){
				$address_obj = $this->Address->get_by_id_and_customer_id($destination,$customer);
				$address = $address_obj->address1 . $address_obj->address2 . ' ' . $address_obj->name . ' ' . '様';
			}else{
			//別の配送先がない場合もしくは0の場合,本登録の住所を表示
				$address = $userdata->address1 . ' ' . $userdata->address2;
			}
			$this->data['address'] = $address;
			//エリア内の会員であるかどうか
			$is_area = $userdata->shop_code != 0 && $userdata->cource_code !=0;
			$this->data['is_area'] = $is_area;
			//エリア内は健康屋の配達可能日のリスト作成
			if($is_area){
				$deliver_day = $this->Cource->get_delivery_day($userdata);
				$select_days =$this->Area->delivery_select_date($deliver_day,$this->deliver_possible_day,$this,$this->Master_days->jdays);
				$this->data['select_days'] = $select_days;
				$this->data['cource_info'] = $deliver_day;
			//エリア内で新規は口座振替用紙のじさんの注釈を表示させる
			//新規の会員かどうかnew_member_flag = 1で新規会員
				$this->data['new_member'] = $userdata->new_member_flag;	
			}else{
			//エリア外はお支払方法から引き落としを外す
				unset($payments[PAYMENT_MEMBER]);
			}
			$this->data['select_days_takuhai'] = $takuhai_select_days;
			$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
			$this->data['days'] = $this->Master_days->jdays;
			//戻り先のパラメータ
			$this->data['param'] = '';
			
			//userdataにお支払方法と、配達日を追加してorder_infoセッションに格納
			/*********************************************************
			*@param payment
			**エリアの場合**
			*@param: select_date 　 宅配エリアコースで決められた宅配日
			**エリア外の場合**
			*@param: delivery    　 宅急便の場合日付を指定するか 0(指定しない) 1(指定する)
			*@param: select_date 　 宅急便の場合の宅配日    0(未選択) 2015-01-01
			*@param: delivery_hour 宅急便の場合の指定時間  0(指定しない) 1(午前中)
			*@param: takuhai      　エリア内で宅急便を希望する　'takuhai'
			*@destination          別の配送先
			*@point 				使用するポイント
			************************************************************/
			if($this->input->post('submit')){
				$payment = $this->input->post('payment');
				$this->form_validation->set_rules('payment','お支払方法','required');
				$this->form_validation->set_message('required','%sを選択してください');
				
				//takuhaiはcheckboxなので初期値を設定
				if(!$this->form_validation->run() === FALSE){
					$order_info = new StdClass();
					$order_info->username = $customer->username;
					$order_info->payment = $payment;
					$order_info->delivery_date = 0;
					//別の配送先が指定さている場合session::userdata('destination')健康屋での配達では指定できない。
					$destination = $this->session->userdata('destination');
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
							//健康屋宅配は別の配送先を指定できない
							$order_info->destination = 0;
							//エリア内で健康屋での宅配の場合、別の配送先が登録されていたらエラーメッセージ
							if(!empty($destination)){
								throw new Exception('宅配スーパー健康屋からの配送では別の配送先を指定できません');
							}
						//エリア内で宅配便が指定されている場合
						}elseif($this->input->post('takuhai') == 'takuhai'){
							$order_info->takuhai = TRUE;
							//日付け指定(delivery = 1)の場合
							if($this->input->post('delivery') == 1){
								$order_info->delivery_date = $this->input->post('takuhai_select_date');
							}
							//エリア内で宅急便希望の場合 時間指定
							$order_info->delivery_hour = $this->input->post('delivery_hour');
							//エリア内で宅急便希望の場合　別の配送先を指定可能？
							$order_info->destination = $destination;
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
						//別の配送先
						$order_info->destination = $destination;
					}
					$this->session->set_userdata('order_info',$order_info);
					
					//クレジットカード決済はカード入力画面に遷移させる,session:order_infoが格納されていたら遷移しない
					if($payment == PAYMENT_CREDIT){
						return redirect('front_order/input_payment');
					}
					return redirect(base_url('front_order/confirm_order'));
				}else{
					$this->data['form_data'] = (object)$this->input->post();
				}
			}
		}catch(Exception $e){
			$this->data['error_message'] = $e->getMessage();
		}
		//$this->session->set_userdata('order_info',$order_info);
		$this->data['success_message']  = $this->session->flashdata('success');
		$this->data['account'] = $this->Master_type_account->account;
		//$this->data['order_info'] = $order_info;
		$this->data['flow_info'] = true;
		//非会員は口座引落しない
		unset($payments[PAYMENT_MEMBER]);
		$this->data['payments'] = $payments;
		$this->load->view('front_order/delivery_info',$this->data);
	}
	
	public function delivery_info_no_member()
	{
		if(!$this->session->userdata('carts')){
			return redirect('front_cart/show_cart');
		}
		$this->data['h2title'] = 'お支払/ 配送情報登録';
		$this->data['title'] = '配送情報';
		//sessionからorder_infoを取得
		$order_info = $this->session->userdata('order_info');
		//order_infoがあればorder_infoをセット
		if(!$order_info){
			$this->data['form_data'] = $this->Order_info;
		}else{
		//日付指定なし　delivery_date = 0 の場合
			if($order_info->delivery_date == 0){
				$order_info->delivery = 0;
			}else{
		//日付が指定されている場合
				$order_info->delivery = 1;
			}
		//delivery_date は　takuhai_select_daysのキー
			$order_info->takuhai_select_date = $order_info->delivery_date;
			$this->data['form_data'] = $order_info;
		}
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
		$this->data['address'] = $userdata->address1.$userdata->address2;
		//非会員は別の住所に配送できない
		$this->data['no_address_flag'] = TRUE;
		
		if($this->input->post('submit')){
			$this->form_validation->set_rules('payment','お支払方法','required');
			$this->form_validation->set_message('required','%sを選択してください');
			if(!$this->form_validation->run() === FALSE){
				$order_info = new StdClass();
				$order_info->takuhai = TRUE;
				$order_info->username = 'no-member';
				//$order_info->bank_name = '';
				//$order_info->type_account = '';
				$order_info->payment = $this->input->post('payment');
				$order_info->delivery_date = 0;
				if($this->input->post('delivery') == 1){
					$order_info->delivery_date = $this->input->post('takuhai_select_date');
				}
				$order_info->delivery_hour = $this->input->post('delivery_hour');
				$order_info->shipping_method = '1';
				//別の配送先は指定できない
				$order_info->destination = 0;
				$this->session->set_userdata('order_info',$order_info);
				if($order_info->payment == PAYMENT_CREDIT){
					return redirect('front_order/input_payment');
				}
				return redirect(base_url('front_order/confirm_order'));
			}else{
				$this->data['form_data'] = (object)$this->input->post();
			}
		}
		//$this->session->set_userdata('order_info',$order_info);
		//$this->data['order_info'] = $order_info;
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->data['flow_info'] = true;
		$payments = $this->Master_payment->method;
		//非会員は口座引落しない
		unset($payments[PAYMENT_MEMBER]);
		$this->data['payments'] = $payments;
		$this->load->view('front_order/delivery_info',$this->data);
	}
	
	public function input_payment()
	{
		$customer = $this->myclass->_checklogin($this->data['customer']);
		//$carts = $this->session->userdata('carts');
		//$total = $this->Advertise->get_total($carts);
		$this->load->model('Master_expire');
		$years = $this->Master_expire->set_expire_year();
		$monthes = $this->Master_expire->set_expire_month();
		$this->data['years'] = $years;
		$this->data['monthes'] = $monthes;
		$this->data['h2title'] = 'クレジットカード情報登録';
		$this->data['title'] = 'クレジットカード情報登録';
		$form_data = $this->Credit;
		if($this->input->post('submit')){
			$form_data = (object)$this->input->post();
			$this->my_validation->validation_credit();
			if(!$this->form_validation->run() === FALSE){
				//$this->session->set_userdata('card_info',$this->Credit);
				/* カード情報をsession:card_info に格納 */
				$card_info = new StdClass();
				$card_info->card_no = $form_data->card_no;
				$card_info->method = '1';
				$card_info->expire = $form_data->expire_month . $form_data->expire_year;
				$card_info->security_code = $form_data->security_code;
				$this->session->set_userdata('card_info',$card_info);
				return redirect('front_order/confirm_order');
			}
		}
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->data['form_data'] = $form_data;
		$this->load->view('front_order/input_payment',$this->data);
	}
	
	public function use_point()
	{
		if(!$order_info = $this->session->userdata('order_info'))
		{
			return redirect('front_order/delivery_info');
		}
		if(!$carts = $this->session->userdata('carts'))
		{
			return redirect('front_order/delivery_info');
		}
		$customer = $this->myclass->_checklogin($this->data['customer']);
		$userdata = $this->Customer->get_by_username($customer);
		$this->data['h2title'] = 'ポイントを使う';
		$this->data['title'] = 'ポイントを使う';
		$this->data['point'] = $userdata->point;
		$form_data = new StdClass();
		$form_data->use_point = '';
		if($this->input->post('submit')){
			try{
				$this->form_validation->set_rules('use_point','ポイント','required|numeric');
				$this->form_validation->set_message('required',"%sが入力されていません");
				$this->form_validation->set_message('numeric','%sは数字で入力してください');
				if(!$this->form_validation->run() === FALSE){
					$form_data = (object)$this->input->post();
					$total = $this->Advertise->get_total($carts);
					$this->_check_point($form_data->use_point,$this->data['point'],$total->total_price);
					//order_info::pointにセットする
					$order_info->point = (int)$form_data->use_point;
					$this->session->set_userdata('order_info',$order_info);
					return redirect('front_order/confirm_order');
				}
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
		}
		$this->data['form_data'] = $form_data;
		$this->load->view('front_order/use_point',$this->data);
	}
		
	public function confirm_order()
	{
		$customer = $this->myclass->_checklogin($this->data['customer']);
		$this->data['h2title'] = 'ご注文の最終確認画面';
		$this->data['title'] = 'ご注文確認';
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$this->data['payments'] = $this->Master_payment->method;
		if(!$carts = $this->session->userdata('carts')){
			$this->session->set_flashdata('error','カートにはなにも入っていません');
			return redirect(base_url('front_cart/show_cart'));
		}
		if(!$order_info = $this->session->userdata('order_info')){
			$this->session->set_flashdata('error','配達情報が入力されていません');
			return redirect(base_url('front_order/delivery_info'));
		}
		
		
		//お支払がクレジットカードなのにカード情報がsessionになかったら戻る
		if($order_info->payment == PAYMENT_CREDIT){
			if(!$card_info = $this->session->userdata('card_info')){
				$this->session->set_flashdata('error','カード情報が入力されていません');
				return redirect(base_url('front_order/input_payment'));
			}
		}
		
		//userdta : 会員はユーザーIDから情報を取り出す、非会員はセッションno-memberから情報をとりだす。
		if($customer->username != 'no-member'){
			$userdata = $this->Customer->get_by_username($customer);
			$param = '';	//戻り先のパラメータ
		}elseif($customer->username == 'no-member'){
			$userdata = new StdClass();
			$userdata = $customer;
			$param = '_no_member';	//戻り先のパラメータ
		}
		$this->data['param'] = $param;
		//別の配送先がある(isset(order_info->address_id)　&& != 0
		//上記の場合別の配送先をセットする
		//order_info->address_id = 0　は請求先住所
		$address = '';
		$name = '';
		$tel = '';
		if(isset($order_info->destination) && $order_info->destination != 0){
			$address_obj = $this->Address->get_by_id_and_customer_id($order_info->destination,$customer);
			$address = $address_obj->address1 . $address_obj->address2;
			$name = $address_obj->name;
			$tel = $address_obj->tel;
		}else{
			$address = $userdata->address1.$userdata->address2;
			$name = $userdata->name;
			$tel = $userdata->tel;
		}
		$form_data = array(
			'name'=>$name,
			'address1'=>$address,
			'tel'=>$tel,
			'delivery_date'=>$order_info->delivery_date,
			'payment'=>$order_info->payment,
			'takuhai'=>$order_info->takuhai,
			'delivery_method'=>$order_info->takuhai,
		);
		$form_data = (object)$form_data;
		
		//配達時間が渡されているときは表示する
		if(isset($order_info->delivery_hour)){
			$form_data->delivery_hour = $order_info->delivery_hour;
		}
		
		//userはポイント残高表示$userdata::pointポイント残高 $order_info::point ポイント利用
		$use_point = (!empty($order_info->point)) ? $order_info->point : 0;
		if($customer->username != 'no-member'){
			$this->data['point'] = (int)$userdata->point - $use_point;
		}
		
		//料金計算
		if(!empty($carts)){
			if($order_info->takuhai){
				//配送料金計算
				$boxes = $this->Box->get_boxes($carts);
				//$pref_id = $this->Customer->get_pref_id($userdata);
				$pref_id = $userdata->pref_id;
				$charge_price = $this->Takuhai_charge->get_total_charge($boxes,$pref_id);
			}else{
				$price = $this->Charge_kenkoya->get_charge();
				$tax = $this->Tax->get_current_tax();
				$charge_price = $price * (1 + $tax);
			}
			//order_infoに追加
			$order_info->charge_price = $charge_price;
			$this->session->set_userdata('order_info',$order_info);
			//合計金額算出
			$total = $this->Advertise->get_total($carts,$order_info);
			$this->data['total'] = $total;
			$this->data['list_product'] = $total->list_product;
		}
		
		//個々の商品が販売可能かどうかしらべる。もし販売対象以外のがあればエラーメッセージ
		//配達日が指定されている場合で、商品に配達期間が指定されている場合のチェック
		$error_messages = array();
		foreach($this->data['list_product'] as $item)
		{
			//商品販売可能かどうか販売期間かどうか
			if(!$this->Advertise_product->check_on_sale($item->id) || !$this->Advertise_product->validate_sale_period($item->id))
			{
				$error_messages[] = "{$item->product_name}は現在お取扱いしていません。カートから削除してください。";
			}
			//配達期間かどうか
			if($order_info->delivery_date!=0) //配達日が指定されている
			{
				$deli_date = new DateTime($order_info->delivery_date);
				if(!$this->Advertise_product->check_delivery_limit_date($deli_date,$item->id))
				{
					$sddate = new DateTime($item->delivery_start_datetime);
					$eddate = new DateTime($item->delivery_end_datetime);
					$sstr = $sddate ? $sddate->format('Y年m月d日') : '';
					$estr = $eddate ? $eddate->format('Y年m月d日') : '';
					$error_messages[] = "{$item->product_name}の配達指定期間内({$sstr}~{$estr})に配達日を指定してください";
				}
			}
		}
		if(count($error_messages)>0)
		{
			$this->data['error_messages'] = $error_messages;
		}
		
		//カード情報がある場合カード番号の下4桁表示
		if($order_info->payment == PAYMENT_CREDIT){
			$bottom_number = substr($card_info->card_no,-4);
			$this->data['card_number'] = 'XXXX-XXXX-XXXX-' . $bottom_number;
		}
		//$this->data['charge_price'] = $total->charge_price;
		$this->data['form_data'] = $form_data;
		$this->data['days'] = $this->Master_days->jdays;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['flow_confirm'] = true;
		$this->load->view('front_order/confirm_order',$this->data);
	}
	
	public function order_process()
	{
		try{
			$customer = $this->myclass->_checklogin($this->data['customer']);
			if(!$carts = $this->session->userdata('carts')){
				$this->session->set_flashdata('error','カートにはなにも入っていません');
				return redirect('front_cart/show_cart');
			}
			
			if(!$order_info = $this->session->userdata('order_info')){
				$this->session->set_flashdata('error','配達情報が入力されていません');
				return redirect(base_url('front_order/delivery_info'));
			}
			
			if($order_info->payment == PAYMENT_CREDIT){
				if(!$card_info = $this->session->userdata('card_info')){
					$this->session->set_flashdata('error','クレジットカード情報が入力されていません');
					return redirect('front_order/delivery_info');
				}
			}
			
			//商品個別の販売可否情報を取得する
			foreach($carts as $cart)
			{
				$c = unserialize($cart);
				if(!$this->Advertise_product->check_on_sale($c->product_id) || !$this->Advertise_product->check_on_sale($c->product_id))
				{
					$this->session->set_flashdata('error','ご購入いただけない商品があります。カートから削除してください。');
					return redirect('front_order/confirm_order');
				}
				if($order_info->delivery_date!= 0)
				{
					$deli_date = new DateTime($order_info->delivery_date);
					if(!$this->Advertise_product->check_delivery_limit_date($deli_date,$c->product_id))
					{
						$product = $this->Advertise_product->get_by_product_id($c->product_id);
						$sdate = new DateTime($product->delivery_start_datetime);
						$edate = new DateTime($product->delivery_end_datetime);
						$sstr = $sdate ? $sdate->format('m月d日') : '';
						$estr = $edate ? $edate->format('m月d日') : '';
						$this->session->set_flashdata('error',"{$product->product_name}は{$sstr}~{$estr}の間にお届けする商品です。配達指定日を変更して下さい。");
						return redirect('front_order/confirm_order');
					}
				}
			}
			$point = isset($order_info->point) ? $order_info->point : 0;

			
			//健康屋の配達で別の配送先が指定されていないこと
			if(!$order_info->takuhai){
				if(!empty($order_info->destination)){
					return redirect('front_order/delvery_info');
				}
			}
			
			if($this->input->post('submit')){
			//order_numberの生成
				$order_number = $this->Order->create_order_number($customer);
			//session:cartsを計算して税抜価格を取得]
				$total = $this->Advertise->get_total($carts, $order_info);
			//クレジットカードの処理
				if($order_info->payment == PAYMENT_CREDIT){
					$this->Credit->job_cd = 'AUTH';
					$this->Credit->order_id = $order_number;
					$this->Credit->amount = $total->discounted;
					$this->Credit->tax = $total->tax_price + $total->charge_price;
					$this->Credit->card_no = $card_info->card_no;
					$this->Credit->method = $card_info->method;
					$this->Credit->expire = $card_info->expire;
					$this->Credit->security_code = $card_info->security_code;
					$output = $this->Credit->exec_credit();
					if($output->isErrorOccurred()){
						$messages = $this->Credit->getErrorMessages($output);
						$this->session->set_flashdata('error',$messages);
						return redirect('front_order/input_payment');
					}
				}
			
				if($customer->username != 'no-member'){
					$userdata = $this->Customer->get_by_username($customer);
					$data=array(
						'customer_id'=>$userdata->id,
						'shop_code'=>$userdata->shop_code,
						'customer_code'=>$userdata->code,
						'address'=>$userdata->address1.$userdata->address2,
						'address_id'=>$order_info->destination,
						'cource_code'=>$userdata->cource_code,
						'payment'=>$order_info->payment,
						'order_number'=>$order_number,
						'total_price'=>$total->total_price,
						'use_point'=>$total->use_point,
						'discounted'=>$total->discounted,
						'tax'=>$total->tax_price,
						'amount'=>$total->amount,
						'create_date'=>date('Y-m-d H:i:s'),
						'delivery_date'=>$order_info->delivery_date,
						'delivery_hour'=>$order_info->delivery_hour,
						'delivery_method'=>$order_info->takuhai,
					);
			//非会員はsessionからデータを取り出す 情報をdbに格納
					}else{
						//$userdata = $this->session->userdata('customer');
						$user_data = array(
							//'code'=>$customer->code,
							'name'=>$customer->name,
							'furigana'=>$customer->furigana,
							'tel'=>$customer->tel,
							'tel2'=>$customer->tel2,
							'no_member_email'=>$customer->email,
							'zipcode'=>$customer->zipcode,
							'address1'=>$customer->address1,
							'address2'=>$customer->address2,
							'member_flag'=>0,
						//ヒカイインはsessionに適当なコードが入っているが000000にする
							'code'=>'000000',
							'create_date'=>date('Y-m-d H:i:s'),
						);
						//customersに格納
						$user_id = $this->Customer->save($user_data);
						$data = array(
							'customer_id'=>$user_id,
							'shop_code'=>0,
							'address'=>$user_data['address1'].$user_data['address2'],
							'address_id'=>0,
							'cource_code'=>0,
							'payment'=>$order_info->payment,
							'order_number'=>$order_number,
							'total_price'=>$total->total_price,
							'tax'=>$total->tax_price,
							'amount'=>(int)$total->total_price + (int)$total->tax_price,
							'create_date'=>date('Y-m-d H:i]s'),
							'delivery_date'=>$order_info->delivery_date,
							'delivery_hour'=>$order_info->delivery_hour,
							'delivery_method'=>$order_info->takuhai,
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
						'order_number'=>$order_number,
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
				//ポイント更新
				if($customer->username != 'no-member')
				{
					$this->Customer->reduct_point($customer,$point);
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
					//dbに格納した情報から取得する	
					$order = $this->Order->get_by_id($last_id);
					$details = $this->Order->get_by_order_number($order->order_number);
					$text = '';
					//$total = array();
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
						//$total[] = $item->sale_price * $item->quantity;
					}
					//$total = array_sum($total);
					//$total = $total - $point;
					//$tax_rate = $this->Tax->get_tax_by_date($order->create_date);
					//$tax  = floor($total * $tax_rate);
					//$total_price = $total + $tax;
				
					//配送先情報の取得order::address_id=0 はCustomer::address1から
					//order::address_id!=1はAddress::addressから
					$address = '';
					if($order->address_id == 0){
						$address = $customer->address1.$customer->address2;
					}else{
						$address = $this->Address->get_by_id($order->address_id);
					}
					
					$text_items = new StdClass();
					$text_items->order_number = $order->order_number;
					$text_items->items  = $text;
					$text_items->total = $total->total_price;
					$text_items->tax = $total->tax_price;
					$text_items->total_price = $total->amount;
					$text_items->address = $address;
					$text_items->point = $point;
					$text_items->charge_price = $total->charge_price;
					$result = $this->my_mail->order_mail($customer,$text_items);
					$this->session->set_flashdata('success','注文を確定しました');
					$this->session->set_userdata('order',$order);
					return redirect(base_url('front_order/complete'));
				}
			}
		}catch(Exception $e){
			log_message('error',$e->getMessage());
			show_404();
		}
	}
	
	public function complete()
	{
		$this->session->unset_userdata('carts');
		$customer = $this->myclass->_checklogin($this->data['customer']);
		$this->data['h2tilte'] = 'ご注文完了';
		$this->data['title'] = '注文完了';
		$order = $this->session->userdata('order');
		$this->data['order_number'] = $order->order_number;
		$this->session->unset_userdata('order');
		//$this->session->unset_userdata('order_price');
		$this->session->unset_userdata('order_info');
		$this->session->unset_userdata('no-member');
		$this->session->unset_userdata('card_info');
		$this->session->unset_userdata('destination');
		$this->data['flow_complete'] = true;
		$this->load->view('front_order/complete',$this->data);
	}
	
	private function _check_point($str,$point,$total = 0)
	{
		if((int)$str > (int)$point){
			throw new Exception('ポイントが利用残高を超えています。');
		}
		if((int)$str > (int)$total){
			throw new Exception('ポイントが購入金額を超えています');
		}
	}		
}
