<?php
include __DIR__.'/../libraries/define.php';
class Test_email extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test','my_mail'));
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function index()
	{
		$customer_id = 500;
		$order_id = 84;
		$this->load->model('Customer');
		$this->load->model('Order');
		$this->load->model('Master_takuhai_hours');
		$customer = $this->Customer->get_by_id($customer_id);
var_dump($customer);
		$order = $this->Order->get_by_id($order_id);
		$details = $this->Order->get_by_order_number('150922-032354-171100001');
		//var_dump($detail);
		$text = '';
		foreach($details as $item){
			if($item->delivery_date != '0000-00-00 00:00:00'){
				$date = new DateTime($item->delivery_date);
				$delivery_date = $date->format('Y年m月d日');
			}else{
				$delivery_date = '日付指定しない';
			}
			if($item->delivery_hour != 0){
				$delivery_hour = $this->Master_takuhai_hours->hours[$item->delivery_hour];
			}else{
				$delivery_hour = '時間指定しない';
			}
			$sale_price = number_format($item->sale_price);
			$text .= "{$item->product_name}     {$sale_price}円  数量:{$item->quantity}  配達予定日:{$delivery_date} {$delivery_hour}\n";
			//$total[] = $item->sale_price * $item->quantity;
		}
		$order->items = $text;
		$order->order_number = '123456';
		$order->total = 500;
		$order->tax = 40;
		$order->charge_price = 400;
		$order->total_price = $order->total + $order->tax + $order->charge_price;
		$order->address = '会員のお客様の住所';
var_dump($order);
		$result = $this->my_mail->mail_shipped($customer,$order);
var_dump($result);
	}
	
	public function shipped()
	{
		$ids = array(1,2);
		$this->load->model('Order');
		$this->load->model('Customer');
		try
		{
			$this->Order->send_shipped_mail($ids);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
}

