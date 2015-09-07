<?php
//include __DIR__.'/../libraries/define_flag.php';
class Order extends CI_Model{
	public $tablename;
	public $detail_tablename;
	public $id;
	public $order_number;
	public $customer_id;
	public $product_id;
	public $advertise_id;
	public $advertise_product_id;
	public $sale_product_id;
	public $quantity;
	public $delivery_date;
	public $shop_code;
	public $customer_code;
	public $cource_code;
	public $access_id;
	public $access_pass;
	//public $csv_flag;
	public $create_date;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_order';
		$this->detail_tablename = 'takuhai_order_detail';
		$this->account_number = '';
	}
	
	/** 出荷済みにする
	* @param array ids
	* @return string ErrorMessage
	*/
	public function change_shipped(array $ids)
	{
		$this->load->model('Credit');
			foreach($ids as $id){
				$order = $this->get_by_id($id);
				if($order->payment == PAYMENT_CREDIT)
				{
					$output = $this->Credit->change_tran($order->order_number);
					if($output->isErrorOccurred())
					{
						$message = $this->Credit->getAlterErrorMessages($output);
						return reset($message);
					}
				}
				$this->db->trans_begin();
				$this->db->where('id',$id);
				$this->db->update($this->tablename,array('status_flag'=>4,'shipped_date'=>date('Y-m-d')));
				$this->db->where('order_id',$id);
				$this->db->update('order_detail',array('status_flag'=>4));
				if($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
			}
	}
	
	/** 受付ずみにする
	* @param array ids
	* @return string ErrorMessage
	*/
	public function change_recieved(array $ids)
	{
		$this->db->trans_begin();
		foreach($ids as $id)
		{
			$order = $this->get_by_id($id);
			$this->db->where('id',$id);
			$this->db->update($this->tablename,array('status_flag'=>1));
			$this->db->where('order_id',$id);
			$this->db->update($this->detail_tablename,array('status_flag'=>1));
			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return show_error('Error has occured');
			}
			else
			{
				$this->db->trans_commit();
			}
		}
	}
	
	/** Orderの更新修正処理
	* @param int order_id
	* @param array postdata
	* @param array product_data
	*/
	public function update_order($order_id,$post_data)
	{
		$data = (object)$post_data;
		$order_data = array(
			'delivery_date'=>$data->delivery_date,
			'delivery_hour'=>$data->delivery_hour,
			'address'=>$data->address,
			'delivery_charge'=>$data->delivery_charge,
			'payment'=>$data->payment,
			'status_flag'=>$data->status_flag,
		);
		$this->db->trans_begin();
		$this->db->where('id',$order_id);
		$this->db->update($this->tablename, $order_data);
		$count = $data->count;
		for($i = 0; $i <= $count; $i++)
		{
			$product_data = array(
				'quantity'=>$data->quantity_{$i},
				'sale_price'=>$data->sale_price_{$i},
				'status_flag'=>$data->status_flag,
			);
			$detail_id = $data->order_detail_id_{$i};
			$this->db->where('id',$detail_id);
			$this->db->update($this->detail_tablename,$product_data);
		}
		if($this->db->trans_status === FALSE)
		{
			$this->db->trans_rollback();
			return show_error('failed');
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
	
	/** 受付済みで未発送一覧を取得する
	* @return object 受付済みのorder
	*/
	public function get_recieved()
	{
		$this->db->where('csv_flag',1);
		$this->db->where('status_flag',1);
		$this->db->order_by('delivery_date','desc');
		$this->db->order_by('delivery_hour','desc');
		$query = $this->db->get($this->tablename);
		return $query->result();
	}
	
	/** オーダー番号を取得する
	 * @param object Customer
	 * @return string order_number
	 */
	public function create_order_number(StdClass $customer)
	{
		if($customer->username != 'no-member'){
			$this->db->select('shop_code,code')->from('customers');
			$this->db->where('username',$customer->username);
			$result = $this->db->get()->row();
			//エリア外の場合shop_codeは0なので3ケタ表示する
			$shop_code = sprintf("%03d",$result->shop_code);
			$result = date('ymd-his') . '-' . $shop_code . $result->code;
		}else{
			$shop_code = sprintf("%03d",$customer->shop_code);
			$result = date('ymd-his') . '-' . $shop_code . $customer->code;
		}
		return $result;
	}
	
	/** order_idとcustomerから購入商品情報を取得する **/
	/* @param int order_id
	/* @params int customer_id
	/* @return object
	*/
	public function get_by_id_customer($order_id , $customer_id)
	{
		$this->db->select("
			o.order_number,
			o.create_date,
			o.shop_code,
			o.address,
			o.cource_code,
			o.payment,
			o.total_price,
			o.tax,
			o.delivery_date,
			o.delivery_hour,
			o.delivery_charge,
			c.code,
			ca.cource_name,
		");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_id = o.id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_cource as ca','ca.cource_code = o.cource_code');
		$this->db->where('c.shop_code = ca.shop_code');
		$this->db->where('o.id ',$order_id);
		$this->db->where('o.customer_id',$customer_id);
		$row = $this->db->get()->row();
		return $row;
	}

	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_order_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
	}
	
	public function show_list_with_detail()
	{
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,p.product_code,p.product_name,p.sale_price,c.name,c.zipcode,a.cource_name,a.takuhai_day");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');	
		return $this->db->get()->result();
	}
	
	public function get_by_order_number($order_number)
	{
		$this->db->select("
			o.order_number,
			o.create_date,
			od.quantity,
			od.delivery_date,
			od.delivery_hour,
			od.sale_price,
			c.name,
			ap.product_name,
		");
		$this->db->from('order as o');
		$this->db->where('o.order_number',$order_number);
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('advertise_product as ap','ap.id = od.advertise_product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		return $this->db->get()->result();
	}
		
	public function get_by_order_id($order_id = null)
	{
		$this->db->select("
			o.order_number
			,o.create_date
			,o.shop_code
			,o.address
			,o.cource_code,
			od.id as order_id
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
			,ca.cource_name
			,ca.takuhai_day
			,ct.takuhai_day
		");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_id = o.id','left');
		$this->db->where('od.id',$order_id);
		//$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		//$this->db->join('master_area as a','a.zipcode = c.zipcode','left');
		$this->db->join('master_cource as ca','ca.cource_code = o.cource_code','left');
		//$this->db->join('advertise as ad','ad.id = od.advertise_id','left');
		$this->db->join('advertise_product as ad_pro','ad_pro.id = od.advertise_product_id','left');
		$this->db->join('master_cource_type as ct','ct.id = ca.cource_type_id','left');
		$this->db->where('c.shop_code = ca.shop_code');
		return $this->db->get()->row();
	}
	
	public function get_by_customer_id($customer_id = null,$formobj=null,$limit = null)
	{
		$this->db->select(
			'o.*,c.name'
		);
		$this->db->from($this->tablename . ' as o');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		if($customer_id){
			$this->db->where('customer_id',$customer_id);
		}
		if($formobj){
			if(!empty($formobj->start_date)){
				$start_datetime = new DateTime($formobj->start_date);
				$start_date = $start_datetime->format('Y-m-d H:i:s');
				$this->db->where('o.create_date >= ' , $start_date);
			}
			if(!empty($formobj->end_date)){
				$end_datetime = new DateTime($formobj->end_date);
				$end_date = $end_datetime->format('Y-m-d H:i:s');
				$this->db->where('o.create_date <= ' , $end_date);
			}
		}
		if($limit)
		{
			$this->db->limit($limit);
		}
		$this->db->order_by('create_date','desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_order_by_customer_with_detail($customer_id = null)
	{
		$this->db->select('
			o.id as order_id
			,o.customer_id
			,o.order_number
			,o.csv_flag
			,o.create_date
			,o.delivery_date
			,od.advertise_id
			,od.advertise_product_id
			,od.quantity
			,od.cancel_flag
			,p.id as product_id
			,p.product_name
			,p.sale_price
			,p.image_name
			,c.name
			,ad.title as advertise_title
		');
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_id = o.id','left');
		$this->db->join('advertise_product  as p','p.id = od.advertise_product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('advertise as ad','ad.id = od.advertise_id','left');
		if($customer_id){ $this->db->where('o.customer_id',$customer_id);}
		$this->db->order_by('create_date','desc');
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	public function get_by_orderId($orderId)
	{
		$this->db->select('
			o.*
			,c.name
			,c.code
			,c.tel
			,c.email
		')->from($this->tablename .' as o');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->where('o.id',$orderId);
		$result = $this->db->get()->result();
		return $result;
	}
	
	public function get_detail_by_order_id($order_id = Null)
	{
		$this->db->select('
			od.id as order_detail_id
			,od.advertise_id
			,od.advertise_product_id
			,od.quantity
			,od.status_flag
			,od.delivery_date
			,od.delivery_hour
			,od.sale_price
			,ap.id as product_id
			,ap.product_name
			,ap.product_code
			,ap.branch_code
		');
		$this->db->from('order_detail as od');
		$this->db->join('advertise_product as ap','ap.id = od.advertise_product_id','left');
		//$this->db->join('advertise as ad','ad.id = od.advertise_id','left');
		//$this->db->join('master_products as p','p.product_code = ap.product_code','left');
		if($order_id){ $this->db->where('od.order_id',$order_id); }
		$this->db->order_by('od.create_date','desc');
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	public function get_detail_by_id($order_detail_id = null)
	{
		$this->db->select('
			o.id as order_id
			,o.customer_id
			,o.order_number
			,o.csv_flag
			,o.create_date
			,od.delivery_date
			,od.advertise_id
			,od.advertise_product_id
			,od.quantity
			,od.status_flag
			,p.id as product_id
			,p.product_name
			,p.sale_price
			,p.image_name
			,p.product_code
			,c.name
			,ad.title as advertise_title
		');
		$this->db->from('order_detail as od');
		$this->db->join('order as o','o.id = od.order_id');
		$this->db->join('advertise_product as p','p.id = od.advertise_product_id','left');
		$this->db->join('advertise as ad','ad.id = od.advertise_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		if($order_detail_id){$this->db->where('od.id',$order_detail_id);}
		$query = $this->db->get();
		$result = $query->result();
		return $result[0];
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	
	public function save_order($data = array())
	{
		$this->db->insert('order_detail',$data);
	}
	
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function get_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->row();
		return $result;
	}
	
	public function get_by_id_with_detail($order_id)
	{
		$this->db->select('
			o.*
			,od.id as order_detail_id
			,od.advertise_product_id
			,od.product_code
			,od.quantity
			,od.sale_price
			,p.product_name
		');
		$this->db->from($this->tablename . ' as o');
		$this->db->join('order_detail as od','od.order_id = o.id','left');
		$this->db->join('advertise_product as p','p.id = od.advertise_product_id','left');
		$this->db->where('o.id',$order_id);
		$result = $this->db->get()->result();
		return $result;
	}
	
	public function get_by_id_with_image($id=null)
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,cost_price,on_sale,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$this->db->where("{$this->tablename}.id",$id);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		//$this->db->update($this->tablename . '_detail',$data);
		$this->db->update($this->tablename,$data);
	}
	public function update_by_order_number($order_number = null,$date = array())
	{
		$this->db->where('order_number',$order_number);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_order_detail($id,$data)
	{
		$this->db->where('id',$id);
		$this->db->update('order_detail',$data);
	}
	
	public function update_order_detail_flag(object $order_obj, array $data)
	{
		foreach($order_obj as $obj)
		{
			$this->db->where('id',$obj->order_detail_id);
			$this->db->update('order_detail' , $data);
		}
	}
		
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
}
