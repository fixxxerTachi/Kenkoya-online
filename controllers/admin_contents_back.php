<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';

class Admin_contents extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper('form');
		$this->load->model('Mail_template');
		$this->load->model('Master_mail_reciever');
		$this->data['side'][site_url('/admin_contents/list_mail_template')] ='メールテンプレート管理';
		$this->data['side']['admin'][site_url('/admin_contents/add_mail_template')] ='メールテンプレート追加';
		$this->data['side'][site_url('/admin_contents/mail_test')] = 'メール送信テスト';
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function mail_test()
	{
		$this->data['h2title'] = 'メール送信テスト';
		$this->data['message'] = 'メールアドレスを入力して下さい';
		if(isset($_POST['submit'])){
			$mail_address=$_POST['mail_address'];
			$result=sendMail($mail_address,'こんにちわ','こんにちわ内容です','info@akatome.co.jp','宅配スーパー健康屋');
			if($result){
				$this->session->set_flashdata('success','メールを送信しました');
				redirect(site_url('/admin_contents/mail_test'));
				$this->data['error_message'] = 'メール送信に失敗しました';
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_mail_test.php',$this->data);		
	}
	
	public function list_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレート管理';
		$this->data['result'] = $this->Mail_template->show_list();
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		if($show_detail){
			$detail_result = $this->Mail_template->get_by_id($id);
			$this->data['show_detail'] = $show_detail;
			$this->data['detail_result'] = $detail_result[0];
		}
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_mail_template.php',$this->data);
	}
	
	public function edit_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレートの変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$id = $this->uri->segment(3);
		$result= $this->Mail_template->get_by_id($id);
		$result=$result[0];
		$this->data['form_data'] = $result;
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		if($this->input->post('submit')){
			$template_name = $this->input->post('template_name');
			$for_customer = $this->input->post('for_customer');
			$mail_title = $this->input->post('mail_title');
			$mail_body = $this->input->post('mail_body');
		//add_validateion
			if(empty($template_name)){
				$result->template_name = $template_name;
				$result->for_customer = $for_customer;
				$result->mail_title = $mail_title;
				$result->mail_body = $mail_body;
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$data=array(
					'template_name'=>$template_name,
					'for_customer'=>$for_customer,
					'mail_title'=>$mail_title,
					'mail_body'=>$mail_body,
				);
				$result = $this->Mail_template->update($id,$data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_contents/list_mail_template'));
			}
		}
		$this->load->view('admin_contents/admin_add_mail_template',$this->data);
	}
		
	public function add_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレートの追加';
		$form_data= (object)array(
			'template_name'=>'',
			'for_customer'=>'',
			'mail_title'=>'',
			'mail_body'=>'',
		);
		$this->data['form_data'] = $form_data;
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		if($this->input->post('submit')){
			$template_name = $this->input->post('template_name');
			$for_customer = $this->input->post('for_customer');
			$mail_title = $this->input->post('mail_title');
			$mail_body = $this->input->post('mail_body');
		//add validation
		
			$this->form_validation->set_rules('template_name','表示名','required');
			$this->form_validation->set_rules('mail_title','件名','required');
			$this->form_validation->set_rules('mail_body','メール本文','required');
			$this->form_validation->set_message('required','%sが未入力です');	
			if($this->form_validation->run() == FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data->template_name = $this->input->post('template_name');
				$form_data->for_customer = $this->input->post('for_customer');
				$form_data->mail_title = $this->input->post('mail_title');
				$form_data->mail_body = $this->input->post('mail_body');
			/*
				$form_data = (object)array(
					'template_name'=>$this->input->post('template_name'),
					'for_customer'=>$this->input->post('for_customer'),
					'mail_title'=>$this->input->post('mail_template'),
					'mail_body'=>$this->input->post('mail_body'),
				);
				$this->data['form_data'] = $form_data;
			*/
			}else{
				$data = array(
					'template_name'=>$template_name,
					'for_customer'=>$for_customer,
					'mail_title'=>$mail_title,
					'mail_body'=>$mail_body,
				);
				$result=$this->Mail_template->save($data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_contents/list_mail_template'));
			}
		}
		$this->data['result'] = $this->Mail_template->show_list();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_add_mail_template.php',$this->data);		
	}
	
	public function delete_mail_template()
	{
		$id = $this->uri->segment(3);
		$this->Mail_template->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(site_url('/admin_contents/list_mail_template'));
	}
	
}
