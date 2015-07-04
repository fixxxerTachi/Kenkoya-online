<?php
class Master_address extends CI_Model
{
	private $tablename;
	
	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'master_address';
	}
	
	/** pref_idを取得する
	*@param string zipcode
	*@return int pref_id
	*/
	public function get_pref_id($zipcode)
	{
		if(empty($zipcode))
		{
			return 0;
		}
		$this->db->select('pref_id')->from($this->tablename);
		$this->db->where('zipcode',$zipcode);
		$row = $this->db->get()->row();
		return $row->pref_id;
	}
}