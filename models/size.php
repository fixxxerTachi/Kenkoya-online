<?php
class Size extends CI_Model{
	public $tablename;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'jan_size';
	}
	
	public function update($product_code, $data)
	{
		if($this->check_size($product_code))
		{
			$this->db->where('product_code',$product_code);
			$this->db->update($this->tablename,$data);
		}
		else
		{
			$data['product_code'] = $product_code;
			$this->db->insert($this->tablename,$data);
		}
	}
	
	public function check_size($product_code)
	{
		$this->db->where('product_code',$product_code);
		$row = $this->db->get($this->tablename)->row();
		if(!empty($row))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		if(!empty($row->weight) && !empty($row->width) && !empty($row->height)
			&& !empty($row->depth) && !empty($row->volume))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
