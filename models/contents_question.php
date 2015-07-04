<?php
class Contents_question extends CI_Model{
	public $tablename;
	public $question;
	public $answer;
	public $category_id;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'contents_question';
	}
	
	public function show_list($del_flag=true)
	{
		$this->db->select('q.id,q.question,q.answer,q.sort_order,q.category_id,q.del_flag,q.show_flag,c.name as category');
		$this->db->from($this->tablename . ' as q');
		$this->db->join('master_question_category as c','c.id = q.category_id','left');
		$this->db->order_by('sort_order','asc');
		if($del_flag){
			$this->db->where('q.del_flag',0);
		}
		$query= $this->db->get();
		return $query->result();
	}
	
	public function show_list_by_category($category_id = null,$del_flag=true, $show_flag = false,$limit = null)
	{
		$this->db->select('q.id,q.question,q.answer,q.sort_order,q.category_id,q.show_flag,q.del_flag,c.name as category_name,c.short_name');
		$this->db->from($this->tablename . ' as q');
		$this->db->join('master_question_category as c','c.id = q.category_id','left');
		$this->db->order_by('sort_order','asc');
		if($del_flag){
			$this->db->where('q.del_flag',0);
		}
		if($category_id){
			$this->db->where('q.category_id',$category_id);
		}
		if($show_flag){
			$this->db->where('q.show_flag',1);
		}
		if($limit){
			$this->db->limit($limit);
		}
		$query= $this->db->get();
		return $query->result();
	}
	
	
	public function show_list_array()
	{
		$result = $this->show_list();
		$arr=array();
		foreach($result as $row){
			$arr[$row->id] = $row->name;
		}
		return $arr;
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	
	public function get_by_id($id=null)
	{
		$this->db->select('q.id,q.question,q.answer,q.sort_order,q.category_id,q.del_flag,c.name as category');
		$this->db->from($this->tablename . ' as q');
		$this->db->join('master_question_category as c','c.id = q.category_id','left');
		$this->db->where('q.id',$id);
		$query= $this->db->get();
		return $query->row();
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
		