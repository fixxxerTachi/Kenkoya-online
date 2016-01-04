<?php
class My_mail{
	public $ci;
	public $contact_url;
	public $footer;
	public $site_url;
	public function __construct()
	{
		$this->ci = get_instance();
		$this->ci->load->model('Mail_template');
		$this->ci->load->model('Admin_mail');
		$this->ci->load->model('Customer');
		$this->ci->load->model('Mail_footer');
		$this->footer = $this->ci->Mail_footer->content;
		$this->contact_url = site_url('contact');
		$this->site_url = site_url();
	}
	
	public function sendMail($to, $subject, $body, $from_email,$from_name)
	{
		mb_language("ja");
		mb_internal_encoding('UTF-8');
		$headers  = "MIME-Version: 1.0 \n" ;
		$headers .= "From: " . mb_encode_mimeheader($from_name,'ISO-2022-JP') ."<".$from_email."> \n";
		$headers .= "Reply-To: " ."".mb_encode_mimeheader($from_name,"ISO-2022-JP") ."" ."<".$from_email."> \n";
		$headers .= "Content-Type: text/plain;charset=ISO-2022-JP \n";
		$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");
		$sendmail_params  = "-f$from_email";
		//$subject = mb_convert_encoding($subject,'ISO-2022-JP','UTF-8');
		$subject = mb_encode_mimeheader($subject);
		$result = mail($to, $subject, $body, $headers, $sendmail_params);
		return $result;
	}
	
