<?php
require_once('config.php');
require_once('common/ErrorMessageHandler.php');

class Credit extends CI_Model{
	public $order_id;
	public $job_cd;
	public $amount;
	public $tax;
	public $method;
	public $pay_times;
	public $card_no;
	public $expire;
	public $expire_year;
	public $expire_month;
	public $security_code;

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_statuses()
	{
		return array(
			'CHECK'=>'有効性チェック',
			'CAPTURE'=>'売上計上(CAPTURE)',
			'AUTH'=>'与信確保(AUTH)',
			'SALES'=>'売上計上',
			'VOID'=>'取消',
			'RETURN'=>'返品',
			'RETURNX'=>'月跨ぎ返品',
			'SAUTH'=>'与信確保(SAUTH)',
		);
	}

	/** クレジット決済取引から実行まで行う
	 * @return object ExecTranOutput
	 * エラーの有無はisErrorOccurredをでチェックする
	 */
	public function exec_credit()
	{
		require_once( 'com/gmo_pg/client/input/EntryTranInput.php');
		require_once( 'com/gmo_pg/client/input/ExecTranInput.php');
		require_once( 'com/gmo_pg/client/input/EntryExecTranInput.php');
		require_once( 'com/gmo_pg/client/tran/EntryExecTran.php');

		//取引登録
		$entryinput = new EntryTranInput();
		$entryinput->setShopId( PGCARD_SHOP_ID );
		$entryinput->setShopPass( PGCARD_SHOP_PASS );
		$entryinput->setJobCd($this->job_cd);
		$entryinput->setOrderId($this->order_id);
		$entryinput->setAmount($this->amount);
		$entryinput->setTax($this->tax);

		//決済実行
		$execinput = new ExecTranInput();
		$execinput->setOrderId($this->order_id);
		$execinput->setMethod($this->method);
		$execinput->setPayTimes($this->pay_times);
		$execinput->setCardNo($this->card_no);
		$execinput->setExpire($this->expire);
		$execinput->setSecurityCode($this->security_code);

		$input = new EntryExecTranInput();
		$input->setEntryTranInput($entryinput);
		$input->setExecTranInput($execinput);
		$exe = new EntryExecTran();

		$output = $exe->exec($input);

		/* 例外にするか画面を用意する*/
		if($exe->isExceptionOccured()){
			$exception = $exe->getException();
			log_message('error',$exception->getMessage());
			show_error('恐れ入ります。現在クレジット取引停止中です');
		}
		/* logging */
		if($output->isErrorOccurred()){
			$errorHandle = new ErrorHandler();
			if($output->isEntryErrorOccurred()){
				$errorList = $output->getEntryErrList();
			}else{
				$errorList = $output->getExecErrList();
			}
			foreach($errorList as $errorInfo)
			{
				$message = 'credit: '. $this->order_id . ' ' . $errorHandle->getMessage($errorInfo->getErrInfo()).'('.$errorInfo->getErrInfo().')';
//var_dump($this->expire);var_dump($errorInfo->getErrInfo());exit;
				$classname =  $this->router->fetch_class();
				$methodname = $this->router->fetch_method();
				log_message('error',$message.':'.$classname.':'.$methodname.':'.__LINE__);
			}
		}
		return $output;
		//echo '<pre>'; print_r($output); echo '</pre>';
	}

	/** クレジット決済時のエラーメッセージを取得する
	 * @param object  EntryExecTranOutput 
	 * @return array errorMessages
	 */
	public function getErrorMessages(EntryExecTranOutput $output )
	{
		$errorHandle = new ErrorHandler();
		if($output->isEntryErrorOccurred()){
			$errorList = $output->getEntryErrList();
		}else{
			$errorList = $output->getExecErrList();
		}
		$message = array();
		foreach($errorList as $errorInfo){
			$message[] = $errorHandle->getMessage($errorInfo->getErrInfo()) . '(' . $errorInfo->getErrInfo() . ')';
		}
		return $message;
	}
	
