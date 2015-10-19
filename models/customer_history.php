<?php
class Customer_history extends CI_Model{
	public $customer_id;
	public $customer_code;
	public $item_id;
	public $content;
	public $username;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_customer_history';
	}
	
	public function get_count()
	{
		$this->db->select('count(id) as count')->from('customer_items');
		$row = $this->db->get()->row();
		return $row->count;
	}
	
	public function save()
	{
		/*
		$data = array(
			'customer_id' => $obj->customer_id,
			'customer_code' => $obj->customer_code,
			'item_id' => $obj->item_id,
			'content' => $obj->content,
			'create_datetime'=>date('Y-m-d H:i:s'),
		);
		*/
		$data = array(
			'customer_id' => $this->customer_id,
			'customer_code' => $this->customer_code,
			'item_id' => $this->item_id,
			'content' => $this->content,
			'create_datetime' => date('Y-m-d H:i:s'),
			'username'=> $this->username,
		);
		$this->db->insert($this->tablename,$data);
	}
}
