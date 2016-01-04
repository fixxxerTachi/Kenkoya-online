<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_mail.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/csv.php';

class Front_contact extends CI_Controller{
	public $data = array();
	public $deliver_possible_day = '+3 days';
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination'));
		$this->load->helper('form');
		$this->load->model('Contact');
		$this->load->model('Mail_template');
		$this->data['customer'] = $this->session->userdata('customer');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$this->data['title'] = 'お問い合わせ';
		$this->load->view('front_contact/index',$this->data);
	}
	
	public function contact()
	{
		$this->data['h2title'] = 'お問い合わせ';
		$this->data['title'] = 'お問い合わせ';
		$form_data = $this->Contact;
		if($this->session->userdata('contact')){
			$form_data = $this->session->userdata('contact');
		}
		$this->data['category_list'] = $this->Contact->show_list_category();
		if($this->input->post('submit')){
			$form_data=array(
				'name'=>$this->input->post('name'),
				'email'=>$this->input->post('email'),
				'email_confirm'=>$this->input->post('email_confirm'),
				'category_id'=>$this->input->post('category_id'),
				'content'=>$this->input->post('content')
			);
			$form_data=(object)$form_data;
			$this->form_validation->set_rules('name','お名前','required|maxlength[100]');
			$this->form_validation->set_rules('email','メールアドレス','required|maxlength[100]');
			$this->form_validation->set_rules('email_confirm','確認用メールアドレス','required|maxlength[100]');
			$this->form_validation->set_rules('content','質問内容','required');
			$this->form_validation->set_message('required','%sが入力されていません');
			$this->form_validation->set_message('maxlength','%sは%s文字以内で入力して下さい');
			if(!$this->form_validation->run() === FALSE){
				if($form_data->email_confirm == $form_data->email){
					$this->session->set_userdata('contact',$form_data);
					return redirect(base_url('contact/confirm_contact'));
				}else{
					$this->data['error_message'] = 'メールアドレスと確認用メールアドレスが異なります';
				}
			}
		}
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('front_contact/contact',$this->data);
	}
	
	public function confirm_contact()
	{
		$this->load->library('my_mail');
		$this->data['h2title'] = 'お問い合わせフォーム：確認画面';
		$this->data['title'] = 'お問い合わせフォーム：確認画面';
		$form_data = $this->session->userdata('contact');
		if($this->input->post('submit')){
			$db_data = array(
				'name'=>$form_data->name,
				'email'=>$form_data->email,
				'category_id'=>$form_data->category_id,
				'content'=>$form_data->content,
				'create_datetime'=>date('Y-m-d H:i:s'),
			);
			$this->Contact->save($db_data);
			if($this->my_mail->send_mail_for_contact($form_data)){
				$this->session->unset_userdata('contact');
				$this->session->set_flashdata('success','ご質問を承りました');
				return redirect(base_url('contact/complete'));
			}else{
				$this->data['error_message'] = 'メールの送信に失敗しました<br>メールアドレスをお確かめのうえ再度やりなおしてください。';
			}
		}
		$this->data['form_data'] = $form_data;
		$this->data['category_list'] = $this->Contact->show_list_category();
		$this->load->view('front_contact/confirm_contact',$this->data);
	}
	
	public function takuhai_member()
	{
		$this->load->model('Contact_takuhai');
		$this->load->library('my_validation');
		$this->data['h2title'] = '宅配サービス会員登録申し込み';
		$this->data['title'] = $this->data['h2title'];
		$sess_member = $this->session->userdata('takuhai_member');
		$form_data = $sess_member ?: $this->Contact_takuhai;
		if($this->input->post('submit'))
		{
			$this->my_validation->validation_rules();
			$this->form_validation->set_rules('age','年齢','max_length[3]|numeric')	;
			if($this->form_validation->run() === FALSE)
			{
				$form_data = (object)$this->input->post();
			}
			else
			{
				$form_data = (object)$this->input->post();
				$this->session->set_userdata('takuhai_member',$form_data);
				return redirect('contact/takuhai_member_confirm');
			}
		}
		$this->data['form_data'] = $form_data;
		$this->load->view('front_contact/takuhai_contact',$this->data);
	}
	
	public function takuhai_member_confirm()
	{
		if(!$this->session->userdata('takuhai_member'))
		{
			return redirect('contact/takuhai_member');
		}
		$this->load->model('Contact_takuhai');
		$this->data['h2title'] = '宅配サービス会員登録申し込み入力内容確認';
		$this->data['title'] = $this->data['h2title'];
		$form_data = $this->session->userdata('takuhai_member');
		$form_data->zipcode = $form_data->zipcode1 . $form_data->zipcode2;
		if($this->input->post('submit'))
		{
			return redirect('contact/takuhai_member_complete');
		}
		$this->data['form_data'] = $form_data;
		$this->load->view('front_contact/confirm_takuhai_contact',$this->data);
	}
	
	public function takuhai_member_complete()
	{
		
	}
	
/*
	private function send_mail($data)
	{
		$send_address = $this->Mail_template->send_address;
		$template_for_user = $this->Mail_template->get_by_id(QUESTION_FOR_USER);
		$template_for_admin = $this->Mail_template->get_by_id(QUESTION_FOR_ADMIN);
		$name = $data->name;
		$email = $data->email;
		$categories = $this->Contact->show_list_category();
		$category = $categories[$data->category_id];
		$content = $data->content;
		//for customer
		$mail_body = str_replace(array('{{name}}','{{content}}','{{category}}'),array($name,$content,$category),$template_for_user->mail_body);
		$result1 = sendMail($data->email,$template_for_user->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		//for admin
		$mail_body = str_replace(array('{{name}}','{{email}}','{{content}}','{{category}}'),array($name,$email,$content,$category),$template_for_admin->mail_body);
		$result2 = sendMail($this->Mail_template->admin_address,$template_for_admin->mail_title,$mail_body,$send_address,$this->Mail_template->sender);
		return ($result1 && $result2);
	}
*/
	
	public function complete()
	{
		$this->data['title'] = 'お問い合わせ完了';
		$this->load->view('front_contact/complete',$this->data);
	}
	
	public function takuhai_member_complete()
	{
		$this->data['h2title'] = '宅配サービス会員登録申し込み受付完了';
		$this->data['title'] = $this->data['h2title'];
		$sess_member = $this->session->userdata('takuhai_member');
var_dump($sess_member);
	}
}
