<?php
class Captcha extends CI_Model{
	public $tablename;
	public $captcha_id;
	public $captcha_time;
	public $ip_address;
	public $word;
	public $username;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'captcha';
		$expiration = time() - 7200;
		$this->db->where('captcha_time < ' ,$expiration);
		$this->db->delete($this->tablename);
	}
	
	public function save($data)
	{
		$this->db->insert($this->tablename,$data);
	}
	
	public function get_by_id($captcha_id)
	{
		$this->db->where('captcha_id',$captcha_id);
		return $this->db->get($this->tablename)->row();
	}
	
	public function update($id,$data)
	{
		$this->db->where('captcha_id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function check_word($word,$ip_address)
	{
		$expiration = time() - 2*60*60;
		$this->db->where('word',$word);
		$this->db->where('ip_address',$ip_address);
		$this->db->where('captcha_time >',$expiration);
		$result = $this->db->get($this->tablename)->row();
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
}