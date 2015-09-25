<?php
class Banner extends CI_Model{
	public $tablename;
	public $id;
	public $image_name;
	public $description;
	public $url;
	public $inside_url;
	public $start_datetime;
	public $end_datetime;
	public $sort_order;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_banner';
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
	
	public function get_image_name()
	{
		$this->db->select('image_name,url,inside_url,description');
		$this->db->from($this->tablename);
		$this->db->where('del_flag',0);
		$this->db->where('show_flag',1);
		$datetime = new DateTime();
		$datetime = $datetime->format('Y-m-d H:i:s');
		$this->db->where('start_datetime <= ',$datetime);
		$this->db->where('end_datetime > ',$datetime);
		$this->db->order_by('sort_order','asc');
		$result = $this->db->get()->result();
		return $result;
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	
	public function get_by_id($id=null)
	{
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