	public function order_mail($customer = null, $obj = null)
	{
		//お客さま用メール送信処理
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(3);
		$name = $customer->name;
		//$address = '〒' . substr($customer->zipcode,0,3).'-'.substr($customer->zipcode,3,6)."\n".$customer->prefecture.$customer->address1.$customer->address2;
		$address = $customer->address1.$customer->address2;
		$mypage_url = site_url('mypage');
		//$contact_url = site_url('contact');
		//配送先の住所を取得
		
		$mail_body = str_replace(
		array(
			'{{name}}',
			'{{order_number}}',
			'{{order_items}}',
			'{{total}}',
			'{{tax}}',
			'{{charge_price}}',
			'{{total_price}}',
			'{{address}}',
			'{{billing_destination}}',
			'{{payment}}',
			'{{my_bank}}',
			'{{mypage_url}}',
			'{{footer}}',
			'{{contact_url}}',
			'{{site_url}}',
		),array(
			$name,
			$obj->order_number,
			mb_convert_kana($obj->items,'K'),
			$obj->total,
			$obj->tax,
			$obj->charge_price,
			$obj->total_price,
			$obj->address,
			$obj->billing_destination,
			$obj->payment->method_name,
			$obj->my_bank,
			site_url('mypage'),
			$this->footer,
			$this->contact_url,
			$this->site_url,
		),
		$mail_template->mail_body);
		$result = $this->sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->ci->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->ci->Admin_mail->show_list();
			$admin_mail_template = $this->ci->Mail_template->get_by_id(4);
			$admin_mail_body = str_replace(array('{{content}}'),array($mail_body),$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = $this->sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->ci->Mail_template->sender);
			}
			if(in_array(false,$admin_result)){
				$admin_result = false;
			}else{
				$admin_result = true;
			}
		}
		return $admin_result;
	}
	
	public function mail_shipped($customer = NULL, $obj = NULL)
	{
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(MAIL_SHIPPED);
		$name = $customer->name;
		$address = $customer->address1 . $customer->address2;
		$mypage_url = site_url('mypage');
		$mail_body = str_replace(
			array(
				'{{name}}',
				'{{order_number}}',
				'{{order_items}}',
				'{{total}}',
				'{{tax}}',
				'{{charge_price}}',
				'{{total_price}}',
				'{{address}}',
				'{{mypage_url}}',
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),
			array(
				$name,
				$obj->order_number,
				mb_convert_kana($obj->items,'K'),
				$obj->total,
				$obj->tax,
				$obj->charge_price,
				$obj->total_price,
				$obj->address,
				site_url('mypage'),
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
			$mail_template->mail_body);
		return $result = $this->sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->ci->Mail_template->sender); 
	}
	
	public function send_mail_change_info($customer = null , $new_info = null, $old_info=null,$kind=null)
	{
		//お客さま用メール送信処理
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(5);
		$url_mypage = site_url('mypage');
		//$url_contact = site_url('contact');
		$mail_body = str_replace(
			array(
				'{{name}}',
				'{{kind}}',
				'{{old_info}}',
				'{{new_info}}',
				'{{mypage_url}}',
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),array(
				$customer->name,
				$kind,
				$old_info,
				$new_info,
				$url_mypage,
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
		$mail_template->mail_body);
		$result = $this->sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->ci->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->ci->Admin_mail->show_list();
			$admin_mail_template = $this->ci->Mail_template->get_by_id(6);
			$admin_mail_body = str_replace(
				array('{{code}}','{{name}}','{{kind}}','{{content}}'),
				array($customer->code,$customer->name,$kind,$mail_body),
			$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = $this->sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->ci->Mail_template->sender);
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
	
	public function send_mail_password($customer,$hashed_word)
	{
		$encoded = urlencode($hashed_word);
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(11);
		//$url_contact = site_url('contact');
		$url = site_url("front_customer/reset_password?key={$encoded}");
		$mail_body = str_replace(
			array(
				'{{url}}',
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),
			array(
				$url,
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
			$mail_template->mail_body
		);
		$result = $this->sendMail($customer->email, $mail_template->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
	}
	
	public function send_mail_username($customer,$pre_username)
	{
		//$encoded = urlencode($hashed_word);
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(12);
		//$url_contact = site_url('contact');
		//$url = site_url("front_customer/reset_username?key={$encoded}");
		$mypage_url = site_url('mypage');
		$mail_body = str_replace(
			array(
				'{{mypage_url}}',
				'{{url_contact}}',
				'{{pre_username}}',
				'{{name}}',
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),
			array(
				$mypage_url,
				$this->contact_url,
				$pre_username,
				$customer->name,
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
			$mail_template->mail_body
		);
		$result = $this->sendMail($customer->email, $mail_template->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
	}
	
	public function send_mail_login_info($obj,$password,$kind=NULL){
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(13);
		//$url_contact = site_url('contact');
		$mypage_url = site_url('mypage');
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
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),
			array(
				$mypage_url,
				$url_contact,
				$obj->name,
				$obj->username,
				$password,
				$obj->zipcode,
				$obj->address1.$obj->address2,
				$obj->tel,
				$obj->email,
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
			$mail_template->mail_body
		);
		$result = $this->sendMail($obj->email,$mail_template->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
	}
	
	public function send_mail_change_order($customer = null , $obj = null,$kind=null)
	{
		//お客さま用メール送信処理
		$customer = $this->ci->Customer->get_by_username($customer);
		$send_address = $this->ci->Mail_template->send_address;
		$mail_template = $this->ci->Mail_template->get_by_id(7);
		$url_mypage = site_url('mypage');
		$url_contact = site_url('contact');
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
				'{{mypage_url}}',
				'{{footer}}',
				'{{contact_url}}',
				'{{site_url}}',
			),array(
				$customer->name,
				$customer->code,
				$obj->order_number,
				$url_mypage,
				$this->footer,
				$this->contact_url,
				$this->site_url,
			),
		$mail_template->mail_body);
		$result = $this->sendMail($customer->email, $mail_template->mail_title, $mail_body, $send_address, $this->Mail_template->sender);
		//管理者用メール送信処理
		if($result){
			$admin_mails = $this->ci->Admin_mail->show_list();
			$admin_mail_template = $this->ci->Mail_template->get_by_id(8);
			$admin_mail_body = str_replace(
				array('{{code}}','{{name}}','{{kind}}','{{content}}'),
				array($customer->code,$customer->name,$kind,$mail_body),
			$admin_mail_template->mail_body);
			$admin_result = array();
			foreach($admin_mails as $mail){
				$admin_result[] = $this->sendMail($mail->email,$admin_mail_template->mail_title,$admin_mail_body,$send_address,$this->ci->Mail_template->sender);
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
	
	public function send_mail_for_contact($data)
	{
		$send_address = $this->ci->Mail_template->send_address;
		$template_for_user = $this->ci->Mail_template->get_by_id(QUESTION_FOR_USER);
		$template_for_admin = $this->ci->Mail_template->get_by_id(QUESTION_FOR_ADMIN);
		$name = $data->name;
		$email = $data->email;
		$categories = $this->ci->Contact->show_list_category();
		$category = $categories[$data->category_id];
		$content = $data->content;
		//for customer
		$mail_body = str_replace(
			array(
				'{{name}}',
				'{{content}}',
				'{{category}}',
				'{{site_url}}',
				'{{contact_url}}',
				'{{footer}}',
			),
			array(
				$name,
				$content,
				$category,
				$this->site_url,
				$this->contact_url,
				$this->footer,
			),
			$template_for_user->mail_body
		);
		$result1 = $this->sendMail($data->email,$template_for_user->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
		//for admin
		$mail_body = str_replace(array('{{name}}','{{email}}','{{content}}','{{category}}'),array($name,$email,$content,$category),$template_for_admin->mail_body);
		$result2 = $this->sendMail($this->ci->Mail_template->admin_address,$template_for_admin->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
		return ($result1 && $result2);
	}
	
	/*
	public function order_change_send_mail($customer = null , $obj = null,$kind=null)
	{
		//お客さま用メール送信処理
		$customer = $this->Customer->get_by_username($customer);
		$send_address = $this->Mail_template->send_address;
		$mail_template = $this->Mail_template->get_by_id(7);
		$url_mypage = site_url('front_customer/mypage');
		$url_contact = site_url('contact');
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


	public function send_mail_order($userdata,$orderdata,$newdata,$type)
	{
		$send_address = $this->ci->Mail_template->send_address;
		$template_for_user = $this->ci->Mail_template->get_by_id(ORDER_CHANGE_FOR_USER);
		$template_for_admin = $this->ci->Mail_template->get_by_id(ORDER_CHANGE_FOR_ADMIN);
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
		$result1 = sendMail($userdata->email,$template_for_user->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
		//for admin
		$mail_body = str_replace(array('{$name}','{$customer_id}','{$content}','{$content1}'),array($name,$customer_id,$content,$content1),$template_for_admin->mail_body);
		$result2 = sendMail($this->ci->Mail_template->admin_address,$template_for_admin->mail_title,$mail_body,$send_address,$this->ci->Mail_template->sender);
		return ($result1 && $result2);
	}
*/
}