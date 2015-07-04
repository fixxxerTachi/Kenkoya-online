<?php
class Allergen extends CI_Model{
	public $tablename;
	public $name;
	public $id;
	public $description;
	public $icon;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'allergen';
	}
	
	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null, $del_flag=TRUE)
	{
		$this->db->where('id',$id);
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query = $this->db->get($this->tablename);
		$result =  $query->result();
		return $result;
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
		