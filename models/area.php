<?php
class Area extends CI_Model{
	public $tablename;
	public $zipcode;
	public $prefecture;
	public $city;
	public $product_id;
	public $address;
	public $cource_id;
	public $cource_name;
	public $takuhai_day;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_master_area';
		$this->account_number = '';
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
	
	public function get_cource_by_zipcode($zipcode = null)
	{
		$this->db->select('a.id as area_id ,a.zipcode,c.cource_id,c.takuhai_day,ct.first,ct.second');
		$this->db->from($this->tablename . ' as a');
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		$this->db->join('master_cource_type as ct','ct.id = c.cource_type');
		if($zipcode){
			$this->db->where('zipcode',$zipcode);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result[0];
	}
	
	public function list_area()
	{
		$this->db->select('distinct city',false);
		$this->db->from($this->tablename);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function list_area_by_area_id($area_id){
		if(!is_numeric($area_id)){ throw new Exception('NO-NUMERIC'); }
		$this->db->select('distinct city', false)->from($this->tablename);
		$this->db->where('area_id',$area_id);
		return $this->db->get()->result();
	}
	
	public function list_area_by_area($city , $initial = null){
		$this->db->select('address,furigana_area,city,zipcode')->from($this->tablename);
		$this->db->where('city',urldecode($city));
		if($initial){
			$this->db->like('furigana_area',$initial,'after');
		}
		return $this->db->get()->result();
	}
	
	public function delivery_select_date($obj = null,$after = '',$self,$week_days)
	{
		$date = new DateTime();
		//配達曜日の数値を取得
		$first = $obj->first;
		$second = $obj->second;
		//本日から〇日後の日付
		$date_after = $date->modify($after);
		//本日から〇日後の曜日の数値(配達指定曜日)
		$possible_day = $date_after->format('w');
		$data = new StdClass();
		$select_date = array();
		
		//配達指定曜日が配達日１と２の間にあったら2番目の配達日から3日分のの日付を取得して配列化
		if($first <= $possible_day && $possible_day <= $second){
			$select_date[] = $date_after->modify($self->Master_days->edays[$second])->format('Y-m-d H:i:s'); 
			$select_date[] = $date_after->modify($self->Master_days->edays[$first])->format('Y-m-d H:i:s'); 
			$select_date[] = $date_after->modify($self->Master_days->edays[$second])->format('Y-m-d H:i:s'); 
		}
		//配達指定曜日が配達日２と1の間にあったら1番目の配達日から3日分の日付をしゅとくして配列化
		if($possible_day > $second || $possible_day < $first){
			$select_date[] = $date_after->modify($self->Master_days->edays[$first])->format('Y-m-d H:i:s');
			$select_date[] = $date_after->modify($self->Master_days->edays[$second])->format('Y-m-d H:i:s');
			$select_date[] = $date_after->modify($self->Master_days->edays[$first])->format('Y-m-d H:i:s');
		}
		
		
		//指定可能開始日をとりあえず格納
		$data->first_date = $select_date[0];
		$arr = array();
		$arr[0] = '-';
		//配列の中身を[yyyy-mm-dd]=>'m月d日形式でselectボックスに表示できる様にする
		foreach($select_date as $k=>$v){
			$date =new DateTime($v);
			$w = $date->format('w');
			$key = $date->format('Y-m-d');
			$arr[$key] = $date->format('m月d日').'('.$week_days[$w] . ')';
		}
		$data->select = $arr;
		return $data;
	}
	
	public function delivery_select_date_for_takuhai($after,$self)
	{
		$date = new DateTime();
		//配達可能初日を取得
		$date_after = $date->modify($after);
		//配達可能日から7日分を格納
		$select_date = array();
		for($i=1; $i <= 7; $i++){
			$select_date[] = $date_after->modify('+1 day')->format('Y-m-d H:i:s');
		}
		$data = new StdClass();
		//指定可能開始日をとりあえず格納
		$data->first_date = $date_after->format('Y-m-d');
		$arr = array();
		$arr[0] = '-';
		foreach($select_date as $k=>$v){
			$date = new DateTime($v);
			$w = $date->format('w');
			$key = $date->format('Y-m-d');
			$arr[$key] = $date->format('m月d日').'('.$self->Master_days->jdays[$w] . ')';
		}
		$data->select = $arr;
		return $data;
	}
	
	public function show_list_with_personal($del_flag=TRUE)
	{
		$this->db->select("c.id as id ,c.familyname,c.firstname,p.user_id,p.username,p.password,p.point,p.rank,p.bank_name,p.type_account,p.account_number");
		$this->db->from($this->tablename . ' as c');
		$this->db->join('personal as p',"p.user_id = c.id",'left');
		if($del_flag ){
			$this->db->where("c.del_flag",0);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function show_list_with_detail()
	{
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,a.cource_name,a.takuhai_day");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');	
		return $this->db->get()->result();
	}
	
	public function get_by_order_number($order_number=null)
	{	
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,od.deliver_date,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,ca.cource_name,ca.takuhai_day");
		$this->db->from('order as o');
		$this->db->where('o.order_number',$order_number);
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');
		$this->db->join('master_cource as ca','ca.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function get_by_order_id($order_id = null)
	{
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,od.deliver_date,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,ca.cource_name,ca.takuhai_day");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->where('od.id',$order_id);
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');
		$this->db->join('master_cource as ca','ca.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function show_list_with_cource($city = Null,$zipcode=null)
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,c.cource_name as cource_name,t.takuhai_day as takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.id = a.cource_id','left');
		$this->db->join('master_cource_type as t','t.id = c.cource_type_id','left');
		$this->db->where('a.shop_id = c.shop_id');
		if($city){
			$this->db->like('a.city',$city);
		}
		if($zipcode){
			$this->db->like('a.zipcode',$zipcode);
		}
		return $this->db->get()->result();
	}
	
	public function get_by_id_with_cource($id = null)
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,a.cource_name,a.takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		$this->db->where('a.id',$id);
		return $this->db->get()->result();
	}
	
	public function is_unique($fieldname='',$value='')
	{
		$this->db->where($fieldname,$value);
		$result = $this->db->get($this->tablename);
		$count = count($result->result());
		if($count > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->row();
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
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	public function get_area_by_zip($zipcode)
	{
		$this->db->where('zipcode',$zipcode);
		$query=$this->db->get('master_area');
		$result = $query->result();
		return $result;
	}
	
}		