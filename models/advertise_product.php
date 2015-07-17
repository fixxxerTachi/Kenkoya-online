<?php
class Advertise_product extends CI_Model{
	public $code;
	public $product_code;
	public $branch_code;
	public $maker;
	public $porduct_name;
	public $size;
	public $cost_price;
	public $sale_price;
	public $profit;
	public $profit_ratio;
	public $freshness_date;
	public $additive;
	public $allergen;
	public $calorie;
	public $note;
	public $note1;
	public $note2;
	public $image_name;
	public $image_group;
	public $page_x;
	public $page_y;
	public $width;
	public $height;
	public $sale_start_datetime;
	public $sale_end_datetime;
	public $delivery_start_datetime;
	public $delivary_end_datetime;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'advertise_product';
	}
		
	/**　チラシ記載の商品番号から商品コードを取得する
	*@param int code
	*@return int product_code
	*/
	public function get_by_product_code($advertise_id,$code)
	{
		$this->db->select('product_code')->from($this->tablename);
		$this->db->where('advertise_id',$advertise_id);
		$this->db->where('code',$code);
		$result = $this->db->get()->row();
		if(!empty($result))
		{
			return $result->product_code;
		}
		else
		{
			return null;
		}
	}
	
	/** product_sizeと連結したデータを取得
	* @param int product_id
	* @return object Product_size
	*/
	public function get_product_with_size($product_id)
	{
		$this->db->select("s.*")->from($this->tablename . ' as ap');
		$this->db->join('product_size as s','s.product_code = ap.product_code','left');
		$this->db->join('temp_zone as tmp','tmp.id = s.temp_zone_id','left');
		$this->db->where('ap.id',$product_id);
		return $this->db->get()->row();
	}		
	
	public function all_data($category_id)
	{
		$this->db->select('ap.id')->from($this->tablename . ' as ap');
		$this->db->join('advertise as ad','ad.id = ap.advertise_id','left');
		$this->db->where('category_id',$category_id);
		$this->db->where('ad.del_flag',0);
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		$this->db->where('ad.start_datetime <= ',$date);
		$this->db->where('ad.end_datetime > ' , $date);
		$query = $this->db->get()->result();
		return count($query);
	}
	
	public function show_list_by_category($category_id,$limit=null,$offset=0)
	{
		if(!is_numeric($category_id)){
			throw new Exception('parameter cannet be numeric');
		}
		$this->db->select('
			ad_p.id,
			ad_p.product_code,
			ad_p.code,
			ad_p.maker,
			ad_p.product_name,
			ad_p.sale_price,
			ad_p.advertise_id,
			ad_p.sale_start_datetime as sale_start_datetime,
			ad_p.sale_end_datetime as sale_end_datetime,
			ad.title,
		');
		$this->db->from($this->tablename . ' as ad_p');
		$this->db->join('advertise as ad','ad_p.advertise_id = ad.id','left');
		//$this->db->join('master_products as p','p.product_code = ad_p.product_code','left');
		$this->db->where('ad_p.category_id',$category_id);
		$this->db->where('ad_p.on_sale',1);
		$this->db->where('ad.del_flag',0);
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		$this->db->where('ad.start_datetime <= ',$date);
		$this->db->where('ad.end_datetime > ',$date);
		//$this->db->where('p.branch_code = ad_p.branch_code');
		if($limit){
			$this->db->limit($limit,$offset);
		}
		return  $this->db->get()->result();
	}
	
	public function get_by_name($name)
	{
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		$this->db->select('
			ad_p.id,
			ad_p.code,
			ad_p.maker,
			ad_p.product_name,
			ad_p.sale_price,
			ad_p.advertise_id,
			ad_p.product_code,
			ad_p.branch_code,
			ad_p.sale_start_datetime as sale_start_datetime,
			ad_p.sale_end_datetime as sale_end_datetime,
			ad.title,
			p.product_code as p_code,
			p.branch_code as p_branch_code,
		');
		$this->db->from($this->tablename . ' as ad_p');
		$this->db->join('advertise as ad','ad_p.advertise_id = ad.id','left');
		$this->db->join('master_products as p','p.product_code = ad_p.product_code','left');
		$this->db->join('master_categories as c','c.id = ad_p.category_id','left');
		$this->db->where("(
			ad_p.product_name like '%{$name}%'  
			or ad_p.maker like '%{$name}%'
			or p.kana like '%{$name}%'
			or p.product_name like '%{$name}%'
			or p.short_name like '%{$name}%'
			or p.category_name like '%{$name}%'
			or p.vendor_name like '%{$name}%'
			or c.name like '%{$name}%'
			or c.show_name like '%{$name}%'
		)");
	/*
		$this->db->or_like('ad_p.product_name',$name);
		$this->db->or_like('ad_p.maker',$name);
		$this->db->or_like('p.kana',$name);
		$this->db->or_like('p.product_name',$name);
		$this->db->or_like('p.short_name',$name);
		$this->db->or_like('p.category_name',$name);
		$this->db->or_like('p.vendor_name',$name);
		$this->db->or_like('c.name',$name);
		$this->db->or_like('c.show_name',$name);
	*/
		$this->db->where('ad_p.on_sale',1);
		$this->db->where('ad.del_flag',0);
		$this->db->where('p.branch_code = ad_p.branch_code');
		$this->db->where('ad.start_datetime < ',$date);
		$this->db->where('ad.end_datetime > ',$date);
		$result = $this->db->get()->result();
		return $result;
	}
	
	public function get_by_product_id($id)
	{
		if(!is_numeric($id)){
			throw new Exception('no decimal');
		}
		$this->db->select('
			ad_p.id,
			ad_p.code,
			ad_p.product_code,
			ad_p.branch_code,
			ad_p.image_name as ad_p_image_name,
			ad_p.size,
			ad_p.maker,
			ad_p.product_name,
			ad_p.sale_price,
			ad_p.advertise_id,
			ad_p.freshness_date,
			ad_p.allergen,
			ad_p.note,
			ad_p.sale_start_datetime as sale_start_datetime,
			ad_p.sale_end_datetime as sale_end_datetime,
			ad_p.delivery_start_datetime as delivery_start_datetime,
			ad_p.delivery_end_datetime as delivery_end_datetime,
			ad.title,
			ad_p.category_id,
		');
		$this->db->from($this->tablename . ' as ad_p');
		$this->db->join('advertise as ad','ad_p.advertise_id = ad.id','left');
		//$this->db->join('master_products as p','ad_p.product_code = p.product_code','left');
		$this->db->where('ad_p.id',$id);
		$this->db->where('ad_p.on_sale',1);
		$this->db->where('ad.del_flag',0);
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		$this->db->where('ad.start_datetime <= ',$date);
		$this->db->where('ad.end_datetime > ',$date);
		$this->db->limit(1);
		$result = $this->db->get()->row();
		return $result;
	}
	
	/** カートに商品を登録する際にチラシの有効期限内で商品マスタ、チラシ商品マスタが販売可能なものを返す
	* @param int product_id
	* @return object Advertise_product
	**/
	public function check_on_sale($product_id)
	{
		$this->db->select('ap.id');
		$this->db->from($this->tablename . ' as ap');
		//$this->db->join('master_products as mp','ap.product_code = mp.product_code','left');
		$this->db->join('advertise as ad','ap.advertise_id = ad.id','left');
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d H:i:s');
		//チラシの有効期限内チェック
		$this->db->where('ad.start_datetime <= ' , $date);
		$this->db->where('ad.end_datetime > ' , $date);
		//販売中チェック
		//$this->db->where('mp.on_sale',1);
		$this->db->where('ap.on_sale',1);
		//$this->db->where('ap.advertise_id', $advertise_id);
		//$this->db->where('mp.branch_code = ap.branch_code');
		$this->db->where('ap.id',$product_id);
		$result = $this->db->get($this->tablename)->row();
		if(!empty($result))
		{	
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/****　商品別に設定された販売期間ないかどうか調べる
	* @param int product_id 
	* @return bool
	**/
	public function validate_sale_period($product_id)
	{
		$this->db->select('sale_start_datetime,sale_end_datetime');
		$this->db->from($this->tablename);
		$this->db->where('id',$product_id);
		$row = $this->db->get()->row();
		$today = new DateTime();
		$ssdatetime = $row->sale_start_datetime ? new DateTime($row->sale_start_datetime) : new DateTime('1000-01-01 00:00:00');
		$sedatetime = $row->sale_end_datetime ? new DateTime($row->sale_end_datetime) : new DateTime('9999-12-31 11:59:59');
		if($ssdatetime <= $today && $today < $sedatetime)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
		
	/***** お届け日限定商品の場合配達日が有効期限内かどうか
	*/
	public function check_delivery_limit_date($dateobj,$product_id)
	{
		$this->db->select('delivery_start_datetime,delivery_end_datetime');
		$this->db->from($this->tablename);
		$this->db->where('id',$product_id);
		$row = $this->db->get()->row();
		$dsdatetime = $row->delivery_start_datetime ? new DateTime($row->delivery_start_datetime) : new DateTime('1000-01-01 00:00:00');
		$dedatetime = $row->delivery_end_datetime ? new DateTime($row->delivery_end_datetime) : new DateTime('9999-12-31 11:59:59');
		if($dsdatetime <= $dateobj && $dateobj < $dedatetime)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	public function show_list($del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->order_by('create_date','desc');
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	public function get_by_id_with_advertise_image($advertise_id = null, $del_flag=True)
	{
		$this->db->select('a.id,a.description,a.start_datetime,a.end_datetime,ai.image_name,ai.description,ai.start_page,ai.end_page');
		$this->db->from($this->tablename . ' as a');
		$this->db->join('advertise_image as ai','ai.advertise_id = a.id','left');
		if($del_flag){ $this->db->where('a.del_flag',0);}
		$query=$this->db->get();
		$result = $query->result();
		return $result[0];
	}
	
	public function get_imageinfo_by_ad_page($advertise_id,$start_page,$end_page)
	{
		$this->db->select('ap.id,ap.image_name,ap.image_group,ap.page_x,ap.page_y,ap.width,ap.height,ap.advertise_id');
		$this->db->from($this->tablename . ' as ap');
		$this->db->where('ap.advertise_id',$advertise_id);
		$this->db->where("ap.page between {$start_page} and {$end_page}");
		return $this->db->get()->result();
	}
	
	public function get_product_by_image_name($image_group,$advertise_id,$on_sale = null)
	{
		$this->db->select('ap.*');
		$this->db->from($this->tablename . ' as ap');
		//$this->db->join('master_products as mp','mp.product_code = ap.product_code','left');
		$this->db->join('advertise as ad','ap.advertise_id = ad.id','left');
		$datetime = new DateTime();
		$date = $datetime->format('Y-m-d');
		$this->db->where('ad.start_datetime <= ',$date);
		$this->db->where('ad.end_datetime > ',$date);
		$this->db->where('ap.image_group',$image_group);
		$this->db->where('ap.advertise_id',$advertise_id);
		//$this->db->where('mp.branch_code = ap.branch_code');
		//販売中
		$this->db->where('ap.on_sale',ONSALE);
		//商品に販売期間がある場合
		//$this->db->where
		return $this->db->get()->result();
	}
	
	public function show_list_arr($del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$this->db->where('end_datetime > ' , date('Y-m-d'));
		$this->db->order_by('create_date','desc');
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		$arr = array();
		$arr[0] = '選択してください';
		foreach($result as $row){
			$arr[$row->id] = $row->title;
		}
		return $arr;
	}
	
	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_products_by_advertise_id($id = null)
	{
		$this->db->where('advertise_id',$id);
		$query = $this->db->get($this->product_tablename);
		$result = $query->result();
		return $result;
	}
	
	public function get_products_by_advertise_id_with_image($advertise_id=null , $obj = null,$limit=null,$offset=null)
	{
		$this->db->select("a.id,a.code,a.product_code,a.maker,a.product_name,a.size,a.sale_price,a.freshness_date,a.additive,a.image_name,a.image_description,
			a.allergen,a.calorie,a.note,a.page,a.category_id,
			p.id as product_id,p.product_code as p_code,p.product_name as p_name,p.short_name,p.sale_price as p_price");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
//		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.advertise_id' , $advertise_id);
		if($obj){
			if($obj->category_id){
				$this->db->where('a.category_id',$obj->category_id);
			}
			if($obj->code){
				$this->db->where('a.code',$obj->code);
			}
			if($obj->product_name){
				$this->db->like('a.product_name',$obj->product_name);
			}
			if($limit && $offset){
				$this->db->limit($limit,$offset);
			}
		}
		$query=$this->db->get();
		return $query->result();
	}
		
	public function get_product_by_id($product_id = Null)
	{
		$this->db->where('id',$product_id);
		$query = $this->db->get($this->product_tablename);
		return $query->result();
	}
	
	public function get_product_by_id_with_product($product_id = null)
	{
		$this->db->select("a.*
			,p.id as product_id
			,p.category_code as p_category_code
			,p.category_name as p_category_name
			,p.product_code as p_product_code
			,p.branch_code as p_branch_code
			,p.product_name as p_product_name
			,p.short_name as p_short_name
			,p.sale_price as p_sale_price
			,p.cost_price as p_cost_price
			,p.on_sale
			");
		$this->db->from($this->product_tablename . ' as a');
		$this->db->join('master_products as p','p.product_code = a.product_code','left');
//		$this->db->join('product_image as i','i.product_id = p.id','left');
		$this->db->where('a.id' , $product_id);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
	}
	public function get_by_id($id=null , $del_flag=null)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tablename);
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$result =  $query->result();
		return $result;
	}
	
	
	public function get_by_id_with_image($id=null,$del_flag=null)
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,cost_price,on_sale,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$this->db->where("{$this->tablename}.id",$id);
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_allergen_by_id($product_id=null){
		$this->db->select('p.id as product_id, a.name as allergen_name , a.icon as icon, a.id as allergen_id, pa.id as pa_middle_id');
		$this->db->from("{$this->tablename} as p ");
		$this->db->join('product_allergen as pa','pa.product_id = p.id','left');
		$this->db->join('allergen as a','a.id = pa.allergen_id','left');
		$this->db->where( 'p.id' ,$product_id );
		$query = $this->db->get();
		return $query->result();
	}	
	
	public function save_allergen($posts=false)
	{
		if($posts){
			$product_id= $this->db->insert_id();
			foreach($posts as $allergen_id){
				$db_data = array(
					'product_id' => $product_id,
					'allergen_id' => $allergen_id,
				);
				$this->db->insert('product_allergen',$db_data);
			}
		}
	}
	
	public function update_allergen($product_id = null , $ids = array() , $posts=array()){
		foreach($ids as $id){
			$this->db->where('id',$id);
			$this->db->delete('product_allergen');
		}
		if($posts){
			foreach($posts as $allergen_id){
				$db_data = array(
					'product_id'=>$product_id,
					'allergen_id'=>$allergen_id,
				);
				$this->db->insert('product_allergen',$db_data);
			}
		}
	}
	
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_product($product_id = null,$data=array())
	{
		if(empty($data->sale_start_datetime))
		{
			$data->sale_start_datetime = null;
		}
		if(empty($data->sale_end_datetime))
		{
			$data->sale_end_datetime = null;
		}
		if(empty($data->delivery_start_datetime))
		{
			$data->delivery_start_datetime = null;
		}
		if(empty($data->delivery_end_datetime))
		{
			$data->delivery_end_datetime = null;
		}
		$this->db->where('id',$product_id);
		$this->db->update('advertise_product',$data);
	}
	
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function save_image($data=array())
	{
		$this->db->insert('product_image',$data);
	}
	
	public function update_image($product_id=null, $data=array())
	{
		$this->db->where('product_id',$product_id);
		$this->db->update('takuhai_product_image',$data);
	}
}
