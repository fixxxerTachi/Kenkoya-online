<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/Classes/PHPExcel.php';
include __DIR__.'/../libraries/Classes/PHPExcel/IOFactory.php';

//include __DIR__.'/../libraries/common.php';
//include __DIR__.'/../libraries/Csv.php';

class Admin_order extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper('form');
		$this->load->model('Base_info');
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
		$this->load->library('my_class');
		$dbh = $this->my_class->getDb();
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
		$no_deli_date = $this->input->post('no_deli_date');
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
		$start_datetime = !empty($start) ? new DateTime($start) : new DateTime('1000-01-01 00:00:00');
		$end_datetime = !empty($end) ? new DateTime($end) : new DateTime('9999-12-30 23:59:59');
		$deli_start_datetime = !empty($dsdate) ? new DateTime($sdate) : new DateTime('1000-01-01 00:00:00');
		$deli_end_datetime = !empty($dedate) ? new DateTime($dedate) : new DateTime('9999-12-30 23:59:59');
		$start_datetime = $start_datetime->format('Y-m-d 00:00:00');
		$end_datetime = $end_datetime->modify('1 days')->format('Y-m-d 00:00:00');
		$deli_start_datetime = $deli_start_datetime->format('Y-m-d 00:00:00');
		$deli_end_datetime = $deli_end_datetime->modify('1 days')->format('Y-m-d 00:00:00');
		$this->db->select("
			o.id as orderId
			,o.order_number
			,o.create_date
			,o.shop_code
			,o.address
			,o.cource_code
			,o.payment
			,o.status_flag as order_status
			,o.delivery_charge
			,o.total_price
			,o.tax
			,od.id as order_id
			,od.order_id as orderid
			,od.product_code
			,od.branch_code
			,od.sale_price
			,od.quantity
			,od.status_flag
			,od.delivery_date
			,od.delivery_hour
			,c.code as customer_code
			,c.name
			,ad_pro.product_name
			,ca.cource_name,
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
		//orderのステータス
		if(count($status_arr) > 0) $this->db->where_in('o.status_flag',$status_arr);
		if($order_number) $this->db->where('o.order_number',$order_number);
		if($customer_code) $this->db->where('c.code',$customer_code);
		if($customer_name){
			$this->db->like('c.name',$customer_name);
			$this->db->or_like('c.furigana',$customer_name);
		}
		
		//日付け指定なしが含まれる場合
		if($no_deli_date)
		{
			$date_where = "(o.create_date >= '{$start_datetime}'
					and o.create_date < '{$end_datetime}'
					and (od.delivery_date >= '{$deli_start_datetime}'
					and od.delivery_date < '{$deli_end_datetime}'
					or od.delivery_date = '0000-00-00 00:00:00')
			)";
		}
		else
		{
			$date_where = "(o.create_date >= '{$start_datetime}'
					and o.create_date < '{$end_datetime}'
					and od.delivery_date >= '{$deli_start_datetime}'
					and od.delivery_date < '{$deli_end_datetime}'
			)";

		}
		$this->db->where($date_where);
		if($this->input->post('submit'))
		{
			$result = $this->db->get()->result();
			$this->data['result'] = $result;
			$subtotal = array();
			foreach($result as $row){
			$total = $row->sale_price * $row->quantity;
			$subtotal[] = $total;
			$this->data['total_price'] = array_sum($subtotal);
		}

		}
		if($this->input->post('makecsv'))
		{
				$result = $this->db->get()->result_array();
				$csv = '';
				$downloadCsvDir = 'download_csv/';
				$filename = 'order' . date('Y-m-d-H-i-s') . '.csv';
				$makeCsvFilename = $downloadCsvDir . $filename;
				//ファイル名にディレクトリを含めるとダウンロードされるときファイル名に変換される
				$this->load->library('csv');
				$this->csv->setData($result);
				$this->csv->getCsvMs($makeCsvFilename);
				
				////受付中は受付澄みにする
				foreach($result as $item)
				{
					if($item['order_status'] == 0)
					{
						if($item['status_flag'] == 0)
						{
							$status = array('status_flag'=>1,'csv_flag' =>1);
							$this->Order->update($item['orderid'],$status);
							$update = array('status_flag'=>1);
							$this->Order->update_order_detail($item['order_id'],$update);
						}
					}
				}
				
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $filename);
				$this->csv->getCsvMs('php://output');
				exit();
				//return redirect('admin_order/list_order');
		}
		
		if($this->input->post('makeOrderItems'))
		{
			//商品情報の読み込み
			$result = $this->db->get()->result();
			//テンプレートの読み込み
			$file = __DIR__.'/../third_party/test.xls';
			//$file = $this->config->item('excel_template');
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$book = $reader->load($file);
			
			//シートの設定
			$book->setActiveSheetIndex(0);
			$sheet = $book->getActiveSheet();
			//$sheet->setTitle('月');
			//用紙サイズをA4に設定
			$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$count = count($result);
			//商品の行を格納、order_idが変わったら0
			$counter = 0;
			//1シートのどのページに格納するか
			$page = 0;
			//シートを追加するか
			$lb_flag = 0;
			for($i = 0; $i < $count; $i++)
			{
				$sheet->setCellValue('B4',$this->Base_info->shop_name);
				$sheet->setCellValue('B5',$this->Base_info->tel);
				if(!empty($result[$i]))
				{
				/* 初回だけ入力すればよい */
					if($counter == 0)
					{
						$sheet->setTitle($result[$i]->order_number);
						$sheet->setCellValueByColumnAndRow($page + 3,2,$result[$i]->customer_code);
						$sheet->setCellValueByColumnAndRow($page + 4,2,$result[$i]->name);
						$sheet->setCellValueByColumnAndRow($page + 1,3,$result[$i]->address);
						$sheet->setCellValueByColumnAndRow($page + 6,35,$result[$i]->tax);
						$sheet->setCellValueByColumnAndRow($page + 6,36,$result[$i]->delivery_charge);
						$total_price = $result[$i]->total_price + $result[$i]->tax + $result[$i]->delivery_charge;
						$sheet->setCellValueByColumnAndRow($page + 6,37,$total_price);
					}
				/*********************/
					//$sheet->setCellValueByColumnAndRow($page + 0,$counter+8,$result[$i]->orderId);
					$sheet->setCellValueByColumnAndRow($page + 1,$counter+8,$counter+1);
					$sheet->setCellValueByColumnAndRow($page + 2,$counter+8,$result[$i]->product_name);
					$sheet->setCellValueByColumnAndRow($page + 4,$counter+8,$result[$i]->sale_price);
					$sheet->setCellValueByColumnAndRow($page + 5,$counter+8,$result[$i]->quantity);
					$sheet->setCellValueByColumnAndRow($page + 6,$counter+8,$result[$i]->sale_price * $result[$i]->quantity);
				}
				$counter++;
				//次の行があって違うオーダーなら違うセルに入力　もしくは　新しいシートを挿入する
				if(!empty($result[$i+1]))
				{
					if($result[$i]->orderId != $result[$i+1]->orderId)
					{
						//改行フラグlb_flagを入れ替え
						$lb_flag = ($lb_flag == 0) ? 1 : 0;
						//lb_flagが1同一セルの2ページ目に入力
						if($lb_flag == 1)
						{
							$counter = 0;
							$page = $page + 8;
						}
						//lb_flagが0なら新しいシートを追加
						elseif($lb_flag == 0)
						{
							$firstSheet = $book->getSheet(0);
							$copied = $firstSheet->copy();
							$copied->setTitle('');
							$sheet = $book->addSheet($copied,null);
							for($j=8; $j<=17; $j++)
							{
								$sheet->setCellValueByColumnAndRow(0,$j,'');
								$sheet->setCellValueByColumnAndRow(2,$j,'');
								$sheet->setCellValueByColumnAndRow(3,$j,'');
								$sheet->setCellValueByColumnAndRow(7,$j,'');
								$sheet->setCellValueByColumnAndRow(9,$j,'');
								$sheet->setCellValueByColumnAndRow(10,$j,'');
							}
							$counter=0;
							$page = 0;
						}
					}
				}
			}/* endfor */
			
			/*** 出力処理 ***/
			$output = '発注明細書.xls';
			//$writer = PHPExcel_IOFactory::createWriter($book,'Excel5');
			//$writer->save($output);
			
			header('Content-Type: application/vnd.ms-excel');
			ob_end_clean();
			header("Content-Disposition: attachment;filename={$output}");
			header("Cache-Control: max-age=0");
			$writer = PHPExcel_IOFactory::createWriter($book,"Excel5");
			$writer->save('php://output');
		}
		$this->data['shops'] = $this->Master_area->list_area;
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
		$this->data['order_status'] = $this->Master_order_status->order_status;
		$orderId = $this->uri->segment(3);
		$result = $this->Order->get_by_orderId($orderId);
		$products = $this->Order->get_detail_by_order_id($orderId);
		$this->data['result'] = $result;
		$this->data['products'] = $products;
		if($this->input->post('submit')){
			$order_data = array(
				'delivery_date' => $this->input->post('delivery_date'),
				'delivery_hour' => $this->input->post('delivery_hour'),
				'address' => $this->input->post('address'),
				'delivery_charge' => $this->input->post('delivery_charge'),
				'payment'=>$this->input->post('payment'),
				'status_flag'=>$this->input->post('status_flag'),
			);
			$product_data = array(
				'quantity' => $this->input->post('quantity'),
				'sale_price' => $this->input->post('sale_price'),
				'status_flag' => $this->input->post('status_flag'),
			);

//			if($this->form_validation->run() === FALSE){
//				$this->data['error_message'] = '未入力項目があります';
//			}else{
				$db_data = $input_data;
				$this->Order->update_order_detail($orderId,$db_data);
				$this->session->set_flashdata('success','登録しました');
				//redirect(base_url('/admin_order/list_order'));
//			}
		}
		$this->data['success_messgae'] = $this->session->flashdata('success');
		$this->data['payments'] = $this->Master_payment->method;
		$this->data['payments_arr'] = $this->Master_payment->show_list_arr();
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
