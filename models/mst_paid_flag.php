<?php
class Mst_paid_flag extends CI_Model{
	public $tablename;
	public $id;
	public $title;
	public $paid_flags = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'mst_paid_flag';
		$this->paid_flags = $this->array_lists();
	}
	
	public function array_lists()
	{
		$data = $this->db->get($this->tablename)->result();
		$arr = array();
		foreach($data as $item)
		{
			$arr[$item->id] = $item->title;
		}
		return $arr;
	}
}