<?php
class Mail_footer extends CI_Model{
	public $tablename;
	public $content;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'mail_footer';
		$this->content = $this->get_content();
	}
	
	public function get_content()
	{
		$this->db->select('content')->from($this->tablename);
		$row = $this->db->get()->row();
		return $row->content;
	}
	
	public function update($content)
	{
		$db_data = array(
			'content' => $content
		);
		$this->db->update($this->tablename,$db_data);
	}
}