<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/SearchMemberInput.php');
	require_once( 'com/gmo_pg/client/tran/SearchMember.php');
	
	//入力パラメータクラスをインスタンス化します
	$input = new SearchMemberInput();/* @var $input SearchMemberInput */
	
	//このサンプルでは、サイトID・パスワードはコンフィグファイルで
	//定数defineしています。
	$input->setSiteId( PGCARD_SITE_ID );
	$input->setSitePass( PGCARD_SITE_PASS );
	
	//会員IDは必須です
	$input->setMemberId( $_POST['MemberID'] );
	
	//API通信クラスをインスタンス化します
	$exe = new SearchMember();/* @var $exec SearchMember */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output SearchMemberOutput */

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

//SearchMember入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/SearchMember.php' );