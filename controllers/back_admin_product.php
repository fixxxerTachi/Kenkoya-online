<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/csv.php';

class Admin_product extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category');
		$this->data['side'] = array(
			site_url('/admin_product/upload') =>'商品一括登録',
			site_url('/admin_product/register') =>'商品個別登録',
			site_url('/admin_product/show_category') =>'カテゴリの追加・変更',
			
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
				$stmt = $dbh->prepare('insert into takuhai_yotubatushin(
					product_code,
					code,
					maker,
					product_name,
					size,
					cost_price,
					sale_price,
					profit,
					profit_ratio,
					freshness_date,
					additive,
					allergen,
					calorie,
					note,
					create_date,
					update_date
				) values(
					:product_code,
					:code,
					:maker,
					:product_name,
					:size,
					:cost_price,
					:sale_price,
					:profit,
					:profit_ratio,
					:freshness_date,
					:additive,
					:allergen,
					:calorie,
					:note,
					:create_date,
					:update_date
				)');

				$error_arr=array();
				foreach($csv as $row){
					$stmt->bindValue(':code',$row[0]);
					$stmt->bindValue(':product_code',$row[1]);
					$stmt->bindValue(':maker',$row[2]);
					$stmt->bindValue(':product_name',$row[3]);
					$stmt->bindValue(':size',$row[4]);
					$stmt->bindValue(':cost_price',$row[5]);
					$stmt->bindValue(':sale_price',$row[6]);
					$stmt->bindValue(':profit',$row[7]);
					$stmt->bindValue(':profit_ratio',$row[8]);
					$stmt->bindValue(':freshness_date',$row[9]);
					$stmt->bindValue(':additive',$row[10]);
					$stmt->bindValue(':allergen',$row[11]);
					$stmt->bindValue(':calorie',$row[12]);
					$stmt->bindValue(':note',$row[13]);
					$stmt->bindValue(':create_date',date('Y-m-d H:i:s'));
					$stmt->bindValue(':update_date',date('Y-m-d H:i:s'));
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
				$this->data['db_message']= "{$counter} 件データベースに登録しました";
			}catch(Exception $e){
				$this->data['error_message'] = $e->getMessage();
			}
			$dbh = null;
		}
//ここまで		
				
		$this->load->view('admin_product/admin_product.php',$this->data);
	}
	
	public function register()
	{
		$this->upload();
	}
	
	public function show_category()
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
				$this->data['success_message'] = '登録しました';
			}
		}
		$this->data['result'] = $this->Category->show_list();
		$this->load->view('admin_product/admin_category.php',$this->data);
	}
	
	public function change_category()
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
				$this->data['success_message'] = '登録しました';
				redirect(site_url('/admin_product/show_category'));
			}
		}
		$this->load->view('admin_product/admin_change_category.php',$this->data);
	}
}
