<?php
class Advertise extends CI_Model{
	public $tablename;
	public $id;
	public $title;
	public $description;
	public $release_start_datetime;
	public $release_start_time;
	public $release_end_datetime;
	public $release_end_time;
	public $start_time;
	public $start_datetime;
	public $end_time;
	public $end_datetime;
	public $deliver_start;
	public $deliver_end;
	public $def_flag;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'advertise';
		$this->product_tablename='advertise_product';
	}
	
	public function show_list($del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->order_by('create_date','desc');
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	
	public function show_advertise_with_image()
	{
		$this->db->select('a.id,a.title,a.description,a.start_datetime,a.end_datetime,ai.image_name');
		$this->db->from($this->tablename . ' as a');
		$this->db->join('advertise_image as ai','ai.advertise_id = a.id','left');
		$today = new DateTime();
		$today = $today->format('Y-m-d H:i:s');
		$this->db->where('a.release_start_datetime <= ',$today);
		$this->db->where('a.release_end_datetime >',$today);
		$this->db->where('a.del_flag',0);
		$this->db->where('ai.start_page',1);
		$this->db->order_by('a.update_date','desc');
		$this->db->order_by('a.id','asc');
		$this->db->group_by('ai.advertise_id');
		return $this->db->get()->result();
	}
	
	public function get_by_id_with_advertise_image($advertise_id = null, $del_flag=True)
	{
		$this->db->select('a.id,a.title,a.description,a.start_datetime,a.end_datetime,ai.image_name,ai.description,ai.start_page,ai.end_page');
		$this->db->from($this->tablename . ' as a');
		$this->db->join('advertise_image as ai','ai.advertise_id = a.id','left');
		if($del_flag){ $this->db->where('a.del_flag',0);}
		$query=$this->db->get();
		$result = $query->result();
		return $result[0];
	}
	
	public function show_list_arr($del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->where('end_datetime > ' , date('Y-m-d'));
		$this->db->order_by('create_date','desc');
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		$arr = array();
		$arr[0] = '選択してください';
		foreach($result as $row){
			$arr[$row->id] = $row->title;
		}
		return $arr;
	}
	
	public function show_list_arr_no_limit()
	{
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		$arr = array();
		$arr[0] = '選択してください';
		foreach($result as $row)
		{
			$arr[$row->id] = $row->title;
		}
		return $arr;
	}
	
	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$this->db->where("{$this->tablename}.del_flag",0);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_products_by_advertise_id($id = null)
	{
		$this->db->where('advertise_id',$id);
		$this->db->where('del_flag',0);
		$query = $this->db->get($this->product_tablename);
		$result = $query->result();
		return $result;
	}
	
	public function is_products_by_advertise_id($id=null)
	{
		if(!empty($id)){ throw new Exception('no id'); }
		$this->db->select('id')->from($this->product_tablename);
		$result = $this->db->get()->result();
		if(!empty($result)){
			return True;
		}else{
			return False;
		}
	}
	
	public function get_products_by_advertise_id_with_image($advertise_id=null , $obj = null,$limit=null,$offset=null)
	{
		$this->db->select("
			a.id,a.code
			,a.product_code
			,a.branch_code
			,a.maker
			,a.product_name
			,a.size,a.sale_price,a.on_sale
			,a.freshness_date
			,a.additive
			,a.image_name
			,a.image_description
			,a.allergen
			,a.calorie
			,a.note
			,a.page
			,a.category_id
			,p.id as product_id
			,p.product_code as p_code
			,p.branch_code as p_branch_code
			,p.product_name as p_name
			,p.short_name
			,a.sale_start_datetime
			,a.sale_end_datetime
			,a.delivery_start_datetime
			,a.delivery_end_datetime
		");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
//		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.advertise_id' , $advertise_id);
		$this->db->where('p.branch_code','a.branch_code');
		if($obj){
			if($obj->category_id){
				$this->db->where('a.category_id',$obj->category_id);
			}
			if($obj->code){
				$this->db->where('a.code',$obj->code);
			}
			if($obj->product_name){
				$this->db->like('a.product_name',$obj->product_name);
			}
			if($limit && !is_null($offset)){
				$this->db->limit($limit,$offset);
			}
		}
		$query=$this->db->get();
		return $query->result();
	}
		
	public function get_product_by_id($product_id = Null)
	{
		$this->db->where('id',$product_id);
		$query = $this->db->get($this->product_tablename);
		return $query->result();
	}
	
	public function get_product_by_id_with_product($product_id = null)
	{
		$this->db->select("a.*
			,p.id as product_id
			,p.category_code as p_category_code
			,p.category_name as p_category_name
			,p.vendor_code as p_vendor_code
			,p.vendor_name as p_vendor_name
			,p.product_code as p_product_code
			,p.branch_code as p_branch_code
			,p.product_name as p_product_name
			,p.short_name as p_short_name
			,p.sale_price as p_sale_price
			,p.cost_price as p_cost_price
			,p.on_sale as p_on_sale
			,p.adddate as p_adddate
			,p.moddate as p_moddate
			,p.create_date as p_create_date
			,p.update_date as p_update_date
			");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->where('a.id' , $product_id);
		$this->db->join('master_products as p','a.product_code = p.product_code','left');
		//$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('p.branch_code = a.branch_code');
		$query=$this->db->get();
		return $query->row();
	}
	
	/** 合計金額を計算する
	 * @param array carts
	 * @param int charge_price
	 * @param int use_point
	 * @return StdClass total_price,tax_price,amount,list_product,use_point
	 */
	 /* point値引きを商品合計でなく個別の商品から引く方法に変更、消費税を個別の商品合計から計算する方法に変更したため*/
	 /* disconunt廃止 */
	public function get_total(array $carts,$order_info)
	{
		$this->load->model('Tax');
		$tax = $this->Tax->get_current_tax();
		$list_product = array();
		$total_price = array();
		//$tax_price = array();
	   	$amount = 0;
		$charge_price = $order_info->charge_price;
		
		//$point 処理用に使用する変数
		$use_point = 0;
		//$show_use_point 表示用の変数
		$point = $use_point;
		$show_use_point = $use_point;
		foreach($carts as $cart){
			$c = unserialize($cart);
			$product = $this->get_product_by_id_with_product($c->product_id);
			$product->quantity = $c->quantity;
			$product->subtotal = $product->sale_price * $c->quantity;
			$total_price[] = $product->subtotal;
			$list_product[] = $product;
			//ポイントを引いた消費税の計算(現在処理中止)
			$subtotal  = $product->subtotal;
			//$tax_price[] = floor($subtotal * $tax);
		}
		$total_price = array_sum($total_price);
		//$discounted = $total_price - (int)$use_point;
		//$tax_price = array_sum($tax_price);
		//$tax_price = floor($tax * $discounted);
		$tax_price = floor($tax * $total_price);
		$amount = $charge_price + $tax_price + $total_price - $show_use_point; 
		$get_point = floor($total_price / 1000);
		$obj = new StdClass();
		$obj->total_price = $total_price;
		$obj->use_point = $show_use_point;
		$obj->discounted = $total_price;
		$obj->tax_price = $tax_price;
		$obj->amount = $amount;
		$obj->list_product = $list_product;
		$obj->get_point = $get_point;
		$obj->charge_price = $charge_price;
		return $obj;
	}

	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null , $del_flag=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$result =  $query->row();
		return $result;
	}
	
	
	public function get_by_id_with_image($id=null,$del_flag=null)
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,cost_price,on_sale,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$this->db->where("{$this->tablename}.id",$id);
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_allergen_by_id($product_id=null){
		$this->db->select('p.id as product_id, a.name as allergen_name , a.icon as icon, a.id as allergen_id, pa.id as pa_middle_id');
		$this->db->from("{$this->tablename} as p ");
		$this->db->join('product_allergen as pa','pa.product_id = p.id','left');
		$this->db->join('allergen as a','a.id = pa.allergen_id','left');
		$this->db->where( 'p.id' ,$product_id );
		$query = $this->db->get();
		return $query->result();
	}	
	
	public function save_allergen($posts=false)
	{
		if($posts){
			$product_id= $this->db->insert_id();
			foreach($posts as $allergen_id){
				$db_data = array(
					'product_id' => $product_id,
					'allergen_id' => $allergen_id,
				);
				$this->db->insert('product_allergen',$db_data);
			}
		}
	}
	
	public function update_allergen($product_id = null , $ids = array() , $posts=array()){
		foreach($ids as $id){
			$this->db->where('id',$id);
			$this->db->delete('product_allergen');
		}
		if($posts){
			foreach($posts as $allergen_id){
				$db_data = array(
					'product_id'=>$product_id,
					'allergen_id'=>$allergen_id,
				);
				$this->db->insert('product_allergen',$db_data);
			}
		}
	}
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_product($product_id = null,$data=array())
	{
		$this->db->where('id',$product_id);
		$this->db->update('advertise_product',$data);
	}
	
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function save_image($data=array())
	{
		$this->db->insert('product_image',$data);
	}
	
	public function update_image($product_id=null, $data=array())
	{
		$this->db->where('product_id',$product_id);
		$this->db->update('takuhai_product_image',$data);
	}
	
	public function delete($id)
	{
		$this->db->where('id',$id);
		$data=array('del_flag'=>1);
		$this->db->update($this->tablename,$data);
	}
}		
