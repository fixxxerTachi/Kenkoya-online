<?php
include __DIR__.'/../libraries/define.php';
//include __DIR__.'/../libraries/common.php';
//include __DIR__.'/../libraries/Csv.php';

class Admin_order extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper('form');
		$this->load->model('Order');
		$this->load->model('Master_order_status');
		$this->load->model('Master_area');
		$this->load->model('Master_payment');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->load->model('Admin_login');
		$this->data['user'] = $this->Admin_login->check_login();
		$this->load->model('Master_takuhai_hours');
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
	public function download()
	{
		$this->data['h2title'] = '受注リストダウンロード';
		$result= array();

		$where = '';
		$checked = 'today';
		$this->load->library('myclass');
		$dbh = $this->myclass->getDb();
		$where = 'where ad_pro.branch_code = p.branch_code and o.csv_flag = ? and o.status_flag = ?';
		$sql = 'select o.id,o.order_number,o.create_date,od.id as order_id,od.advertise_id,od.advertise_product_id,od.quantity,od.delivery_date,c.code,c.name,c.zipcode,a.cource_name,a.takuhai_day,ad_pro.product_code,ad_pro.product_name,ad_pro.sale_price ';
		$sql.= 'from takuhai_order as o left join takuhai_order_detail as od on od.order_number = o.order_number ';
		$sql.= 'left join takuhai_customers as c on c.id = o.customer_id ';
		$sql.= 'left join takuhai_master_area as a on a.zipcode = c.zipcode ';
		$sql.= 'left join takuhai_advertise_product as ad_pro on ad_pro.id = od.advertise_product_id ';
		$sql.= 'left join takuhai_master_products as p on p.product_code = ad_pro.product_code ';
		$sth = $dbh->prepare($sql . $where);
		//csvフラグが0で受付中を抽出
		$sth->execute(array(0,0));
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$ids = array_map(function($arr){
			return $arr['id'];
		},$result);
		$order_ids = array_map(function($arr){
			return $arr['order_id'];
		},$result);
//echo '<pre>';print_r($order_ids);echo '</pre>';exit;
		if(isset($_POST['submit'])){
			try{
				//csvダウンロード時csv_flag=1
				//$where = 'where id between 1 and 5';
				$sth = $dbh->prepare('update takuhai_order set csv_flag = ? , status_flag = ? where id in (' . implode(',',$ids) . ')');
				$sth->execute(array(1,1));
				$sth = $dbh->prepare('update takuhai_order_detail set status_flag = ? where id in (' . implode(',',$order_ids) . ')');
				$sth->execute(array(1));
			
				$csv = '';
				$downloadCsvDir = 'download_csv/';
				$filename = 'order' . date('Y-m-d-H-i-s') . '.csv';
				$makeCsvFilename = $downloadCsvDir . $filename;
				//ファイル名にディレクトリを含めるとダウンロードされるときファイル名に変換される
				//$csv = new Csv();
				$this->load->library('csv');
				$this->csv->setData($result);
				$this->csv->getCsvMs($makeCsvFilename);
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $filename);
				$this->csv->getCsvMs('php://output');
				exit();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		$this->data['result'] = $result;
		$this->load->view('admin_order/admin_order.php',$this->data);
	}
	
	public function list_order()
	{
		$this->data['h2title'] = '受注リスト';
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$order_number = $this->input->post('order_number');
		$customer_code = $this->input->post('customer_code');
		$customer_name = $this->input->post('customer_name');
		$start = $this->input->post('start_date');
		$end = $this->input->post('end_date');
		$status_arr = $this->input->post('status');
		$deli_start = $this->input->post('deliver_start_date');
		$deli_end = $this->input->post('deliver_end_date');
		//受付中にチェック
		if(!$status_arr) $status_arr=array(
			'0',
		);
		$form_data = array(
			'order_number'=>$order_number,
			'customer_code'=>$customer_code,
			'customer_name'=>$customer_name,
			'start_date'=>$start,
			'end_date'=>$end,
			'deliver_start_date'=>$deli_start,
			'deliver_end_date'=>$deli_end,
			'status_arr'=>$status_arr,
		);
		$this->data['form_data'] = (object)$form_data;
		$sdate = new DateTime($start);
		$edate = new DateTime($end);
		$dsdate = new DateTime($deli_start);
		$dedate = new DateTime($deli_end);
		$start_datetime = $sdate->format('Y-m-d 00:00:00');
		$end_date = $edate->modify('1 days');
		$end_datetime = $edate->format('Y-m-d 00:00:00');
		$deli_start_datetime = $dsdate->format('Y-m-d 00:00:00');
		$deli_end_date = $dedate->modify('1 days');
		$deli_end_datetime = $dedate->format('Y-m-d 00:00:00');
		$this->db->select("
			o.order_number,o.create_date,o.shop_code,o.address,o.cource_code,o.payment,
			od.id as order_id,od.product_code,od.branch_code,od.sale_price,od.quantity,od.status_flag,od.delivery_date,od.delivery_hour,
			c.code as customer_code,c.name,
			ad_pro.product_name,
			ca.cource_name,
		 ");
		$this->db->from('order as o');
		//$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		//とりあえずorder_idで連結しておく、order_numberの方が良いかも
		$this->db->join('order_detail as od','od.order_id = o.id','left');
		//とりあえずcustomer_idで連結しておく,customer_codeとshop_codeのほうが良いかも
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('advertise_product as ad_pro','ad_pro.id = od.advertise_product_id','left');
		$this->db->join('master_cource as ca','ca.cource_code = o.cource_code');
		$this->db->where('c.shop_code = ca.shop_code');
		$this->db->order_by('o.id','desc');
		
		//条件絞り込み
		if(count($status_arr) > 0) $this->db->where_in('o.status_flag',$status_arr);
		if($order_number) $this->db->where('o.order_number',$order_number);
		if($customer_code) $this->db->where('c.code',$customer_code);
		if($customer_name){
			$this->db->like('c.name',$customer_name);
			$this->db->or_like('c.furigana',$customer_name);
		}
		if($start) $this->db->where('o.create_date >=' ,$start_datetime);
		if($end) $this->db->where('o.create_date <' , $end_datetime);
		if($deli_start) $this->db->where('od.delivery_date >=' , $deli_start_datetime);
		if($deli_end) $this->db->where('od.delivery_date <' , $deli_end_datetime);
		$result = $this->db->get()->result();
		$subtotal = array();
		foreach($result as $row){
			$total = $row->sale_price * $row->quantity;
			$subtotal[] = $total;
		}
		$this->data['shops'] = $this->Master_area->list_area;
		$this->data['total_price'] = array_sum($subtotal);
		$this->data['result'] = $result;
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_order/list_order',$this->data);
	}
	
	public function detail_order()
	{
		$this->data['h2title'] = '受注詳細';
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$order_number = $this->uri->segment(3);
		$this->data['result'] = $this->Order->get_by_order_number($order_number);
		$this->load->view('admin_order/detail_order.php',$this->data);
	}	
	
	public function edit_order()
	{
		$this->data['h2title'] = '受注データ変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$this->data['status_flag'] = $this->Master_order_status->order_status;
		$this->data['takuhai_hours'] = $this->Master_takuhai_hours->hours;
		$order_detail_id = $this->uri->segment(3);
		$result = $this->Order->get_by_order_id($order_detail_id);
		$this->data['result'] = $result;
		if($this->input->post('submit')){
			$input_data = array(
				'quantity' => $this->input->post('quantity'),
				'delivery_date' => $this->input->post('delivery_date'),
				'delivery_hour' => $this->input->post('delivery_hour'),
				'status_flag' => $this->input->post('status_flag'),
			);
//			if($this->form_validation->run() === FALSE){
//				$this->data['error_message'] = '未入力項目があります';
//			}else{
				$db_data = $input_data;
				$this->Order->update_order_detail($order_detail_id,$db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_order/list_order'));
//			}
		}
		$this->load->view('admin_order/edit_order.php',$this->data);
		
	}
	
	public function list_payment()
	{
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['hours'] = $this->Master_takuhai_hours->hours;
		$this->data['h2title'] = '配達済み登録';
		$this->data['result'] = $this->Order->get_recieved();
		if($this->input->post('shipped'))
		{
			$ids = $this->input->post('shipped');
			//クレジットで売上処理が失敗した場合エラーメッセージが返る
			$message = $this->Order->change_shipped($ids);
			if(!empty($message)){
				$this->data['error_message'] = $message;
			}else{
				$this->session->set_flashdata('success','更新しました');
				return redirect('admin_order/list_payment');
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');	
		$this->load->view('admin_order/list_payment',$this->data);
	}
}