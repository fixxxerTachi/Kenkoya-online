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
		$this->load->library(array('session','form_validation','pagination'));
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Customer_info');
		$this->load->model('Master_mail_magazine');
		$this->load->model('Master_hour');
		$this->load->model('Personal');
		$this->load->model('Master_show_flag');
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
		$form_data = $this->Customer;
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
		$this->data['form_data'] = $form_data;
		if($this->input->post('post_zip')){
			$zipcode = $this->input->post('zipcode');
			$result = $this->Customer->get_area_by_zip($zipcode);
			if(count($result) > 0){
				$this->session->set_userdata(array('result'=>$result[0]));
			}else{
				$this->session->set_userdata(array('result'=>'no_data'));
			}
			redirect(site_url('/admin_customer/add_customer'));

		}
		if($this->input->post('submit')){
			$input_data = array(
				'nmae' => $this->input->post('name'),
				'furigana' => $this->input->post('furigana'),
				'email' =>$this->input->post('email'),
				'zipcode' => $this->input->post('zipcode'),
				'prefecture' => $this->input->post('prefecture'),
				'address1' => $this->input->post('address1'),
				'address2' => $this->input->post('address2'),
				'tel' => $this->input->post('tel'),
				'age' => $this->input->post('age'),
				'create_date' => date('Y-m-d H:i:s'),
			);
			$this->form_validation->set_rules('familyname','姓','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data = (object)$input_data;
			}else{
				$db_data = $input_data;
				$this->Customer->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_customer'));
			}		
		}
		$this->data['succsss_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/add_customer.php',$this->data);
	}
	
	public function list_customer()
	{
		$this->data['h2title'] = '会員一覧';
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		$form_data = array(
			'code'=>'',
			'name'=>'',
		);

		if($this->input->post('search')){
			$form_data = array(
				'name'=> $this->input->post('name'),
				'code'=>$this->input->post('code'),
			);
			$form_data = (object)$form_data;
			$this->session->set_userdata('form_data',$form_data);
			$this->session->unset_userdata('form_data');
			$this->data['result'] = $this->Customer->show_list_where(0,0,$form_data);
		}else{
			$offset = $this->uri->segment(5);
			$config['uri_segment'] = 5;
			$config['per_page'] = 20;
			$config['base_url'] = base_url('admin_customer/list_customer') . '/-/-/';
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
		$this->data['detail_result'] = $detail_result[0];
		$this->data['h2title'] = "{$this->data['detail_result']->name}さんの詳細情報";
		$this->load->view('admin_customer/detail_customer',$this->data);
	}

	public function edit_customer()
	{
		$this->data['h2title'] = '会員情報の変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$id = $this->uri->segment(3);
		$result= $this->Customer->get_by_id($id);
		$result=$result[0];
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
			$input_data = array(
				'name' => $this->input->post('name'),
				'furigana' => $this->input->post('furigana'),
				'email' =>$this->input->post('email'),
				'zipcode' => $this->input->post('zipcode'),
				'prefecture' => $this->input->post('prefecture'),
				'address1' => $this->input->post('address1'),
				'address2' => $this->input->post('address2'),
				'tel' => $this->input->post('tel'),
				'age' => $this->input->post('age'),
			);
		//add_validateion
			$this->form_validation->set_rules('familyname','姓','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$input_data;
			}else{
				$db_data = $input_data;
				$this->Customer->update($id, $db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_customer/list_customer'));
			}		
		}
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
		$customer = $result[0];
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
		$username = uniqid();
		$password = uniqid();
		$id = $this->uri->segment(3);
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
			$filename = 'test.csv';
			$csv = new Csv();
			$result = array(array($username,$password));
			$csv->setData($result);
			$csv->getCsvMs('test.csv');
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
		$customer = $result[0];
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
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate')){
					$dbh->query('TRUNCATE takuhai_customers');
				}
				$stmt = $dbh->prepare('insert into takuhai_customers(
					code,
					name,
					furigana,
					age,
					tel,
					email,
					zipcode,
					prefecture,
					address1,
					address2
				) values(
					:code,
					:name,
					:furigana,
					:age,
					:tel,
					:email,
					:zipcode,
					:prefecture,
					:address1,
					:address2
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':code',$row[0]);
					$stmt->bindValue(':name',$row[1]);
					$stmt->bindValue(':furigana',$row[2]);
					$stmt->bindValue(':age',$row[3]);
					$stmt->bindValue(':tel',$row[4]);
					$stmt->bindValue(':email',$row[5]);
					$stmt->bindValue(':zipcode',$row[6]);
					$stmt->bindValue(':prefecture',$row[7]);
					$stmt->bindValue(':address1',$row[8]);
					$stmt->bindValue(':address2',$row[9]);
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
		$this->data['h2title'] = '会員お知らせ登録';
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
				redirect(site_url('/admin_customer/list_info'));
			}
		}
		$this->load->view('admin_customer/add_customer_info',$this->data);
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
			$this->data['detail_result'] = $detail_result[0];
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
		$id = $this->uri->segment(3);
		$result = $this->Customer_info->get_by_id($id);
		$this->data['form_data'] = $result[0];
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
		redirect(site_url('/admin_customer/list_info'));
	}
	public function change_show_flag_customer_info()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Customer_info->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_customer/list_info');
	}
}