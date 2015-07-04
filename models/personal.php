<?php
class Personal extends CI_Model{
	public $tablename;
	public $user_id;
	public $username;
	public $password;
	public $point;
	public $rank;
	public $bank_name;
	public $type_account;
	public $account_number;
	public $mail_magezine;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_personal';
		$this->rank = 'a';
		$this->point = 1;
		$this->mail_magazine = 1;
	}
	
	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query= $this->db->get($this->tablename);
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
	public function get_by_user_id($user_id=null)
	{
		$this->db->where('user_id',$user_id);
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
		$this->db->update($this->tablename,$data);
	}
	
	public function update_with_user_id($user_id=null,$data=array())
	{
		$this->db->where('user_id',$user_id);
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
	
	public function delete_with_user_id($user_id=null){
		$this->db->where('user_id',$user_id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}

}		