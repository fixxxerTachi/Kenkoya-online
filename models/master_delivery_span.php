<?php
class Master_delivery_span extends CI_Model{
	public $tablename;
	public $id;
	public $span;
	public $takuhai_span;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'master_delivery_span';
		$this->span = $this->get_span()->span;
	}
	
	public function get_span()
	{
		$this->db->select('*')->from($this->tablename);
		$row = $this->db->get()->row();
		return $row;
	}
	
	public function update($id,$db_data)
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$db_data);
	}
}