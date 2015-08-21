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
		$info = $this->get_info();
		$this->shop_name = $info->shop_name;
		$this->zipcode = $info->zipcode;
		$this->address = $info->address;
		$this->tel = $info->tel;
		$this->tel1 = $info->tel1;
		$this->fax = $info->fax;
		$this->email = $info->email;
		$this->url = $info->url;
		$this->logo = $info->logo_url;
	}
	
	public function get_info()
	{
		return $this->db->get($this->tablename)->row();
	}
}