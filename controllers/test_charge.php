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
		$this->tablename = 'boxes';
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
	
public function test_charge_price()
	{
		header('content-type: text/html; charset=utf-8');
		//商品情報をセット
		$carts = array();
		
		$normal_v = new StdClass();
		$normal_v->product_id = 2905;
		$normal_v->product_code = 9999;
		$normal_v->quantity = 10;
		//volume 189550
		
		$normal_w = new StdClass();
		$normal_w->product_id = 2906;
		$normal_w->product_code=9998;
		$normal_w->quantity=1;
		//volume 189300 
		
		$cool_v = new StdClass();
		$cool_v->product_id = 2907;
		$cool_v->product_code = 9997;
		$cool_v->quantity = 32;
		//volume 189550
		
		$cool_w = new StdClass();
		$cool_w->product_id = 2908;
		$cool_w->product_code = 9996;
		$cool_w->quantity = 1;
		//volume 189300
		
		$cold_v = new StdClass();
		$cold_v->product_id = 2909;
		$cold_v->product_code = 9995;
		$cold_v->quantity = 2;
		//volume 189550
		
		$cold_w = new StdClass();
		$cold_w->product_id = 2910;
		$cold_w->product_code = 9994;
		$cold_w->quantity = 10;
		//volume 189300
		
		$carts[] = serialize($normal_v);
		$carts[] = serialize($cool_v);
		//$carts[] = serialize($cool_v);
		//$carts[] = serialize($normal_v);
		//$carts[] = serialize($cool_v);
		
//echo '1.カートの中身';var_dump($carts);echo '<hr>';
		//Box::get_box
		$boxes = $this->Box->get_boxes($carts);
//echo '2.最終結果：(Box::get_boxes)'; var_dump($boxes);echo '<hr>';
//echo '<p>Box::get_boxの処理過程</p>';
		
		/***get_boxesの処理過程 Box::get_boxes***/
		$list_product = array();
		foreach($carts as $cart)
		{
			$c = unserialize($cart);
			$product = $this->Advertise_product->get_product_with_size($c->product_id);
			$product->quantity = $c->quantity;
//$product->volume = 256000005;
//$product->weight = 30001;
			$list_product[] = $product;
		}
//echo '3.商品のサイズ情報などを取得する:<pre>'; print_r($list_product); echo '</pre>';
		//総重量、総体積を取得するためにそれぞれを配列に格納(温度帯別)
		//$weights = array();
		//$volumes = array();
		//温度帯別クラス
		$normal_obj = array();
		$cold_obj = array();
		$freeze_obj = array();
		$ice_obj = array();
		$box_obj = array();
		
		//箱を格納する配列を宣言
		$box_arr = array();
		//カートの中身を温度帯別に精査してみる
		//バラ商品(他商品と混在可は箱、ケース売りは商品を配列に格納
		foreach($list_product as $p)
		{
			if($p->temp_zone_id == TEMP_NORMAL)
			{
				$normal_obj[] = $p;
			}
			elseif($p->temp_zone_id == TEMP_COLD)
			{
				$cold_obj[] = $p;
			}
			elseif($p->temp_zone_id == TEMP_FREEZE)
			{
				$freeze_obj[] = $p;
			}
			elseif($p->temp_zone_id == TEMP_ICE)
			{
				$ice_obj[] = $p;
			}
			else{
				$box_obj[] = $p;
			}
		}
//echo '=========== 4. 温度帯ごとに格納する===============================<br>';
//echo '通常 : ';var_dump($normal_obj);echo '<br>';
//echo '冷蔵 : ';var_dump($cold_obj);echo '<br>';
//echo '冷凍 : ';var_dump($freeze_obj);echo '<br>';
//echo 'アイス : ';var_dump($ice_obj);echo '<br>';
//echo '=============================================================<br>';
		//冷蔵と常温が混在している場合冷蔵に常温混入可
		if(!empty($normal_obj) && !empty($cold_obj)){
			$box_arr[] = $this->get_box_with_normal_and_cold($normal_obj,$cold_obj);
		}else{
			if(!empty($normal_obj))
			{
				$box_arr[] = $this->get_box_by_temp_zone(TEMP_NORMAL,$normal_obj);
			}
			if(!empty($cold_obj))
			{
				$box_arr[] = $this->get_box_by_temp_zone(TEMP_COLD,$cold_obj);
			}
		}
		if(!empty($freeze_obj))
		{
			$box_arr[] = $this->get_box_by_temp_zone(TEMP_FREEZE,$freeze_obj);
		}
		if(!empty($ice_obj))
		{
			$box_arr[] = $this->get_box_by_temp_zone(TEMP_ICE,$ice_obj);
		}
		if(!empty($box_obj))
		{
			$box_arr[] = $this->convert_zone_id($box_obj);
		}
//echo '最終的な箱：<pre>'; print_r($box_arr);echo '</pre>';
		//return $this->myclass->array_flatten($box_arr);
		$result = array();
        array_walk_recursive($box_arr, function($v) use (&$result){
            $result[] = $v;
        });
//var_dump( $result );
	}
	
	public function get_box_by_temp_zone($temp_zone_id, array $obj)
	{
//echo '================== Box::get_box_by_temp_zone( ==========================<br>';
		/* 温度帯に属した箱一覧 */
		$data = $this->get_size_data($temp_zone_id,$obj);
//echo '箱情報(get_box_by_temp_zone):<pre>';print_r($data);echo '</pre>';
		$value_box_arr = array();
		$weight_box_arr = array();
//////***********************体積から箱計算*************************////////
/***** 古いコード**********
		//半端の体積数を計算する,一番大きな箱に入るかどうか確かめる
		$left_volume = $data->total_volume - ($data->max_volume_size * $data->volume_quantity);
echo '半端の体積数:<pre>';var_dump($left_volume); echo '</pre>';
		//最大の箱の個数分を格納
		for($i=0; $i < $data->volume_quantity; $i++)
		{
			$value_box_arr[] = $data->max_box;
		}
echo '最大の箱の個数分(1箱に収まる場合は空):<pre>';print_r($value_box_arr);echo '</pre>';
		//半端があれば残りを入れる箱を格納
		if(!empty($left_volume))
		{
			//半端の体積に収まる箱を取得する
			$left_box = $this->get_box($data->boxes,$left_volume);
			$value_box_arr[] = $left_box;
		}
echo '箱の種類:<pre>';print_r($data->boxes);echo '</pre>';
**************** ここまで古いコード ***************/
/****************　新しいコード***************/
		/*商品サイズ*/
		$left_volume = $data->total_volume;
		/*最小の箱の容量*/
		$min_volume = min($data->boxes)->volume;
//echo '箱の最小体積数:<pre>';print_r($min_volume);echo '</pre>';
		$data_key = '';
		$count = count($data->boxes);
		/**
		*商品容量が箱最小容量以下になるまで箱に詰め込む
		*/
		while($left_volume >= $min_volume)
		{
			for($i=0; $i < $count; $i++)
			{
				if($left_volume <= $data->boxes[$i]->volume)
				{
					$data_key = $i;
					break;
				}else{
					$data_key = $count-1;
				}
			}
			
			if($left_volume >= $min_volume)
			{
				$left_volume = $left_volume - $data->boxes[$data_key]->volume;
				$value_box_arr[] = $data->boxes[$data_key];
			}
			else
			{
				break;
			}
		}
		//半端があれば残りを入れる箱を格納
		if($left_volume > 0)
		{
			$left_box = $this->get_box($data->boxes,$left_volume);
			$value_box_arr[] = $left_box;
		}
		
//echo 'left_volume:<pre>'; print_r($left_volume); echo '</pre>';
//echo 'value_box_arr:<pre>'; print_r($value_box_arr); echo '</pre>';
/****************** ここまで新しいコード **************************/
////***********************重さから箱計算******************************//////
		$left_weight = $data->total_weight - (MAX_WEIGHT * $data->weight_quantity);
		for($i=0; $i < $data->weight_quantity; $i++)
		{
			$weight_box_arr[] = $data->weight_box;
			//$weight_value_total += $data->weight_box->volume;
		}
		if(!empty($left_weight))
		{
			//半端の重さにあった箱を取得する
			$weight_left_box = $this->get_box_from_weight($temp_zone_id,$left_weight);
			$weight_box_arr[] = $weight_left_box;
			//$weight_value_total += $weight_left_box->volume;
		}
		
//echo 'normal、cold,アイスのバラ';
//echo '<pre>';print_r($value_box_arr);echo '</pre>';
//echo 'weight_box_arr<pre>';print_r($weight_box_arr);echo '</pre>';
//echo '<hr>';		
////////////volume,weight箱数の多い方を選択して返す
		if(count($value_box_arr) > count($weight_box_arr))
		{
//echo 'convert_zone_id<pre>';print_r($value_box_arr);echo '</pre>';
			return $this->convert_zone_id($value_box_arr);
		}
		//箱数が同じ場合はそれぞれの体積を計算して大きい方を返す
		elseif(count($value_box_arr) == count($weight_box_arr))
		{
			$total_vol = array();
			$weight_vol = array();
			foreach($value_box_arr as $item)
			{
				$vol_vol[] = $item->volume;
			}
			foreach($weight_box_arr as $item)
			{
				$weight_vol[] = $item->volume;
			}
			$vol_total_vol = array_sum($vol_vol);
			$weight_total_vol = array_sum($weight_vol);
			if($vol_total_vol >= $weight_total_vol)
			{
				return $this->convert_zone_id($value_box_arr);
			}else{
				return $this->convert_zone_id($weight_box_arr);
			}
		}
		else
		{
			return $this->convert_zone_id($weight_box_arr);
		}
	}
	/*** 計算に必要なサイズデータを算出する
	*@param array ProductClass
	*@param id temp_zone_id
	*@return object StdClass
	*/
	public function get_size_data($temp_zone_id,$objects)
	{
//echo '=======================================　Box::get_size_data============================================<br>';
//echo 'get_size_dataに渡されるobjects:<pre>';print_r($objects);echo '</pre>';
		//温度帯別の箱を取得する
		$boxes = $this->get_by_temp_zone($temp_zone_id);
//echo '温度帯の箱一覧(get_size_data):<pre>';print_r($boxes);echo '</pre><br>';
		//MAX_WEIGHTから基準となる箱を取得する
		$weight_box = $this->get_box_from_weight($temp_zone_id,MAX_WEIGHT);
//echo '最大重量(15kg)で計算した箱のサイズ:<pre>';print_r($weight_box);echo '</pre>';
		//最大の箱を取得する
		$max_box = end($boxes);
//echo '最大の箱:<pre>';print_r($max_box);echo '</pre>';
		//最小の箱を取得する
		$min_box = reset($boxes);
//echo '最小の箱:<pre>';print_r($min_box);echo '</pre>';
		//体積計算の基準を取得する（一番大きいサイズ)
		$max_volume_size = $max_box->volume;
//echo '最大の箱の体積:<pre>';print_r(number_format($max_volume_size));echo '</pre>';
		//総重量、総体積を取得する
		$weights = array();
		$volumes = array();
		foreach($objects as $obj)
		{
			$weights[] = $obj->weight * $obj->quantity;
			$volumes[] = $obj->volume * $obj->quantity;
		}
//echo '商品の重量の配列:<pre>';print_r($weights);echo '</pre>';
		$total_weight = array_sum($weights);
//$total_weight = 24000;
//echo '温度帯別商品総重量:<pre>';print_r(number_format($total_weight));echo '</pre>';
		$total_volume = array_sum($volumes);
//echo '温度帯別商品体積:<pre>';print_r(number_format($total_volume));echo '</pre>';
		//体積、重さで箱数を計算してみる
		$weight_qt = (int)floor($total_weight / MAX_WEIGHT);
		$vol_qt = (int)floor($total_volume / $max_volume_size);
		//配列で返す
		$data = array(
			'weight_quantity'=>$weight_qt,
			'volume_quantity'=>$vol_qt,
			'total_weight'=>$total_weight,
			'total_volume'=>$total_volume,
			'max_volume_size'=>$max_volume_size,
			'max_box'=>$max_box,
			'min_box'=>$min_box,
			'weight_box'=>$weight_box,
			'boxes'=>$boxes,
		);
//echo 'get_size_dataで返される情報:<pre>';print_r($data);echo '</pre>';
//echo '=======================================End:get_size_data==================================<br>';
		return (object)$data;
	}
	
	/** 温度帯から箱を取得して体積の少ない順にソートする
	* @param int temp_zone_id
	* @return object Box
	*/
	public function get_by_temp_zone($temp_zone_id)
	{
		$this->db->select('*')->from($this->tablename);
		$this->db->where('temp_zone_id',$temp_zone_id);
		$result = $this->db->get()->result();
		//volumeの昇順に並べる
		asort($result);
		return $result;
	}
	
	//最高梱包重量から適した箱を取得する
	public function get_box_from_weight($temp_zone_id,$weight)
	{
		$this->db->where('temp_zone_id',$temp_zone_id);
		$this->db->where('weight >=' , $weight);
		$this->db->order_by('weight asc');
		$result = $this->db->get($this->tablename)->row();
//var_dump($result);
		if(!empty($result))
		{
			return $result;
		}
		else{
			$this->db->select_min('weight');
			$this->db->where('temp_zone_id',$temp_zone_id);
			return $this->db->get($this->tablename)->row();
		}
	}

	/** バラ梱包箱のboxデータからzone_idのみを取り出して配列に格納
	*@param array BoxClass or ProductClass
	*@retrun array zone_id
	*/
	public function convert_zone_id(array $boxes)
	{
	//バラ専用箱Boxesには(temp_zone_id が　5-8)zone_idが入っているのでそれを販売個数分格納
		$zone_ids = array();
		foreach($boxes as $box)
		{
			if($box->temp_zone_id >= TEMP_NORMAL && $box->temp_zone_id <= TEMP_ICE)
			{
				if(isset($box->zone_id)){
					$zone_ids[] = $box->zone_id;
				}
			}
			elseif($box->temp_zone_id < TEMP_NORMAL)
			{
				if(!empty($box->quantity))
				{
					$quantity = $box->quantity;
					for($i = 0; $i < $quantity; $i++)
					{
						$zone_ids[] = $box->temp_zone_id;
					}
				}
			}
		}
		return $zone_ids;
	}
	
	public function get_box_with_normal_and_cold(array $normal_obj, array $cold_obj)
	{
		$normal_data = $this->get_size_data(TEMP_NORMAL,$normal_obj);
		$cold_data = $this->get_size_data(TEMP_COLD,$cold_obj);
		$value_box_arr = array();
		$weight_box_arr = array();
		///////////////体積から箱計算//////////////////////////
		//////cold 半端の体積数を計算
		/** ここから新しく記述**/
		/** normal,coldの合計でnormalの箱で計算した場合の箱を取得しそれからcoldの場合の箱に置き換える **/
		$cold_left_volume = $cold_data->total_volume;
		$cold_max_box = max($cold_data->boxes)->volume;
		$normal_left_volume = $normal_data->total_volume;
		$normal_max_box = max($normal_data->boxes)->volume;
		$total_left_volume = $cold_left_volume + $normal_left_volume;
		$count = count($cold_data->boxes);
echo 'cold_left_volume:<pre>';print_r($cold_left_volume);echo '</pre>';
echo 'cold_max_box:<pre>';print_r($cold_max_box);echo '</pre>';
echo 'normal_left_volume:<pre>';print_r($normal_left_volume);echo '</pre>';
echo 'normal_max_box:<pre>';print_r($normal_max_box);echo '</pre>';
echo 'total_left_volume:<pre>';print_r($total_left_volume); echo '</pre>';

		//まずは冷凍で考えてみる
		while($cold_left_volume >= 0)
		{
			for($i = 0; $i < $count; $i++)
			{
				//一箱に入る場合その箱のキーを取得する
				if($cold_left_volume <= $cold_data->boxes[$i]->volume)
				{
					$cold_normal_left_volume = $cold_left_volume + $normal_left_volume;
//echo 'cold_normal_left_volume:<pre>';print $cold_normal_left_volume; echo '</pre>';
					for($j = 0; $j < $count; $j++)
					{
						if($cold_normal_left_volume <= $cold_data->boxes[$j]->volume)
						{
							$cold_data_key = $j;
							break;
						}
						else
						{
							$cold_data_key = $count -1;
						}
					}
					//$cold_data_key = $i;
					//break;
				}
				else
				{
				//一箱に入らない場合最大の箱を返す
					$cold_data_key = $count - 1;
				}
			}
			$cold_left_volume = $cold_left_volume - $cold_data->boxes[$cold_data_key]->volume;
			$value_box_arr[] = $cold_data->boxes[$cold_data_key];
		}
echo 'cold_left_volume:<pre>';print_r($cold_left_volume);echo '</pre>';
		//次に常温で考える
		//coldの半端の箱にnormalを入れているため、その分を差し引く
		//現在$cold_left_boxにはマイナスで箱の余分が格納されいるのでその分をnormal_left_boxにプラスする
		$normal_left_volume = $normal_left_volume + $cold_left_volume;
		$normal_box_count = count($normal_data->boxes);
		while($normal_left_volume >= 0)
		{
			for($i = 0; $i < $normal_box_count; $i++)
			{
				if($normal_left_volume <= $normal_data->boxes[$i]->volume)
				{
					$normal_data_key = $i;
					break;
				}
				else
				{
					$normal_data_key = $normal_box_count -1;
				}
			}
			$normal_left_volume = $normal_left_volume - $normal_data->boxes[$normal_data_key]->volume;
			$value_box_arr[] = $normal_data->boxes[$normal_data_key];
		}
echo 'value_box_arr:<pre>'; print_r($value_box_arr); echo '</pre>';
		//if($total_left_volume <= $cold_max_box)
		/*
		$cold_data_key = '';
		$count = count($cold_data->boxes);
		// normal,coldの合計で全箱数の配列をcoldで取得
		while($total_left_volume > 0)
		{
			//対象となる温度帯の箱を小さい物から精査してvalue_box_arrに格納
			for($i = 0; $i < $count; $i++)
			{
				//箱に入る場合はその箱配列のキーを取得
				if($total_left_volume <= $cold_data->boxes[$i]->volume)
				{
					$cold_data_key = $i;
					break;
				}
				else
				{
					$cold_data_key = $count-1;
				}
			}
			$total_left_volume = $total_left_volume - $cold_data->boxes[$cold_data_key]->volume;
			$value_box_arr[] = $cold_data->boxes[$cold_data_key];
		}

		// normalの箱数を計算する
		while($normal_left_volume > 0)
		{
			for($i=0; $i < $count; $i++)
			{
				if($normal_left_volume <= $normal_data->boxes[$i]->volume)
				{
					$cold_data_key = $i;
					break;
				}
				else
				{
					$cold_data_key = $count-1;
				}
			}
			$normal_left_volume = $normal_left_volume - $normal_data->boxes[$cold_data_key]->volume;
			$cold_box_arr[] = $normal_data->boxes[$cold_data_key];
		}
		
		if(!empty($cold_box_arr) && !empty($value_box_arr))
		{
			//先頭からnormalの箱をcoldの箱に置き換える
			foreach($cold_box_arr as $cold)
			{
				if($cold->volume > $value_box_arr[0]->volume)
				{
					array_shift($value_box_arr);
					$value_box_arr[] = $cold;
				}
			}
		}
		*/
		/*
		//残り(最小の箱以下)がある場合その残りが入る箱を取得する
		if($cold_left_volume > 0)
		{
			//残り（最小の箱以下)がある場合その残りのが入る箱を取得する
			$cold_left_box = $this->get_box($cold_data->boxes,$cold_left_volume);
			//その箱の余りのスペースに常温を格納するため箱の余りを取得
			$cold_box_left = $cold_left_box->volume - $cold_left_volume;
			//箱のあまりスペースにnormalが収まる場合はcold_left_boxにを格納,そうでない場合半端のcold箱を拡大する
			$remain = $normal_left_volume - $cold_box_left;
			//normalが収まる場合はcoldの半端を格納
			if($remain <= 0)
			{
				$value_box_arr[] = $cold_left_box;
			}
			else
			{
				$left_volume = $normal_left_volume + $cold_left_volume;
				while($left_volume > 0)
				{
					for($i = 0; $i < $count; $i++)
					{
						if($left_volume <= $cold_data->boxes[$i]->volume)
						{
							$cold_data_key = $i;
							break;
						}
						else
						{
							$cold_data_key = $count -1;
						}
					}
					$left_volume = $left_volume - $cold_data->boxes[$cold_data_key]->volume;
					$value_box_arr[] = $cold_data->boxes[$cold_data_key];
				}
			}
		}
		else
		{
			$cold_box_left = $cold_left_volume * -1;
		}
		*/
		/*** 次に通常を計算　***/
		//冷蔵の箱の余分に通常が入らない場合、新たに通常の箱を用意する
		/*
		if($normal_left_volume > $cold_box_left)
		{
			$normal_left_volume = $normal_left_volume - $cold_box_left;
			$normal_data_key = '';
			$count = count($normal_data->boxes);
			while($normal_left_volume >= $normal_min_volume)
			{
				for($i=0; $i < $count; $i++)
				{
					if($normal_left_volume <= $normal_data->boxes[$i]->volume)
					{
						$normal_data_key = $i;
						break;
					}
					else
					{
						$normal_data_key = $count-1;
					}
				}
				
				if($normal_left_volume >= $normal_min_volume)
				{
					$normal_left_volume = $normal_left_volume - $normal_data->boxes[$normal_data_key]->volume;
					$value_box_arr[] = $normal_data->boxes[$normal_data_key];
					echo $normal_left_volume;
				}
			}
			
			if($normal_left_volume > 0)
			{
				$normal_left_box = $this->get_box($normal_data->boxes,$normal_left_volume);
				$value_box_arr[] = $normal_left_box;;
			}
		}
		*/
		
		/** ここまで新しい記述 **/
		
		//////////////////////重さから箱計算///////////////////////
		//重量から考えて、最大梱包数量で箱を計算した場合の残りの重量
		/*** ここから新しいコード **
		
		
		
		
		*** ここまで新しいコード ***/
		/*** ここから古いコード ***/
		/*
		$left_weight = $cold_data->total_weight - (MAX_WEIGHT * $cold_data->weight_quantity);
		//MaxWeightの箱を格納
		for($i=0; $i<$cold_data->weight_quantity; $i++)
		{
			$weight_box_arr[] = $cold_data->weight_box;
		}
		/*** ここから古いコード****/
		/*
		if(!empty($left_weight))
		{
			//半端の重さにあった箱を取得する
			$weight_left_box = $this->get_box_from_weight(TEMP_COLD,$left_weight);
			$weight_box_arr[] = $weight_left_box;
			//coldの半端の箱の残りの重量を取得する
			$cold_left_box_weight = $weight_left_box->weight - $left_weight;
		}
		////normalの計算
		//cold箱にnormalが全部入る場合は上記left_boxにすべて入るので何もしない
		//そうでない場合normalの半端の個数を格納
		if($cold_left_box_weight < $normal_data->total_weight)
		{
			//cold箱に入れた場合の残りのnormalの重量
			$normal_total_weight = $normal_data->total_weight - $cold_left_box_weight;
			//normalの最大箱に入れた場合の残りの容量
			$normal_left_weight = $normal_total_weight - (MAX_WEIGHT * $normal_data->weight_quantity);
			//normalの最大の箱の個数分を格納
			for($i=0; $i < $normal_data->weight_quantity; $i++)
			{
				$weight_box_arr[] = $normal_data->weight_box;
			}
			//normal半端があれば残りを入れる箱を取得
			if(!empty($normal_left_weight))
			{
				//normal半端に収まる箱を取得する
				$normal_left_box = $this->get_box_from_weight(TEMP_NORMAL,$normal_left_weight);
				$weight_box_arr[] = $normal_left_box;
			}
		}
		*/
		/** ここまで古いコード ****/
//echo 'weight_box_arr:<pre>:';print_r($weight_box_arr);echo '</pre>';


//echo 'normal、cold混在';
//echo '<pre>';print_r($value_box_arr);echo '</pre>';
//echo '<pre>';print_r($weight_box_arr);echo '</pre>';
//echo '<hr>';
		
		//volume,weight箱数の多い方を選択して返す
		if(count($value_box_arr) > count($weight_box_arr)){
			return $this->convert_zone_id($value_box_arr);
		}
		elseif(count($value_box_arr) == count($weight_box_arr))
		{
			$total_vol = array();
			$weight_vol = array();
			foreach($value_box_arr as $item)
			{
				$vol_vol[] = $item->volume;
			}
			foreach($weight_box_arr as $item)
			{
				$weight_vol[] = $item->volume;
			}
			$vol_total_vol = array_sum($vol_vol);
			$weight_total_vol = array_sum($weight_vol);
			if($vol_total_vol >= $weight_total_vol)
			{	
				return $this->convert_zone_id($value_box_arr);
			}else{
				return $this->convert_zone_id($weight_box_arr);
			}
		}
		else
		{
			return $this->convert_zone_id($weight_box_arr);
		}
	}

	/** box_idの配列 から箱の種類名を取得する
	*@param int box_id
	*@return string boxname
	*/
	public function get_box_name()
	{
		$ids = array(1,4,7);
		$box_str = $this->Box->get_box_name($ids);
		var_dump($box_str);
		echo $this->unit->run($box_str,'常温80サイズ,冷蔵80サイズ,冷凍80サイズ');
	}
}
