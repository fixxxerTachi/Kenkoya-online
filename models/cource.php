<?php
class Cource extends CI_Model{
	public $tablename;
	public $cource_code;
	public $cource_name;
	public $shop_id;
	public $cource_type_id;
	public $takuhai_day;
	public $delivery_person_id;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_master_cource';
		$this->takuhai_day = $this->cource_type_id;
	}
		
	public function get_delivery_day($customer)
	{
		$shop_id = $customer->shop_id;
		$cource_code = $customer->cource_code;
		$this->db->select('t.takuhai_day,t.first,t.second');
		$this->db->from($this->tablename . ' as c');
		$this->db->join('master_cource_type as t','t.id = c.cource_type_id','left');
		$this->db->where('c.shop_id',$shop_id);
		$this->db->where('c.cource_code',$cource_code);
		$result = $this->db->get()->row();
		return $result;
	}
	
	public function show_list($shop_id = Null,$del_flag=TRUE)
	{
		if($del_flag)
		{
			$this->db->where('del_flag','0');
		}
		if($shop_id)
		{
			$this->db->where('shop_id',$shop_id);
		}
		$query= $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	public function show_list_with_delivery_person($del_flag=TRUE)
	{
		$this->db->select('c.*,d.id as delivery_person_id,d.name as delivery_person_name');
		$this->db->from($this->tablename.' as c');
		$this->db->join('delivery_person as d','c.delivery_person_id = d.id','left');
		if($del_flag){
			$this->db->where('c.del_flag',0);
		}
		$query=$this->db->get();
		return $query->result();
	}
	
	public function show_order_list($del_flag=TRUE)
	{
		
		if($del_flag){
			$this->db->where('del_flag','0');
		}
	}
	
	public function show_list_for_dropdown($shop_id = null)
	{
		$arr = array();
		if($shop_id)
		{
			$result = $this->show_list($shop_id);
		}
		else
		{
			$result = $this->show_list();
		}
		foreach($result as $row){
			$arr[$row->id] = $row->cource_name;
		}
		return $arr;
	}
	
	public function show_list_with_detail()
	{
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,a.cource_name,a.takuhai_day");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');	
		return $this->db->get()->result();
	}
	
	public function get_by_order_number($order_number=null)
	{	
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,od.deliver_date,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,ca.cource_name,ca.takuhai_day");
		$this->db->from('order as o');
		$this->db->where('o.order_number',$order_number);
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');
		$this->db->join('master_cource as ca','ca.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function get_by_order_id($order_id = null)
	{
		$this->db->select("o.order_number,o.create_date,od.id as order_id,od.product_id,od.quantity,od.cancel_flag,od.deliver_date,p.product_code,p.product_name,p.sale_price,c.familyname,c.firstname,c.zipcode,ca.cource_name,ca.takuhai_day");
		$this->db->from('order as o');
		$this->db->join('order_detail as od','od.order_number = o.order_number','left');
		$this->db->where('od.id',$order_id);
		$this->db->join('master_products as p','p.id = od.product_id','left');
		$this->db->join('customers as c','c.id = o.customer_id','left');
		$this->db->join('master_area as a','a.zipcode = c.zipcode','left');
		$this->db->join('master_cource as ca','ca.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function get_by_cource_id($cource_id = null)
	{
		$this->db->where('cource_id',$cource_id);
		$result = $this->db->get($this->tablename)->result();
		if(count($result) > 1){
			throw new Exception('候補が複数存在します');
		}
		return $result[0];
	}
	
	public function show_list_with_cource()
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,c.cource_name as cource_name,c.takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		return $this->db->get()->result();
	}
	
	public function get_by_id_with_cource($id = null)
	{
		$this->db->select('a.id,a.zipcode,a.prefecture,a.city,a.address,a.cource_id,c.cource_name,c.takuhai_day');
		$this->db->from("{$this->tablename} as a");
		$this->db->join('master_cource as c','c.cource_id = a.cource_id','left');
		$this->db->where('a.id',$id);
		return $this->db->get()->result();
	}

	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	
	public function get_by_id($id=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		$result =  $query->row();
		return $result;
	}
	
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename ,$data);
	}
	
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	public function get_area_by_zip($zipcode)
	{
		$this->db->where('zipcode',$zipcode);
		$query=$this->db->get('master_area');
		$result = $query->row;
		$this->cource_id = $result + 1;
		return $result;
	}
	
	public function get_max($fieldname='')
	{
		$this->db->select_max($fieldname);
		$query = $this->db->get($this->tablename);
		$result=$query->result();
		return $result[0]->cource_code;
	}
	
}		