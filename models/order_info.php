<?php
class Order_info extends CI_Model{
	public $tablename;
	public $id;
	public $takuhai;
	public $delivery;
	public $takuhai_select_date;
	public $delivery_hour = 0;
	public $payment;
}