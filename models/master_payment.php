<?php

class Master_payment extends CI_Model{
	public $tablename;
	public $id;
	public $method_name;
	public $notice;
	public $description;
	public $method;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'payment';
		$this->method = $this->show_list();
	}
	
	/*
	public function show_list_arr()
	{
		$this->db->where('del_flag',0);
		$result = $this->db->get($this->tablename)->result_array();
		return $result;
	}
	*/
	
	public function show_list()
	{
		/*$this->db->where('del_flag',0);*/
		$result = $this->db->get($this->tablename)->result();
		return $result;
	}
	
	public function save($data)
	{
		$this->db->insert($this->tablename,$data);
	}
	
	public function delete($id)
	{
		$data = array('del_flag' => 1);
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function get_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->get($this->tablename)->row();
	}
	
	public function update($id,$data)
	{	
		$id = $this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function show_list_arr()
	{
		$arr_list = $this->show_list();
		$arr = array();
		$count = count($arr_list);
		for($i = 0; $i < $count; $i++)
		{
			$arr[$i] = $arr_list[$i]->method_name;
		}
		return $arr;
	}

}