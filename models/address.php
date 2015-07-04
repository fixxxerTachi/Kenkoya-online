<?php
class Address extends CI_Model{
	public $shop_cod;
	public $cource_code;
	public $name;
	public $furigana;
	public $zipcode1;
	public $zipcode2;
	public $zipcode;
	public $address1;
	public $address2;
	public $tel;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_addresses';
	}
	
	/**　別の配送先に登録されている住所を取得
	* @param object StdClass customer
	* @return object AddressClass
	*/	
	public function get_addresses(StdClass $customer)
	{
		$this->db->where('del_flag',0);
		$this->db->where('customer_id',$customer->id);
		$this->db->order_by('update_datetime','desc');
		$this->db->limit(4);
		return $this->db->get($this->tablename)->result();
	}
	
	/** 住所を登録して登録したidを返す 
	* @param object StdClass address_data
	* @return int AddressClass->id
	*/
	public function save($data)
	{
		$this->db->insert($this->tablename,$data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**　配送先住所の取得
	* @param int address_id
	* @param object Customer
	* @return object Address
	*/
	public function get_by_id_and_customer_id($address_id, StdClass $customer)
	{
		$this->db->where('id',$address_id);
		$this->db->where('customer_id',$customer->id);
		$result = $this->db->get($this->tablename)->row();
		return $result;
	}
	
	/** 配送先住所の取得
	* @param int address_id
	* @reteurn string address
	*/
	public function get_by_id($address_id)
	{
		$this->db->where('id',$address_id);
		$this->db->where('del_flag',0);
		$result = $this->db->get($this->tablename)->row();
		return $result->address1 . $result->address2;
	}
	
	/** 配送先の更新
	*@param array data
	*@return int insert_id
	*/
	public function update($address_id,$data)
	{
		$this->db->where('id',$address_id);
		$this->db->update($this->tablename,$data);
	}
	
	/** 削除(フラグを立てる)
	*@param int address_id
	*@reteun void
	*/
	public function delete($address_id)
	{
		$db_data = array('del_flag'=>1);
		$this->db->where('id',$address_id);
		$this->db->update($this->tablename,$db_data);
	}
	
	/** idが存在するか調べる
	* @param int id
	* @return bool
	*/
	public function check_addres_id($address_id,$url = '')
	{
		if(empty($address_id) || !is_numeric($address_id))
		{
			return redirect($url);
		}
		$this->db->select('id')->from($this->tablename);
		$this->db->where('id',$address_id);
		$result = $this->db->get()->row();
		if(empty($result))
		{
			return redirect($url);
		}else{
			return TRUE;
		}
	}
}