<?php
//set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\TACHIBANA\xampp2\www\CodeIgniter_2.2.0\application_admin\libraries\gmo');
require_once( 'config.php');
require_once( 'common/ErrorMessageHandler.php');

//require_once( 'com/gmo_pg/client/input/EntryTranInput.php');
//require_once( 'com/gmo_pg/client/tran/EntryTran.php');

class Test_gmo_member extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
	}
	
	public function save_member()
	{
		require_once('com/gmo_pg/client/input/SaveMemberInput.php');
		require_once('com/gmo_pg/client/tran/SaveMember.php');
		$input = new SaveMemberInput();
		$input->setSiteId( PGCARD_SITE_ID );
		$input->setSitePass( PGCARD_SITE_PASS );
		$input->setMemberId('123456789');
		$input->setMemberName( mb_convert_encoding('テストのユーザー','SJIS','UTF-8'));
		$exe = new SaveMember();
		$output = $exe->exec( $input );
	}

	public function menu()
	{
		$this->load->view('test_gmo/index');
	}
	
	public function test_entry1(){
		if( isset( $_POST['submit'] ) ){
			require_once( 'com/gmo_pg/client/input/EntryTranInput.php');
			require_once( 'com/gmo_pg/client/tran/EntryTran.php');
			
			//入力パラメータクラスをインスタンス化します
			$input = new EntryTranInput();/* @var $input EntryTranInput */
			
			//このサンプルでは、ショップID・パスワードはコンフィグファイルで
			//定数defineしています。
			$input->setShopId( PGCARD_SHOP_ID );
			$input->setShopPass( PGCARD_SHOP_PASS );
			
			//各種パラメータを設定しています。
			//実際には、処理区分や利用金額、オーダーIDといったパラメータをカード所有者が直接入力することは無く、
			//購買内容を元に加盟店様システムで生成した値が設定されるものと思います。
			$input->setJobCd( $_POST['JobCd']);
			$input->setOrderId( $_POST['OrderID'] );
			$input->setItemCode( $_POST['ItemCode'] );
			$input->setAmount( $_POST['Amount']);
			$input->setTax( $_POST['Tax']);
			$input->setTdFlag( $_POST['TdFlag']);
			$input->setTdTenantName( $_POST['TdTenantName']);
			
			//API通信クラスをインスタンス化します
			$exe = new EntryTran();/* @var $exec EntryTran */
			
			//パラメータオブジェクトを引数に、実行メソッドを呼び、結果を受け取ります。
			$output = $exe->exec( $input );/* @var $output EntryTranOutput */

			//実行後、その結果を確認します。
			
			if( $exe->isExceptionOccured() ){//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

				//サンプルでは、例外メッセージを表示して終了します。
				require_once( PGCARD_SAMPLE_BASE . '/display/Exception.php');
				exit();
				
			}else{
				
				//例外が発生していない場合、出力パラメータオブジェクトが戻ります。
				
				if( $output->isErrorOccurred() ){//出力パラメータにエラーコードが含まれていないか、チェックしています。
					
					//サンプルでは、エラーが発生していた場合、エラー画面を表示して終了します。
					require_once( PGCARD_SAMPLE_BASE . '/display/Error.php');
					exit();
					
				}

				//例外発生せず、エラーの戻りもないので、正常とみなします。
				//このif文を抜けて、結果を表示します。
			}
		}
		require_once( PGCARD_SAMPLE_BASE . '/display/EntryTran.php' );
	}


	/** クレジットカードの決済を行う
	 * @param string shopId
	 * @param string shopPass
	 * @param string orderId
	 * @param string JobDd 取引区分 CHECK,AUTH
	 * @param int amount
	 * @param int tax
	 * @param string method 支払方法　1:一括,2:分割
	 * @param int payTimes 支払回数
	 * @param string cardNo
	 * @param string expire YYMM
	 * @param string securityCode 
	 * @return object ExecTranOutput
	 */
	public function test_entry_exec()
	{
		require_once( 'com/gmo_pg/client/input/EntryTranInput.php');
		require_once( 'com/gmo_pg/client/input/ExecTranInput.php');
		require_once( 'com/gmo_pg/client/input/EntryExecTranInput.php');
		require_once( 'com/gmo_pg/client/tran/EntryExecTran.php');

		$job = 'AUTH';
		$amount = 999;
		$orderId = date('ymdHis');
		$tax = 88;
		
		//取引登録
		$entryinput = new EntryTranInput();
		$entryinput->setShopId( PGCARD_SHOP_ID );
		$entryinput->setShopPass( PGCARD_SHOP_PASS );
		$entryinput->setJobCd($job);
		$entryinput->setOrderId($orderId);
		$entryinput->setAmount($amount);
		$entryinput->setTax($tax);
		
		//決済実行
		$method = '2';
		$payTimes = 3;
		$cardNo = '4111111111111111';
		$expire = '1703';
		$securityCode = '522';
		$execinput = new ExecTranInput();
		$execinput->setOrderId($orderId);
		$execinput->setMethod($method);
		$execinput->setPayTimes($payTimes);
		$execinput->setCardNo($cardNo);
		$execinput->setExpire($expire);
		$execinput->setSecurityCode($securityCode);
		$input = new EntryExecTranInput();
		$input->setEntryTranInput($entryinput);
		$input->setExecTranInput($execinput);
		$exe = new EntryExecTran();
		$output = $exe->exec($input);
		if($exe->isExceptionOccured()){
			echo '通信できません';
			$exception = $exe->getException();
			echo $exception->getMessage();
		}
		if($output->isErrorOccurred()){
			echo '取引できません';
			$errorHandle = new ErrorHandler();
			if($output->isEntryErrorOccurred()){
				$errorList = $output->getEntryErrList();
			}else{
				$errorList = $output->getExecErrList();
			}
			foreach($errorList as $errorInfo)
			{
				$message = $errorInfo->getErrCode() 
					. ':' 
					. $errorInfo->getErrInfo() 
					. ':' . $errorHandle->getMessage($errorInfo->getErrInfo());
				//echo $message . '<br>';
				$classname =  $this->router->fetch_class();
				$methodname = $this->router->fetch_method();
				log_message('error',$message.':'.$classname.':'.$methodname.':'.__LINE__);
			}
		}
		echo '<pre>'; print_r($output); echo '</pre>';
	}

	public function test_credit()
	{
		$this->load->model('credit');
		$this->credit->job_cd= 'AUTH';
		$this->credit->order_id = date('ymdhis');
		$this->credit->amount = 100000;
		$this->credit->tax = 88;
		$this->credit->method = '2';
		$this->credit->pay_times = '6';
		$this->credit->card_no = '4111111111111111';
		$this->credit->expire = '1807';
		$this->credit->security_code = '666';
		$output = $this->credit->exec_credit();
		if($output->isErrorOccurred()){
			var_dump($message = $this->credit->getErrorMessages($output));
		}
	}
	
	/** AccessId,Acccesspwを取得する(取引登録,有効性のチェック,取引できるかどうか確認)
	* @JobDd 'CHECK' だと金額不要
	* @return 取引ID　取引password
	**/
	public function test_get_accessid()
	{
		require_once('com/gmo_pg/client/input/EntryTranInput.php');
		require_once('com/gmo_pg/client/tran/EntryTran.php');
		$input = new EntryTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setJobCd('CHECK');
		$input->setOrderId(date('ymdHis'));
		$exe = new EntryTran();
		$output = $exe->exec( $input );
	//	var_Dump($output);
		return $output;	
	}

	/** 決済内容の取消を行う
	 * @param accessId
	 * @param accessPass
	 * @param JobCd	 'RETURN','VOID'
	 * @return obejct AlterTranOutput
	 */
	public function test_alter_tran()
	{
		require_once('com/gmo_pg/client/input/AlterTranInput.php');
		require_once('com/gmo_pg/client/tran/AlterTran.php');
		//$output = $this->test_get_accessid();
		$accessId = 'f467f2b7a702c542f77891f2da776736'; 
		$accessPass = 'fa0a0c237d20d68e3ca0c74e1796d584'; 
		$input = new AlterTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId($accessId);
		$input->setAccessPass($accessPass);	
		$input->setJobCd('VOID');
		//$input->setAmount(999);
		$exe = new AlterTran();
		$output = $exe->exec($input);

		//通信エラー処理
		if($exe->isExceptionOccured()){
			echo '通信できません';
		}else{
			if($output->isErrorOccurred()){
				echo '取引できません';
				$errorHandle = new ErrorHandler();
				$errorList = $output->getErrList();
				foreach($errorList as $errInfo){
					echo '<li>'
						. $errInfo->getErrCode()
						. ':' . $errInfo->getErrInfo()
						. ':' . $errorHandle->getMessage( $errInfo->getErrInfo() )
						. '</li>';
				}
			}else{
				var_Dump($output);
			}
		}
	}

	/** 取り消した決済に再度オーソリティを行う
	 * @param shopId
	 * @param shopPass
	 * @param accessId
	 * @param accessPass
	 * @param jobCd(AUTH)
	 * @param amount 
	 * @param method(支払方法)
	 * @param paytimes(支払回数)
	 * @return object AlterTranOutput
	 */
	public function test_again_tran()
	{
		require_once('com/gmo_pg/client/input/AlterTranInput.php');
		require_once('com/gmo_pg/client/tran/AlterTran.php');
		$accessId = 'f467f2b7a702c542f77891f2da776736';
		$accessPass = 'fa0a0c237d20d68e3ca0c74e1796d584';
		$input = new AlterTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId($accessId);
		$input->setAccessPass($accessPass);
		$input->setJobCd('AUTH');
		$input->setAmount(9000);
		$input->setTax(90);
		$input->setMethod('2');
		$input->setPayTimes(5);
		$exe = new AlterTran();
		$output = $exe->exec($input);
		var_dump($output);
	}

	/**売上の確定を行う　仮売上実施後90日以上経過するとerrorになる
	 * @param accessId
	 * @param accessPass
	 * @param jobcd SALES
	 * @param amount
	 * @return object AlterTranINput
	 */
	public function test_exe_sale()
	{
		require_once('com/gmo_pg/client/input/AlterTranInput.php');
		require_once('com/gmo_pg/client/tran/AlterTran.php');
		$accessId = '3204f49a5407fd16291fef26cdd8c55e';
		$accessPass = '6eafc6a28bc84ff37e3af3b64f99ab06';
		$input = new AlterTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId($accessId);
		$input->setAccessPass($accessPass);
		$input->setJobCd('SALES');
		$input->setAmount(999);
		$exe = new AlterTran();
		$output = $exe->exec($input);
		var_dump($output);
	}

	/** 完了した決済に金額の変更を行う
	 * @param acccessId
	 * @param accessPass
	 * @param jobCd 金額変更後の処理区分 
	 * @param amount 変更後の金額
	 * @return object ChangeTranOutput
	 */
	public function test_change_tran()
	{
		require_once('com/gmo_pg/client/input/ChangeTranInput.php');
		require_once('com/gmo_pg/client/tran/ChangeTran.php');
		$accessId = '3204f49a5407fd16291fef26cdd8c55e';
		$accessPass = '6eafc6a28bc84ff37e3af3b64f99ab06';
		$input = new ChangeTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId( $accessId);
		$input->setAccessPass($accessPass);
		$input->setJobCd('AUTH');
		$input->setAmount(1200);
		$input->setTax(90);
		$exe = new ChangeTran();
		$output = $exe->exec($input);
		var_dump($output);
	}

	/** 取引状態確認
	 * @param orderId
	 * @return object SearchTradeOutput
	 */
	public function test_search()
	{
		require_once('com/gmo_pg/client/input/SearchTradeInput.php');
		require_once('com/gmo_pg/client/tran/SearchTrade.php');
		$input = new SearchTradeInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setOrderId('150216085539');
		$exe = new SearchTrade();
		$output = $exe->exec($input);
		var_dump($output);
	}

	/** コンビ日取引登録のみ
	 * @param shopPass
	 * @param shopId
	 * @param orderId
	 * @return object EntryCvsOutput
	 */
	public function test_cvs_tran()
	{
		require_once('com/gmo_pg/client/input/EntryTranCvsInput.php');
		require_once('com/gmo_pg/client/tran/EntryTranCvs.php');
		$orderId = date('ymdHis');
		$input = new EntryTranCvsInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setOrderId($orderId);
		$input->setAmount(1000);
		$input->setTax(100);
		var_dump($input);
		$exe = new EntryTranCvs();
		$output = $exe->exec($input);
		$output->orderId = $orderId;
		return $output;
		//var_dump($output);		
	}	

	/** コンビニ取引登録決済実行
	 * @param accessId
	 * @param accessPass
	 * @param orderId
	 * @param convinience
	 * @param customerName
	 * @param customerKana
	 * @param telNo
	 * @param receiptsDisp11 お問い合わせ先（会社名)
	 * @param receiptsDisp12 お問い合わせ先電話番号
	 * @param receiptsDisp13 お問い合わせ時間
	 * @return ExecTranOutput 
	 */
	public function test_cvs_exec()
	{
		require_once('com/gmo_pg/client/input/ExecTranCvsInput.php');
		require_once('com/gmo_pg/client/tran/ExecTranCvs.php');	
		$input = new ExecTranCvsInput();
		$accessobj = $this->test_cvs_tran();
		$input->setAccessId($accessobj->accessId);
		$input->setAccessPass($accessobj->accessPass);
		$input->setOrderId($accessobj->orderId);
		$input->setConvenience('00001');
		$input->setCustomerName( mb_convert_encoding('tachibana', 'SJIS',PGCARD_SAMPLE_ENCODING));
		$input->setCustomerKana( mb_convert_encoding('tachibana', 'SJIS',PGCARD_SAMPLE_ENCODING));
		$input->setTelNo('989-9988-9988');
		$input->setReceiptsDisp11( mb_convert_encoding('宅配スーパー健康屋','SJIS',PGCARD_SAMPLE_ENCODING));
		$input->setReceiptsDisp12( mb_convert_encoding('02055888855','SJIS',PGCARD_SAMPLE_ENCODING));
		$input->setReceiptsDisp13( mb_convert_encoding('09:00-12:00','SJIS',PGCARD_SAMPLE_ENCODING));
		$exe = new ExecTranCvs();
		$output = $exe->exec($input);
		var_Dump($output);
	}

	/** 登録と決済実行を同時に行う
	 *　@param shopId
	 * @param shopPass
	 * @param orderId
	 * @param amount
	 * @param tax
	 * @param convinience
	 * @param customerName
	 * @param customerKana
	 * @param telNo
	 * @param receiptDisp11
	 * @param receiptDisp12
	 * @param receiptDisp13
	 * @return object EntryExecTranCvsOutput 　
	 */
	public function test_cvs_tran_exec()
	{
		require_once('com/gmo_pg/client/input/EntryTranCvsInput.php');
		require_once('com/gmo_pg/client/input/ExecTranCvsInput.php');
		require_once('com/gmo_pg/client/input/EntryExecTranCvsInput.php');
		require_once('com/gmo_pg/client/tran/EntryExecTranCvs.php');
		$orderId = date('ymdhis');
		//取引登録
		$entryInput = new EntryTranCvsInput();
		$entryInput->setShopId( PGCARD_SHOP_ID);
		$entryInput->setShopPass( PGCARD_SHOP_PASS);
		$entryInput->setOrderId( $orderId );
		$entryInput->setAmount(8000);
		$entryInput->setTax(64);

		//決済実行
		$execInput = new ExecTranCvsInput();
		$execInput->setOrderId($orderId);
		$execInput->setConvenience('00001');
		$execInput->setCustomerName(mb_convert_encoding('tahcibana','SJIS',PGCARD_SAMPLE_ENCODING));
		$execInput->setCustomerKana(mb_convert_encoding('tachibana','SJIS',PGCARD_SAMPLE_ENCODING));	
		$execInput->setTelNo('09000998833');
		$execInput->setReceiptsDisp11(mb_convert_encoding('宅配スーパー健康屋','SJIS',PGCARD_SAMPLE_ENCODING));
		$execInput->setReceiptsDisp12(mb_convert_encoding('02022555588','SJIS',PGCARD_SAMPLE_ENCODING));
		$execInput->setReceiptsDisp13(mb_convert_encoding('09:00-12:00','SJIS',PGCARD_SAMPLE_ENCODING));
		$input = new EntryExecTranCvsInput();
		$input->setEntryTranCvsInput($entryInput);
		$input->setExecTranCvsInput($execInput);
		$exe = new EntryExecTranCvs();
		$output = $exe->exec($input);	
		echo '<pre>';print_r($output);echo '</pre>';
	}

	/** 決済結果の取得
	 * @param string shopId
	 * @param string shopPass
	 * @param string orderId
	 * @param string payType
	 * @return object SearchTradeMuliOutput
	 */ 
	public function test_search_multi(){
		require_once('com/gmo_pg/client/input/SearchTradeMultiInput.php');
		require_once('com/gmo_pg/client/tran/SearchTradeMulti.php');
		$orderId = '150216171944';
		$input = new SearchTradeMultiInput();
		$input->setShopId( PGCARD_SHOP_ID);
		$input->setShopPass( PGCARD_SHOP_PASS);
		$input->setOrderId($orderId);
		$input->setPayType(3);
		$exe = new SearchTradeMulti();
		$output = $exe->exec($input);
		var_Dump($output);
	}

	public function test_get_code()
	{
		$this->load->model('Order');
		$customer = new StdClass();
		$customer->username = '171000649';
		$code  = $this->Order->create_order_number($customer);
		var_Dump($code);
	}

}

