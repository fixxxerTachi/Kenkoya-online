<?php
class Cart extends CI_Model{
	public $advertise_id;
	public $product_id;
	public $quantity;
	public $sale_price;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
}