<?php
class Recommend extends CI_Model{
	public $tablename;
	public $id;
	public $product_code;
	public $advertise_id;
	public $advertise_product_code;
	public $comment;
	public $sort_order;
	public $start_date;
	public $end_date;
	public $show_flag;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'recommend';
//		$this->product_tablename='advertise_product';
		$max_sort_order = $this->get_max('sort_order');
		$this->sort_order = (int)$max_sort_order + 1;
		$this->show_flag = 1;
	}
	
	public function show_list($del_flag = true)
	{
		$this->db->select('r.*
			,p.product_name'
		);
		$this->db->from($this->tablename . ' as r');
		$this->db->join('master_products as p','p.product_code = r.product_code','left');
		if($del_flag){
			$this->db->where('r.del_flag','0');
		}
		$this->db->order_by('create_date','desc');
		$query= $this->db->get();
		return $query->result();
	}
	
	public function show_list_side_recommend($limit = null)
	{
		$this->db->select('
			r.product_code,
			r.advertise_id,
			r.advertise_product_code,
			ad.title as title,
			ad_pro.product_name as ad_pro_product_name,
			ad_pro.product_code,
			ad_pro.sale_price as ad_pro_sale_price,
			ad_pro.maker,
			ad_pro.id as product_id,
			ad_pro.image_name as ad_pro_image_name,
		');
		$this->db->from($this->tablename . ' as r');
		$this->db->join('advertise_product as ad_pro','ad_pro.code = r.advertise_product_code','left');
		$this->db->join('advertise as ad','ad.id = ad_pro.advertise_id','left');
		//$this->db->join('master_products as p','p.product_code = ad_pro.product_code','left');
		//$this->db->group_by('r.id');
		$this->db->where('r.advertise_id = ad.id');
		//$this->db->where('p.branch_code = ad_pro.branch_code');
		$this->db->where('r.del_flag',0);
		$this->db->where('r.show_flag',1);
		$this->db->where('ad_pro.on_sale',1);
		$datetime = new DateTime();
		$datetime = $datetime->format('Y-m-d H:i:s');
		$this->db->where('ad.start_datetime <= ',$datetime);
		$this->db->where('ad.end_datetime > ' , $datetime);
		$this->db->where('ad.del_flag',0);
		if($limit){
			$this->db->limit($limit);
		}
		$this->db->order_by('sort_order','asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function show_list_with_advertise_and_product($del_flag = true)
	{
		$this->db->select('r.*
			,ad.id as ad_id
			,ad.title as ad_title
			,ad_pro.code as ad_code
			,ad_pro.product_code as ad_pro_code
			,ad_pro.product_name as ad_pro_product_name
			,ad_pro.sale_price as ad_pro_sale_price
			,ad_pro.image_name as ad_pro_image_name'
		);
		$this->db->from($this->tablename . ' as r');
		$this->db->join('advertise_product as ad_pro','ad_pro.code = r.advertise_product_code','left');
		$this->db->join('advertise as ad','ad.id = r.advertise_id','left');
		//$this->db->join('master_products as p','p.product_code = ad_pro.product_code','left');
		//$this->db->where('ad_pro.code = r.advertise_product_code');
		$this->db->where('r.advertise_id = ad_pro.advertise_id');
		if($del_flag){
			$this->db->where('r.del_flag',0);
		}
		$this->db->group_by('r.id');
		$this->db->order_by('sort_order','asc');
		$query= $this->db->get();
		return $query->result();
	}
	
		public function show_list_with_advertise_and_product_by_id($id = null,$del_flag = true)
	{
		$this->db->select('r.*
			,p.product_name as p_product_name
			,p.sale_price as p_sale_price
			,ad.id as ad_id
			,ad.title as ad_title
			,ad_pro.code as ad_code
			,ad_pro.product_code as ad_pro_code
			,ad_pro.product_name as ad_pro_product_name
			,ad_pro.sale_price as ad_pro_sale_price
			,ad_pro.image_name as ad_pro_image_name'
		);
		$this->db->from($this->tablename . ' as r');
		$this->db->join('master_products as p','p.product_code = r.product_code','left');
		$this->db->join('advertise_product as ad_pro','ad_pro.code = r.advertise_product_code','left');
		$this->db->join('advertise as ad','ad.id = r.advertise_id','left');
		$this->db->group_by('r.id');
		if($del_flag){
			$this->db->where('r.del_flag',0);
		}
		$this->db->where('r.id',$id);
		$this->db->order_by('sort_order','asc');
		$query= $this->db->get();
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
	
	public function get_products_by_advertise_id($id = null)
	{
		$this->db->where('advertise_id',$id);
		$query = $this->db->get($this->product_tablename);
		$result = $query->result();
		return $result;
	}
	
	public function get_product_code_by_advertise_product_code($ad_pro_code = Null)
	{
		$this->db->select('p.product_code');
		$this->db->from('advertise_product as ap');
		$this->db->join('master_products as p','p.product_code = ap.product_code','left');
		$this->db->where('ap.code',$ad_pro_code);
		$query = $this->db->get();
		$result = $query->result();
		return $result[0];
	}
	
	public function get_products_by_advertise_id_with_image($advertise_id=null)
	{
		$this->db->select("a.id,a.code,a.product_code,a.maker,a.product_name,a.size,a.sale_price,a.freshness_date,a.additive,a.image_name,a.image_description,
			a.allergen,a.calorie,a.note,a.page,a.category_id,
			p.id as product_id,p.product_code as p_code,p.product_name as p_name,p.short_name,p.sale_price as p_price");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
//		$this->db->join('product_image as i','i.product_id = p.id','left');
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
		$this->db->select("a.*
			,p.id as product_id
			,p.category_code as p_category_code
			,p.category_name as p_category_name
			,p.product_code as p_product_code
			,p.branch_code as p_branch_code
			,p.product_name as p_product_name
			,p.short_name as p_short_name
			,p.sale_price as p_sale_price
			,p.cost_price as p_cost_price
			,p.show_name as p_show_name
			,p.on_sale
			,p.image_description as p_image_description
			,p.contents as p_contents
			,p.jan_code
			,p.price1
			,p.price2
			,p.price3
			");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
//		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.id' , $product_id);
		$query=$this->db->get();
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
	public function get_max($fieldname='')
	{
		$this->db->select_max($fieldname);
		$query = $this->db->get($this->tablename);
		$result=$query->result();
		return $result[0]->sort_order;
	}
	
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
}