<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranDocomoInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranDocomoInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranDocomoInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranDocomo.php');

	//入力パラメータクラスをインスタンス化します

	//取引登録時に必要なパラメータ
	$entryInput = new EntryTranDocomoInput();


	$entryInput->setShopID( $_POST['ShopID']);
	$entryInput->setShopPass( $_POST['ShopPass']);
	$entryInput->setOrderID( $_POST['OrderID']);
	$entryInput->setJobCd( $_POST['JobCd']);
	$entryInput->setAmount( $_POST['Amount']);
	$entryInput->setTax( $_POST['Tax']);

	//決済実行のパラメータ
	$execInput = new ExecTranDocomoInput();


	$execInput->setShopID( $_POST['ShopID']);
	$execInput->setShopPass( $_POST['ShopPass']);
	$execInput->setOrderID( $_POST['OrderID']);
	$execInput->setAccessID( $_POST['AccessID']);
	$execInput->setAccessPass( $_POST['AccessPass']);
	$execInput->setClientField1( mb_convert_encoding( $_POST['ClientField1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField2( mb_convert_encoding( $_POST['ClientField2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField3( mb_convert_encoding( $_POST['ClientField3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setDocomoDisp1( mb_convert_encoding( $_POST['DocomoDisp1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setDocomoDisp2( mb_convert_encoding( $_POST['DocomoDisp2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRetURL( $_POST['RetURL']);
	$execInput->setPaymentTermSec( $_POST['PaymentTermSec']);
	$execInput->setDispShopName( mb_convert_encoding( $_POST['DispShopName'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setDispPhoneNumber( $_POST['DispPhoneNumber'] );
	$execInput->setDispMailAddress( $_POST['DispMailAddress'] );

	//取引登録＋決済実行の入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranDocomoInput();/* @var $input EntryExecTranDocomoInput */
	$input->setEntryTranDocomoInput( $entryInput );
	$input->setExecTranDocomoInput( $execInput );

	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranDocomo();/* @var $exec EntryExecTranDocomo */

	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranDocomoOutput */

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

//EntryExecTranDocomo入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranDocomo.php' );