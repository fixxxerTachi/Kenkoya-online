<?php
class Admin_login extends CI_Model{
	public $tablename;
	public $id;
	public $username;
	public $password;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'admin_users';
	}
	
	
	public function is_unique_user($username = Null)
	{
		$this->db->where('username',$username);
		$this->db->where('del_flag',0);
		$result = $this->db->get($this->tablename);
		$result = $result->result();
		if(count($result) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function get_checked_controllers($id = null)
	{
		$this->db->where('user_id',$id);
		$query=$this->db->get('admin_users_urls')->result();
		$arr=array();
		foreach($query as $row){
			$arr[] = $row->url_id;
		}
		return $arr;
	}
	public function login_action($username = null , $password= null)
	{
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$query=$this->db->get($this->tablename);
		$result = $query->result();
		if(count($result) > 0){
			$result = $result[0];
			$info = new StdClass();
			$info->username = $result->username;
			$info->id = $result->id;
			$info->checked_controllers = $this->get_checked_controllers($result->id);
			$this->session->set_userdata('admin',$info);
			return $info;
		}else{
			return false;
		}
	}
	public function check_login()
	{
		if(!$this->session->userdata('admin')){
			if($this->router->class != 'admin_admin' || $this->router->method != 'login'){
				redirect(base_url('admin_admin/login'));
			}
		}else{
			return $this->session->userdata('admin');
		}
	}
}