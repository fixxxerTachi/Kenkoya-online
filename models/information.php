<?php
class Information extends CI_Model{
	public $tablename;
	public $id;
	public $title;
	public $content;
	public $url;
	public $image_name;
	public $image_description;
	public $start_datetime;
	public $end_datetime;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_information';
		$max_sort_order = $this->get_max('sort_order');
		$this->sort_order = (int)$max_sort_order + 1;
	}
	
	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->order_by('sort_order','asc');
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_list_front()
	{
		$this->db->where('del_flag',0);
		$this->db->where('show_flag',1);
		$this->db->order_by('sort_order','asc');
		$today = new DateTime();
		$today = $today->format('Y-m-d H:i:s');
		$this->db->where('start_datetime <= ',$today);
		$this->db->where('end_datetime > ',$today);
		$query = $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null , $del_flag=false)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->row();
		return $result;
	}
	
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename ,$data);
	}
	public function get_max($fieldname='')
	{
		$this->db->select_max($fieldname);
		$query = $this->db->get($this->tablename);
		$result=$query->result();
		return $result[0]->sort_order;
	}
	
}