<?php
class MyClass{
	/* 全角英数字を半角にする */
	public function convert_alpha($str)
	{
		$result = mb_convert_kana($str,'a','UTF-8');
		return $result;
	}
	
	/*　カタカナ変換 */
	public function convert_kana($str)
	{
		$result = mb_convert_kana($str,'KVCS','UTF-8');
		return $result;
	}
	/*　全角空白に変換　*/
	public function convert_space($str)
	{
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
	
	public function array_flatten($array){
        $result = array();
        array_walk_recursive($array, function($v) use (&$result){
            $result[] = $v;
        });
        return $result;
    }


}