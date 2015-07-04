<?php
class Product extends CI_Model{
	public $tablename;
	public $id;
	public $category_code;
	public $category_name;
	public $product_code;
	public $branch_code;
	public $product_name;
	public $short_name;
	public $sale_price;
	public $cost_price;
	public $show_name;
	public $on_sale;
	public $image_name;
	public $image_description;
	public $contents;
	public $jan_code;
	public $allergen;
	public $price1;
	public $price2;
	public $price3;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'master_products';
		$this->on_sale = 1;
	}
	
	public function resize($image_path,$code,$size)
	{
		$path ='images/products/';
		$filename = 'ak'.$code . '.jpg';
		$original = $image_path;
		list($width,$height) = getimagesize($original);
		$src = imagecreatefromjpeg($original);
		$dst = imagecreatetruecolor($size,$size);
		imagecopyresized($dst,$src,0,0,0,0,$size,$size,$width,$height);
		imagejpeg($dst,$path. $filename);
		if(file_exists($path . $filename)){
			unlink($original);
			return true;
		}else{
			return false;
		}
	}

	
	public function show_list()
	{
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function category_list(){
		$this->db->select('distinct category_name',false);
		$this->db->from($this->tablename);
		$query = $this->db->get();
		$categories = $query->result();
		$arr=array();
		foreach($categories as $cate){
			$arr[$cate->category_name] = $cate->category_name;
		}
		return $arr;
	}
	
	public function show_list_where($limit = null , $offset=null, $obj=null)
	{
		if($obj->product_name){
			$this->db->like('product_name',$obj->product_name);
		}
		if($obj->product_code){
			$this->db->where('product_code',$obj->product_code);
		}
		if($obj->category_name){
			$this->db->like('category_name',$obj->category_name);
		}
		$this->db->limit($limit,$offset);
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function num_rows($obj = null)
	{
		$this->db->select('id');
		$this->db->from($this->tablename);
		if($obj->product_name){
			$this->db->like('product_name',$obj->product_name);
		}
		if($obj->product_code){
			$this->db->where('product_code',$obj->product_code);
		}
		if($obj->category_name){
			$this->db->like('category_name',$obj->category_name);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name,image_description");
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
	
	public function get_by_id_with_image($id=null)
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,cost_price,on_sale,image_name,image_description");
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
	
	public function save_allergen($posts=array(),$product_id='')
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
}		