<?php

class Master_payment extends CI_Model{
	public $tablename;
	public $id;
	public $method_name;
	public $notice;
	public $description;
	public $show_flag;
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
	public function check_payment($id)
	{
		if(!array_key_exists($id,$this->show_list(TRUE)))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	public function show_list($show_flag = TRUE)
	{
		$this->db->where('del_flag',0);
		if($show_flag)
		{
			$this->db->where('show_flag',1);
		}
		$result = $this->db->get($this->tablename)->result();
		$arr = array();
		foreach($result as $v)
		{
			$arr[$v->id] = $v;
		}
		return $arr;
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
			if(0 == $i)
			{
				$arr[$i] = '選択してください';
			}
			$arr[$i] = $arr_list[$i]->method_name;
		}
		return $arr;
	}
		
	/*** 表示非表示のドロップダウンリストのリスト***/
	public function list_show_flag()
	{
		return array(
			'0'=>'非表示',
			'1'=>'表示',
		);
	}

}
