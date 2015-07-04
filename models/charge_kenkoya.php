<?php
class Charge_kenkoya extends CI_Model{
	public $tablename;
	
	public function __construct()
	{
		$this->tablename = 'charge_kenkoya';
	}
	
	public function get_charge()
	{
		$this->db->select('price')->from($this->tablename);
		$date = new DateTime();
		$date = $date->format('Y-m-d H:i:s');
		$this->db->where('start_datetime <= ',$date);
		$this->db->where('end_datetime >' , $date);
		$this->db->where('del_flag',0);
		return $this->db->get()->row()->price;
	}
}