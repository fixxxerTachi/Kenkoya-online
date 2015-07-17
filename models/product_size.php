<?php
class Product_size extends CI_Model{
	public $tablename;
	public $id;
	public $product_code;
	public $temp_zone_id;
	public $weight;
	public $height;
	public $width;
	public $depth;
	public $volume;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'product_size';
		$this->new_tablename = 'jan_size';
	}
	
	/*** product_code に重複がないかチェックする**/
	public function check_duplicate($product_code)
	{
		$this->db->select('product_code')->from($this->new_tablename);
		$this->db->where('product_code',(int)$product_code);
		$row = $this->db->get()->row();
		if(!empty($row))
		{
			return True;
		}
		else
		{
			return False;
		}
	}
	
	public function get_list_no_size($advertise_id)
	{
		/*
		$this->db->select('ad_p.advertise_id,ad_p.code,ad_p.product_code,ad_p.product_name,s.weight,s.width,s.height,s.volume');
		$this->db->from('advertise_product as ad_p');
		$this->db->join($this->new_tablename . ' as s','s.product_code = ad_p.product_code','left');
		$this->db->where('ad_p.advertise_id',$advertise_id);
		$this->db->where('s.weight',0);
		$this->db->or_where('s.width',0);
		$this->db->or_where('s.height',0);
		$this->db->or_where('s.depth',0);
		$this->db->or_where('s.volume',0);
		*/
		//return $this->db->get()->result_array();
		$query = $this->db->query("select
			ad_p.advertise_id
			,ad_p.code
			,ad_p.product_code
			,ad_p.product_name
			,s.weight
			,s.width
			,s.height
			,s.depth
			from takuhai_advertise_product as ad_p
			left join takuhai_jan_size as s
			on s.product_code = ad_p.product_code
			where ad_p.advertise_id = {$advertise_id}
			and (s.weight = 0 or s.width = 0 and s.height = 0 and s.volume = 0)
			order by ad_p.code asc;
		");
		return $query->result_array();
	}
	
	public function get_list_all_size($advertise_id)
	{
		$query = $this->db->query("select
			ad_p.advertise_id
			,ad_p.code
			,ad_p.product_code
			,ad_p.product_name
			,s.weight
			,s.width
			,s.height
			,s.depth
			from takuhai_advertise_product as ad_p
			left join takuhai_jan_size as s
			on s.product_code = ad_p.product_code
			where ad_p.advertise_id = {$advertise_id}
			order by ad_p.code asc;
		");
		return $query->result_array();
	}
	
	public function get_by_product_code($product_code)
	{
		//$this->db->select('s.product_code,s.temp_zone_id,s.weight,s.height,s.width,s.depth,s.volume,p.product_name');
		$this->db->select('s.product_code,s.weight,s.height,s.width,s.depth,s.volume,p.product_name');
		$this->db->from($this->new_tablename . ' as s');
		$this->db->join('master_products as p','p.product_code = s.product_code','left');
		$this->db->where('s.product_code',$product_code);
		return $this->db->get($this->tablename)->row();
	}

	public function update_by_code($code,$data)
	{
		$this->db->where('product_code',$code);
		$this->db->update($this->new_tablename,$data);
	}
	
	public function save(array $data)
	{
		$this->db->insert($this->tablename,$data);
	}
		
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
		
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
}
