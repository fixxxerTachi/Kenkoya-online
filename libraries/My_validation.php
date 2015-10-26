<?php
class My_validation{
	public $ci;
	public function __construct()
	{
		$this->ci = get_instance();
		$this->ci->load->model('Customer');
	}
	
	public function validation_message(){
		$this->ci->form_validation->set_message('required','%sを入力してください');
		$this->ci->form_validation->set_message('numeric','%sは半角数字で入力してください');
		$this->ci->form_validation->set_message('alpha_numeric','%sは半角英数字で入力してください');
		$this->ci->form_validation->set_message('alpha_dash','%sは数字とハイフン(―),アンダースコア(_)で入力してください');
		$this->ci->form_validation->set_message('max_length',mb_convert_encoding('%sは%s文字以内で入力してください','utf-8'));
		$this->ci->form_validation->set_message('min_length',mb_convert_encoding('%sは%s文字以上で入力してください','utf-8'));
		$this->ci->form_validation->set_message('valid_email',mb_convert_encoding('%sは正しい形式で入力してください','utf-8'));
	}
	
	public function validation_rules($no_member = False)
	{
		$this->ci->form_validation->set_error_delimiters('<p class="error">','</p>');
		$this->ci->form_validation->set_rules('name','お名前','required|max_length[50]');
		$this->ci->form_validation->set_rules('furigana','フリガナ','required|max_length[100]|callback_kana_check');
		$this->ci->form_validation->set_rules('email_confirm','メールアドレス','required|max_length[100]|valid_email');
		$this->ci->form_validation->set_rules('email','メールアドレス(確認用)','required|max_length[100]|valid_email');
		$this->ci->form_validation->set_rules('zipcode1','郵便番号','required|max_length[3]|numeric');
		$this->ci->form_validation->set_rules('zipcode2','郵便番号','required|max_length[4]|numeric');
		$this->ci->form_validation->set_rules('zipcode1','郵便番号','required|numeric');
		$this->ci->form_validation->set_rules('zipcode2','郵便番号','required|numeric');
		$this->ci->form_validation->set_rules('prefecture','県名','required|max_length[10]');
		$this->ci->form_validation->set_rules('address1','住所','required|max_length[200]');
		$this->ci->form_validation->set_rules('tel','電話番号','required|alpha_dash|max_length[14]|callback_tel_check|callback_tel_format_check');
		$this->ci->form_validation->set_rules('tel2','携帯電話番号','alpha_dash|max_length[14]|callback_tel_check|callback_tel_format_check');
		$this->ci->form_validation->set_rules('year','誕生日(年)','required|max_length[4]|numeric');
		$this->ci->form_validation->set_rules('month','誕生日(月)','required|max_length[2]|numeric');
		$this->ci->form_validation->set_rules('day','誕生日(日)','required|max_length[2]|numeric');
		/*
		if($this->input->post('member')){
			$this->form_validation->set_rules('username','ユーザーID','required|max_length[50]');
			$this->form_validation->set_rules('password','パスワード','required|max_length=[50]');
		}*/
		$this->validation_message();
	}
	
	public function add_address_validation_rules()
	{
		$this->ci->form_validation->set_error_delimiters('<p class="error">','</p>');
		$this->ci->form_validation->set_rules('name','お名前','required|max_length[50]');
		$this->ci->form_validation->set_rules('furigana','フリガナ','required|max_length[100]');
		$this->ci->form_validation->set_rules('zipcode1','郵便番号','required');
		$this->ci->form_validation->set_rules('zipcode2','郵便番号','required');
		$this->ci->form_validation->set_rules('address1','住所','required|max_length[200]');
		$this->ci->form_validation->set_rules('tel','電話番号','required|max_length[14]');
		$this->ci->form_validation->set_rules('tel','電話番号','required|max_length[14]');
		$this->validation_message();
	}
	
	public function validation_login()
	{
		$this->ci->form_validation->set_rules('username','ユーザーID','required|min_length[8]|callback_username_format_check|max_length[50]');
		$this->ci->form_validation->set_rules('password','パスワード','required|alpha_numeric|min_length[8]|max_length[50]');
		$this->validation_message();
	}
	
