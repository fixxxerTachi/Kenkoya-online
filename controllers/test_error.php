<?php
/**	初期状態ではCodeIgnirerはすべてのPHPエラーを表示する
* index.phpの先頭にerror_reporting()
* エラー報告を無効にしてもログファイルに記録する
*/
/** logの取り方
* 初期値はapplication配下のlogsディレクトリに格納される
* config.phpの$config['log_threshold']が0だとログ記録が無効 ( 開発段階では1 )
*/
/** エラー表示のテンプレート
* show_error('message' [,init $status_code=500])
* この関数は application/errors/error_general.php

* show_404('page' [,'log_error'])
* この関数は　application/errors/error_404.php
* show_404（）が呼び出されるとCodeIgniterは自動的にログに記録する
*/
/** log_message('level','message');
* level ( 'error' , 'debug' , 'info');
*/
class Test_error extends CI_Controller {
	public $classname;
	public $methodname;
	public $customre_code;
	
	public function __construct()
	{
		parent::__construct();
		$this->classname = $this->router->fetch_class();
		$this->methodname = $this->router->fetch_method();
		$this->customer_code = '12345';
		$this->load->model('Credit');
	}
	
	public function index()
	{
		$log_message = 'error_message:' . $this->classname . '/' . $this->methodname . '/' . __LINE__ . 'line';
		/* ログに書き込んでからエラーページを表示させる */
		log_message('error',$log_message);
		return show_error($log_message);
		
		/* show_404は通常logに書き込む*/
		//return show_404($log_message);
	}
	
	public function test_exception()
	{
		try
		{
			echo $this->classname . '/' . $this->methodname;
			$num= 8;
			if($num > 5)
			{
				throw new Exception(' over 5 ');
			}
		}
		catch(Exception $e)
		{
			if(!empty($e))
			{
				echo $e->getMessage();
			}
		}		
	}
	
	public function test_gmo()
	{
		//var_dump($this->Credit);
		$this->Credit->job_cd = 'AUTH';
		$this->Credit->order_id = '809800809';
		$this->Credit->amount = 200;
		$this->Credit->tax = 20;
		$this->Credit->card_no = '444444444444444444';
		$this->Credit->method = 1;
		$this->Credit->expire = 1509;
		$this->Credit->security_code = 8888888;
		$output = $this->Credit->exec_credit();
		echo '<pre>';print_r($output);echo '</pre>';
		$messages = $this->Credit->getErrorMessages($output);
		echo '<pre>';print_r($messages);echo '</pre>';
	}
}
