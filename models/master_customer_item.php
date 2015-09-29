<?php
class Master_customer_item extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_customer_items';
	}
	
	public function get_items()
	{
		$this->db->select('id,item_name')->from($this->tablename);
		$query = $this->db->get();
		return $query->result();
	}
	
	/*** item_name => id　のリストを返す　***
	*@return array 
	**/
	public function list_items()
	{
		$lists = array();
		$items = $this->get_items();
		foreach($items as $item)
		{
			$lists[$item->item_name] = (int)$item->id;
		}
		return $lists;
	}
}
