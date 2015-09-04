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
		$this->db->where('product_code',$product_code);
		$this->db->update($this->tablename,$data);
	}
}
