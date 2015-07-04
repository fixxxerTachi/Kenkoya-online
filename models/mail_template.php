<?php
class Mail_template extends CI_Model{
	public $tablename;
	public $send_address;
	public $sender;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'mail_template';
		$this->send_address = 'info@kenkouya-market.com';
		$this->support_address = 'support@kenkouya-market.com';
		$this->admin_address = 'k_tachibana@akatome.co.jp';
		$this->sender = '宅配スーバー健康屋';
	}
	
	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->result();
		$result = $result[0];
		return $result;
	}
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}

	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
}
		