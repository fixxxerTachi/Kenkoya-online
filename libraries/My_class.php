<?php
class My_Class{
	/* 英数字変換 */
	public function convert_alpha($str)
	{
		$result = mb_convert_kana($str,'a','UTF-8');
		//空白を取り除く
		return $result;
	}
	/*　半角カナと半角スペースを全角にする */
	public function convert_kana($str)
	{
		//前後の空を取り除く
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 最後の半角、全角スペースを、空文字に置き換える
		$str = preg_replace('/[ 　]+$/u', '', $str);
		$result = mb_convert_kana($str,'KVS','UTF-8');
		return $result;
	}
	
	/*　お名前で空白がある場合全角空白に変換して統一する　*/
	public function convert_space($str)
	{
		//前後の空を取り除く
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 最後の半角、全角スペースを、空文字に置き換える
		$str = preg_replace('/[ 　]+$/u', '', $str);
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