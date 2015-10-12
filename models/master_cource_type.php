<?php
class Master_cource_type extends CI_Model{
	public $tablename;
	public $takuhai_day;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'master_cource_type';
	}
	
	public function array_lists()
	{
		$data = $this->db->get($this->tablename)->result();
		$arr = array();
		foreach($data as $item)
		{
			$arr[$item->id] = $item->takuhai_day;
		}
		return $arr;
	}
	
}