	/** order_numberがない場合の処理は? **/
	/** 仮売上中の情報を取得する　
	* @param string order_number
	* @return object SearchTradeOutput
	*/
	public function search_trade($order_number)
	{
		require_once('com/gmo_pg/client/input/SearchTradeInput.php');
		require_once('com/gmo_pg/client/tran/SearchTrade.php');
		$input = new SearchTradeInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setOrderId($order_number);
		$exe = new SearchTrade();
		$output = $exe->exec($input);
		if($exe->isExceptionOccured()){
			return show_error('恐れ入ります。現在クレジット取引停止中です');
		}else{
			if($output->isErrorOccurred()){
				$errorHandle = new ErrorHandler();
				$errorList = $output->getErrList();
				foreach($errorList as $errorInfo)
				{
					$message = 'credit: '. $order_number . ' ' . $errorHandle->getMessage($errorInfo->getErrInfo());
					$classname = $this->router->fetch_class();
					$methodname = $this->router->fetch_method();
					log_message('error',$message . ':' . $classname . ':' . $methodname . ':' . __LINE__);
				}
				throw new Exception($message);
			}
		}
		return $output;
	}
	
	/**　取引済みの金額を確定する 
	* @param order_number
	* return object AlterTranOutput
	*/
	public function change_tran($order_number)
	{
		require_once( 'com/gmo_pg/client/input/AlterTranInput.php');
		require_once( 'com/gmo_pg/client/tran/AlterTran.php');
		$output = $this->search_trade($order_number);
		$input = new AlterTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId( $output->accessId );
		$input->setAccessPass( $output->accessPass );
		$input->setJobCd('SALES');
		$input->setAmount( $output->amount );
		$exe = new AlterTran();
		$output = $exe->exec($input);
		if($exe->isExceptionOccured())
		{
			return show_error('恐れ入ります。現在クレジット取引停止中です');
		}else{
			if($output->isErrorOccurred()){
				$errorHandle = new ErrorHandler();
				$errorList = $output->getErrList();
				foreach($errorList as $errorInfo)
				{
					$message = 'credit: '. $order_number . ' ' . $errorHandle->getMessage($errorInfo->getErrInfo());
					$classname = $this->router->fetch_class();
					$methodname = $this->router->fetch_method();
					log_message('error',$message . ':' . $classname . ':' . $methodname . ':' . __LINE__);
				}
			}
			return $output;
		}
	}
	
	/** 仮売上の取消
	* @param order_number
	* @return object AlterTrainOutput
	*/
	public function alter_tran($order_number , $job = 'VOID')
	{
		$output = $this->search_trade($order_number);
		if($output->isErrorOccurred()){
			return $output;
		}
		require_once( 'com/gmo_pg/client/input/AlterTranInput.php');
		require_once( 'com/gmo_pg/client/tran/AlterTran.php');
		$input = new AlterTranInput();
		$input->setShopId( PGCARD_SHOP_ID );
		$input->setShopPass( PGCARD_SHOP_PASS );
		$input->setAccessId( $output->accessId );
		$input->setAccessPass($output->accessPass );
		$input->setJobCd($job);
		$input->setAmount($output->amount);
		$exe = new AlterTran();	
		$result = $exe->exec( $input );
		if($exe->isExceptionOccured()){
			$exception = $exe->getException();
			log_message($exception->getMessage());
			return show_error('恐れ入ります。現在クレジット取引停止中です');
		}else{
			if($result->isErrorOccurred()){
				$errorHandle = new ErrorHandler();
				$errorList = $result->getErrList();
				foreach($errorList as $errorInfo)
				{
					$message = 'credit: '. $order_number . ' ' . $errorHandle->getMessage($errorInfo->getErrInfo());
					$classname = $this->router->fetch_class();
					$methodname = $this->router->fetch_method();
					log_message('error',$message . ':' . $classname . ':' . $methodname . ':' . __LINE__);
				}
			}
		}					
		return $result;
	}
	
	/**取消のエラーメッセージを取得する 
	* @param object AlterTranOutput
	* @return array messages
	*/
	public function getAlterErrorMessages($output)
	{
		$errorHandle = new ErrorHandler();
		if($output->isErrorOccurred()){
			$errorList = $output->getErrList();
		}
		$message = array();
		foreach($errorList as $errorInfo){
			$message[] = $errorHandle->getMessage($errorInfo->getErrInfo());
		}
		return $message;
	}
	
	/** 受付済みの受注の取引状況を取得する
	*
	*/
	public function get_order_tran()
	{
		$this->load->model('Order');
		$orders = $this->Order->get_recieved();
		if(count($orders) > 0){
			$arr = array();
			foreach($orders as $order)
			{
				$output = $this->search_trade($order->order_number);
				$arr[] = $output;
			}
			return $arr;
		}else{
			return FALSE;
		}
	}
}