	//管理画面の会員追加と編集
	public function validation_add_customer()
	{
		$this->ci->form_validation->set_rules('cource_id','コース名','required|numeric');
		$this->ci->form_validation->set_rules('code','顧客コード','required|numeric|callback_code_length');
		$this->ci->form_validation->set_rules('name','お名前','required');
		$this->ci->form_validation->set_rules('furigana','フリガナ','required|callback_kana_check');
		$this->ci->form_validation->set_rules('zipcode','郵便番号','required|numeric|callback_zipcode_length');
		$this->ci->form_validation->set_rules('address1','住所','required');
		$this->ci->form_validation->set_rules('tel','電話番号','required|numeric|max_length[14]');
		$this->ci->form_validation->set_rules('tel2','携帯電話番号','numeric|max_length[14]');
		$this->ci->form_validation->set_rules('email','メールアドレス','max_length[100]|valid_email');
		//$this->ci->form_validation->set_rules('birthday','お誕生日','callback_birthday_format_check');
		$this->ci->form_validation->set_rules('year','お誕生日(西暦)','required|numeric');
		$this->ci->form_validation->set_rules('month','お誕生日(月)','required|numeric');
		$this->ci->form_validation->set_rules('day','お誕生日(日)','required|numeric');
	}
	
	//メールアドレスと確認用メールアドレスが同一かどうか確認する
	public function check_email_confirm($obj)
	{
		if($obj->email != $obj->email_confirm){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/*
	public function validation_zip($zip)
	{
		if(empty($zip) || !is_numeric($zip)){
			return false;
		}else{
			return true;
		}
	}
	*/
	
	public function validation_credit()
	{
		$this->ci->form_validation->set_rules('card_no','カード番号','required|numeric');
		$this->ci->form_validation->set_rules('expire_month','有効期限(月）','required|numeric');
		$this->ci->form_validation->set_rules('expire_year','有効期限（年）','required|numeric');
		$this->ci->form_validation->set_rules('security_code','セキュリティーコード','required|numeric');
		$this->validation_message();
	}
	//顧客コードの桁数チェック
	public function code_length($str)
	{
		$length = 6;
		if(mb_strlen($str,'UTF-8') !== $length)
		{
			$this->ci->form_validation->set_message('code_length','%sは' . $length . '文字で入力してください');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	//郵便番号の桁数チェック
	public function zipcode_length($str)
	{
		$length=7;
		if(mb_strlen($str,'UTF-8') !== $length)
		{
			$this->ci->form_validation->set_message('code_length','%sは' . $length . '文字で入力してください');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	//顧客コードの重複チェック
	public function code_check($str)
	{
		if($this->ci->Customer->check_code($str))
		{
			return TRUE;
		}
		else
		{
			$this->ci->form_validation->set_message('code_check','%sはすでに登録されています');
			return FALSE;
		}
	}
	//ユーザー名の重複をチェック
	public function username_check($str)
	{
		if($this->ci->Customer->check_username($str)){
			return TRUE;
		}else{
			$this->ci->form_validation->set_message('username_check','%sはすでに登録されています');
			return FALSE;
		}
	}
	//メールアドレスの重複をチェック
	public function email_check($str)
	{
		if($this->ci->Customer->check_email($str)){
			return TRUE;
		}else{
			$this->ci->form_validation->set_message('email_check','%sはすでに登録されいるメールアドレスです');
			return FALSE;
		}
	}
	
	//電話番号の重複チェック
	public function tel_check($str)
	{
		if(!empty($str))
		{
			if($this->ci->Customer->check_tel($str))
			{
				return TRUE;
			}
			else
			{
				$this->ci->form_validation->set_message('tel_check','%sはすでに登録されている電話番号です');
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
	//電話番号のフォーマットチェック
	public function tel_format_check($str)
	{
		if(!empty($str))
		{
			if (preg_match("/^([0-9]{2,5})\-([0-9]{2,5})\-([0-9]{4,5})$/", $str))
			{
				return TRUE;
			}
			else
			{
				$this->ci->form_validation->set_message('tel_format_check','%sは正しい形式で入力してください');
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	*/
	
	//誕生日のフォーマットチェック
	public function birthday_format_check($str)
	{
		if(preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$str))
		{
			return TRUE;
		}
		else
		{
			$this->ci->form_validation->set_message('birthday_format_check','%sは正しい形式で入力してください');
			return FALSE;
		}
	}
	
	//全角かたかなチェック
	public function kana_check($str)
	{
		if(preg_match("/^[ァ-ヶー　\s]+$/u", $str)) {
			return TRUE;
		}else{
			$this->ci->form_validation->set_message('kana_check','%sは全角カタカナで入力してください');
			return FALSE;
		}
	}
	
	/** usernameの形式チェック（半角英数字-_ **/
	/*
	public function username_format_check($str)
	{
		if(preg_match("/^[a-zA-Z0-9_-]+$/u",$str))
		{
			return TRUE;
		}
		else
		{	
			$this->ci->form_validation->set_message('username_format_check','%sは半角英数字,-,_のみ使用できます。');
			return FALSE;
		}
	}
	*/
}
