<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';

class Admin_product extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->model('Category');
		$this->load->model('Product');
		$this->load->model('Master_on_sale');
		$this->load->model('Allergen');
		$this->data['side'] = array(
			site_url('/admin_product/upload') =>'商品一括登録',
			site_url('/admin_product/register_product') =>'商品個別登録',
			site_url('/admin_product/list_product') =>'登録商品表示',
			site_url('/admin_product/register_category') =>'カテゴリの追加・変更',			
			site_url('/admin_product/list_allergen') =>'アレルゲン情報表示',			
			site_url('/admin_product/add_allergen') =>'アレルゲン情報の追加',			
		);
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
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
					product_code,
					branch_code,
					product_name,
					short_name,
					sale_price,
					cost_price
				) values(
					:product_code,
					:branch_code,
					:product_name,
					:short_name,
					:sale_price,
					:cost_price
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':product_code',$row[0]);
					$stmt->bindValue(':branch_code',$row[1]);
					$stmt->bindValue(':product_name',$row[2]);
					$stmt->bindValue(':short_name',$row[3]);
					$stmt->bindValue(':sale_price',$row[4]);
					$stmt->bindValue(':cost_price',$row[5]);
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
			try{
				$product_code = $this->input->post('product_code');
				$branch_code = $this->input->post('branch_code');
				$product_name = $this->input->post('product_name');
				$short_name = $this->input->post('short_name');
				$sale_price = $this->input->post('sale_price');
				$cost_price = $this->input->post('cost_price');
				$on_sale = $this->input->post('on_sale');
				$allergen = $this->input->post('allergen');
				if(empty($product_code) || empty($branch_code) || empty($product_code) 
					|| empty($short_name) || empty($sale_price) || empty($cost_price)){
					$form_data->product_code = $product_code;
					$form_data->branch_code = $branch_code;
					$form_data->product_name = $product_name;
					$form_data->short_name = $short_name;
					$form_data->sale_price = $sale_price;
					$form_data->cost_price = $cost_price;
					$form_data->on_sale = $on_sale;
					$form_data->allergen = $allergen;
					if(!empty($_FILES['image']['tmp_name'])){
						unlink($_FILES['image']['tmp_name']);
					}
					$this->data['error_message'] = '未入力項目があります';
				}else{
					$db_data=array(
						'product_code' => $product_code,
						'branch_code' => $branch_code,
						'product_name' => $product_name,
						'short_name' => $short_name,
						'sale_price' => $sale_price,
						'cost_price' => (float)$cost_price,
						'on_sale' => $on_sale,
					);
					if(!empty($_FILES['image']['name'])){
						uploadImageAdd($this->Product,$db_data,$allergen);
					}else{			
						$result = $this->Product->save($db_data);
						$this->Product->save_allergen($allergen);
					}
					$this->session->set_flashdata('success', '登録しました');
					redirect(site_url('/admin_product/register_product'));
				}
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_product/admin_register_product.php',$this->data);
	}
	
	public function list_product()
	{
		$this->data['h2title'] = '登録商品表示';
		$this->data['result'] = $this->Product->show_list_with_image();
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
		$result = $this->Product->get_by_id_with_image($id);
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
			$product_code = $this->input->post('product_code');
			$branch_code = $this->input->post('branch_code');
			$product_name = $this->input->post('product_name');
			$short_name = $this->input->post('short_name');
			$sale_price = $this->input->post('sale_price');
			$cost_price = $this->input->post('cost_price');
			$on_sale = $this->input->post('on_sale');
			$allergen = $this->input->post('allergen');
			$image_name = (!empty($result[0]->image_name)) ? $result[0]->image_name : '';
			if(empty($product_code) || empty($branch_code) || empty($product_code) 
				|| empty($short_name) || empty($sale_price) || empty($cost_price)){
				$result[0]->product_code = $product_code;
				$result[0]->branch_code = $branch_code;
				$result[0]->product_name = $product_name;
				$result[0]->short_name = $short_name;
				$result[0]->sale_price = $sale_price;
				$result[0]->cost_price = $cost_price;
				$result[0]->on_sale = $on_sale;
				$result[0]->allergen = $allergen;
				$result[0]->image_name = $image_name;
				if(!empty($_FILES['image']['tmp_name'])){
					unlink($_FILES['image']['tmp_name']);
				}
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$db_data=array(
					'product_code' => (int)$product_code,
					'branch_code' => (int)$branch_code,
					'product_name' => $product_name,
					'short_name' => $short_name,
					'sale_price' => (int)$sale_price,
					'cost_price' => (float)$cost_price,
					'on_sale' => $on_sale,
				);
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
							$result=$this->Product->update($id,$db_data);
//							$last_id = $this->Product->last_insert_id();
							$data_image=array(
								'product_id'=>$id,
								'image_name'=>$_FILES['image']['name']
							);
							if(!empty($image_name)){
								$result = $this->Product->update_image($id,$data_image);
							}
							if(empty($image_name)){
								$result = $this->Product->save_image($data_image);
							}
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
							redirect(site_url('/admin_product/register_product'));					
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						unlink($_FILES['image']['tmp_name']);
						redirect(site_url('/admin_product/register_product'));
					}	
				}else{			
					$result = $this->Product->update($id,$db_data);
					$this->Product->update_allergen($id,$pa_middle_ids,$allergen);
				}			
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
		$result=$this->Product->get_by_id_with_image($id);
		$this->data['allergen'] = $this->Product->get_allergen_by_id($id);
		$this->data['result'] = $result[0];
		$this->load->view('admin_product/admin_detail_product.php',$this->data);
	}
	
	public function register_category()
	{
		$this->data['h2title'] = 'カテゴリの追加、変更';
		$this->data['message'] = 'カテゴリを追加する場合はカテゴリ名を入力して登録ボタンを押して下さい';
		if($this->input->post('submit')){
			$category_name= $this->input->post('category_name');
			if(empty($category_name)){
				$this->data['error_message'] = 'カテゴリ名が入力されていません';
			}else{
				$data= array('name'=>$category_name);
				$result=$this->Category->save_category($data);
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
		$this->data['value'] = $result;
		if($this->input->post('submit')){
			$category_name= $this->input->post('category_name');
			if(empty($category_name)){
				$this->data['error_message'] = 'カテゴリ名が入力されていません';
			}else{
				$data= array('name'=>$category_name);
				$result=$this->Category->change_category($id,$data);
				$this->session->set_flashdata('success','登録しました');
				redirect(site_url('/admin_product/register_category'));
			}
		}
		$this->load->view('admin_product/admin_edit_category.php',$this->data);
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
							$result - $this->Allergen->update($id,$db_data);
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
					redirect(site_url('/admin_product/list_allergen'));
				}					
			}
		}
		$this->load->view('admin_product/admin_add_allergen.php',$this->data);
	}

}
