<?php
include __DIR__.'/../libraries/define.php';
class Test_charge extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test','myclass'));
		$this->load->model('Product_size');
		$this->load->model('Takuhai_charge');
		$this->load->model('Box');
		$this->load->model('Advertise_product');
		$this->load->model('Cart');
		$this->load->model('Box');
		$this->load->model('Master_address');
		$this->load->model('Nohin');
		$this->load->model('Customer');
	}
	
	public function index()
	{
		$carts = $this->session->userdata('carts');
		$boxes = $this->Box->get_boxes($carts);
//echo '<pre>'; print_r($flattened); echo '</pre>';
		//var_dump($this->Takuhai_charge->charge_price($flattened[0],18));
		var_dump($this->Takuhai_charge->get_total_charge($boxes,18));
		
	}
	
	/**	箱の種類と箱数を取得する
	* @param object cart
	* @return  box
	*/
	private function get_boxes($carts)
	{
		$list_product = array();
		foreach($carts as $cart)
		{
			$c = unserialize($cart);
			$product = $this->Advertise_product->get_product_with_size($c->product_id);
			$product->quantity = $c->quantity;
			$list_product[] = $product;
		}
//echo '<pre>'; print_r($list_product); echo '</pre>';
		foreach($list_product as $p)
		{
			$weights[] = $p->weight * $p->quantity;
			$volumes[] = $p->volume * $p->quantity;
		}
		//とりあえず温度帯別の箱を取得する
		$boxes = $this->Box->get_by_temp_zone($list_product[0]->temp_zone_id);
		//最大サイズの箱を取得する
		asort($boxes);
		//基準を取得する
		$max_volume_size = end($boxes)->volume;
		$max_weight = 5000;
		//総重量、総体積を取得
		$total_volume = array_sum($volumes);
		$total_weight = array_sum($weights);
		
		//重量、体積で箱数を計算してみる
		$vol_qt = floor($total_volume / $max_volume_size);
		$weight_qt = floor($total_weight / $max_weight);
echo 'total_vol:' . number_format($total_volume);
echo 'total_weight:' . number_format($total_weight);
echo 'box by vol:' . $vol_qt . '<br>';
echo 'box by weight:' . $weight_qt;

		//重量がmax_weightに収まる場合は体積で計算
		//残りの体積数を計算する
		$left_volume = $total_volume - ($max_volume_size * $vol_qt);
		//残りの体積数に収まる箱を取得する
		$box = $this->get_box($boxes,$left_volume);
		//object Boxと個数を配列で返す
		$box->quantity = $vol_qt + 1;
		var_dump($box);
	}
	
	//箱を取得する
	private function get_box($boxes,$volume)
	{
		foreach($boxes as $key =>$box)
		{
			if($volume <= $box->volume)
			{
				return $box;
			}
		}
		return false;
	}
	
	private function match_box($boxes,$index,$volume)
	{
		$box = $boxes[$index];
		$quantity = ceil($volume / $box->volume);
		$box->quantity = $quantity;
		return $box;
	}
	
	private function get_index($boxes,$volume,$start_index=0)
	{	
		$count = count($boxes);
		$index = $start_index;
		while(True){
			if($index < $count){
				if($boxes[$index]->volume >= $volume)
				{
					return $index;
				}
				$index++;
			}
			elseif($index >= $count)
			{			
				$left_volume = $this->left_volume($boxes,$volume,$count);
				return $left_volume;
			}
		}
	}
	
	/** 箱を精査し対応する箱を返すなかったらfals */
	/*
	private function get_box($boxes,$volume)
	{
		$count = count($boxes);
		for($i=0; $i<$count; $i++)
		{
			if($volume <= (int)$boxes[$i]->volume)
			{
				return 'key is ' . $i;
			}
		}
		$left = $this->get_left($boxes,$volume,$count-1);
		return 'left is ' . $left;
	}*/
	
	/**　あまりの体積を取得する*/
	private function get_left($boxes,$volume,$key)
	{
		$left = $volume - (int)$boxes[$key]->volume;
		return $left;
	}
	
	/** 余りを0以下になるまで精査する */
	private function loop_left($boxes,$volume,$keys)
	{
		$left = 0;
		$volume = $volume - $left;
		foreach($keys as $key)
		{
			$vol = $this->get_left($boxes,$left,$key);			
		}	
	}
	
	public function test_pref_id($zipcode)
	{
		$pref_id = $this->Master_address->get_pref_id('9291723');
		var_dump($pref_id);
	}
	
	public function test_nohin()
	{
		/*
		$date = new DateTime();
		$date = $date->format('Ymd');
		var_dump((int)$date);
		*/
		$customer = $this->session->userdata('customer');
		$userdata = $this->Customer->get_by_username($customer);
		$order_info = $this->session->userdata('order_info');
		echo '<pre>';print_r($userdata);echo '</pre>';
		echo '<pre>';print_r($order_info);echo '</pre>';
		var_dump($this->Nohin->is_Nohin($userdata,$order_info));
	}

}
