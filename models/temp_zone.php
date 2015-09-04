<?php
class Temp_zone extends CI_Model{
	public $tablename;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'temp_zone';
	}

	public function get_short_names()
	{
		$result = $this->db->get($this->tablename)->result();
		$temp_zones = array();
		foreach($result as $v)
		{
			$temp_zones[$v->id] = $v->short_name;
		}
		return $temp_zones;
	}

	public function get_names()
	{
		$result = $this->db->get($this->tablename)->result();
		$names = array();
		//現在idの5～8は未使用
		for($i=0; $i < 4; $i++)
		{
			$names[$result[$i]->id] = $result[$i]->text;
		}
		return $names;
	}
}
