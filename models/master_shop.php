<?php
class Master_shop extends CI_Model{
	public $tablename;
	public $id;
	public $shop_name;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'master_shop';
	}
	
	public function array_lists($zero = FALSE)
	{
		$data = $this->db->get($this->tablename)->result();
		$arr = array();
		foreach($data as $item)
		{
			$arr[$item->id] = $item->shop_name;
		}
		if($zero)
		{
			$arr[0] = '選択してください';
			ksort($arr);
		}
		return $arr;
	}
	
	
}