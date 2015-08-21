<?php
class Base_info extends CI_Model{
	public $tablename;
	public $shop_name;
	public $zipcode;
	public $address;
	public $tel;
	public $tel1;
	public $fax;
	public $email;
	public $url;
	public $logo;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'base_info';
	}
	
	public function get_info()
	{
		return $this->db->get($this->tablename)->row();
	}
}