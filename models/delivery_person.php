<?php
class Delivery_person extends CI_Model{
	public $tablename;
	public $id;
	public $name;
	public $image;
	public $introduction;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_delivery_person';
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
		
	public function show_list_for_dropdown()
	{
		$arr = array();
		$arr[0] = '選択してください';
		$result = $this->show_list(TRUE);
		foreach($result as $row){
			$arr[$row->id] = $row->name;
		}
		return $arr;
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
	
	public function get_by_cource_id($cource_id = null)
	{
		$this->db->where('cource_id',$cource_id);
		$result = $this->db->get($this->tablename)->result();
		if(count($result) > 1){
			throw new Exception('候補が複数存在します');
		}
		return $result[0];
	}
	
	public function show_list_with_cource()
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,c.cource_name,c.takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function get_by_id_with_cource($id = null)
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,c.cource_name,c.takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		$this->db->where('a.id',$id);
		return $this->db->get()->result();
	}

	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->result();
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
		$this->db->update($this->tablename ,$data);
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
		$this->cource_id = $result[0] + 1;
		return $result[0];
	}
	
	public function get_max($fieldname='')
	{
		$this->db->select_max($fieldname);
		$query = $this->db->get($this->tablename);
		$result=$query->result();
		return $result[0]->cource_id;
	}
	
}		