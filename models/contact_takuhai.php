<?php
class Contact_takuhai extends CI_Model
{
	public $tablename;
	public $name;
	public $furigana;
	public $email;
	public $email_confirm;
	public $zipcode1;
	public $zipcode2;
	public $prefecture;
	public $address1;
	public $address2;
	public $tel;
	public $tel2;
	public $age;
	public $question;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
}