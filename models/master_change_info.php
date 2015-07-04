<?php
class Master_change_info{
	public $info;
	public function __construct()
	{
		$this->info = array(
			'name'=>'名前',
			'address'=>'住所',
			'tel'=>'電話番号',
			'email'=>'メールアドレス',
			'maga'=>'メールマガジン',
		);
	}
}