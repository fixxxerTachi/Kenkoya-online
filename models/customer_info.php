<?php
class Customer_info extends CI_Model{
	public $tablename;
	public $id;
	public $title;
	public $contents;
	public $start_datetime;
	public $end_datetime;
	public $del_flag;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_customer_info';
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
	
	public function show_list_sorted($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->order_by('sort_order','asc');
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_list_front($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->order_by('sort_order','asc');
		$datetime = new DateTime();
		$today = $datetime->format('Y-m-d H:i:s');
		$this->db->where('start_datetime <=',$today);
		$this->db->where('end_datetime >=' , $today);
		$this->db->where('show_flag',1);
		$query = $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$query = $this->db->get();
		return $query->result();
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
	
	public function delete_customer_info()
	{
		$id = $this->uri->segment(3);
		$this->delete($id);
	}
}		