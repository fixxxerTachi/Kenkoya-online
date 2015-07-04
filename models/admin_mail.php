<?php
class Admin_mail extends CI_Model{
	public $tablename;
	public $status;
	public $email;
	public $name;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'admin_mail';
	}
	
	public function show_list($del_flag=true)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function get_by_id($id = Null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		return $query->row();
	}
	
	public function update($id,$data)
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function save($data)
	{
		$this->db->insert($this->tablename,$data);
	}
	
	public function delete($id)
	{
		$this->db->where('id',$id);
		$data = array(
			'del_flag'=>1
		);
		$this->db->update($this->tablename,$data);
	}
}