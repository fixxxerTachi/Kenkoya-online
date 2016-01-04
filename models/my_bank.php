<?php
class My_bank extends CI_Model{
	public $name;
	public $furigana;
	public $branch_name;
	public $branch_furigana;
	public $type;
	public $account;
	public $account_name;
	public $account_furigana;
	public $tablename;
	public $search;
	public $replace;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'my_bank';
		$row = $this->get();
		$this->name = $row->name;
		$this->furigana = $row->furigana;
		$this->branch_name = $row->branch_name;
		$this->branch_furigana = $row->branch_furigana;
		$this->type = $row->type;
		$this->account = $row->account;
		$this->account_name = $row->account_name;
		$this->account_furigana = $row->account_furigana;
		$this->search = array(
			'{{name}}',
			'{{furigana}}',
			'{{branch_name}}',
			'{{branch_furigana}}',
			'{{type}}',
			'{{account}}',
			'{{account_name}}',
			'{{account_furigana}}',
		);
		$this->replace = array(
			$this->name,
			$this->furigana,
			$this->branch_name,
			$this->branch_furigana,
			$this->type,
			$this->account,
			$this->account_name,
			$this->account_furigana,
		);
	}
	
	public function get()
	{
		return $this->db->get($this->tablename)->row();
	}
	
	public function update($data)
	{
		$id = 1;
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
}