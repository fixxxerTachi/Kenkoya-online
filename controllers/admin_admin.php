<?php
class Admin_admin extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(
			array(
				'session','form_validation','upload','my_class','csv','my_mail','pagination'
				)
		);
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Master_mail_magazine');
		$this->load->model('Cource');
		$this->load->model('Area');
		$this->load->model('Delivery_person');
		$this->load->model('Master_hour');
		$this->load->model('Tax');
		$this->load->model('Admin_urls');
		$this->load->model('Master_controllers');
		$this->load->model('Admin_users');
		$this->load->model('Admin_login');
		$this->load->model('Master_show_flag');
		$this->load->model('Contact');
		$this->load->model('Question_category');
		$this->load->model('Mail_template');
		$this->load->model('Master_area');
		$this->load->model('Admin_mail');
		$this->load->model('Takuhai_charge');
		$this->load->model('Box');
		$this->load->model('Payment');
		$this->load->model('Master_cource_type');
		$this->load->model('Master_shop');
		$this->load->model('Master_payment');
		$this->load->model('Master_delivery_span');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['user'] = $this->Admin_login->check_login();
		if(!empty($this->data['user'])){
			$this->Admin_urls->get_user_site_menu($this->data['user']->id,$this);
			$this->Admin_urls->is_access_url($this->data['user']->id,$this);
		}
	}

	/*** 配達エリア一覧 ***/
	public function list_area(){
		$this->data['h2title'] = '配達エリア一覧';
		$this->data['message'] = 'エリアを選択するか郵便番号で検索してください';
		$this->data['zipcode'] = '';
		$city = $this->uri->segment(3);
		if($city){
			$this->data['result'] = $this->Area->show_list_with_cource(urldecode($city));
		}
		if($this->input->post('submit')){
			$zipcode = $this->input->post('zipcode');
			$this->data['result'] = $this->Area->show_list_with_cource(urldecode($city),$zipcode);
			$this->data['zipcode'] = $zipcode;
		}
		$this->data['list_city'] = $this->Area->list_area();
		$this->data['city_name'] = urlencode($city);
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/list_area.php',$this->data);
	}

	/*** 配達エリア変更 ***/
	public function edit_area(){
		$this->data['h2title'] = 'エリアマスタ変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$id= $this->uri->segment(3);
		$city = urldecode($this->uri->segment(4));
		$this->data['city_name'] = $city;
		$result = $this->Area->get_by_id($id);
		$this->data['form_data'] = $result;
		$shops = $this->Master_shop->array_lists();
		$this->data['shops'] = $shops;
		$this->data['cource_list'] = $this->Cource->show_list_for_dropdown($result->shop_id,TRUE);
		
		if($this->input->post('submit')){
			$input_data = array(
				'address' =>$this->input->post('address'),
				'furigana_area'=>$this->input->post('furigana_area'),
				'shop_id'=>$this->input->post('shop_id'),
				'cource_id' =>$this->input->post('cource_id'),
			);
			$this->form_validation->set_rules('cource_id','コース番号','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$input_data;
			}else{
				$this->Area->update($id,$input_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url("admin_admin/list_area/{$city}"));
			}
		}
		$this->load->view('admin_admin/edit_area',$this->data);
	}

	/***　配達エリア新規追加 ***/
	public function add_area()
	{
		$this->data['h2title'] = '宅配エリア登録';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$this->data['cource_list'] = $this->Cource->show_list_for_dropdown();
		$form_data = $this->Area;
		if($this->uri->segment(3)){
			$id = $this->uri->segment(3);
			$form_data->cource_id = $id;
		}
		$this->data['form_data'] = $form_data;
		if($this->input->post('submit')){
			$input_data = array(
				'zipcode' => $this->input->post('zipcode'),
				'prefecture' => $this->input->post('prefecture'),
				'city'=>$this->input->post('city'),
				'address'=>$this->input->post('address'),
				'cource_id'=>$this->input->post('cource_id'),
				'cource_name'=>$this->input->post('cource_name'),
				'takuhai_day'=>$this->input->post('takuhai_day'),
				//'cource_id'=>$this->input->post('cource_id'),
			);
			$this->form_validation->set_rules('zipcode','郵便番号','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$input_data;
			}elseif($this->Area->is_unique('zipcode',$input_data['zipcode'])){
				$this->data['error_message'] = '既に登録されている番号です';
				$this->data['form_data'] = (object)$input_data;
			}else{
				$this->Area->save($input_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_admin/list_area'));
			}
		}
		$this->load->view('admin_admin/add_area',$this->data);
	}

	/*** 配達エリア一括登録 ****/
	public function upload_area()
	{
		$this->data['h2title'] = '配達エリア一括登録';
		$this->data['db'] = $this->my_class->getDb();
		$this->data['message'] = 'csvファイルをアップロードして下さい';
		$this->data['shops'] = $this->Master_shop->array_lists(TRUE);
		$this->data['list_area'] = $this->Master_area->list_area;
		
		if(isset($_FILES['csvfile'])){
			try{
				$area = $this->input->post('shop_id');
				if(empty($area)){ throw new Exception('エリアを選択してください'); }
				$upload_file_info = $this->csv->uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = $this->csv->convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = $this->my_class->getDb();
				if($this->input->post('trancate')){
					$dbh->query('TRUNCATE takuhai_master_area');
				}
				$stmt = $dbh->prepare('insert into takuhai_master_area(
					shop_id,
					shop_code,
					zipcode,
					furigana_prefecture,
					furigana_city,
					furigana_area,
					prefecture,
					city,
					address,
					cource_code,
					cource_name,
					takuhai_day,
					cource_id
				) values(
					:shop_id,
					:shop_code,
					:zipcode,
					:furigana_prefecture,
					:furigana_city,
					:furigana_area,
					:prefecture,
					:city,
					:address,
					:cource_code,
					:cource_name,
					:takuhai_day,
					:cource_id
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':shop_id',(int)$area);
					$stmt->bindValue(':shop_code',$row[0]);
					$stmt->bindValue(':zipcode',$row[1]);
					$stmt->bindValue(':furigana_prefecture',$row[2]);
					$stmt->bindValue(':furigana_city',$row[3]);
					$stmt->bindValue(':furigana_area',$row[4]);
					$stmt->bindValue(':prefecture',$row[5]);
					$stmt->bindValue(':city',$row[6]);
					$stmt->bindValue(':address',$row[7]);
					$stmt->bindValue(':cource_code',$row[8]);
					$stmt->bindValue(':cource_id',$row[9]);
					$stmt->bindValue(':cource_name',$row[10]);
					$stmt->bindValue(':takuhai_day',$row[11]);
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
				redirect(base_url('admin_admin/upload_area'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/upload_area.php',$this->data);
	}

	/*** コース一覧、登録、編集,csv一括登録***/
	public function add_cource()
	{
		$list_items = $this->Master_cource_type->array_lists();
		$shops = $this->Master_shop->array_lists();
		$this->data['list_items'] = $list_items;
		$this->data['shops'] = $shops;

		/** コース編集 **/
		$id = $this->uri->segment(3);
		if($id)
		{
			$cource = $this->Cource->get_by_id($id);
		}
		
		
		/** $courceがあれば編集 無ければ　追加 **/
		if(isset($cource))
		{
			$this->data['h2title'] = 'コース編集';
			$form_data = $cource;
			$form_data->takuhai_day = $cource->cource_type_id;
			$this->data['edit_flag'] = TRUE;
		}
		else
		{
			$this->data['h2title'] = 'コース登録';
			$form_data = $this->Cource;
			$this->data['edit_flag'] = FALSE;
		}
		
		/** コース追加 **/
		if($this->input->post('submit')){
			$this->form_validation->set_rules('cource_code','コース番号','required|numeric');
			$this->form_validation->set_rules('cource_name','コース名','required');
			$this->form_validation->set_rules('takuhai_day','配達曜日','required|numeric');
			if($this->form_validation->run() === FALSE)
			{
				$this->data['error_message'] = '未入力項目があります';
				$form_data = (object)$this->input->post();
			}
			else
			{
				$input_data = array(
					'cource_code' => $this->input->post('cource_code'),
					'cource_name' => $this->input->post('cource_name'),
					'cource_type_id'=>$this->input->post('takuhai_day'),
					'shop_id'=>$this->input->post('shop_id'),
				);
				$this->Cource->save($input_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url("/admin_admin/add_cource"));
			}
		}
		
		/** コース編集 **/
		if($this->input->post('edit_sub'))
		{
			$this->form_validation->set_rules('cource_code','コース番号','required|numeric');
			$this->form_validation->set_rules('cource_name','コース名','rquired');
			$this->form_validation->set_rules('takuhai_day','配達曜日','required|numeric');
			if($this->form_validation->run() === FALSE)
			{
				$this->data['error_message'] = '未入力項目があります';
				$form_data = $this->input->post();
			}
			else
			{
				$input_data = array(
					'cource_code'=>$this->input->post('cource_code'),
					'cource_name'=>$this->input->post('cource_name'),
					'cource_type_id'=>$this->input->post('takuhai_day'),
					'shop_id'=>$this->input->post('shop_id'),
				);
				$this->Cource->update($id,$input_data);
				$this->session->set_flashdata('success','登録しました');
				return redirect('admin_admin/add_cource');
			}
		}
		
		$this->data['form_data'] = $form_data;
		/** コース一括登録 **/
		$this->data['db'] = $this->my_class->getDb();
		if(isset($_FILES['csvfile'])){
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate')){
					$dbh->query('TRUNCATE takuhai_master_cource');
				}
				$stmt = $dbh->prepare('insert into takuhai_master_cource(
					cource_code,
					cource_name,
					shop_code,
					cource_type_id
				) values(
					:cource_code,
					:cource_name,
					:shop_code,
					:cource_type_id
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':cource_code',$row[0]);
					$stmt->bindValue(':cource_name',$row[1]);
					$stmt->bindValue(':shop_code',$row[3]);
					$stmt->bindValue(':cource_type_id',$row[4]);
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
				redirect(base_url('/admin_admin/add_cource'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['result'] = $this->Cource->show_list_with_delivery_person(TRUE);
		$this->load->view('admin_admin/add_cource',$this->data);
	}
	
	/*** コース削除 ***/
	public function delete_cource()
	{
		$id = $this->uri->segment(3);
		$this->Cource->delete($id);
		$this->session->set_flashdata('success','削除しました');
		return redirect('admin_admin/add_cource');
	}

	/*** 消費税率一覧、追加、変更 ***/
	public function add_tax()
	{
		$this->data['result'] = $this->Tax->show_list();
		$this->data['form_data'] = $this->Tax;
		$this->data['hour_list'] = $this->Master_hour->hour;
		/*** 変更処理 ***/
		$id = $this->uri->segment(3);
		if(!empty($id))
		{
			$this->data['h2title'] = '消費税率変更';
			$detail = $this->Tax->get_by_id($id);
			$this->data['form_data'] = $detail;
			$this->data['edit_flag'] = TRUE;
		}
		//追加の場合
		else
		{
			$this->data['h2title'] = '消費税率新規追加';
			$this->data['edit_flag'] = FALSE;
		}
		
		/*** 更新処理***/
		if($this->input->post('edit_submit'))
		{
			$form_data = array(
				'tax'=>$this->input->post('tax'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			$this->form_validation->set_rules('tax','消費税率','required');
			if($this->form_validation->run() ===FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$form_data['tax'] = $form_data['tax'] / 100;
				$result = $this->Tax->update($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url("admin_admin/add_tax"));
			}
		}
		
		/***　新規追加 ***/
		if($this->input->post('submit')){
			$form_data = array(
				'tax'=>$this->input->post('tax'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			$this->form_validation->set_rules('tax','消費税率','required');
			if($this->form_validation->run() ===FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$form_data['tax'] = $form_data['tax'] / 100;
				$result = $this->Tax->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url("admin_admin/add_tax"));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_tax',$this->data);
	}

	/*** 消費税率削除 ***/
	public function delete_tax()
	{
		$id = $this->uri->segment(3);
		$this->Tax->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_admin/add_tax'));
	}

	/*** 管理サイトurl一覧、新規追加、***/
	public function add_admin_urls()
	{
		$this->data['h2title'] = '管理サイトURL追加';
		$this->data['result'] = $this->Admin_urls->show_list();
		$this->data['controllers'] = $this->Master_controllers->controllers;
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		/*** 更新処理の場合と追加の場合 ***/
		$id = $this->uri->segment(3);
		if($id)
		{
			$this->data['h2title'] = '管理サイトURL変更';
			$form_data = $this->Admin_urls->get_by_id($id);
			$this->data['edit_flag'] = TRUE;
		}
		else
		{
			$this->data['h2title'] = '管理サイトURL追加';
			$form_data = $this->Admin_urls;
			$this->data['edit_flag'] = FALSE;
		}
		$this->data['form_data'] = $form_data;
		/*** 更新処理 ***/
		if($this->input->post('edit_submit'))
		{
			$form_data = array(
				'controller'=>$this->input->post('controllers'),
				'url'=>$this->input->post('url'),
				'name'=>$this->input->post('name'),
			);
			$this->form_validation->set_rules('url','URL','required');
			$this->form_validation->set_rules('name','サイト名','required');
			if($this->form_validation->run() === FALSE)
			{
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}
			else
			{
				$this->Admin_urls->update($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				return redirect('admin_admin/add_admin_urls',$this->data);
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_admin_urls',$this->data);
		
		/*** 順序変更処理 ***/
		if($this->input->post('change_order')){	
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Admin_urls->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_admin/add_admin_urls'));
		}
		/*** 新規登録 ***/
		if($this->input->post('submit')){
			$form_data=array(
				'controller'=>$this->input->post('controllers'),
				'url'=>$this->input->post('url'),
				'name'=>$this->input->post('name'),
				'sort_order'=>$this->Admin_urls->sort_order,
			);
			$this->form_validation->set_rules('url','URL','required');
			$this->form_validation->set_rules('name','サイト名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$this->Admin_urls->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('admin_admin/add_admin_urls'));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_admin_urls',$this->data);
	}
	
	/*** urlの削除***/
	public function delete_admin_urls()
	{
		$id = $this->uri->segment(3);
		$this->Admin_urls->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('admin_admin/add_admin_urls'));
	}
	
	/*** ユーザー登録 ***/
	public function edit_roles()
	{
		/*** 追加 ***/
		$form_data = $this->Admin_users;
		$this->data['form_data'] = $form_data;
		if($this->input->post('submit'))
		{
			$form_data = array(
				'username'=>$this->input->post('username'),
				'password'=>$this->input->post('password'),
			);
			$this->form_validation->set_rules('username','ユーザー名','required');
			$this->form_validation->set_rules('password','パスワード','required');
			if($this->form_validation->run() === FALSE)
			{
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message'] = '未入力項目があります';
			}
			else if($this->Admin_users->is_unique_user($form_data['username']))
			{
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message'] = 'ユーザー名が重複しています';
			}
			else
			{
				$form_data['password'] = sha1($form_data['password']);
				$result = $this->Admin_users->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				return redirect('admin_admin/edit_roles');
			}
		}
		
		/*** 変更 ***/
		$result = $this->Admin_users->show_list();
		$this->data['result'] = $result;
		$id = $this->uri->segment(3);
		$user = $this->Admin_users->get_by_id($id);
		$this->data['userdata'] = $user;
		$this->data['urls'] = $this->Admin_urls->show_list();
		$this->data['controllers'] = $this->Master_controllers->controllers;
		$this->data['checked_controllers'] = $this->Admin_users->get_checked_controllers($id);
		if($this->input->post('edit_submit')){
			$checked_arr = $this->input->post('urls');
			$this->Admin_users->save_roles($id,$checked_arr);
			$this->session->set_flashdata('success',$this->data['userdata']->username .' 登録しました');
			redirect(base_url('admin_admin/edit_roles'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_roles',$this->data);
	}

	/*** ユーザー削除 ***/
	public function delete_admin_roles()
	{
		$id = $this->uri->segment(3);
		$this->Admin_users->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('admin_admin/edit_roles'));
	}

	/*** お問い合わせ管理　***/
	public function list_contact()
	{
		$param = $this->uri->segment(3);
		if(empty($param))
		{
			return redirect('admin_admin/list_contact/all');
		}
		$this->data['h2title'] = 'お問い合わせ管理';
		$offset = $this->uri->segment(4);
		$offset = $offset ?: 0;
		//全てのレコード数を求める
		if($param == 'all')
		{
			$result = $this->Contact->show_list();
		}
		else
		{
			$result = $this->Contact->show_list(null,null,true);
		}
		/*pagination*/
		$total_count = count($result);
		$limit = 10;
		$categories = $this->Question_category->show_list_array();
		/*未返信のみ表示でpostされてきた場合*/
		if($this->input->post('no-reply-param'))
		{
			if($this->input->post('no-reply'))
			{
				$result = $this->Contact->show_list($offset,$limit,TRUE);
			}
		}
		if($param == 'no-reply')
		{
			$result = $this->Contact->show_list($offset,$limit,TRUE);
		}
		elseif($param == 'all')
		{
			$result = $this->Contact->show_list($offset,$limit);
		}
		else
		{
			$result = $this->Contact->show_list($offset,$limit);
		}
		$config['base_url'] = site_url('admin_admin/list_contact') . '/' . $param;
		$config['total_rows'] = $total_count;
		$config['per_page'] = $limit;
		$config['cur_tag_open'] ='<span class="current">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);
		$this->data['pages'] = $this->pagination->create_links();

		$this->data['result'] = $result;
		$this->data['categories'] = $categories;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/list_contact',$this->data);
	}
	
	/*** お問い合わせの返信 ****/
	public function reply_contact(){
		$this->load->model('Mail_footer');
		$footer = $this->Mail_footer->content;
		$footer = str_replace(
			array('{{site_url}}','{{contact_url}}'),
			array(site_url(),site_url('contact')),
			$footer
		);
		$header = "いつも宅配スーパー健康屋をご利用いただき、誠にありがとうございます。";
		$this->data['h2title'] = 'お問い合わせ:返信';
		$id = $this->uri->segment(3);
		$categories = $this->Question_category->show_list_array();
		$form_data = $this->Contact->get_by_id($id);
		$this->data['title'] = '【宅配スーパー健康屋】お客様サポート';
		$category = $categories[$form_data->category_id];
		$reply_content = str_replace("\n","\n>",$form_data->content);
		$content = "{$form_data->name}様\n{$header}\n\n\n>【お問い合わせ内容】\n>{$category}\n>\n>{$reply_content}\n{$footer}";
		if($this->input->post('submit'))
		{
			$form_data->title = $this->input->post('title');
			$form_data->content = $this->input->post('content');
			$result = $this->send_mail($form_data);
			if($result){
				$this->session->set_flashdata('success','メールを送信しました');
				$db = $this->Contact->db;
				$db_data=array(
					'reply_flag'=>1,
					'reply_content'=>$form_data->content,
				);
				$db->where('id',$id);
				$db->update('question',$db_data);
				return redirect('admin_admin/list_contact');
			}else{
				$this->session->set_flashdata('error','メール送信に失敗しました');
				$this->data['error_message']  = $this->session->flashdata('error');
			}
		}
		$this->data['content'] = $content;
		$this->data['form_data'] = $form_data;
		$this->load->view('admin_admin/reply_contact',$this->data);
	}
	
	/*** お問い合わせの詳細 ***/
	public function detail_contact()
	{
		$this->data['h2title'] = 'お問い合わせ詳細';
		$id = $this->uri->segment(3);
		$form_data = $this->Contact->get_by_id($id);
		$this->data['categories'] = $this->Contact->show_list_category();
		$this->data['form_data'] = $form_data;
		$this->load->view('admin_admin/detail_contact',$this->data);
	}
		
	/*** 管理者メールアドレス追加 ***/
	public function add_admin_mail()
	{
		$this->data['h2title'] = '管理者メールアドレス追加';
		$form_data = $this->Admin_mail;
		$this->data['result'] = $this->Admin_mail->show_list();;
		$this->data['form_data'] = $form_data;
		if($this->input->post('submit')){
			$form_data = array(
				'name'=>$this->input->post('name'),
				'email'=>$this->input->post('email'),
			);
			$this->form_validation->set_rules('name','名前','required');
			$this->form_validation->set_rules('email','メールアドレス','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力です';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$result = $this->Admin_mail->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				return redirect('admin_admin/add_admin_mail');
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_admin_mail',$this->data);
	}
	
	/*** 管理者メールアドレス変更 ***/
	public function edit_admin_mail()
	{
		$this->data['h2title'] = '管理者メールアドレス変更';
		$this->data['result'] = $this->Admin_mail->show_list();
		$id = $this->uri->segment(3);
		$result = $this->Admin_mail->get_by_id($id);
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
			$form_data=array(
				'name'=>$this->input->post('name'),
				'email'=>$this->input->post('email'),
			);
			$this->form_validation->set_rules('name','名前','required');
			$this->form_validation->set_rules('email','メールアドレス','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力です';
			}else{
				$result = $this->Admin_mail->update($id,$form_data);
				$this->session->set_flashdata('success','変更しました');
				return redirect('admin_admin/add_admin_mail');
			}
		}
		$this->load->view('admin_admin/add_admin_mail',$this->data);
	}

	
	/*** 管理者メールアドレス削除 ***/
	public function delete_admin_mail()
	{
		$id = $this->uri->segment(3);
		$this->Admin_mail->delete($id);
		$this->session->set_flashdata('success','削除しました');
		return redirect('admin_admin/add_admin_mail');
	}
	
	/*** 配送料金の登録変更 ***/
	public function delivery_charge()
	{
		$this->data['h2title'] = '配送料金の登録・変更';
		$zone_list = $this->Takuhai_charge->show_list();
		$this->data['zone_list'] = $zone_list;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/delivery_charge',$this->data);
	}
	
	/*** 温度帯別情報の変更 ***/
	public function edit_zone()
	{
		$this->data['h2title'] = '温度帯：表示名の編集';
		$id = $this->uri->segment(3);
		$result = $this->Takuhai_charge->get_by_id($id);
		$form_data = $result;
		if($this->input->post('submit'))
		{
			$form_data = (object)$this->input->post();
				if(empty($form_data->max_weight) || empty($form_data->max_volume))
				{
					$this->session->set_flashdata('error','未入力項目があります');
					$this->data['form_data'] = $form_data;
					return redirect('admin_admin/edit_zone/' . $id);
				}
				$db_data = array(
					'text'=>$form_data->text,
					'max_weight'=>$form_data->max_weight,
					'max_volume' =>$form_data->max_volume,
					'description'=>$form_data->description,
				);
				$this->Takuhai_charge->update($id,$db_data);
				$this->session->set_flashdata('success',$result->text . 'を変更しました');
				return redirect('admin_admin/delivery_charge');
		}
		//$this->data['temp_list'] = $this->Master_temp_zone->list;
		$this->data['form_data'] = $form_data;
		$this->data['error_message'] = $this->session->flashdata('error');
		$this->load->view('admin_admin/edit_zone',$this->data);
	}
	
	/*** 都道府県別配送料金の変更 ***/
	public function edit_charge()
	{
		$zone_id = $this->uri->segment(3);
		$temp_zone = $this->Takuhai_charge->get_by_id($zone_id);
		$this->data['h2title'] = "{$temp_zone->text} : 配送料金の登録・変更";
		$result = $this->Takuhai_charge->show_charge_list_by_zone($zone_id);
		$this->data['result'] = $result;
		if($this->input->post('submit'))
		{
			$form_data = $this->input->post();
			$id_list = $this->Takuhai_charge->list_ids($zone_id);
			foreach($id_list as $id)
			{
				$keyname = 'charge_' . $id->id;
				$charge = $form_data[$keyname];
				$this->Takuhai_charge->update_charge($id->id,$charge);
			}
			$this->session->set_flashdata('success',$temp_zone->text .' 更新しました');
			return redirect('admin_admin/delivery_charge');
		}
		$this->load->view('admin_admin/edit_charge',$this->data);
	}
	
	/**** 梱包箱データ登録***/
	public function add_box()
	{
		$this->data['h2title'] = '梱包箱登録';
		$this->data['list'] = $this->Box->show_list();
		$zones = $this->Takuhai_charge->list_temp_zone();
		$form_data = $this->Box;
		if($this->input->post('submit'))
		{
			$form_data = (object)$this->input->post();
			if(!empty($form_data->temp_zone_id) || !empty($form_data->volume))
			{
				$db_data = array(
					'temp_zone_id'=>$form_data->temp_zone_id,
					'name'=>$form_data->name,
					'height'=>$form_data->height,
					'width'=>$form_data->width,
					'depth'=>$form_data->depth,
					'volume'=>$form_data->volume,
					'weight'=>$form_data->weight,
				);
				$this->Box->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				return redirect('admin_admin/add_box');
			}else{
				$this->data['error_message'] = '未入力項目があります';
			}
		}
		$this->data['zones'] = $zones;
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_box',$this->data);
	}
	
	/*** 箱情報変更 ***/
	public function edit_box()
	{
		$this->data['h2title'] = '梱包箱情報変更';
		$this->data['list'] = $this->Box->show_list();
		$this->data['zones'] = $this->Takuhai_charge->list_temp_zone();
		$id = $this->uri->segment(3);
		if($this->input->post('edit_submit'))
		{
			$form_data = (object)$this->input->post();
			$db_data = array(
				'temp_zone_id'=>$form_data->temp_zone_id,
				'name'=>$form_data->name,
				'width'=>$form_data->width,
				'height'=>$form_data->height,
				'depth'=>$form_data->depth,
				'volume'=>$form_data->volume,
				'weight'=>$form_data->weight,
			);
			$this->Box->update($id,$db_data);
			$this->session->set_flashdata('success',"更新しました");
			return redirect('admin_admin/add_box');
		}
		$result = $this->Box->get_by_id($id);
		$this->data['form_data'] = $result;
		$this->data['edit_flag'] = True;
		$this->load->view('admin_admin/add_box',$this->data);
	}
	
	/*** 箱情報削除 ***/
	/*** 箱情報削除 ***/
	public function delete_box()
	{
		$id = $this->uri->segment(3);
		$this->Box->delete($id);
		$this->session->set_flashdata('success','削除しました');
		return redirect(base_url('admin_admin/add_box'));
	}


	/*** お支払方法登録処理 ***/
	public function payment()
	{
		$this->data['h2title'] = 'お支払方法登録、変更';
		$result = $this->Master_payment->show_list();
		$this->data['result'] = $result;
		$id = $this->uri->segment(3);
		$form_data = $this->Master_payment;
		if(!empty($id))
		{
			$form_data = $this->Master_payment->get_by_id($id);
			$this->data['edit'] = true;
		}
		if($this->input->post('submit')){
			$form_data = $this->input->post();
			if(!empty($form_data['method_name']))
			{
				$db_data = array(
					'method_name'=>$form_data['method_name'],
					'notice'=>$form_data['notice'],
					'description'=>$form_data['description'],
					'show_flag'=>$form_data['show_flag'],
				);
				if(!empty($id)){
					$this->Master_payment->update($id,$db_data);
					$this->session->set_flashdata('success','更新しました');
				}else{
					$this->Master_payment->save($db_data);
					$this->session->set_flashdata('success','登録しました');
				}
				return redirect('admin_admin/payment');
			}else{
				$this->data['error_message'] = '未入力項目があります';
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['form_data'] = $form_data;
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$this->load->view('admin_admin/payment',$this->data);
	}
	
	/*** お支払方法削除 ***/
	public function delete_payment()
	{
		$id = $this->uri->segment(3);
		$this->Payment->delete($id);
		$this->session->set_flashdata('success','削除しました');
		return redirect(base_url('admin_admin/payment'));
	}

	/*** 都道府県住所マスタアップロード ***/
	public function upload_master_address()
	{
		$this->data['h2title'] = '住所マスタ登録';
		$this->data['message'] = 'csvファイルをアップロードして下さい';
		if(isset($_FILES['csvfile']))
		{
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate'))
				{
					$dbh->query('TRUNCATE takuhai_master_address');
				}
				$stmt = $dbh->prepare('insert into takuhai_master_address(
						pref_id
						,zipcode
						,prefecture
						,city
						,address
					) values(
						:pref_id,
						:zipcode,
						:prefecture,
						:city,
						:address
					)');
				
				$error_arr = array();
				foreach($csv as $row)
				{
					$stmt->bindValue(':pref_id',$row[0]);
					$stmt->bindValue(':zipcode',$row[2]);
					$stmt->bindValue(':prefecture',$row[6]);
					$stmt->bindValue(':city',$row[7]);
					if(isset($row[8])){
						$stmt->bindValue(':address',$row[8]);
					}else{
						$stmt->bindValue(':address',null);
					}
					$stmt->execute();
					$error_info = $stmt->errorInfo();
					$error_arr[] = $stmt->errorInfo();
					if($error_info[0] != '00000')
					{
						$this->data['error_message'] = 'データベースの登録に失敗しました';
						break;
					}
				}
				$counter = 0;
				foreach($error_arr as $err)
				{
					if($err[0] == '00000')
					{
						$counter += 1;
					}
				}
				$this->session->set_flashdata('success',"{$upload_filename} ：{$counter} 件データベースに登録しました");
				return redirect(base_url('admin_admin/upload_master_address'));
			}
			catch(Exception $e)
			{
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/upload_address',$this->data);		
	}

	public function index()
	{
		$this->data['h2title'] = 'メニューを選択';
		$this->load->view('index_admin',$this->data);
	}
	
	
	public function login()
	{
		$this->data['h2title'] = '管理サイト：ログイン';
		$this->data['message'] = 'ユーザー名とパスワードを入力してください';
		if($this->input->post('submit')){
			$form_data=array(
				'username'=>$this->input->post('username'),
				'password'=>sha1($this->input->post('password')),
			);
			$form_data = (object)$form_data;
			if(!$this->Admin_login->login_action($form_data->username,$form_data->password))
			{
				$this->data['error_message'] = 'ユーザー名かパスワードが間違っています';
			}else{
				redirect(base_url('admin_order'));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/login',$this->data);
	}
	
	public function logout()
	{
		if($this->session->userdata('admin')){
			$this->session->unset_userdata('admin');
		}
		$this->session->set_flashdata('success','ログアウトしました');
		redirect(base_url('admin_admin/login'));
	}
	
	public function change_show_flag_urls()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Admin_urls->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_admin/add_admin_urls');
	}
		
	public function upload_nohin()
	{
		$this->data['h2title'] = '納品情報登録';
		$this->data['message'] = '2行目のhanbaitenIdはshop_codeに書き換えること';
		if(isset($_FILES['csvfile']))
		{
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate'))
				{
					$dbh->query('TRUNCATE takuhai_nohin');
				}
				$stmt = $dbh->prepare('insert into takuhai_nohin(
					shop_code
					,customer_code
					,product_code
					,delivery_id
					,uriage_ym
					,ymd
					,weekday
					,item_cnt
					,create_datetime
				) values(
					:shop_code,
					:customer_code,
					:product_code,
					:delivery_id,
					:uriage_ym,
					:ymd,
					:weekday,
					:item_cnt,
					:create_datetime
				)');
				$error_arr = array();
				foreach($csv as $row)
				{
					$stmt->bindValue(':shop_code',$row[1]);
					$stmt->bindValue(':customer_code',$row[2]);
					$stmt->bindValue(':product_code',$row[3]);
					$stmt->bindValue(':delivery_id',$row[6]);
					$stmt->bindValue(':uriage_ym',$row[7]);
					$stmt->bindValue(':ymd',$row[8]);
					$stmt->bindValue(':weekday',$row[9]);
					$stmt->bindValue(':item_cnt',$row[13]);
					$stmt->bindValue(':create_datetime',date('Y-m-d H:i:s'));
					$stmt->execute();
					$error_info = $stmt->errorInfo();
					$error_arr[] = $stmt->errorInfo();
					if($error_info[0] !== '00000')
					{
						$this->data['error_message'] = 'データベースの登録に失敗しました';
						break;
					}
				}
				$counter = 0;
				foreach($error_arr as $err)
				{
					if($err[0] == '00000')
					{
						$counter += 1;
					}
				}
				$this->session->set_flashdata('success',"{$upload_filename}: {$counter}件データベースに登録しました");
				return redirect(base_url('admin_admin/upload_nohin'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/upload_nohin',$this->data);
	}
	
	
	/*** 配達日の間隔は時間指定できる間隔を変更する **/
	public function edit_span()
	{
		$this->data['h2title'] = '配達日間隔変更';
		$result = $this->Master_delivery_span->get_span();
		$this->data['result'] = $result;
		
		//変更 
		$id = $this->uri->segment(3);
		$edit_flag = !empty($id) ? TRUE : FALSE;
		if($this->input->post('submit'))
		{
			$db_data = array(
				'span' => $this->input->post('span'),
				'takuhai_span' => $this->input->post('takuhai_span'),
			);
			$this->Master_delivery_span->update($id,$db_data);
			$this->session->set_flashdata('success','更新しました');
			return redirect('admin_admin/edit_span');
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->data['edit_flag'] = $edit_flag;
		$this->load->view('admin_admin/edit_span',$this->data);
	}

	private function send_mail($data = null , $order = null)
	{
		$send_address = $this->Mail_template->support_address;
		//$mail_template = $this->Mail_template->get_by_id(1);
		//$mail_template = $mail_template[0];
		//$username = $customer->name;
		//$title = $customer->address1;
		//$contents = $order->order_number;
		//$mail_body = str_replace(array('{$title}','{$username}','{$contents}'),array($title,$username,$contents),$mail_template->mail_body);
		$result = $this->my_mail->sendMail($data->email,$data->title,$data->content,$send_address,$this->Mail_template->sender);
		return $result;
	}
	
	public function show_cource()
	{
		$shop_id = $this->uri->segment(3);
		$cources = $this->Cource->show_list_for_dropdown($shop_id);
		echo json_encode($cources);
	}

	/*
	public function edit_payment()
	{
		$id = $this->uri->segment(3);
		$form_data = $this->Payment->get_by_id($id);
		
	}
	*/
	
	
	
	/*
	public function add_customer()
	{
		$this->data['h2title'] = '会員新規登録';
		$this->data['message'] = '下記の情報を入力して登録ボタンを押してください';
		$form_data = $this->Customer;
		$this->data['form_data'] = $form_data;
		if($this->input->post('submit')){
			$input_data = array(
				'familyname' => $this->input->post('familyname'),
				'firstname' => $this->input->post('firstname'),
				'familyname_furigana' => $this->input->post('familyname_furigana'),
				'firstname_furigana' => $this->input->post('firstname_furigana'),
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
				redirect(base_url('/admin_customer/list_customer'));
			}		
		}
		$this->data['succsss_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/add_customer.php',$this->data);	
	}
	
	public function list_customer()
	{
		$this->data['h2title'] = '会員一覧';
		$this->data['result'] = $this->Customer->show_list();
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		if($show_detail){
			$detail_result = $this->Customer->get_by_id($id);
			$this->data['show_detail'] = $show_detail;
			$this->data['detail_result'] = $detail_result[0];
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_customer/list_customer.php',$this->data);
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
				'familyname' => $this->input->post('familyname'),
				'firstname' => $this->input->post('firstname'),
				'familyname_furigana' => $this->input->post('familyname_furigana'),
				'firstname_furigana' => $this->input->post('firstname_furigana'),
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
				redirect(base_url('/admin_customer/list_customer'));
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
		redirect(base_url('/admin_customer/list_customer'));
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
				redirect(base_url('/admin_customer/list_customer'));
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
			redirect(base_url('/admin_customer/list_personal'));
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
				redirect(base_url('/admin_customer/list_personal'));
			}
		}
		$this->data['message'] = "{$customer->familyname} {$customer->firstname}様のお支払情報登録";
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
		redirect(base_url('/admin_customer/list_personal'));
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
			redirect(base_url('/admin_customer/list_personal'));
		}
		$this->data['message'] = "{$customer->familyname} {$customer->firstname}様の情報";
		$this->load->view('admin_customer/add_personal_mail_magazine',$this->data);
	}
	*/
	

/*
	public function upload_cource()
	{
		$this->data['h2title'] = '配達コース一括登録';
		$this->data['db'] = $this->my_class->getDb();
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
					$dbh->query('TRUNCATE takuhai_master_cource');
				}
				$stmt = $dbh->prepare('insert into takuhai_master_cource(
					cource_code,
					cource_name,
					shop_code,
					cource_type_id
				) values(
					:cource_code,
					:cource_name,
					:shop_code,
					:cource_type_id
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':cource_code',$row[0]);
					$stmt->bindValue(':cource_name',$row[1]);
					$stmt->bindValue(':shop_code',$row[3]);
					$stmt->bindValue(':cource_type_id',$row[4]);
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
				redirect(base_url('/admin_admin/upload_cource'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/upload_cource.php',$this->data);
	}
*/
	
	
/*
	public function list_cource()
	{
		$this->data['h2title'] = 'コース一覧';
//		$this->data['result'] = $this->Cource->show_list(FALSE);
		$this->data['result'] = $this->Cource->show_list_with_delivery_person(FALSE);
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/list_cource',$this->data);
	}
*/
/*
	public function edit_cource()
	{
		$this->data['h2title'] = 'コースマスタ変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$this->data['list_delivery_person'] = $this->Delivery_person->show_list_for_dropdown();
		$id = $this->uri->segment(3);
		$result = $this->Cource->get_by_id($id);
		$this->data['form_data'] = $result[0];
		if($this->input->post('submit')){
			$input_data = array(
				'cource_id' =>$this->input->post('cource_id'),
				'cource_name' =>$this->input->post('cource_name'),
				'takuhai_day' =>$this->input->post('takuhai_day'),
				'delivery_person_id' => $this->input->post('delivery_person_id'),
			);
			$this->form_validation->set_rules('cource_id','コース番号','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$input_data;
			}else{
				$this->Cource->update($id,$input_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_admin/list_cource'));
			}
		}
		$this->load->view('admin_admin/add_cource',$this->data);
	}
*/

	/*
	public function add_delivery_person(){
		$this->data['h2title'] = '配達員登録';
		$form_data = $this->Delivery_person;
		$this->data['form_data'] = $form_data;
		$image_path = DELIVERY_IMAGE_PATH;
		if($this->input->post('submit')){
			if(!empty($_FILES['image']['name'])){
				$form_data = array(
					'image'=>$_FILES['image']['name'],
					'name'=>$this->input->post('name'),
					'introduction'=>$this->input->post('introduction'),
				);
				if(is_uploaded_file($_FILES['image']['tmp_name'])){
					if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
						$result = $this->Delivery_person->save($form_data);
						$this->session->set_flashdata('success','登録しました');
						redirect(base_url('/admin_admin/list_delivery_person'));
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						unlink($_FILES['image']['tmp_name']);
					}
				}else{
					$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
				}
			}else{
				$this->data['error_message'] = 'ファイルが選択されていません';
			}
		}
		$this->load->view('admin_admin/add_delivery_person',$this->data);
	}

	public function edit_delivery_person(){
		$this->data['h2title'] = '配達員登録';
		$id = $this->uri->segment(3);
		$form_data = $this->Delivery_person->get_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$image_path = DELIVERY_IMAGE_PATH;
		$old_image = $image_path . $form_data[0]->image;
		if($this->input->post('submit')){
			$form_data = array(
				'image'=>$form_data[0]->image,
				'name'=>$this->input->post('name'),
				'introduction'=>$this->input->post('introduction'),
			);
			if(!empty($_FILES['image']['name'])){
				if(is_uploaded_file($_FILES['image']['tmp_name'])){
					if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
						unlink($old_image);
						$form_data['image'] = $_FILES['image']['name'];
						$result = $this->Delivery_person->update($id,$form_data);
						$this->session->set_flashdata('success','登録しました');
						redirect(base_url('/admin_admin/list_delivery_person'));
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						unlink($_FILES['image']['tmp_name']);
					}
				}else{
					$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
				}
			}else{
				$result = $this->Delivery_person->update($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_admin/list_delivery_person'));
			}
		}
		$this->load->view('admin_admin/add_delivery_person',$this->data);
	}

	public function list_delivery_person()
	{
		$this->data['h2title'] = '配達員リスト';
		$this->data['result'] = $this->Delivery_person->show_list();
		$this->load->view('admin_admin/list_delivery_person',$this->data);
	}
	
	public function delete_delivery_person()
	{
		$id = $this->uri->segment(3);
		$result = $this->Delivery_person->get_by_id($id);
		$old_image = DELIVERY_IMAGE_PATH . $result[0]->image;
		unlink($old_image);
		$this->Delivery_person->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_admin/list_delivery_person'));
	}
	*/

/*
	public function edit_tax()
	{
		$this->data['h2title'] = '消費税率適用時期変更';
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['result'] = $this->Tax->show_list();
		$id = $this->uri->segment(3);
		$detail = $this->Tax->get_by_id($id);
		$this->data['form_data'] = $detail;
		if($this->input->post('submit')){
			$form_data = array(
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			$result = $this->Tax->update($id,$form_data);
			$this->session->set_flashdata('success','登録しました');
			redirect(base_url("admin_admin/add_tax"));
		}
		$this->load->view('admin_admin/edit_tax',$this->data);
	}
	*/
	
	/*
	public function edit_admin_urls()
	{
		$this->data['h2title'] = '管理サイトURL編集';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$id =$this->uri->segment(3);
		$form_data = $this->Admin_urls->get_by_id($id);
		$this->data['form_data'] = $form_data;
		$this->data['result'] = $this->Admin_urls->show_list();
		$this->data['controllers'] = $this->Master_controllers->controllers;
		if($this->input->post('submit')){
			$form_data=array(
				'controller'=>$this->input->post('controllers'),
				'url'=>$this->input->post('url'),
				'name'=>$this->input->post('name'),
			);
			$this->form_validation->set_rules('url','URL','required');
			$this->form_validation->set_rules('name','サイト名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$this->Admin_urls->update($id, $form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('admin_admin/add_admin_urls'));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_admin_urls',$this->data);
	}

	*/
	/*
	public function add_admin_users()
	{
		$this->data['h2title'] = '管理ユーザー登録';
		$form_data = $this->Admin_users;
		$this->data['form_data'] = $form_data;
		if($this->input->post('submit')){
			$form_data = array(
				'username'=>$this->input->post('username'),
				'password'=>$this->input->post('password'),
			);
			$this->form_validation->set_rules('username','ユーザー名','required');
			$this->form_validation->set_rules('password','パスワード','required');
			if($this->form_validation->run() === FALSE){
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message'] = '未入力項目があります';
			}else if($this->Admin_users->is_unique_user($form_data['username'])){
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message']  = 'ユーザー名が重複しています';
			}else{
				$form_data['password'] = sha1($form_data['password']);
				$result  = $this->Admin_users->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('admin_admin/add_admin_users'));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_admin_users.php',$this->data);
	}
	
	
	/*
	public function add_roles()
	{
		$result = $this->Admin_users->show_list();
		$this->data['result'] = $result;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_admin/add_roles',$this->data);
	}*/

}
