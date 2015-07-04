<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';

class Admin_advertise extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination'));
		$this->load->library(array('myclass'));
		$this->load->helper(array('form'));
		$this->load->model('Advertise');
		$this->load->model('Category');
		$this->load->model('Product');
		$this->load->model('Allergen');
		$this->load->model('Master_on_sale');
		$this->load->model('Advertise_image');
		$this->load->model('Master_hour');
		$this->load->model('Banner');
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
	
	public function add_advertise()
	{
		$this->data['h2title'] = '商品チラシ登録';
		$form_data = $this->Advertise;
		$this->data['form_data'] = $form_data;
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['result'] = $this->Advertise->show_list();
		$this->data['Advertise_image'] = $this->Advertise_image;
		if($this->input->post('submit')){
			$form_data = array(
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'start_datetime'=>$this->input->post('start_datetime'),
				'end_datetime'=>$this->input->post('end_datetime'),
				'start_time'=>$this->input->post('start_time'),
				'end_time'=>$this->input->post('end_time'),
				'deliver_start'=>$this->input->post('deliver_start'),
				'deliver_end'=>$this->input->post('deliver_end'),
				'create_date' => date('Y-m-d H:i:s'),
			);
			$this->form_validation->set_rules('title','タイトル','required');
			$this->form_validation->set_rules('start_datetime','開始日','required');
			$this->form_validation->set_rules('end_datetime','終了日','required');
			if($this->form_validation->run() === FALSE){
			//日付と時刻の結合
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$form_data['start_datetime'] = $form_data['start_datetime'] . ' ' . $form_data['start_time'];
				$form_data['end_datetime'] = $form_data['end_datetime'] . ' ' . $form_data['end_time'];	
				unset($form_data['start_time']);
				unset($form_data['end_time']);

				$db_data = (object)$form_data;
				$this->Advertise->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_advertise/add_advertise'));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/add_advertise',$this->data);
log_message('error',$this->Advertise_image->db->last_query());
	}
	
	public function edit_advertise(){
		$this->data['h2title'] = 'チラシデータの変更';
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['Advertise_image'] = $this->Advertise_image;
		$this->data['result'] = $this->Advertise->show_list();
		$id = $this->uri->segment(3);
		$result = $this->Advertise->get_by_id($id);
		$s_date = new DateTime($result[0]->start_datetime);
		$e_date = new DateTime($result[0]->end_datetime);
		$del_start_date = new DateTime($result[0]->deliver_start);
		$del_end_date = new DateTime($result[0]->deliver_end);
		$start_datetime = $s_date->format('Y/m/d');
		$start_time = $s_date->format('H:i:s');
		$end_datetime = $e_date->format('Y/m/d');
		$end_time = $e_date->format('H:i:s');
		$del_start_date = $del_start_date->format('Y/m/d');
		$del_end_date = $del_end_date->format('Y/m/d');
		$result[0]->start_datetime = $start_datetime;
		$result[0]->end_datetime = $end_datetime;
		$result[0]->start_time = $start_time;
		$result[0]->end_time = $end_time;
		$result[0]->deliver_start = $del_start_date;
		$result[0]->deliver_end = $del_end_date;
		$this->data['form_data'] = $result[0];
		if($this->input->post('submit')){
			$form_data= array(
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'start_datetime'=>$this->input->post('start_datetime'),
				'end_datetime'=>$this->input->post('end_datetime'),
				'start_time'=>$this->input->post('start_time'),
				'end_time'=>$this->input->post('end_time'),
				'deliver_start'=>$this->input->post('deliver_start'),
				'deliver_end'=>$this->input->post('deliver_end'),
			);
			$this->form_validation->set_rules('title','タイトル','required');
			$this->form_validation->set_rules('start_datetime','開始日','required');
			$this->form_validation->set_rules('end_datetime','終了日','required');
			if($this->form_validation->run() === FALSE){
				$this->data['form_data'] = (object)$form_data;
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$form_data['start_datetime'] = $form_data['start_datetime'] . ' ' . $form_data['start_time'];
				$form_data['end_datetime'] = $form_data['end_datetime'] . ' ' . $form_data['end_time'];	
				unset($form_data['start_time']);
				unset($form_data['end_time']);
				$db_data = (object)$form_data;
				$this->Advertise->update($id,$db_data);
				$this->session->set_flashdata('success','変更しました');
				redirect(base_url('/admin_advertise/add_advertise'));
			}
		}
		$this->load->view('admin_advertise/add_advertise',$this->data);
	}
	
	public function delete_advertise()
	{
		$id = $this->uri->segment(3);
		$this->Advertise->delete($id);
		$this->session->set_flashdata('success','削除しました');
		return redirect(base_url('admin_advertise/add_advertise'));
	}
	
	public function upload_product()
	{
		$id = $this->uri->segment(3);
		$result = $this->Advertise->get_by_id($id);
		$result = $result[0];
		$this->data['result'] = $result;
		$this->data['category'] = $this->Category->show_list_array();
		$this->data['db'] = getDb();
		$this->data['h2title'] = "{$result->title} : 商品データ一括登録";
		
//ここから		
		if(isset($_FILES['csvfile'])){
			try{
				$category_id = $this->input->post('category');
				//if($category_id == '0'){ throw new Exception('カテゴリを選択してください'); }
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				if($this->input->post('trancate')){
					$dbh->query("delete from takuhai_advertise_product where advertise_id = {$id}");
				}
				$stmt = $dbh->prepare('insert into takuhai_advertise_product(
					code,
					product_code,
					maker,
					product_name,
					size,
					sale_price,
					freshness_date,
					additive,
					calorie,
					note,
					image_name,
					image_group,
					page_x,
					page_y,
					width,
					height,
					page,
					create_date,
					advertise_id,
					category_id
				) values(
					:code,
					:product_code,
					:maker,
					:product_name,
					:size,
					:sale_price,
					:freshness_date,
					:additive,
					:calorie,
					:note,
					:image_name,
					:image_group,
					:page_x,
					:page_y,
					:width,
					:height,
					:page,
					:create_date,
					:advertise_id,
					:category_id
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':code',$row[0]);
					$stmt->bindValue(':product_code',$row[1]);
					$stmt->bindValue(':maker',$row[2]);
					$stmt->bindValue(':product_name',$row[3]);
					$stmt->bindValue(':size',$row[4]);
					$stmt->bindValue(':sale_price',$row[6]);
					$stmt->bindValue(':freshness_date',$row[9]);
					$stmt->bindValue(':additive',$row[11]);
					$stmt->bindValue(':calorie',$row[12]);
					$stmt->bindValue(':note',$row[13]);
					$stmt->bindValue(':image_name',$row[16] . $row[17]);
					$stmt->bindValue(':image_group',$row[18]);
					$stmt->bindValue(':page_x',$row[19]);
					$stmt->bindValue(':page_y',$row[20]);
					$stmt->bindValue(':width',$row[21]);
					$stmt->bindValue(':height',$row[22]);
					$stmt->bindValue(':page',$row[23]);
					$stmt->bindValue(':create_date',date('Y-m-d H:i:s'));
					$stmt->bindValue(':advertise_id',$id);
					$stmt->bindValue(':category_id',$row[24]);
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
				redirect(base_url('/admin_advertise/add_advertise'));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
//ここまで	
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/upload_product.php',$this->data);
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
					redirect(base_url('/admin_product/register_product'));
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
		$this->data['list_category'] = $this->Category->show_list_array();
		$this->data['on_sale'] = $this->Master_on_sale->on_sale;
		$advertise_id = $this->uri->segment(3);
		$ad_result = $this->Advertise->get_by_id($advertise_id);
		$this->data['h2title'] = "{$ad_result[0]->title}: 登録商品表示";
		$category_id = $this->uri->segment(4);
		$code=$this->uri->segment(5);
		$product_name = $this->uri->segment(6);
		
		$category_id = $category_id ?: '-';
		$code = $code ?: '-';
		$product_name=$product_name ?: '-';
		
		$f_category_id = (is_numeric($category_id)) ? $category_id : '';
		$f_code=(is_numeric($code)) ? $code :'';
		$f_product_name=(is_numeric($product_name)) ? $product_name : '';
		
		$this->data['ad_id'] = $advertise_id;
		$form_data = array(
			'category_id'=>$f_category_id,
			'code'=>$f_code,
			'product_name'=>$f_product_name,
		);
		$form_data = (object)$form_data;
		$config['uri_segment'] = 7;
		$config['per_page'] = 20;
		$config['base_url'] = base_url("admin_advertise/list_product/{$advertise_id}/{$category_id}/{$code}/{$product_name}/");
		if($this->input->post('search')){
			$form_data=array(
				'category_id'=>$this->input->post('category_id'),
				'code'=>$this->input->post('code'),
				'product_name'=>$this->input->post('product_name'),
			);
			$form_data = (object)$form_data;
			$offset=0;
			//parameter
			$category_id = ($form_data->category_id) ?: '-';
			$code=($form_data->code) ?: '-';
			$product_name=($form_data->product_name) ?: '-';
			$param = "{$category_id}/{$code}/{$product_name}";
			$config['base_url'] = base_url("admin_advertise/list_product/{$advertise_id}/{$param}");
			$result = $this->Advertise->get_products_by_advertise_id_with_image($advertise_id,$form_data,$config['per_page'],$offset);
			$all_result = $this->Advertise->get_products_by_advertise_id_with_image($advertise_id,$form_data);
			$config['total_rows'] = count($all_result);
			$this->data['result'] = $result;
		}else{
			$offset = $this->uri->segment(7);
			$result = $this->Advertise->get_products_by_advertise_id_with_image($advertise_id,$form_data,$config['per_page'],$offset);
			$all_result = $this->Advertise->get_products_by_advertise_id_with_image($advertise_id,$form_data);
			$config['total_rows'] = count($all_result);
			$this->data['result'] = $result;
		}
		$config['last_link'] = false;
		$config['first_link'] = false;
		$config['next_link'] = '次へ';
		$config['prev_link'] = '前へ';
		$config['cur_tag_open'] = '<span class="current">';
		$config['cur_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		$this->data['links'] =  $this->pagination->create_links();
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/list_product',$this->data);
	}
	
	public function detail_product()
	{
		$ad_id = $this->uri->segment(4);
		$product_id = $this->uri->segment(3);
		$ad_result = $this->Advertise->get_by_id($ad_id);
		$product_result = $this->Advertise->get_product_by_id_with_product($product_id);
		$this->data['ad_result'] = $ad_result[0];
		$this->data['result'] = $product_result[0];
		$this->data['allergen'] = $this->Product->get_allergen_by_id($product_result[0]->product_id);
		$this->data['h2title'] = "{$this->data['ad_result']->title}: 商品情報の詳細";
		$this->load->view('admin_advertise/detail_product.php',$this->data);
	}

	public function edit_product()
	{
		$this->data['h2title'] = '登録商品の変更';
		$this->data['message'] = '商品情報を修正して登録ボタンを押して下さい';
		$this->data['on_sale'] = $this->Master_on_sale->on_sale;
		$this->data['allergens'] = $this->Allergen->show_list();
		$id = $this->uri->segment(3);
		$ad_id = $this->uri->segment(4);
		$this->data['ad_id'] = $ad_id;
		//advertieseのproducts(master_productsと連結したもの)取得
		$ad_result = $this->Advertise->get_product_by_id_with_product($id);
		$ad_result = $ad_result[0];
		$product_id =$ad_result->product_id;
		$allergen_arr = $this->Product->get_allergen_by_id($ad_result->product_id);

		$allergens=array();
		$pa_middle_ids = array();
		foreach($allergen_arr as $a){
			$allergens[] = $a->allergen_id;
			$pa_middle_ids[] = $a->pa_middle_id;
		}
		//$allergens アレルゲンのid
		//$pa_middle_ids 中間テーブルのid
		
		$ad_result->allergens = $allergens;
		$this->data['form_data'] = $ad_result;
		
		if($this->input->post('submit')){
			//advertise
			$form_data = array(
				'code'=>$this->input->post('code'),
				'product_code'=>$this->input->post('product_code'),
				'branch_code'=>$this->input->post('branch_code'),
				'product_name'=>$this->input->post('product_name'),
				'size'=>$this->input->post('size'),
				'sale_price'=>$this->input->post('sale_price'),
				'freshness_date'=>$this->input->post('freshness_date'),
				'allergen'=>$this->input->post('allergen'),
				'on_sale'=>$this->input->post('on_sale'),
				//'image_name'=>$this->data['form_data']->image_name,
				//'image_description'=>$this->input->post('image_description'),
				//以下master_products
				'p_category_code'=>$this->data['form_data']->p_category_code,
				'p_category_name'=>$this->data['form_data']->p_category_name,
				'p_product_code'=>$this->data['form_data']->p_product_code,
				'p_branch_code'=>$this->input->post('p_branch_code'),
				'p_product_name'=>$this->data['form_data']->p_product_name,
				'p_short_name'=>$this->data['form_data']->p_short_name,
				'p_sale_price'=>$this->data['form_data']->p_sale_price,
				'p_cost_price'=>$this->data['form_data']->p_cost_price,
				'p_show_name'=>$this->input->post('p_show_name'),
				'p_on_sale'=>$this->input->post('p_on_sale'),
				'p_image_name'=>$this->data['form_data']->p_image_name,
				'p_image_description'=>$this->input->post('p_image_description'),
				'p_contents'=>$this->input->post('p_contents'),
				'jan_code'=>$this->input->post('jan_code'),
				'price1'=>$this->input->post('price1'),
				'price2'=>$this->input->post('price2'),
				'price3'=>$this->input->post('price3'),
			);
			$this->data['form_data'] = (object)$form_data;
			$this->form_validation->set_rules('code','商品コード','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$allergens = $this->input->post('allergens');
				$this->data['form_data']->allergens = $allergens;
			}else{
				$ad_product_data = array(
					'code'=>$this->data['form_data']->code,
					'product_code'=>$this->data['form_data']->product_code,
					'branch_code'=>$this->data['form_data']->branch_code,
					'product_name'=>$this->data['form_data']->product_name,
					'size'=>$this->data['form_data']->size,
					'sale_price'=>$this->myclass->convert_alpha($this->data['form_data']->sale_price),
					'freshness_date'=>$this->data['form_data']->freshness_date,
					'allergen'=>$this->data['form_data']->allergen,
					'on_sale'=>$this->data['form_data']->on_sale,
					//'image_description'=>$this->data['form_data']->image_description,
				);
				$ad_product_data=(object)$ad_product_data;
				$product_data=array(
					'branch_code' => (int)$this->data['form_data']->p_branch_code,
					'show_name' => $this->data['form_data']->p_show_name,
					'on_sale' => $this->data['form_data']->p_on_sale,
					'image_description' => $this->data['form_data']->p_image_description,
					'contents' => $this->data['form_data']->p_contents,
					'jan_code' => $this->data['form_data']->jan_code,
					'price1'=>$this->data['form_data']->price1,
					'price2'=>$this->data['form_data']->price2,
					'price3'=>$this->data['form_data']->price3,
				);
				$product_data = (object)$product_data;
				
				if(!empty($this->data['form_data']->image_name)){
					$ad_old_image = 'images/ad_products/' . $form_data['image_name'];
				}else{
					$ad_old_image = false;
				}
				if(!empty($_FILES['ad_image']['name'])){
					if(is_uploaded_file($_FILES['ad_image']['tmp_name'])){
						if(move_uploaded_file($_FILES['ad_image']['tmp_name'],'images/ad_products/'.$_FILES['ad_image']['name'])){
							$ad_product_data->image_name = $_FILES['ad_image']['name'];
							if($ad_old_image){ unlink($ad_old_image);}
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['ad_image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}
					
				
				if(!empty($this->data['form_data']->p_image_name)){
					$old_image = 'images/products/' . $form_data['p_image_name'];
				}else{
					$old_image = false;
				}
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
							$product_data->image_name = $_FILES['image']['name'];
							$this->Product->update($product_id,$product_data);
							$allergens = $this->input->post('allergens');
							$this->Product->update_allergen($product_id,$pa_middle_ids,$allergens);
							$this->Advertise->update_product($id,$ad_product_data);
							$this->session->set_flashdata('success','登録しました');
							if($old_image){ unlink($old_image);}
							redirect(base_url('admin_advertise/list_product/' . $ad_id));
							
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
							redirect(base_url('/admin_product/register_product'));
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						unlink($_FILES['image']['tmp_name']);
						redirect(base_url('/admin_product/register_product'));
					}	
				}else{
					$result = $this->Product->update($product_id,$product_data);
					$allergens = $this->input->post('allergens');
					$this->Product->update_allergen($product_id,$pa_middle_ids,$allergens);
					$this->Advertise->update_product($id,$ad_product_data);
					$this->session->set_flashdata('success','登録しました');
					redirect(base_url('admin_advertise/list_product/' . $ad_id));
				}
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/edit_product',$this->data);
	}
	
	public function upload_image()
	{
		$ad_id = $this->uri->segment(3);
		$ad_result = $this->Advertise->get_by_id($ad_id);
		$image_result = $this->Advertise_image->show_list($ad_id);
		$this->data['image_result'] = $image_result;
		$this->data['ad_result'] = $ad_result[0];
		$this->data['form_data'] = $this->Advertise_image;
		$this->data['h2title'] = "{$this->data['ad_result']->title}: 画像アップロード";
		$image_path = AD_IMAGE_PATH;
		$image_name = '';
		if($this->input->post('submit')){
			if(!empty($_FILES['image']['name'])){
//				$image_info = pathinfo($_FILES['image']['name']);
				if($this->input->post('image_name')){
					$image_name = $this->input->post('image_name');
				}else{
					$image_name = $_FILES['image']['name'];
				}
				
				$form_data=array(
					'advertise_id'=>$this->input->post('advertise_id'),
					'description'=>$this->input->post('description'),
					'start_page'=>$this->input->post('start_page'),
					'end_page'=>$this->input->post('end_page'),
					'image_name'=>$image_name,
					'create_date'=>date('Y-m-d H:i:s'),
				);
				if(is_uploaded_file($_FILES['image']['tmp_name'])){
					if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
						$result = $this->Advertise_image->save($form_data);
						rename($image_path.$_FILES['image']['name'],$image_path.$image_name);
						$this->session->set_flashdata('success','登録しました');
						redirect(base_url('/admin_advertise/upload_image/' . $ad_id));
					}else{
						unlink($_FILES['image']['tmp_name']);
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
				}
			}else{
				$this->data['error_message'] = 'ファイルが選択されていません';
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/upload_image',$this->data);
	}
	
	public function edit_image()
	{
		$image_path = AD_IMAGE_PATH;
		$id = $this->uri->segment(3);
		$ad_id = $this->uri->segment(4);
		$ad_result = $this->Advertise->get_by_id($ad_id);
		$image_result_id = $this->Advertise_image->get_by_id($id);
		$image_result = $this->Advertise_image->show_list($ad_id);
		$this->data['image_result'] = $image_result;
		$this->data['ad_result'] = $ad_result[0];
		$this->data['form_data'] = $image_result_id[0];
		$this->data['h2title'] = "{$this->data['ad_result']->title} : 画像データ変更";
		$old_image_name = $this->data['form_data']->image_name;
		if($this->input->post('submit')){
			$form_data=array(
				'advertise_id'=>$this->input->post('advertise_id'),
				'description'=>$this->input->post('description'),
				'start_page'=>$this->input->post('start_page'),
				'end_page'=>$this->input->post('end_page'),
				'image_name'=>$this->input->post('image_name'),
				'create_date'=>date('Y-m-d H:i:s'),
			);
			if(!empty($_FILES['image']['name'])){
				if($this->input->post('image_name')){
					$image_name=$this->input->post('image_name');
					$form_data['image_name'] = $image_name;
				}else{
					$form_data['image_name'] = $_FILES['image']['name'];
				}
				
				if(is_uploaded_file($_FILES['image']['tmp_name'])){
					if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
						unlink($image_path.$old_image_name);
						$result = $this->Advertise_image->update($id,$form_data);
						rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
						$this->session->set_flashdata('success','変更しました');
						redirect(base_url('/admin_advertise/upload_image/' . $ad_id));
					}else{
						unlink($_FILES['image']['tmp_name']);
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
				}
			}else{
				$result = $this->Advertise_image->update($id,$form_data);
				rename($image_path.$old_image_name,$image_path.$form_data['image_name']);
				$this->session->set_flashdata('success','変更しました');
				redirect(base_url('/admin_advertise/upload_image/' . $ad_id));
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/upload_image',$this->data);
	}
	
/*
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
				redirect(base_url('/admin_product/register_category'));
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
				redirect(base_url('/admin_product/register_category'));
			}
		}
		$this->load->view('admin_product/admin_edit_category.php',$this->data);
	}
*/
/*	
	public function upload_image()
	{
		$this->data['h2title'] = '商品画像のアップロード';
		$result = $this->Product->show_list();
		$this->data['result'] = $result;
		$this->load->view('admin_product/admin_upload_list_product.php',$this->data);
	}
*/	
/*
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
							redirect(base_url('/admin_product/list_allergen'));
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
					redirect(base_url('/admin_product/list_allergen'));
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
		$this->data['success'] = $this->session->flashdata('success');
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
							redirect(base_url('admin_product/list_allergen'));
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
					redirect(base_url('/admin_product/list_allergen'));
				}					
			}
		}
		$this->load->view('admin_product/admin_add_allergen.php',$this->data);
	}
*/

	public function delete_image()
	{
		$image_id =$this->uri->segment(3);
		$ad_id = $this->uri->segment(4);
		if($this->Advertise_image->delete_image($image_id)){
			$this->session->set_flashdata('success','削除しました');
			return redirect("admin_advertise/upload_image/{$ad_id}");
		}
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
				redirect(base_url('/admin_advertise/register_category'));
			}
		}
		$this->data['result'] = $this->Category->show_list();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/admin_category.php',$this->data);
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
				redirect(base_url('/admin_advertise/register_category'));
			}
		}
		$this->data['result'] = $this->Category->show_list();
		$this->load->view('admin_advertise/admin_category.php',$this->data);
	}
	
	public function add_banner()
	{
		$this->data['result'] = $this->Banner->show_list();
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'バナー登録';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$form_data = $this->Banner;
		$this->data['form_data'] = $form_data;
		$image_path = BANNER_IMAGE_PATH;
		if($this->input->post('submit')){
			if(!$this->input->post('end_date')){
				$end_date = '9999-12-31';
			}else{
				$end_date = $this->input->post('end_date');
			}
			$form_data=array(
				'image_name'=>$this->input->post('image_name'),
				'description'=>$this->input->post('description'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$end_date . ' ' . $this->input->post('end_time'),
				'url'=>$this->input->post('url'),
				'inside_url'=>$this->input->post('inside_url'),
				'create_datetime'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Banner->sort_order,
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
				$this->data['form_data'] = (object)$form_data;
			}else{
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
							if(!empty($form_data['image_name'])){
								rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
							}else{
								$form_data['image_name'] = $_FILES['image']['name'];
							}
							$result = $this->Banner->save($form_data);
							$this->session->set_flashdata('success','登録しました');
							redirect(base_url('/admin_advertise/add_banner'));
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					$this->data['error_message'] = 'ファイルが選択されていません';
					$this->data['form_data'] = (object)$form_data;
				}
			}
		}
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Banner->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_advertise/add_banner'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/add_banner',$this->data);
	}

	public function edit_banner()
	{
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'バナー変更';
		$id = $this->uri->segment(3);
		$form_data = $this->Banner->get_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$image_path = BANNER_IMAGE_PATH;
		$old_image = $image_path . $this->data['form_data']->image_name;
		if($this->input->post('submit')){
			if(!$this->input->post('end_date')){
				$end_date = '9999-12-31';
			}else{
				$end_date = $this->input->post('end_date');
			}
			$form_data=array(
				'image_name'=>$this->input->post('image_name'),
				'description'=>$this->input->post('description'),
				'url'=>$this->input->post('url'),
				'inside_url'=>$this->input->post('inside_url'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
			}else{
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
							if(!empty($form_data['image_name'])){
								rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
							}else{
								$form_data['image_name'] = $_FILES['image']['name'];
							}
							//前の画像名と登録画像名が異なる場合古い画像を削除
							if($old_image != $image_path . $form_data['image_name']){
								unlink($old_image);
							}
							$result = $this->Banner->update($id,$form_data);
							$this->session->set_flashdata('success','登録しました');
							redirect(base_url('/admin_advertise/add_banner'));
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					//画像がアップロードされない場合画像名変更
					rename($old_image,$image_path . $form_data['image_name']);
					$result = $this->Banner->update($id,$form_data);
					$this->session->set_flashdata('success','登録しました');
					redirect(base_url('/admin_advertise/add_banner'));
				}
			}
			$this->data['form_data'] = (object)$form_data;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_advertise/edit_banner',$this->data);	
	}
	
	public function delete_banner()
	{
		$id = $this->uri->segment(3);
		$this->Banner->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_advertise/add_banner'));
	}
	private function extentioncheck($data='')
	{
		$check = substr($data,strpos($data,'.')+1);
		if(!in_array($check,array('jpg','jpeg','png'))){
			return false;
		}
		return true;
	}
	
	public function change_show_flag_banner()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Banner->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_advertise/add_banner');
	}
	
	public function add_admin_mail()
	{
	
	}
	
	public function upload_code(){
		$this->data['h2title'] = '商品コード登録';
		$id = $this->uri->segment(3);
		if(empty($id)){
			throw new Exception('パラメータが渡されていません');
		}
		if(isset($_FILES['csvfile'])){
			try{
				$upload_file_info = uploadCsv();
				$upload_file_name = $upload_file_info['uploaded_file_name'];
				$upload_file_message = $upload_file_info['message'];
				$csv = convertCsvToDb($upload_file_name);
				$this->data['upload_message'] = $upload_file_message;
				$dbh = getDb();
				$stmt = $dbh->prepare("
					update takuhai_advertise_product set 
					product_code = :product_code,
					branch_code = :branch_code where code= :code and advertise_id  = {$id}
				");
				$error_arr = array();
//var_dump($csv);exit;
				foreach($csv as $row){
					$codes = explode('-',$row[1]);
					$stmt->bindValue(':code',(int)$row[0]);
					$stmt->bindValue(':product_code',$codes[0]);
					$stmt->bindValue(':branch_code',$codes[1]);
					$stmt->execute();
					$error_info=$stmt->errorInfo();
					$error_arr[] = $stmt->errorInfo();
					if($error_info[0] != '00000'){
						$this->data['error_message'] = 'データベースの登録に失敗しました';
						break;
					}
				}
				$counter = 0;
				$counter = 0;
				foreach($error_arr as $err){
					if($err[0] == '00000'){
						$counter += 1;
					}
				}
				$this->session->set_flashdata('success',"{$counter}件更新しました");
				redirect(base_url("/admin_advertise/upload_code/{$id}"));
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view("admin_advertise/upload_code",$this->data);
	}
}
