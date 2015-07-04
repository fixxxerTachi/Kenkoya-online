<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranNetcashInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranNetcashInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranNetcashInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranNetcash.php');
	
	//入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranNetcashInput();/* @var $input EntryExecTranNetcashInput */
		$input->setShopID($_POST['ShopID']);
		$input->setShopPass($_POST['ShopPass']);
		$input->setOrderID($_POST['OrderID']);
		$input->setAmount($_POST['Amount']);
		$input->setTax($_POST['Tax']);
		$input->setAccessID($_POST['AccessID']);
		$input->setAccessPass($_POST['AccessPass']);
		$input->setRetURL($_POST['RetURL']);
		$input->setClientField1($_POST['ClientField1']);
		$input->setClientField2($_POST['ClientField2']);
		$input->setClientField3($_POST['ClientField3']);
		$input->setNetCashPayType($_POST['NetCashPayType']);

	
	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranNetcash();/* @var $exec EntryExecTranNetcash */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranNetcashOutput */

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

//EntryExecTranNetcash入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranNetcash.php' );