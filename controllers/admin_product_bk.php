<?php
/*
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';
*/

class Admin_product extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination'));
		$this->load->model('Category');
		$this->load->model('Product');
		$this->load->model('Master_on_sale');
		$this->load->model('Allergen');
		$this->load->model('Recommend');
		$this->load->model('Advertise');
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
	
	public function upload()
	{
		$this->data['h2title'] = '商品一括登録';
		$this->data['db'] = getDb();
		$this->data['message'] = 'csvファイルをアップロードしてください';
		
//ここから		
		if(isset($_FILES['csvfile'])){
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate')){
					$dbh->query('TRUNCATE takuhai_master_products');
				}
				$stmt = $dbh->prepare('insert into takuhai_master_products(
					category_code,
					category_name,
					product_code,
					branch_code,
					product_name,
					short_name,
					sale_price,
					cost_price
				) values(
					:category_code,
					:category_name,
					:product_code,
					:branch_code,
					:product_name,
					:short_name,
					:sale_price,
					:cost_price
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':category_code',$row[0]);
					$stmt->bindValue(':category_name',$row[1]);
					$stmt->bindValue(':product_code',$row[2]);
					$stmt->bindValue(':branch_code',$row[3]);
					$stmt->bindValue(':product_name',$row[4]);
					$stmt->bindValue(':short_name',$row[5]);
					$stmt->bindValue(':sale_price',$row[6]);
					$stmt->bindValue(':cost_price',$row[7]);
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
				redirect(site_url('/admin_product/upload'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
//ここまで	
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_upload_product.php',$this->data);
	}
	
	public function register_product()
	{
		$this->data['h2title'] = '個別商品追加・変更';
		$this->data['message'] = '以下の項目を入力し登録ボタンを押してください';
		$this->data['on_sale'] = $this->Master_on_sale->on_sale;
		$form_data = $this->Product;
		$this->data['form_data'] = $form_data;
		$this->data['allergens'] = $this->Allergen->show_list();
		if($this->input->post('submit')){
			$form_data = array(
				'category_code'=>$this->input->post('category_code'),
				'category_name'=>$this->input->post('category_name'),
				'product_code'=>$this->input->post('product_code'),
				'branch_code'=>$this->input->post('branch_code'),
				'product_name'=>$this->input->post('product_name'),
				'short_name'=>$this->input->post('short_name'),
				'sale_price'=>$this->input->post('sale_price'),
				'cost_price'=>$this->input->post('cost_price'),
				'show_name'=>$this->input->post('show_name'),
//				'allergen'=>$this->input->post('allergen'),
				'on_sale'=>$this->input->post('on_sale'),
				'image_name'=>$this->input->post('image_name'),
				'image_description'=>$this->input->post('image_description'),
				'contents'=>$this->input->post('contents'),
				'jan_code'=>$this->input->post('jan_code'),
				'price1'=>$this->input->post('price1'),
				'price2'=>$this->input->post('price2'),
				'price3'=>$this->input->post('price3'),
			);
			$this->form_validation->set_rules('product_code','商品コード','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message']  = '未入力項目があります';
				$this->data['form_data']  = (object)$form_data;
			}else{
				$db_data = (object)$form_data;
				if(!empty($_FILES['image']['name'])){
					//uploadImageAdd($this->Product,$db_data,$allergen,$on_sale,$image_description);
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
							$db_data->image_name = $_FILES['image']['name'];
							$result = $this->Product->save($db_data);
							$product_id = $this->Product->last_insert_id();
							$allergen = $this->input->post('allergen');
							$this->Product->save_allergen($allergen,$product_id);
							$this->session->set_flashdata('success', '登録しました');
							redirect(site_url('/admin_product/register_product'));
						}else{
							unlink($_FILES['image']['tmp_name']);
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					$result = $this->Product->save($db_data);
					$product_id = $this->Product->last_insert_id();
					$allergen = $this->input->post('allergen');
					$this->Product->save_allergen($allergen,$product_id);
					$this->session->set_flashdata('success', '登録しました');
					redirect(site_url('/admin_product/register_product'));
				}
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_register_product.php',$this->data);
	}
	
	public function list_product()
	{
		$this->data['h2title'] = '登録商品表示';
		$this->data['message'] = 'カテゴリを選択、もしくは商品コードまたは商品名を入力してください';
		$this->data['form_data'] = $this->Product;
		$this->data['list_category'] = $this->Product->category_list();
		$offset = $this->uri->segment(6) ?: 0;
		if($this->input->post('search')){
			$form_data=array(
				'product_code'=>$this->input->post('product_code'),
				'product_name'=>$this->input->post('product_name'),
				'category_name'=>$this->input->post('category_name'),
			);
			$form_data=(object)$form_data;
			$this->data['form_data'] = $form_data;
			$category_name= $form_data->category_name ?: '-';
			$product_code = $form_data->product_code ?: '-';
			$product_name = $form_data->product_name ?: '-';
			$param = "/{$category_name}/{$product_code}/{$product_name}/";
			$offset=0;
			$this->data['result'] = $this->Product->show_list_where(20,$offset,$form_data);
		}else{
			$category_name = $this->uri->segment(3) ?: '-';
			$product_code = $this->uri->segment(4) ?: '-';
			$product_name = $this->uri->segment(5) ?: '-';
			$param = "/{$category_name}/{$product_code}/{$product_name}/";
			if($category_name == false || $category_name == '-'){ $category_name = ''; }
			if($product_code == false || $product_code == '-'){ $product_code = '';}
			if($product_name == false || $product_name == '-'){ $product_name = '';}
			$form_data = array(
				'category_name'=>urldecode($category_name),
				'product_name'=>urldecode($product_name),
				'product_code'=>$product_code,
			);
			$form_data = (object)$form_data;
			$this->data['result'] = $this->Product->show_list_where(20,$offset,$form_data);
		}
		$config['uri_segment'] = 6;
		$config['base_url'] = base_url('admin_product/list_product') . $param;
		$config['total_rows'] = $this->Product->num_rows($form_data);
		$config['per_page'] = 20;
		$config['last_link'] = false;
		$config['first_link'] = false;
		$config['next_link'] = '次へ';
		$config['prev_link'] = '前へ';
		$config['cur_tag_open'] = '<span class="current">';
		$config['cur_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		$this->data['links'] =  $this->pagination->create_links();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_list_product.php',$this->data);
	}

	public function edit_product()
	{
		$this->data['h2title'] = '登録商品の変更';
		$this->data['message'] = '商品情報を修正して登録ボタンを押して下さい';
		$this->data['on_sale'] = $this->Master_on_sale->on_sale;
		$this->data['allergens'] = $this->Allergen->show_list();
		$id = $this->uri->segment(3);
		$result = $this->Product->get_by_id($id);
		$allergen_arr = $this->Product->get_allergen_by_id($id);
		$allergen=array();
		$pa_middle_ids = array();
		foreach($allergen_arr as $a){
			$allergen[] = $a->allergen_id;
			$pa_middle_ids[] = $a->pa_middle_id;
		}
		$result[0]->allergen = $allergen;
		$this->data['form_data'] = $result[0];
		if($this->input->post('submit')){
			$form_data = array(
				'category_code'=>$this->data['form_data']->category_code,
				'category_name'=>$this->data['form_data']->category_name,
				'product_code'=>$this->data['form_data']->product_code,
				'branch_code'=>$this->input->post('branch_code'),
				'product_name'=>$this->data['form_data']->product_name,
				'short_name'=>$this->data['form_data']->short_name,
				'sale_price'=>$this->data['form_data']->sale_price,
				'cost_price'=>$this->data['form_data']->cost_price,
				'show_name'=>$this->input->post('show_name'),
				'sale_price'=>$this->input->post('sale_price'),
				//'allergen'=>$this->input->post('allergen'),
				'image_name'=>$this->data['form_data']->image_name,
				'image_description'=>$this->input->post('image_description'),
				'contents'=>$this->input->post('contents'),
				'jan_code'=>$this->input->post('jan_code'),
				'price1'=>$this->input->post('price1'),
				'price2'=>$this->input->post('price2'),
				'price3'=>$this->input->post('price3'),
			);
			$db_data = (object)$form_data;
			if(!empty($form_data['image_name'])){
				$old_image = 'images/products/' . $form_data['image_name'];
			}else{
				$old_image = false;
			}
			if(!empty($_FILES['image']['name'])){
				if(is_uploaded_file($_FILES['image']['tmp_name'])){
					if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
						$db_data->image_name = $_FILES['image']['name'];
						$result=$this->Product->update($id,$db_data);
						$allergen = $this->input->post('allergen');
						$this->Product->update_allergen($id,$pa_middle_ids,$allergen);
						$this->session->set_flashdata('success','登録しました');
						if($old_image){ unlink($old_image);};
						redirect(site_url('admin_product/list_product'));
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						unlink($_FILES['image']['tmp_name']);
						redirect(site_url('/admin_product/register_product'));
					}
				}else{
					$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					redirect(site_url('/admin_product/register_product'));
				}
			}else{
				$result = $this->Product->update($id,$db_data);
				$allergen = $this->input->post('allergen');
				$this->Product->update_allergen($id,$pa_middle_ids,$allergen);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_product/list_product'));
			}			
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_edit_product.php',$this->data);
	}
	
	public function detail_product()
	{
		$this->data['h2title'] = '商品情報の詳細';
		$id=$this->uri->segment(3);
		$result=$this->Product->get_by_id($id);
		$this->data['allergen'] = $this->Product->get_allergen_by_id($id);
		$this->data['result'] = $result[0];
		$this->load->view('admin_product/admin_detail_product.php',$this->data);
	}
	
	public function register_category()
	{
		$this->data['h2title'] = 'カテゴリの追加、変更';
		$this->data['message'] = 'カテゴリを追加する場合はカテゴリ名を入力して登録ボタンを押して下さい';
		$this->data['form_data'] = $this->Category;
		if($this->input->post('submit')){
			$form_data=array(
				'name'=> $this->input->post('name'),
				'show_name'=> $this->input->post('show_name'),
			);
			$this->form_validation->set_rules('name','カテゴリ名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = 'カテゴリ名が入力されていません';
			}else{
				$result=$this->Category->save_category($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_product/register_category'));
			}
		}
		$this->data['result'] = $this->Category->show_list();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_category.php',$this->data);
	}
	
	public function edit_category()
	{
		$this->data['h2title'] = 'カテゴリの変更';
		$this->data['message'] = 'カテゴリ名を修正して登録ボタンを押して下さい';
		$id = $this->uri->segment(3);
		$result = $this->Category->get_category_by_id($id);
		$this->data['form_data'] = $result;
		
		if($this->input->post('submit')){
			$form_data=array(
				'name'=> $this->input->post('name'),
				'show_name'=> $this->input->post('show_name'),
			);
			$this->form_validation->set_rules('name','カテゴリ名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = 'カテゴリ名が入力されていません';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$result=$this->Category->change_category($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_product/register_category'));
			}
		}
		$this->data['result'] = $this->Category->show_list();
		$this->load->view('admin_product/admin_category.php',$this->data);
	}

/*	
	public function upload_image()
	{
		$this->data['h2title'] = '商品画像のアップロード';
		$result = $this->Product->show_list();
		$this->data['result'] = $result;
		$this->load->view('admin_product/admin_upload_list_product.php',$this->data);
	}
*/	
	public function add_allergen()
	{
		$this->data['h2title'] ='アレルゲン情報の追加';
		$form_data = $this->Allergen;
		$this->data['form_data'] = $form_data;
		
		//postされた時の処理
		if($this->input->post('submit')){
			$this->form_validation->set_rules('name','アレルゲン名','required');
			if($this->form_validation->run() == FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data->name = $this->input->post('name');
				$form_data->description = $this->input->post('description');
			}else{
			//validationに成功した時の処理
				$db_data=array(
					'name'=>$this->input->post('name'),
					'description'=>$this->input->post('description'),
				);
				if(!empty($_FILES['icon']['name'])){
				//ファイルがアップロードされた場合
					if(is_uploaded_file($_FILES['icon']['tmp_name'])){
						if(move_uploaded_file($_FILES['icon']['tmp_name'] , 'images/icon/' . $_FILES['icon']['name'])){
						//ファイルのアップロードに成功した場合
							$db_data['icon'] = $_FILES['icon']['name'];
							$result = $this->Allergen->save($db_data);
							$this->session->set_flashdata('success','登録しました');
							redirect(site_url('/admin_product/list_allergen'));
						}else{
						//アップロードに失敗した場合
							unlink($_FILES['icon']['tmp_name']);
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						}
					}else{
					//アップロードに失敗した場合
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
				//ファイルをアップロードしなかった場合
					$result = $this->Allergen->save($db_data);
					$this->session->set_flashdata('success','登録しました');
					redirect(site_url('/admin_product/list_allergen'));
				}
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_add_allergen.php',$this->data);		
	}
	
	public function list_allergen()
	{
		$this->data['h2title'] = 'アレルゲン一覧';
		$this->data['result'] = $this->Allergen->show_list();
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		if($show_detail){
			$detail_result = $this->Allergen->get_by_id($id);
			$this->data['show_detail'] = $show_detail;
			$this->data['detail_result'] = $detail_result[0];
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_list_allergen.php',$this->data);
	}
	
	public function edit_allergen()
	{
		$this->data['h2title'] = 'アレルゲンの変更';
		$this->data['message'] = '内容を修正して登録ボタンを押して下さい';
		$id = $this->uri->segment(3);
		$result = $this->Allergen->get_by_id($id);
		$result = $result[0];
		$this->data['form_data'] = $result;
		if($this->input->post('submit')){
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$this->form_validation->set_rules('name','アレルゲン名','required');
			if($this->form_validation->run() == FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$result->name = $name;
				$result->description = $description;
			}else{
				$db_data=array(
					'name'=>$name,
					'description'=>$description,
				);
				if(!empty($_FILES['icon']['name'])){
					if(is_uploaded_file($_FILES['icon']['tmp_name'])){
						if(move_uploaded_file($_FILES['icon']['tmp_name'],'images/icon/' . $_FILES['icon']['name'])){
							$db_data['icon'] = $_FILES['icon']['name'];
							$result = $this->Allergen->update($id,$db_data);
							$this->session->set_flashdata('success','登録しました');
							redirect(site_url('admin_product/list_allergen'));
						}else{
							unlink($_FILES['icon']['tmp_name']);
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					$result = $this->Allergen->update($id,$db_data);
					$this->session->set_flashdata('success','登録しました');
					redirect(site_url('admin_product/list_allergen'));
				}
			}
		}
		$this->load->view('admin_product/admin_add_allergen.php',$this->data);
	}
	

}
