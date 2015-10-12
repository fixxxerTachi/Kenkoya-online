<?php
include __DIR__.'/../libraries/define.php';
class My_Class{
	/* 前後のスペースを取り除く　*/
	public function trim_space($str)
	{
		//前の半角、全角スペースを取り除く
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 最後の半角、全角スペースを、空文字に置き換える
		$str = preg_replace('/[ 　]+$/u', '', $str);
		return $str;
	}
	/* 英数字変換 */
	public function convert_alpha($str)
	{
		$result = mb_convert_kana($str,'a','UTF-8');
		return $result;
	}
	/*　文字列中の半角カナと半角スペースを全角にする */
	public function convert_kana($str)
	{
		$str = $this->trim_space($str);
		$result = mb_convert_kana($str,'KVS','UTF-8');
		return $result;
	}
	
	/*　名前などで文字中にで空白がある場合全角空白に変換して統一する　*/
	public function convert_space($str)
	{
		$str = $this->trim_space($str);
		$result = mb_convert_kana($str,'S','UTF-8');
		return $result;
	}
	
	public function getDb(){
		try{
			$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
			$user = DB_USER;
			$password = DB_PASSWORD;
			$options = array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			); 
			return $dbh = new PDO($dsn, $user, $password, $options);
		}catch(Exception $e){
			$e->getMessage();
		}
	}
	
	public function _checklogin($customer,$ref = ''){
		if(empty($customer)){
			if($ref == 'mypage'){
				$ref = 'mypage';
			}
			return redirect("front_customer/login_action/{$ref}");
		}else{
			return $customer;
		}
	}
	
	public function _test()
	{
		echo 'this is MyClass';
	}

}