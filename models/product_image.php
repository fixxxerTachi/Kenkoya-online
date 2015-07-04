<?php
class Product_image extends CI_Model{
	public $tablename;
	public $id;
	public $image_name;
	public $image_text;
	public $thumb_name;
	public $thumb_text;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'product_image';
		$this->product_tablename='advertise_product';
	}
	/*
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	
	public function image_save($obj){
		$data = array(
			'product_code'=>$obj->product_code,
			'image_name'=>'ap' . $obj->product_code . '.jpg',
			'image_text'=>$obj->image_description,
			'create_datetime'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert($this->tablename,$data);
	}
	*/
	
	public function resize($image_path,$code,$size)
	{
		$path ='images/products/';
		$filename = 'ap'.$code . '.jpg';
		$filename_thumb = $code . '_thumb.jpg';
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
	
	public function count_advertise_image($advertise_id)
	{
		if(!empty($advertise_id) && !is_numeric($advertise_id)){ throw new Exception('no numeric value');}
		$this->db->select('id')->from($this->tablename);
		$this->db->where('advertise_id',$advertise_id);
		$result = $this->db->get()->result();
		if(!empty($result)){
			return count($result);
		}else{
			return 0;
		}
	}
	
	public function show_list($advertise_id,$del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$this->db->where('advertise_id',$advertise_id);
		$this->db->order_by('start_page','asc');
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_list_by_advertise($ad_id)
	{
		$this->db->select('i.image_name,i.description,i.start_page,i.end_page,ad.id,ad.title');
		$this->db->from($this->tablename . ' as i');
		$this->db->join('advertise as ad','i.advertise_id = ad.id','left');
		$this->db->where('i.advertise_id',$ad_id);
		$this->db->where('ad.del_flag',0);
		$this->db->where('i.del_flag',0);
		$date = new DateTime();
		$today = $date->format('Y-m-d H:i:s');
		$this->db->where('start_datetime <= ', $today);
		$this->db->where('end_datetime > ', $today);
		$this->db->order_by('i.start_page','asc');
		return $this->db->get()->result();
	}
	
	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_products_by_advertise_id($id = null)
	{
		$this->db->where('advertise_id',$id);
		$query = $this->db->get($this->product_tablename);
		$result = $query->result();
		return $result;
	}
	
	public function count_advertise_products($advertise_id)
	{
		if(!empty($advertise_id) && !is_numeric($advertise_id)){ throw new Exception('no numeric');}
		$this->db->select('id')->from($this->product_tablename);
		$this->db->where('advertise_id',$advertise_id);
		$result = $this->db->get()->result();
		if(!empty($result)){
			return count($result);
		}else{
			return 0;
		}
	}
	
	public function get_products_by_advertise_id_with_image($advertise_id=null)
	{
		$this->db->select("a.id,a.code,a.product_code,a.maker,a.product_name,a.size,a.sale_price,a.freshness_date,a.additive,
			a.allergen,a.calorie,a.note,a.page,a.category_id,
			p.id as product_id,p.product_name as p_name,p.short_name,p.sale_price as p_price,p.on_sale,
			i.image_name");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.advertise_id' , $advertise_id);
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
		$this->db->select("a.id,a.code,a.product_code,a.maker,a.product_name,a.size,a.sale_price,a.freshness_date,a.additive,
			a.allergen,a.calorie,a.note,a.page,a.category_id,
			p.id as product_id,p.product_name as p_name,p.short_name,p.sale_price as p_price,p.on_sale,
			i.image_name");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.id' , $product_id);
		$query=$this->db->get();
		return $query->result();
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
/*	
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
	*/
	
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
	
	public function delete_image($id){
		$this->db->select('image_name')->from($this->tablename);
		$this->db->where('id',$id);
		$image_name = $this->db->get()->row();
		unlink(base_url(AD_IMAGE_PATH . $image_name));
		$this->db->where('id',$id);
		$this->db->update($this->tablename,array('del_flag'=>1));
		return true;
	}
}