<?php
class Box extends CI_Model{
	public $tablename;
	public $id;
	public $temp_zone_id;
	public $zone_id;
	public $name;
	public $height;
	public $width;
	public $depth;
	public $volume;
	public $note;
	public $weight;
	public $quantity;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('myclass');
		$this->tablename = 'boxes';
	}
	
	
	/**	箱の種類と箱数を取得する
	* @param object cart
	* @return  box
	*/
	public function get_boxes($carts)
	{
		//カートに入っている商品を取得する
		$list_product = array();
		foreach($carts as $cart)
		{
			$c = unserialize($cart);
			$product = $this->Advertise_product->get_product_with_size($c->product_id);
			$product->quantity = $c->quantity;
			$list_product[] = $product;
var_dump($product);
		}
//echo '<pre>'; print_r($list_product); echo '</pre>';
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
/*
echo '=============================================<br>';
echo 'normal:';var_dump($normal_obj);echo '<br>';
echo 'cold:';var_dump($cold_obj);echo '<br>';
echo 'freeze:';var_dump($freeze_obj);echo '<br>';
echo 'ice:';var_dump($ice_obj);echo '<br>';
echo '==============================================<br>';
*/
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
		//return $this->myclass->array_flatten($box_arr);
		$result = array();
        array_walk_recursive($box_arr, function($v) use (&$result){
            $result[] = $v;
        });
        return $result;

	}
	
	public function get_no_box_by_temp_zone($temp_zone_id, array $obj)
	{
		
	}
	
	public function get_box_by_temp_zone($temp_zone_id, array $obj)
	{
		$data = $this->get_size_data($temp_zone_id,$obj);
		$value_box_arr = array();
		$weight_box_arr = array();
//////***********************体積から箱計算*************************////////
		//半端の体積数を計算する,一番大きな箱に入るかどうか確かめる
		$left_volume = $data->total_volume - ($data->max_volume_size * $data->volume_quantity);
		//最大の箱の個数分を格納
		for($i=0; $i < $data->volume_quantity; $i++)
		{
			$value_box_arr[] = $data->max_box;
		}
		//半端があれば残りを入れる箱を格納
		if(!empty($left_volume))
		{
			//半端の体積に収まる箱を取得する
			$left_box = $this->get_box($data->boxes,$left_volume);
			$value_box_arr[] = $left_box;
		}
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
//echo '<pre>';print_r($weight_box_arr);echo '</pre>';
//echo '<hr>';		
////////////volume,weight箱数の多い方を選択して返す	
		if(count($value_box_arr) > count($weight_box_arr))
		{
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
	
	/*** 体積にあった箱を取得する
	*@param object Boxes
	*@param int volume
	*@return obect box
	*/
	public function get_box($boxes,$volume)
	{
		foreach($boxes as $box)
		{
			if($volume <= $box->volume)
			{
				return $box;
			}
		}
		return false;
	}
	
	
	public function get_box_with_normal_and_cold(array $normal_obj, array $cold_obj)
	{
		$normal_data = $this->get_size_data(TEMP_NORMAL,$normal_obj);
		$cold_data = $this->get_size_data(TEMP_COLD,$cold_obj);
		$value_box_arr = array();
		$weight_box_arr = array();
		///////////////体積から箱計算//////////////////////////
		//////cold 半端の体積数を計算
		$left_volume = $cold_data->total_volume - ($cold_data->max_volume_size * $cold_data->volume_quantity);
		//最大の箱の個数分を計算
		for($i=0; $i < $cold_data->volume_quantity; $i++)
		{
			$value_box_arr[] = $cold_data->max_box;
		}
		//半端があれば残りを入れる箱を格納
		if(!empty($left_volume))
		{
			//半端の体積に収まる箱を取得する
			$cold_left_box = $this->get_box($cold_data->boxes, $left_volume);
			$value_box_arr[] = $cold_left_box;
			//coldの半端の箱の容量を取得する
			$cold_left_box_value = $cold_left_box->volume - $left_volume;
		}
		/////normal 箱の計算
		//cold箱にnormalが全部入る場いいは上記left_boxに全て入るので何もしない
		//そうでない場合normalの半端の個数分を格納
		if($cold_left_box_value < $normal_data->total_volume)
		{
			//cold箱に入れた場合の残りのnormalの容量
			$normal_total_volume = $normal_data->total_volume - $cold_left_box_value;
			//normalの最大箱に入れた場合の残りの容量
			$normal_left_volume = $normal_total_volume - ($normal_data->max_volume_size * $normal_data->volume_quantity);
			//normalの最大の箱の個数分を格納
			for($i = 0; $i < $normal_data->volume_quantity; $i++)
			{
				$value_box_arr[] = $normal_data->max_box;
			}
			//normal半端があれば残りを入れる箱を取得
			if(!empty($normal_left_volume))
			{
				//normal半端に収まる箱を取得する
				$normal_left_box = $this->get_box($normal_data->boxes, $normal_left_volume);
				$value_box_arr[] = $normal_left_box;
			}
		}
		//////////////////////重さから箱計算///////////////////////
		//重量から考えて、最大梱包数量で箱を計算した場合の残りの重量
		$left_weight = $cold_data->total_weight - (MAX_WEIGHT * $cold_data->weight_quantity);
		//MaxWeightの箱を格納
		for($i=0; $i<$cold_data->weight_quantity; $i++)
		{
			$weight_box_arr[] = $cold_data->weight_box;
		}
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
//echo 'normal、cold混在';
//echo '<pre>';print_r($value_box_arr);echo '</pre>';
//echo '<pre>';print_r($weight_box_arr);echo '</pre>';
//echo '<hr>';
		
		//volume,weight箱数の多い方を選択して返す
		if(count($value_box_arr) > count($weight_box_arr)){
			return $this->convert_zone_id($value_box_arr);
		}elseif(count($value_box_arr) == count($weight_box_arr)){
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
		}else{
			return $this->convert_zone_id($weight_box_arr);
		}
			
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
	
	
	public function save($data)
	{
		$this->db->insert($this->tablename,$data);
	}
	
	public function show_list()
	{
		$this->db->where('del_flag',0);
		return $this->db->get($this->tablename)->result();
	}
	
	public function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,array('del_flag'=>1));
	}
	
	public function get_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->get($this->tablename)->row();
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
	
	/*** 計算に必要なサイズデータを算出する
	*@param array ProductClass
	*@param id temp_zone_id
	*@return object StdClass
	*/
	public function get_size_data($temp_zone_id,$objects)
	{
		//温度帯別の箱を取得する
		$boxes = $this->get_by_temp_zone($temp_zone_id);
		//MAX_WEIGHTから基準となる箱を取得する
		$weight_box = $this->get_box_from_weight($temp_zone_id,MAX_WEIGHT);
		//最大の箱を取得する
		$max_box = end($boxes);
		//最小の箱を取得する
		$min_box = reset($boxes);
		//体積計算の基準を取得する（一番大きいサイズ)
		$max_volume_size = $max_box->volume;
		//総重量、総体積を取得する
		$weights = array();
		$volumes = array();
		foreach($objects as $obj)
		{
			$weights[] = $obj->weight * $obj->quantity;
			$volumes[] = $obj->volume * $obj->quantity;
		}
		$total_weight = array_sum($weights);
//$total_weight = 24000;
		$total_volume = array_sum($volumes);
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
		return (object)$data;
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
	
	public function update($id,$data)
	{	
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
}
