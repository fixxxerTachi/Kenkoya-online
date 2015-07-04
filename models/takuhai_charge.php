<?php
class Takuhai_charge extends CI_Model{
	public $tablename;
	public $charge_tablename;
	public $pref_tablename;
	public $id;
	public $text;
	public $max_weight;
	public $volume;
	public $description;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_temp_zone';
		$this->charge_tablename = 'takuhai_charge';
		$this->class_tablename = 'takuhai_charge_class';
		$this->pref_tablename = 'takuhai_master_prefecture';
		$this->block_tablename = 'master_block';
//		$this->product_tablename='advertise_product';
	}
	
	/**温度帯リスト
	* @return array Temp_zone object;
	*/
	public function show_list()
	{
		return $result = $this->db->get($this->class_tablename)->result();
	}
	
	/**温度帯詳細
	* @return object Temp_zone
	*/
	public function get_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->get($this->class_tablename)->row();
	}
	
	/**配送区分のidをキー、nameをvalueとした配列を返す
	*@return array Temp_zone
	*/
	public function list_temp_zone()
	{
		$this->db->select('id,text')->from($this->tablename);
		$result = $this->db->get()->result();
		$arr = array();
//現状takuhai_temp_zoneのidの4-8は使用しないので
/*
		foreach($result as $item)
		{
			$arr[$item->id] = $item->text;
		}
*/
		for($i = 0; $i <= 3; $i++)
		{
			$arr[$result[$i]->id] = $result[$i]->text;
		}
		return $arr;
	}
		
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->class_tablename,$data);
	}
	
	public function update_by_code($code,$data)
	{
		$this->db->where('product_code',$code);
		$this->db->update($this->tablename,$data);
	}
		
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	
	/**温度帯別の配送料金表
	*@param id 温度帯のid
	*@return object Takuhai_charge
	*/
	public function show_charge_list_by_zone($zone_id)
	{
		$this->db->where('zone_id',$zone_id);
		$result = $this->db->get($this->charge_tablename)->result();
		return $result;
	}
	
	/**配送料金のアップデート
	*@param int id
	*@param int charge
	*/
	public function update_charge($id,$charge)
	{
		$this->db->where('id',$id);
		$this->db->update($this->charge_tablename,array('charge'=>(int)$charge));
	}
	
	/**温度帯別のidリストを取得　updateに使うため
	*@param int zone_id
	*@return object Charge
	*/
	public function list_ids($zone_id)
	{
		$this->db->select('id')->from($this->charge_tablename);
		$this->db->where('zone_id',$zone_id);
		return $result = $this->db->get()->result();
	}
	
	/*** 温度帯と住所から配達料金を取得
	*@param array zone_ids
	*@param int pref_id
	*@return int charge_price
	*/
	public function charge_price($zone_id, $pref_id)
	{
//zone_idがないということはproduct_sizeに商品が登録されていない falseを返して無視する	
		if(empty($zone_id))
		{
			return false;
		}
		$this->db->select('charge')->from($this->charge_tablename);
		$this->db->where('zone_id',$zone_id);
		$this->db->where('pref_id',$pref_id);
		return $this->db->get()->row()->charge;		
	}
	
	/*** 温度帯の配列から配送料金の合計を取得
	*@param array zone_ids
	*@param int pref_id
	*@return int total_charge*/
	public function get_total_charge($zone_ids,$pref_id)
	{
		$arr = array();
		$arr = array_map(function($id) use($pref_id)
		{
			return $this->charge_price($id,$pref_id);
		},$zone_ids);
		return array_sum($arr);
	}
	
	/***　地域ごとの都道府県名を取得する
	*@param int block_id
	*@return object 県名
	**/
	public function get_prefname_by_block_id($block_id)
	{
		$this->db->select('name')->From($this->pref_tablename . ' as p');
		//$this->db->join('master_block as b','b.id = p.block_id','left');
		$this->db->where('p.block_id',$block_id);
		$result = $this->db->get()->result();
		$arr = array();
		foreach($result as $row)
		{
			$arr[] = $row->name;
		}
		return implode(',',$arr);
	}
	
	/***　地域名のリストを取得する 
	*@return StdClass block_name
	*/
	public function get_block_name()
	{
		$this->db->select('area_name')->from($this->block_tablename);
		return $this->db->get()->result();
	}
	
	
	/** 温度帯のリストを取得
	*@return object 温度帯
	**/
	public function get_charge_class()
	{
		$this->db->select('text')->from($this->class_tablename);
		$this->db->order_by('id asc');
		return $this->db->get()->result();
	}
	
	/*** 温度帯から地域別の配送料を表示する 
	*
	*/
	public function get_charge_price_by_temp_id($temp_zone_id,$block_id)
	{
		$this->db->select('c.pref_name,c.charge')->from($this->charge_tablename . ' as c');
		$this->db->join($this->pref_tablename . ' as p','p.id = c.pref_id','left');
		$this->db->join($this->block_tablename . ' as b','b.id = p.block_id','left');
		$this->db->group_by('p.block_id');
		$this->db->where('c.zone_id',$temp_zone_id);
		$this->db->where('p.block_id',$block_id);
		return $this->db->get()->row();
	}
}
