<?php
class Nohin extends CI_Model{
	public $tablename;
	
	public function __construct()
	{
		$this->tablename = 'nohin';
	}

	/*配達日に別の定期配送商品があるかどうか
	*@param object stdClass customer
	*@param object stdClass order_info
	*@return bool
	*/
	public function is_nohin($customer,$order_info)
	{
		$date = new DateTime($order_info->delivery_date);
		$date = $date->format('Ymd');
		$this->db->select('id')->from($this->tablename);
		$this->db->where('shop_code',(int)$customer->shop_code);
		$this->db->where('customer_code',(int)$customer->code);
		$this->db->where('ymd',(int)$date);
		$this->db->where('item_cnt != 0');
		$result = $this->db->get()->result();
		if(!empty($result))
		{
			return true;
		}else{
			return false;
		}		
	}
}