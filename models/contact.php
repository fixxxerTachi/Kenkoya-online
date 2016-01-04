<?php
class Contact extends CI_Model{
	public $tablename;
	public $id;
	public $category_id;
	public $name;
	public $email;
	public $email_confirm;
	public $content;
	public $reply_flag;
	public $create_date;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'question';
	}
	
	public function show_list_category($del_flag=TRUE)
	{
		if($del_flag){ $this->db->where('del_flag',0); }
		$query = $this->db->get('master_question_category');
		$result = $query->result();
		$arr = array();
		$arr[0] = '-';
		foreach($result as $row){
			$arr[$row->id] = $row->name;
		}
		return $arr;
	}

	public function show_list($offset = null, $limit = null, $no_reply = null)
	{
		if(is_null($offset) && !is_null($limit))
		{
			$this->db->limit($limit);
		}
		if(!is_null($offset) && !is_null($limit))
		{
			$this->db->limit($limit, $offset);
		}
		if(!is_null($no_reply))
		{
			$this->db->where('reply_flag',0);
		}
		$this->db->order_by('create_datetime','desc');
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
	
	public function save($data = array())
	{
		$this->db->insert($this->tablename,$data);
	}
	
	public function get_category_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->result();
		return $result;
	}
	
	public function get_by_id($id){
		$this->db->where('id',$id);
		return $this->db->get($this->tablename)->row();
	}
	
	public function change_category($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
}
		