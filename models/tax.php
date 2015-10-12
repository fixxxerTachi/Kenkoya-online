<?php
class Tax extends CI_Model{
	public $tablename;
	public $tax;
	public $start_datetime;
	public $end_datetime;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_tax';
		$this->account_number = '';
		$this->start_datetime=date('Y-m-d H:i:s');
		$this->end_datetime=date('Y-m-d H:i:s');
	}
	
	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function show_order_list($del_flag=TRUE)
	{
		
		if($del_flag){
			$this->db->where('del_flag','0');
		}
	}
	
	public function get_by_id($id){
		$this->db->where('id',$id);
		 return $this->db->get($this->tablename)->row();
	}
	
	public function get_current_tax($del_flag = TRUE)
	{
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->where('start_datetime <=' , $date);
		$this->db->where('end_datetime >=' , $date);
		$query = $this->db->get($this->tablename);
		$result = $query->row();
		return $result->tax;
	}
	
	public function get_tax_by_date($datetime,$del_flag = TRUE)
	{
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->where('start_datetime <=' , $date);
		$this->db->where('end_datetime >=' , $date);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result[0]->tax;
	}

	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename ,$data);
	}
	
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	
	public function save(array $data)
	{
		$this->db->insert($this->tablename,$data);
	}
	
}