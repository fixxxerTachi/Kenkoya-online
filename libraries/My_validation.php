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
		$this->ci->form_validation->set_message('alpha_dash','%sは数字とハイフン(―)で入力してください');
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
		//$this->form_validation->set_rules('zipcode','郵便番号','required');
		$this->ci->form_validation->set_rules('zipcode1','郵便番号','required|numeric');
		$this->ci->form_validation->set_rules('zipcode2','郵便番号','required|numeric');
		$this->ci->form_validation->set_rules('prefecture','県名','required|max_length[10]');
		$this->ci->form_validation->set_rules('address1','住所','required|max_length[200]');
		/*$this->form_validation->set_rules('street','住所、番地','required|max_length[100]');*/
		$this->ci->form_validation->set_rules('tel','電話番号','required|alpha_dash|max_length[15]|callback_tel_check|callback_tel_format_check');
		$this->ci->form_validation->set_rules('tel2','携帯電話番号','alpha_dash|max_length[15]|callback_tel_check|callback_tel_format_check');
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
		$this->ci->form_validation->set_rules('tel','電話番号','required|max_length[15]');
		$this->ci->form_validation->set_rules('tel','電話番号','required|max_length[15]');
		$this->validation_message();
	}
	
	public function validation_login()
	{
		$this->ci->form_validation->set_rules('username','ユーザーID','required|max_length[50]');
		$this->ci->form_validation->set_rules('password','パスワード','required|max_length[50]');
		$this->validation_message();
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
	public function validation_zip($zip)
	{
		if(empty($zip) || !is_numeric($zip)){
			return false;
		}else{
			return true;
		}
	}

	public function validation_credit()
	{
		$this->ci->form_validation->set_rules('card_no','カード番号','required|numeric');
		$this->ci->form_validation->set_rules('expire_month','有効期限(月）','required|numeric');
		$this->ci->form_validation->set_rules('expire_year','有効期限（年）','required|numeric');
		$this->ci->form_validation->set_rules('security_code','セキュリティーコード','required|numeric');
		$this->validation_message();
	}
	/**** 以下はコントローラから呼び出し 
	
	//ユーザー名の重複をチェック
	public function username_check($str)
	{
		if($this->Customer->check_username($str)){
			return TRUE;
		}else{
			$this->form_validation->set_message('username_check','%sはすでに登録されています');
			return FALSE;
		}
	}
	//メールアドレスの重複をチェック
	public function email_check($str)
	{
		if($this->Customer->check_email($str)){
			return TRUE;
		}else{
			$this->form_validation->set_message('email_check','%sはすでに登録されいるメールアドレスです');
			return FALSE;
		}
	}
	
	//全角かたかなチェック
	public function kana_check($str)
	{
		if(preg_match("/^[a]+$/u", $str)) {
			return TRUE;
		}else{
			$this->form_validation->set_message('kana_check','%sは全角カタカナで入力してください');
			return FALSE;
		}
	}
	******************/
}
