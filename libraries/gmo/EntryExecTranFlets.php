<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranFletsInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranFletsInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranFletsInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranFlets.php');
	
	//入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranFletsInput();/* @var $input EntryExecTranFletsInput */
		$input->setShopID($_POST['ShopID']);
		$input->setShopPass($_POST['ShopPass']);
		$input->setOrderID($_POST['OrderID']);
		$input->setJobCd($_POST['JobCd']);
		$input->setAmount($_POST['Amount']);
		$input->setTax($_POST['Tax']);
		$input->setAccessID($_POST['AccessID']);
		$input->setAccessPass($_POST['AccessPass']);
		$input->setClientField1($_POST['ClientField1']);
		$input->setClientField2($_POST['ClientField2']);
		$input->setClientField3($_POST['ClientField3']);
		$input->setCenterCode($_POST['CenterCode']);
		$input->setRetURL($_POST['RetURL']);
		$input->setErrorRcvURL($_POST['ErrorRcvURL']);
		$input->setItemId($_POST['ItemId']);
		$input->setItemName($_POST['ItemName']);
		$input->setPaymentTermSec($_POST['PaymentTermSec']);

	
	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranFlets();/* @var $exec EntryExecTranFlets */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranFletsOutput */

	//実行後、その結果を確認します。
	
	if( $exe->isExceptionOccured() ){//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

		//サンプルでは、例外メッセージを表示して終了します。
		require_once( PGCARD_SAMPLE_BASE . '/display/Exception.php');
		exit();
		
	}else{
		
		//例外が発生していない場合、出力パラメータオブジェクトが戻ります。
		
		if( $output->isErrorOccurred() ){//出力パラメータにエラーコードが含まれていないか、チェックしています。
			
			//サンプルでは、エラーが発生していた場合、エラー画面を表示して終了します。
			require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecError.php');
			exit();
		}

		//例外発生せず、エラーの戻りもなく、3Dセキュアフラグもオフであるので、実行結果を表示します。
	}
	
}

//EntryExecTranFlets入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranFlets.php' );