<?php
class Question_category extends CI_Model{
	public $tablename;
	public $name;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'master_question_category';
	}
	
	public function show_list($del_flag=true)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_list_array()
	{
		$result = $this->show_list();
		$arr=array();
		$arr[0] = '選択してください';
		foreach($result as $row){
			$arr[$row->id] = $row->name;
		}
		return $arr;
	}
	
	public function save_category($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_category_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->result();
		return $result;
	}
	
	public function change_category($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
}
		