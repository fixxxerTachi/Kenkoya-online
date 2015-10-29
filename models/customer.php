<?php
class Customer extends CI_Model{
	public $tablename;
	public $id;
	public $shop_id;
	public $cource_id;
	public $code;
	public $customer_code;
	public $name;
	public $furigana;
	public $birthday;
	public $year;
	public $month;
	public $day;
	public $tel;
	public $tel2;
	public $email;
	public $zipcode;
	public $zipcode1;
	public $zipcode2;
	public $pref_id;
	public $prefecture;
	public $address1;
	public $address2;
	public $street;
	public $paymant_id;
	public $point;
	public $rank;
	public $username;
	public $password;
	public $bank_name;
	public $type_account;
	public $account_number;
	public $mail_magazine;
	public $member_flag;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablename = 'takuhai_customers';
		$this->account_number = '';
		$this->member_flag = 1;
		$this->cource_id = NO_DELI_AREA;
		$this->load->library('my_exception');
	}
	
	//宅配料金算出の為pref_idを取得
	public function get_pref_id(StdClass $customer)
	{
		$this->db->select('pref_id')->from($this->tablename);
		$this->db->where('username',$customer->username);
		$row = $this->db->get()->row();
		return $row->pref_id;
	}
	
	//check_email,tel,usernameは重複してはいけない為、存在したらfalse
	public function check_email($email, $del_falg = TRUE, $customer_id = NULL)
	{
		//customer_idが渡されている場合、その顧客以外で
		if(!empty($customer_id))
		{
			$this->db->where('id <> ',$customer_id);
		}
		$this->db->select('email');
		$this->db->from($this->tablename);
		$this->db->where('email',$email);
		$this->db->where('code is not null',null,false);
		if($del_falg)
		{
			$this->db->where('del_flag',0);
		}
		$result = $this->db->get()->row();
		if(!empty($result->email)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function check_username($str,$del_flag=True, $customer_id = NULL)
	{
		//customer_idが渡されている場合、その顧客以外で
		if(!empty($customer_id))
		{
			$this->db->where('id <> ',$customer_id);
		}
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$this->db->where('username',$str);
		$query = $this->db->get($this->tablename);
		$result = $query->row();

		if(!empty($result)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function check_tel($str,$del_flag=True, $customer_id = NULL)
	{
		if(!empty($str))
		{
			//customer_idが渡されている場合、その顧客以外で
			if(!empty($customer_id))
			{
				$this->db->where('id <> ',$customer_id);
			}
			if($del_flag){
				$this->db->where('del_flag',0);
			}
			$this->db->where("(tel = {$str} or tel2 = {$str})");
			$query = $this->db->get($this->tablename);
			$result = $query->row();
			if(!empty($result)){
				return FALSE;
			}else{
				return TRUE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	public function check_code($code, $del_flag = TRUE, $customer_id = NULL)
	{
		//customer_idが渡される場合はその顧客を除外
		if(!empty($customer_id))
		{
			$this->db->where('id <> ' , $customer_id);
		}
		if($del_flag)
		{
			$this->db->where('del_flag',0);
		}
		$this->db->where('code',$code);
		$result = $this->db->get($this->tablename)->row();
		if(!empty($result))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function check_customer_no_web($shop_code,$customer_code,$tel)
	{
		$obj = $this->get_tel_numbers($shop_code,$customer_code);
		if(!empty($obj)){
			$tel = str_replace('-','',$tel);
			$db_tel = str_replace('-','',$obj->tel);
			$db_tel2 = str_replace('-','',$obj->tel2);
			if($tel == $db_tel || $tel == $db_tel2){
				return $shop_code. $customer_code;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function check_login($obj){
		$this->db->where('username',$obj->username);
		$this->db->where('password',sha1($obj->password));
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if(count($result) < 1){
			return FALSE;
		}else{
			$this->load->library('session');
			$user_data = $result[0];
			/*
			$data = array(
				'user_id'=>$user_data->id,
				'username'=>$user_data->name
			);
			$data = (object)$data;
			*/
			$this->session->set_userdata('login_user',$user_data);
			return TRUE;
		}
	}
	
	/** shop_code,codeのみ取得する
	 * @param object Customer
	 * @return  string shop . code
	 */
	public function get_code($obj)
	{
		$this->db->select('s.code as shop_code,c.code as code')->from('customers as c');
		$this->db->join('master_cource as co','co.id = c.cource_id','left');
		$this->db->join('master_shop as s','s.id = co.shop_id','left');
		$this->db->where('c.username',$obj->username);
		$result = $this->db->get()->row();
		return $result->shop_code . $result->code;
	}

	/** order_numberの生成
	 * @param object Customer
	 * @return string 
	 */
	public function create_order_number($obj)
	{
		$customer_code = $this->get_code($obj);
		return date('ymd-his') . $customer_code;
	}	
		
	/** webから会員登録した人を取得 new_member_flag = 1
	* @param string shop_code
	* @param int flag_number
	* @return object Customer::new_member_flag = 1
	*/
	public function get_new_member($shop_code = '',$flag = 1)
	{
		$this->db->select('c.*,ca.cource_name');
		$this->db->from($this->tablename . ' as c');
		$this->db->join('master_cource as ca','ca.cource_code = c.cource_code','left');
		$this->db->where('c.shop_code = ca.shop_code');
		$this->db->where('c.new_member_flag',$flag);
		if($shop_code != ''){
			$this->db->where('c.shop_code',$shop_code);
		}
		$result = $this->db->get()->result_array();
		return $result;
	}
		
	/** 新規ユーザーを取り込み済みにする new_member_flag=0,change_info=null
	*　@param object Customer new_member_flag = 1
	* @return void
	*/	
	public function update_new_member_flag($data)
	{
		$ids = array();
		foreach($data as $item)
		{
			$ids[] = $item['id'];
		}
		$col = array('new_member_flag'=>0);
		$this->db->where_in('id',$ids);
		$this->db->update($this->tablename,$col);
	}
	
	/** 会員情報を変更した人を取得する change_info is not null
	* @param string shop_code
	* @return object Customer
	*/
	public function get_change_member($shop_code)
	{
		$this->db->select('c.*,ca.cource_name');
		$this->db->from($this->tablename . ' as c');
		$this->db->join('master_cource as ca','ca.cource_code = c.cource_code','left');
		$this->db->where('c.shop_code = ca.shop_code');
		$this->db->where('c.change_info is not null');
		if($shop_code != ''){
			$this->db->where('c.shop_code',$shop_code);
		}
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	/**　会員情報変更済みを閲覧済みにする change_info = null
	*@param object Customer
	*@return void
	*/
	public function update_change_member_flag($data)
	{
		$ids = array();
		foreach($data as $item)
		{
			$ids[] = $item['id'];
		}
		$col = array('change_info'=>null);
		$this->db->where_in('id', $ids);
		$this->db->update($this->tablename,$col);
	}
	
	/** 顧客IDから会員登録住所の表示（請求先住所)
	*@param int customer_id
	*@return array name,address
	**/
	public function show_address($customer_id)
	{
		$this->db->select('name,address1,address2')->from($this->tablename);
		$this->db->where('id',$customer_id);
		$row = $this->db->get()->row();
		if(!empty($row))
		{
			return array('name'=>$row->name, 'address'=>$row->address1 . $row->address2);
		}
		else
		{
			throw new Exception($this->my_exception->log_info(). '顧客情報が存在しません(ID:' . $customer_id . ')');
		}
	}

	public function get_by_username_and_password($obj)
	{
		$this->db->where('username',$obj->username);
		$this->db->where('password',$obj->password);
		$result = $this->db->get($this->tablename)->row();
		if(!empty($result)){
			return $result;
		}else{
			throw new Exception('登録できませんでした');
		}
	}

	public function check_login_customer($obj){
		$this->db->where('username',$obj->username);
		$query = $this->db->get($this->tablename);
		$result = $query->row();
		if(empty($result)){
			return FALSE;
		}else{
			$this->load->library('encrypt');
			$decoded = $this->encrypt->decode($result->password);
			if($decoded == $obj->password){
				$this->load->library('session');
				//id,username,name,メアドのみobjectとして格納
				$customer = new StdClass();
				$customer->id = $result->id;
				$customer->username = $result->username;
				$customer->name = $result->name;
				$customer->email = $result->email;
				$csutomer->code = $result->code;
				$this->session->set_userdata('customer',$customer);
				//ログイン時session no-memberがあったら削除する
				if($this->session->userdata('no-member')){
					$this->session->unset_userdata('no-member');
				}
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}

	public function check_password($sessobj,$dbobj,$inputobj){
		if($sessobj->username == $dbobj->username){
			$decoded = $this->encrypt->decode($dbobj->password);
			if($decoded == $inputobj->current_pw){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
		
	public function check_username_tel_name($username,$tel,$name,$del_flag = true)
	{
		if($del_flag){
			$this->db->where('del_flag',0);
		}
		$this->db->where('username',$username);
		$result  = $this->db->get($this->tablename)->row();
		if(empty($result)){ throw new Exception('ユーザー名が登録されていません'); }
		$tel = str_replace('-','',$tel);
		$tel1 = str_replace('-','',$result->tel);
		$tel2 = str_replace('-','',$result->tel2);
		$db_name = trim(mb_convert_kana($result->name, "s", 'UTF-8'));
		
		if($name == $db_name && ($tel == $tel1 || $tel == $tel2)){
			return $result;
		}else{
			throw new Exception('お名前もしくはお電話番号が登録されていません');
		}
	}
	
		
	
	public function check_by_code($obj){
		$code = $obj->code;
		$this->db->where('code',$code);
		$this->db->select('id,code,name,username,email');
		$this->db->from($this->tablename);
		return $this->db->get()->row();
	}
	
	public function check_by_username($username){
		$this->db->where('username',$username);
		$this->db->select('id,username,email,name,shop_code,code');
		$this->db->form($this->tablename);
		$result = $this->db->get()->row();
		if(!empty($result)){
			return $result;
		}else{
			return　false;
		}
	}

	public function get_point($obj){
		$this->db->select('point')->from($this->tablename);
		$this->db->where('username',$obj->username);
		return $this->db->get()->row();
	}

	public function show_list($del_flag=TRUE)
	{
		if($del_flag){
			$this->db->where('del_flag','0');
		}
		$query= $this->db->get($this->tablename);
		return $query->result();
	}
	
	/** 会員リストの一覧表示と条件検索
	*@return object Customer
	*/
	public function show_list_where($limit=null,$offset=null,$obj=null,$del_flag=True)
	{
		$this->db->select('
			c.cource_id as cource_id
			,c.code as customer_code
			,c.id as id
			,c.name
			,c.tel
			,c.tel2
			,c.zipcode
			,c.pref_id
			,c.address1
			,c.address2
			,ca.cource_name as cource_name
			,s.shop_name as shop_name
		');
		$this->db->from($this->tablename . ' as c');
		//$this->db->join('master_cource as ca','ca.cource_code = c.cource_code','left');
		//$this->db->where('c.shop_id = ca.shop_id');
		$this->db->join('master_cource as ca','ca.id = c.cource_id','left');
		$this->db->join('master_shop as s','s.id = ca.shop_id','left');
		if($del_flag){
			$this->db->where('c.del_flag','0');
		}
		if(!empty($obj)){
			if(!empty($obj->name)){
				$this->db->like('c.name',$obj->name);
			}
			if(!empty($obj->code)){
				$this->db->like('c.code',$obj->code);
			}
		}
		$this->db->where('member_flag',1);
		$this->db->limit($limit,$offset);
		$query= $this->db->get();
		return $query->result();
	}
	
	public function show_list_conditions($obj)
	{
		$this->db->select('
			c.cource_id as cource_id
			,c.code as customer_code
			,c.id as id
			,c.name
			,c.furigana
			,c.tel
			,c.tel2
			,c.zipcode
			,c.pref_id
			,c.address1
			,c.address2
			,ca.cource_name as cource_name
			,s.shop_name as shop_name
			,s.id as shop_id
		');
		$this->db->from($this->tablename . ' as c');
		$this->db->join('master_cource as ca','ca.id = c.cource_id','left');
		$this->db->join('master_shop as s','s.id = ca.shop_id','left');
		$this->db->where('c.del_flag','0');
		if(!empty($obj)){
			if($obj->shop_id != 0){
				$this->db->where('s.id',$obj->shop_id);
			}
			if($obj->cource_id != 0){
				$this->db->where('c.cource_id',$obj->cource_id);
			}
			if(!empty($obj->name)){
				$this->db->like('c.name',$obj->name);
			}
			if(!empty($obj->code)){
				$this->db->like('c.code',$obj->code);
			}
			if(!empty($obj->tel)){
				$this->db->like('c.tel',$obj->tel);
			}
			if(!empty($obj->address1)){
				$this->db->like('c.address1',$obj->address1);
			}
			if(!empty($obj->address2)){
				$this->db->like('c.address2',$obj->address2);
			}
		}
		$query= $this->db->get();
		return $query->result();
	}
	
	public function new_customer($name = false){
		//$this->db->where('code','');
		//$this->db->or_where('code is null');
		$this->db->where("(code = '' or code is null)");
		if($name){
			$this->db->like('name',$name);
		}
		$this->db->where('del_flag','0');
		return $this->db->get($this->tablename)->result();
	}
	
	public function get_tel_numbers($shop_code,$customer_code)
	{
		$this->db->select('shop_code,code,tel,tel2');
		$this->db->from($this->tablename);
		$this->db->where('shop_code',$shop_code);
		$this->db->where('code',$customer_code);
		return $this->db->get()->row();
	}
	
	
	public function num_rows()
	{
		$this->db->select('id');
		$this->db->from($this->tablename);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function show_list_with_image()
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,on_sale,cost_price,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function show_list_with_personal($del_flag=TRUE)
	{
		$this->db->select("c.id as id ,c.familyname,c.firstname,p.user_id,p.username,p.password,p.point,p.rank,p.bank_name,p.type_account,p.account_number");
		$this->db->from($this->tablename . ' as c');
		$this->db->join('personal as p',"p.user_id = c.id",'left');
		if($del_flag ){
			$this->db->where("c.del_flag",0);
		}
		$query = $this->db->get();
		return $query->result();
	}
		
	public function save($data=array())
	{
		$this->db->insert($this->tablename, $data);
		if($this->db->affected_rows()){
			$id = $this->db->insert_id();
			return $id;
		}else{
			throw new Exception('cannot save customer_information');
		}
	}
	
	public function get_by_id($id=null , $del_flag = True)
	{
		$this->db->select('
			c.cource_id as cource_id
			,c.code as code
			,c.id as id
			,c.name
			,c.furigana
			,c.birthday
			,c.tel
			,c.tel2
			,c.email
			,c.zipcode
			,c.address1
			,c.address2
			,c.username
			,ca.cource_name
			,s.shop_name
			,s.id as shop_id
		');
		$this->db->from($this->tablename . ' as c');
		$this->db->join('master_cource as ca','ca.id = c.cource_id','left');
		$this->db->join('master_shop as s','s.id = ca.shop_id','left');
		$this->db->where('c.id',$id);
		$query = $this->db->get();
		$result =  $query->row();
		return $result;
	}
	
	public function get_by_username($customer)
	{
		$this->db->where('username',$customer->username);
		$result = $this->db->get($this->tablename)->row();
		return $result;
	}
	
	
	public function get_by_email($email)
	{
		$this->db->select('*')->from($this->tablename);
		$this->db->where('email',$email);
		$result = $this->db->get()->row();
		if(!empty($result)){
			return $result;
		}else{
			throw new Exception('メールアドレスが登録されていません');
		}
	}
	
	public function reduct_point($customer,$point)
	{
		$result = $this->get_by_username($customer);
		$point = $result->point - $point;
		$this->db->where('username',$customer->username);
		$this->db->update($this->tablename,array('point'=>$point));
	}


	/*
	public function get_by_id_with_image($id=null)
	{
		$this->db->select("{$this->tablename}.id as id,product_code,branch_code,product_name,short_name,sale_price,cost_price,on_sale,image_name");
		$this->db->from($this->tablename);
		$this->db->join('product_image',"product_image.product_id = {$this->tablename}.id",'left');
		$this->db->where("{$this->tablename}.id",$id);
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
	*/
	public function update($id=null,$data=array())
	{
		$this->db->where('id',$id);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_by_code($user_code,$data)
	{
		$shop_code=substr($user_code,0,3);
		$code = substr($user_code,3);
		$this->db->select('id');
		$this->db->from($this->tablename);
		$this->db->where('shop_code',$shop_code);
		$this->db->where('code',$code);
		$result = $this->db->get()->row();
		if(empty($result)){
			throw new Exception('登録されていません');
		}
		$this->db->where('code',$code);
		$this->db->where('shop_code',$shop_code);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_by_username($username,$data)
	{
		if($this->check_username){
			throw new Exception('ユーザー名が登録されていません');
		}
		$this->db->where('username',$username);
		$this->db->update($this->tablename,$data);
	}
	
	public function update_by_email($customer,$data)
	{
		if($this->check_email($customer->email)){
			throw new Exception('メールアドレスが登録されていません');
		}
		$this->db->where('email',$customer->email);
		$this->db->update($this->tablename,$data);
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
	public function delete($id=null){
		$this->db->where('id',$id);
		$data = array('del_flag'=> 1);
		$this->db->update($this->tablename,$data);
	}
	public function get_area_by_zip($zipcode)
	{
		$this->db->select('a.*,ct.takuhai_day');
		$this->db->from('master_area as a');
		$this->db->join('master_cource as c','c.id = a.cource_id','left');
		$this->db->join('master_cource_type as ct','ct.id = c.cource_type_id','left');
		$this->db->where('a.zipcode',$zipcode);
		$query=$this->db->get();
		$result = $query->row();
		return $result;
	}
	
	/*
	public function check_password($sessobj,$dbobj,$inputobj){
		if($sessobj->id == $dbobj->id){
			if($dbobj->password == sha1($inputobj->current_pw)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	*/
	
	
	
	public function re_session_userdata($user_id = null)
	{
		if($user_id)
		{
			$customer = $this->get_by_id($user_id);
			$this->session->set_userdata('customer',$customer);
		}else{
			return False;
		}
	}
	
	public function get_max_usercode()
	{
		$this->db->select('max(code) as maxcode')->from($this->tablename);
		$result = $this->db->get()->row();
		return (int)$result->maxcode;
	}
	
}
