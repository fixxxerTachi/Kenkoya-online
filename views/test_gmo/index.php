<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja-JP" xml:lang="ja-JP">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css; charset=UTF-8" />
	
	<link rel="stylesheet" href="style/pgcommon.css" charset="UTF-8" />
	<title>PGマルチペイメントサービス モジュールタイプサンプル</title>
</head>
<body>

<div id="header">
	<h1>PGマルチペイメントサービス モジュールタイプ(PHP) 呼び出しサンプル</h1>
</div>

<div id="main">

	<dl>
		<dt>クレジットカード取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTran.php">取引登録</a></dt>
				<dd>EntryTranの呼び出しサンプルです。加盟店様でオーダーIDや処理区分、利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、カード会社へはデータを送信しません。</dd>
				
				<dt><a href="ExecTran.php">決済実行</a></dt>
				<dd>ExecTranの呼び出しサンプルです。加盟店様がカード所有者から受け取った(画面入力された）カード番号又は会員IDを
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、カード会社へデータを送信します。</dd>
				
				<dt><a href="EntryExecTran.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、カード番号等カード所有者が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
		
				<dt><a href="AlterTran.php">取引変更</a></dt>
				<dd>AlterTranの呼び出しサンプルです。実行した取引を変更します(仮売上を返品する、一度返品した取引を再度売上げる等)。</dd>
				
				<dt><a href="ChangeTran.php">金額変更</a></dt>
				<dd>ChangeTranの呼び出しサンプルです。実行した取引の金額を変更します。</dd>
				
				<dt><a href="SearchTrade.php">取引照会</a></dt>
				<dd>SearchTradeの呼び出しサンプルです。あるオーダーIDを指定し、その取引の現状態を取得します。</dd>
			</dl>
		</dd>
		<dt>会員・カード関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="TradedCard.php">取引後カード登録</a></dt>
				<dd>TradedCardの呼び出しサンプルです。オーダーID・会員IDを指定し、そのオーダーで利用したカードを、会員カードとして登録します。</dd>
				
				<dt><a href="SaveMember.php">会員登録</a></dt>
				<dd>MemberSaveの呼び出しサンプルです。会員情報を保存します。</dd>
				
				<dt><a href="SearchMember.php">会員検索</a></dt>
				<dd>SearchMemberの呼び出しサンプルです。ある会員IDの情報(会員名)を取得します。</dd>
				
				<dt><a href="UpdateMember.php">会員更新</a></dt>
				<dd>UpdateMemberの呼び出しサンプルです。ある会員IDの会員名称を更新します。</dd>
				
				<dt><a href="DeleteMember.php">会員削除</a></dt>
				<dd>DeleteMemberの呼び出しサンプルです。ある会員IDを削除します。削除されたIDの再利用は不可能となります。</dd>
				
				<dt><a href="SaveCard.php">カード登録</a></dt>
				<dd>SaveCardの呼び出しサンプルです。会員にカードを登録します。</dd>
				
				<dt><a href="SearchCard.php">カード検索</a></dt>
				<dd>DeleteCardの呼び出しサンプルです。ある会員のカードを検索します。登録カード連番を指定してそのカードの情報を取得する、または
				登録カード連番を指定せず、その会員の全カードの情報を取得する事が可能です。</dd>
				
				<dt><a href="DeleteCard.php">カード削除</a></dt>
				<dd>DeleteCardの呼び出しサンプルです。ある会員のカードを削除します。削除されたカードを利用可能に戻すことは不可能です。</dd>
			</dl>
		</dd>
		<dt>モバイルSuica取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranSuica.php">取引登録</a></dt>
				<dd>EntryTranSuica.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、Suicaセンターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranSuica.php">決済実行</a></dt>
				<dd>ExecTranSuicaの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、Suicaセンターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranSuica.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranSuicaの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>モバイルEdy取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranEdy.php">取引登録</a></dt>
				<dd>EntryTranEdy.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、Edyセンターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranEdy.php">決済実行</a></dt>
				<dd>ExecTranEdyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、Edyセンターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranEdy.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranEdyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>コンビニ取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranCvs.php">取引登録</a></dt>
				<dd>EntryTranCvs.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranCvs.php">決済実行</a></dt>
				<dd>ExecTranCvsの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranCvs.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranCvsの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>Pay-easy取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranPayEasy.php">取引登録</a></dt>
				<dd>EntryTranPayEasy.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranPayEasy.php">決済実行</a></dt>
				<dd>ExecTranPayEasyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranPayEasy.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranPayEasyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>PayPal取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranPaypal.php">取引登録</a></dt>
			<dd>EntryTranPaypal.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranPaypal.php">決済実行</a></dt>
			<dd>ExecTranPaypalの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranPaypal.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranPaypalの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="PaypalStart.php">支払手続き開始</a></dt>
			<dd>PaypalStartの呼び出しサンプルです。Paypal支払い手続きを開始します。</dd>

			<dt><a href="CancelTranPaypal.php">払い戻し実行</a></dt>
			<dd>CancelTranPaypalの呼び出しサンプルです。Paypal払い戻しを開始します。</dd>
		</dl>
		</dd>		
		<dt>WebMoney取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranWebmoney.php">取引登録</a></dt>
			<dd>EntryTranWebmoney.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranWebmoney.php">決済実行</a></dt>
			<dd>ExecTranWebmoneyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranWebmoney.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranWebmoneyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="WebmoneyStart.php">支払手続き開始</a></dt>
			<dd>WebmoneyStartの呼び出しサンプルです。Webmoney支払い手続きを開始します。</dd>
		</dl>
		</dd>		
		<dt>auかんたん決済取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranAu.php">取引登録</a></dt>
			<dd>EntryTranAu.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranAu.php">決済実行</a></dt>
			<dd>ExecTranAuの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranAu.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranAuの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="AuStart.php">支払手続き開始</a></dt>
			<dd>AuStartの呼び出しサンプルです。Au支払い手続きを開始します。</dd>

			<dt><a href="AuCancelReturn.php">払い戻し実行</a></dt>
			<dd>AuCancelReturnの呼び出しサンプルです。Au払い戻しを開始します。</dd>

			<dt><a href="AuSales.php">売上確定実行</a></dt>
			<dd>AuSalesの呼び出しサンプルです。Au売上確定を開始します。</dd>

			<dt><a href="DeleteAuOpenID.php">OpenID解除実行</a></dt>
			<dd>DeleteAuOpenIDの呼び出しサンプルです。OpenID解除を開始します。</dd>
		</dl>
		</dd>
		<dt>ドコモケータイ払い関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranDocomo.php">取引登録開始</a></dt>
			<dd>EntryTranDocomoの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranDocomo.php">決済実行開始</a></dt>
			<dd>ExecTranDocomoの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranDocomo.php">登録・決済開始</a></dt>
			<dd>EntryExecTranDocomoの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="DocomoStart.php">支払手続き開始</a></dt>
			<dd>DocomoStartの呼び出しサンプルです。支払手続き開始を開始します。</dd>

			<dt><a href="DocomoCancelReturn.php">決済取消開始</a></dt>
			<dd>DocomoCancelReturnの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="DocomoSales.php">売上確定開始</a></dt>
			<dd>DocomoSalesの呼び出しサンプルです。売上確定を開始します。</dd>

		</dl>
		</dd>
		<dt>ドコモ継続決済関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranDocomoContinuance.php">取引登録開始</a></dt>
			<dd>EntryTranDocomoContinuanceの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranDocomoContinuance.php">決済実行開始</a></dt>
			<dd>ExecTranDocomoContinuanceの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranDocomoContinuance.php">登録・決済開始</a></dt>
			<dd>EntryExecTranDocomoContinuanceの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="DocomoContinuanceStart.php">決済実行開始</a></dt>
			<dd>DocomoContinuanceStartの呼び出しサンプルです。ドコモ継続課金の支払い手続きを開始します。</dd>

			<dt><a href="DocomoContinuanceCancelReturn.php">決済取消開始</a></dt>
			<dd>DocomoContinuanceCancelReturnの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="DocomoContinuanceSales.php">売上確定開始</a></dt>
			<dd>DocomoContinuanceSalesの呼び出しサンプルです。売上確定を開始します。</dd>

			<dt><a href="DocomoContinuanceUserChange.php">継続課金変更（お客様）</a></dt	>
			<dd>DocomoContinuanceUserChangeの呼び出しサンプルです。継続課金変更（お客様）を開始します。</dd>

			<dt><a href="DocomoContinuanceUserEnd.php">継続課金終了（お客様）</a></dt>
			<dd>DocomoContinuanceUserEndの呼び出しサンプルです。継続課金終了（お客様）を開始します。</dd>

			<dt><a href="DocomoContinuanceShopChange.php">継続課金変更（加盟店）</a></dt>
			<dd>DocomoContinuanceShopChangeの呼び出しサンプルです。継続課金変更（加盟店）を開始します。</dd>

			<dt><a href="DocomoContinuanceShopEnd.php">継続課金終了（加盟店）</a></dt>
			<dd>DocomoContinuanceShopEndの呼び出しサンプルです。継続課金終了（加盟店）を開始します。</dd>

		</dl>
		</dd>
		<dt>ソフトバンクケータイ支払い関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranSb.php">取引登録開始</a></dt>
			<dd>EntryTranSbの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranSb.php">決済実行開始</a></dt>
			<dd>ExecTranSbの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranSb.php">登録・決済開始</a></dt>
			<dd>EntryExecTranSbの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="SbStart.php">支払手続き開始開始</a></dt>
			<dd>SbStartの呼び出しサンプルです。支払手続き開始を開始します。</dd>

			<dt><a href="SbCancel.php">決済取消開始</a></dt>
			<dd>SbCancelの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="SbSales.php">売上確定開始</a></dt>
			<dd>SbSalesの呼び出しサンプルです。売上確定を開始します。</dd>

		</dl>
		</dd>
		<dt>じぶん銀行関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranJibun.php">取引登録開始</a></dt>
			<dd>EntryTranJibunの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranJibun.php">決済実行開始</a></dt>
			<dd>ExecTranJibunの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranJibun.php">登録・決済開始</a></dt>
			<dd>EntryExecTranJibunの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="JibunStart.php">支払手続き開始</a></dt>
			<dd>JibunStartの呼び出しサンプルです。じぶん銀行支払い手続きを開始します。</dd>
		</dl>
		</dd>
		<dt>au継続関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranAuContinuance.php">取引登録</a></dt>
			<dd>EntryTranAuContinuance.phpの呼び出しサンプルです。</dd>

			<dt><a href="ExecTranAuContinuance.php">決済実行</a></dt>
			<dd>ExecTranAuContinuanceの呼び出しサンプルです。</dd>

			<dt><a href="EntryExecTranAuContinuance.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranAuContinuanceの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceStart.php">手続き開始</a></dt>
			<dd>AuContinuanceStartの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceCancel.php">課金解約</a></dt>
			<dd>AuContinuanceCancelの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceChargeCancel.php">課金売上取消・返金</a></dt>
			<dd>AuContinuanceChargeCancelの呼び出しサンプルです。</dd>

		</dl>
		</dd>
		<dt>JcbPreca取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranJcbPreca.php">取引登録</a></dt>
			<dd>EntryTranJcbPreca.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranJcbPreca.php">決済実行</a></dt>
			<dd>ExecTranJcbPrecaの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranJcbPreca.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranJcbPrecaの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="JcbPrecaBalanceInquiry.php">残高照会</a></dt>
			<dd>JcbPrecaBalanceInquiryの呼び出しサンプルです。JcbPrecaの残高照会を開始します。</dd>

			<dt><a href="JcbPrecaCancel.php">キャンセル</a></dt>
			<dd>JcbPrecaCancelの呼び出しサンプルです。JcbPrecaのキャンセルを開始します。</dd>
		</dl>
		</dd>
		<dt>Flets取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranFlets.php">取引登録</a></dt>
			<dd>EntryTranFlets.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranFlets.php">決済実行</a></dt>
			<dd>ExecTranFletsの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranFlets.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranFletsの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="FletsStart.php">フレッツ取引開始</a></dt>
			<dd>FletsStartの呼び出しサンプルです。Fletsのフレッツ取引開始を開始します。</dd>

			<dt><a href="FletsCancel.php">キャンセル</a></dt>
			<dd>FletsCancelの呼び出しサンプルです。Fletsのキャンセルを開始します。</dd>

			<dt><a href="FletsChange.php">金額変更</a></dt>
			<dd>FletsChangeの呼び出しサンプルです。Fletsの金額変更を開始します。</dd>

			<dt><a href="FletsSales.php">売上確定</a></dt>
			<dd>FletsSalesの呼び出しサンプルです。Fletsの売上確定を開始します。</dd>
		</dl>
		</dd>
		<dt>Netcash取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranNetcash.php">取引登録</a></dt>
			<dd>EntryTranNetcash.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranNetcash.php">決済実行</a></dt>
			<dd>ExecTranNetcashの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranNetcash.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranNetcashの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="NetcashStart.php">NET CASH取引開始</a></dt>
			<dd>NetcashStartの呼び出しサンプルです。NetcashのNET CASH取引開始を開始します。</dd>
		</dl>
		</dd>
		<dt>RakutenId取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranRakutenId.php">取引登録</a></dt>
			<dd>EntryTranRakutenId.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranRakutenId.php">決済実行</a></dt>
			<dd>ExecTranRakutenIdの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranRakutenId.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranRakutenIdの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="RakutenIdStart.php">楽天ID取引開始</a></dt>
			<dd>RakutenIdStartの呼び出しサンプルです。RakutenIdの楽天ID取引開始を開始します。</dd>

			<dt><a href="RakutenIdCancel.php">キャンセル</a></dt>
			<dd>RakutenIdCancelの呼び出しサンプルです。RakutenIdのキャンセルを開始します。</dd>

			<dt><a href="RakutenIdChange.php">金額変更</a></dt>
			<dd>RakutenIdChangeの呼び出しサンプルです。RakutenIdの金額変更を開始します。</dd>

			<dt><a href="RakutenIdSales.php">売上確定</a></dt>
			<dd>RakutenIdSalesの呼び出しサンプルです。RakutenIdの売上確定を開始します。</dd>
		</dl>
		</dd>

		<dt>マルチペイメント共通</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="SearchTradeMulti.php">取引照会</a></dt>
				<dd>SearchTradeMultiの呼び出しサンプルです。あるオーダーIDを指定し、その取引の現状態を取得します。</dd>
			</dl>
		</dd>
	</dl>
</div>

<div id="footer">
	<em>Copyright (c) 2008 GMO Payment Gateway,Inc. All Rights Reserved.</em>
</div>

</body>
</html>!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja-JP" xml:lang="ja-JP">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css; charset=UTF-8" />
	
	<link rel="stylesheet" href="style/pgcommon.css" charset="UTF-8" />
	<title>PGマルチペイメントサービス モジュールタイプサンプル</title>
</head>
<body>

<div id="header">
	<h1>PGマルチペイメントサービス モジュールタイプ(PHP) 呼び出しサンプル</h1>
</div>

<div id="main">

	<dl>
		<dt>クレジットカード取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTran.php">取引登録</a></dt>
				<dd>EntryTranの呼び出しサンプルです。加盟店様でオーダーIDや処理区分、利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、カード会社へはデータを送信しません。</dd>
				
				<dt><a href="ExecTran.php">決済実行</a></dt>
				<dd>ExecTranの呼び出しサンプルです。加盟店様がカード所有者から受け取った(画面入力された）カード番号又は会員IDを
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、カード会社へデータを送信します。</dd>
				
				<dt><a href="EntryExecTran.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、カード番号等カード所有者が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
		
				<dt><a href="AlterTran.php">取引変更</a></dt>
				<dd>AlterTranの呼び出しサンプルです。実行した取引を変更します(仮売上を返品する、一度返品した取引を再度売上げる等)。</dd>
				
				<dt><a href="ChangeTran.php">金額変更</a></dt>
				<dd>ChangeTranの呼び出しサンプルです。実行した取引の金額を変更します。</dd>
				
				<dt><a href="SearchTrade.php">取引照会</a></dt>
				<dd>SearchTradeの呼び出しサンプルです。あるオーダーIDを指定し、その取引の現状態を取得します。</dd>
			</dl>
		</dd>
		<dt>会員・カード関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="TradedCard.php">取引後カード登録</a></dt>
				<dd>TradedCardの呼び出しサンプルです。オーダーID・会員IDを指定し、そのオーダーで利用したカードを、会員カードとして登録します。</dd>
				
				<dt><a href="SaveMember.php">会員登録</a></dt>
				<dd>MemberSaveの呼び出しサンプルです。会員情報を保存します。</dd>
				
				<dt><a href="SearchMember.php">会員検索</a></dt>
				<dd>SearchMemberの呼び出しサンプルです。ある会員IDの情報(会員名)を取得します。</dd>
				
				<dt><a href="UpdateMember.php">会員更新</a></dt>
				<dd>UpdateMemberの呼び出しサンプルです。ある会員IDの会員名称を更新します。</dd>
				
				<dt><a href="DeleteMember.php">会員削除</a></dt>
				<dd>DeleteMemberの呼び出しサンプルです。ある会員IDを削除します。削除されたIDの再利用は不可能となります。</dd>
				
				<dt><a href="SaveCard.php">カード登録</a></dt>
				<dd>SaveCardの呼び出しサンプルです。会員にカードを登録します。</dd>
				
				<dt><a href="SearchCard.php">カード検索</a></dt>
				<dd>DeleteCardの呼び出しサンプルです。ある会員のカードを検索します。登録カード連番を指定してそのカードの情報を取得する、または
				登録カード連番を指定せず、その会員の全カードの情報を取得する事が可能です。</dd>
				
				<dt><a href="DeleteCard.php">カード削除</a></dt>
				<dd>DeleteCardの呼び出しサンプルです。ある会員のカードを削除します。削除されたカードを利用可能に戻すことは不可能です。</dd>
			</dl>
		</dd>
		<dt>モバイルSuica取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranSuica.php">取引登録</a></dt>
				<dd>EntryTranSuica.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、Suicaセンターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranSuica.php">決済実行</a></dt>
				<dd>ExecTranSuicaの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、Suicaセンターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranSuica.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranSuicaの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>モバイルEdy取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranEdy.php">取引登録</a></dt>
				<dd>EntryTranEdy.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、Edyセンターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranEdy.php">決済実行</a></dt>
				<dd>ExecTranEdyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、Edyセンターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranEdy.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranEdyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>コンビニ取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranCvs.php">取引登録</a></dt>
				<dd>EntryTranCvs.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranCvs.php">決済実行</a></dt>
				<dd>ExecTranCvsの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranCvs.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranCvsの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>Pay-easy取引関連</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="EntryTranPayEasy.php">取引登録</a></dt>
				<dd>EntryTranPayEasy.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
				通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>
				
				<dt><a href="ExecTranPayEasy.php">決済実行</a></dt>
				<dd>ExecTranPayEasyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
				設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>
				
				<dt><a href="EntryExecTranPayEasy.php">取引登録＋決済実行</a></dt>
				<dd>EntryExecTranPayEasyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
				オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>
				
			</dl>
		</dd>
		<dt>PayPal取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranPaypal.php">取引登録</a></dt>
			<dd>EntryTranPaypal.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranPaypal.php">決済実行</a></dt>
			<dd>ExecTranPaypalの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranPaypal.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranPaypalの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="PaypalStart.php">支払手続き開始</a></dt>
			<dd>PaypalStartの呼び出しサンプルです。Paypal支払い手続きを開始します。</dd>

			<dt><a href="CancelTranPaypal.php">払い戻し実行</a></dt>
			<dd>CancelTranPaypalの呼び出しサンプルです。Paypal払い戻しを開始します。</dd>
		</dl>
		</dd>		
		<dt>WebMoney取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranWebmoney.php">取引登録</a></dt>
			<dd>EntryTranWebmoney.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranWebmoney.php">決済実行</a></dt>
			<dd>ExecTranWebmoneyの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranWebmoney.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranWebmoneyの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="WebmoneyStart.php">支払手続き開始</a></dt>
			<dd>WebmoneyStartの呼び出しサンプルです。Webmoney支払い手続きを開始します。</dd>
		</dl>
		</dd>		
		<dt>auかんたん決済取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranAu.php">取引登録</a></dt>
			<dd>EntryTranAu.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranAu.php">決済実行</a></dt>
			<dd>ExecTranAuの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。この呼び出しで、後続決済センターへデータを送信します。</dd>

			<dt><a href="EntryExecTranAu.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranAuの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="AuStart.php">支払手続き開始</a></dt>
			<dd>AuStartの呼び出しサンプルです。Au支払い手続きを開始します。</dd>

			<dt><a href="AuCancelReturn.php">払い戻し実行</a></dt>
			<dd>AuCancelReturnの呼び出しサンプルです。Au払い戻しを開始します。</dd>

			<dt><a href="AuSales.php">売上確定実行</a></dt>
			<dd>AuSalesの呼び出しサンプルです。Au売上確定を開始します。</dd>

			<dt><a href="DeleteAuOpenID.php">OpenID解除実行</a></dt>
			<dd>DeleteAuOpenIDの呼び出しサンプルです。OpenID解除を開始します。</dd>
		</dl>
		</dd>
		<dt>ドコモケータイ払い関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranDocomo.php">取引登録開始</a></dt>
			<dd>EntryTranDocomoの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranDocomo.php">決済実行開始</a></dt>
			<dd>ExecTranDocomoの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranDocomo.php">登録・決済開始</a></dt>
			<dd>EntryExecTranDocomoの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="DocomoStart.php">支払手続き開始</a></dt>
			<dd>DocomoStartの呼び出しサンプルです。支払手続き開始を開始します。</dd>

			<dt><a href="DocomoCancelReturn.php">決済取消開始</a></dt>
			<dd>DocomoCancelReturnの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="DocomoSales.php">売上確定開始</a></dt>
			<dd>DocomoSalesの呼び出しサンプルです。売上確定を開始します。</dd>

		</dl>
		</dd>
		<dt>ドコモ継続決済関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranDocomoContinuance.php">取引登録開始</a></dt>
			<dd>EntryTranDocomoContinuanceの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranDocomoContinuance.php">決済実行開始</a></dt>
			<dd>ExecTranDocomoContinuanceの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranDocomoContinuance.php">登録・決済開始</a></dt>
			<dd>EntryExecTranDocomoContinuanceの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="DocomoContinuanceStart.php">決済実行開始</a></dt>
			<dd>DocomoContinuanceStartの呼び出しサンプルです。ドコモ継続課金の支払い手続きを開始します。</dd>

			<dt><a href="DocomoContinuanceCancelReturn.php">決済取消開始</a></dt>
			<dd>DocomoContinuanceCancelReturnの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="DocomoContinuanceSales.php">売上確定開始</a></dt>
			<dd>DocomoContinuanceSalesの呼び出しサンプルです。売上確定を開始します。</dd>

			<dt><a href="DocomoContinuanceUserChange.php">継続課金変更（お客様）</a></dt	>
			<dd>DocomoContinuanceUserChangeの呼び出しサンプルです。継続課金変更（お客様）を開始します。</dd>

			<dt><a href="DocomoContinuanceUserEnd.php">継続課金終了（お客様）</a></dt>
			<dd>DocomoContinuanceUserEndの呼び出しサンプルです。継続課金終了（お客様）を開始します。</dd>

			<dt><a href="DocomoContinuanceShopChange.php">継続課金変更（加盟店）</a></dt>
			<dd>DocomoContinuanceShopChangeの呼び出しサンプルです。継続課金変更（加盟店）を開始します。</dd>

			<dt><a href="DocomoContinuanceShopEnd.php">継続課金終了（加盟店）</a></dt>
			<dd>DocomoContinuanceShopEndの呼び出しサンプルです。継続課金終了（加盟店）を開始します。</dd>

		</dl>
		</dd>
		<dt>ソフトバンクケータイ支払い関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranSb.php">取引登録開始</a></dt>
			<dd>EntryTranSbの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranSb.php">決済実行開始</a></dt>
			<dd>ExecTranSbの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranSb.php">登録・決済開始</a></dt>
			<dd>EntryExecTranSbの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="SbStart.php">支払手続き開始開始</a></dt>
			<dd>SbStartの呼び出しサンプルです。支払手続き開始を開始します。</dd>

			<dt><a href="SbCancel.php">決済取消開始</a></dt>
			<dd>SbCancelの呼び出しサンプルです。決済取消を開始します。</dd>

			<dt><a href="SbSales.php">売上確定開始</a></dt>
			<dd>SbSalesの呼び出しサンプルです。売上確定を開始します。</dd>

		</dl>
		</dd>
		<dt>じぶん銀行関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranJibun.php">取引登録開始</a></dt>
			<dd>EntryTranJibunの呼び出しサンプルです。取引登録を開始します。</dd>

			<dt><a href="ExecTranJibun.php">決済実行開始</a></dt>
			<dd>ExecTranJibunの呼び出しサンプルです。決済実行を開始します。</dd>

			<dt><a href="EntryExecTranJibun.php">登録・決済開始</a></dt>
			<dd>EntryExecTranJibunの呼び出しサンプルです。登録・決済を開始します。</dd>

			<dt><a href="JibunStart.php">支払手続き開始</a></dt>
			<dd>JibunStartの呼び出しサンプルです。じぶん銀行支払い手続きを開始します。</dd>
		</dl>
		</dd>
		<dt>au継続関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranAuContinuance.php">取引登録</a></dt>
			<dd>EntryTranAuContinuance.phpの呼び出しサンプルです。</dd>

			<dt><a href="ExecTranAuContinuance.php">決済実行</a></dt>
			<dd>ExecTranAuContinuanceの呼び出しサンプルです。</dd>

			<dt><a href="EntryExecTranAuContinuance.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranAuContinuanceの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceStart.php">手続き開始</a></dt>
			<dd>AuContinuanceStartの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceCancel.php">課金解約</a></dt>
			<dd>AuContinuanceCancelの呼び出しサンプルです。</dd>

			<dt><a href="AuContinuanceChargeCancel.php">課金売上取消・返金</a></dt>
			<dd>AuContinuanceChargeCancelの呼び出しサンプルです。</dd>

		</dl>
		</dd>
		<dt>JcbPreca取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranJcbPreca.php">取引登録</a></dt>
			<dd>EntryTranJcbPreca.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranJcbPreca.php">決済実行</a></dt>
			<dd>ExecTranJcbPrecaの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranJcbPreca.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranJcbPrecaの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="JcbPrecaBalanceInquiry.php">残高照会</a></dt>
			<dd>JcbPrecaBalanceInquiryの呼び出しサンプルです。JcbPrecaの残高照会を開始します。</dd>

			<dt><a href="JcbPrecaCancel.php">キャンセル</a></dt>
			<dd>JcbPrecaCancelの呼び出しサンプルです。JcbPrecaのキャンセルを開始します。</dd>
		</dl>
		</dd>
		<dt>Flets取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranFlets.php">取引登録</a></dt>
			<dd>EntryTranFlets.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranFlets.php">決済実行</a></dt>
			<dd>ExecTranFletsの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranFlets.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranFletsの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="FletsStart.php">フレッツ取引開始</a></dt>
			<dd>FletsStartの呼び出しサンプルです。Fletsのフレッツ取引開始を開始します。</dd>

			<dt><a href="FletsCancel.php">キャンセル</a></dt>
			<dd>FletsCancelの呼び出しサンプルです。Fletsのキャンセルを開始します。</dd>

			<dt><a href="FletsChange.php">金額変更</a></dt>
			<dd>FletsChangeの呼び出しサンプルです。Fletsの金額変更を開始します。</dd>

			<dt><a href="FletsSales.php">売上確定</a></dt>
			<dd>FletsSalesの呼び出しサンプルです。Fletsの売上確定を開始します。</dd>
		</dl>
		</dd>
		<dt>Netcash取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranNetcash.php">取引登録</a></dt>
			<dd>EntryTranNetcash.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranNetcash.php">決済実行</a></dt>
			<dd>ExecTranNetcashの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranNetcash.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranNetcashの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="NetcashStart.php">NET CASH取引開始</a></dt>
			<dd>NetcashStartの呼び出しサンプルです。NetcashのNET CASH取引開始を開始します。</dd>
		</dl>
		</dd>
		<dt>RakutenId取引関連</dt>
		<dd>
		<dl class="divisional">
			<dt><a href="EntryTranRakutenId.php">取引登録</a></dt>
			<dd>EntryTranRakutenId.phpの呼び出しサンプルです。加盟店様でオーダーIDや利用金額を決定して、PGマルチペイメントサービスと通信します。
			通信して、PGマルチペイメントサービス内での取引ID(AccessID)を受け取ります。この呼び出しでは、後続決済センターへはデータを送信しません。</dd>

			<dt><a href="ExecTranRakutenId.php">決済実行</a></dt>
			<dd>ExecTranRakutenIdの呼び出しサンプルです。加盟店様がお客様から受け取った(画面入力された）決済に必要な情報を
			設定し、取引登録で払い出された取引IDで決済を行います。</dd>

			<dt><a href="EntryExecTranRakutenId.php">取引登録＋決済実行</a></dt>
			<dd>EntryExecTranRakutenIdの呼び出しサンプルです。上記「取引登録」および「決済実行」を一度に実行します。
			オーダーID等の加盟店様が払い出す値と、決済に必要なお客様が入力する情報を一度に設定し、PGマルチペイメントサービスと通信します。</dd>

			<dt><a href="RakutenIdStart.php">楽天ID取引開始</a></dt>
			<dd>RakutenIdStartの呼び出しサンプルです。RakutenIdの楽天ID取引開始を開始します。</dd>

			<dt><a href="RakutenIdCancel.php">キャンセル</a></dt>
			<dd>RakutenIdCancelの呼び出しサンプルです。RakutenIdのキャンセルを開始します。</dd>

			<dt><a href="RakutenIdChange.php">金額変更</a></dt>
			<dd>RakutenIdChangeの呼び出しサンプルです。RakutenIdの金額変更を開始します。</dd>

			<dt><a href="RakutenIdSales.php">売上確定</a></dt>
			<dd>RakutenIdSalesの呼び出しサンプルです。RakutenIdの売上確定を開始します。</dd>
		</dl>
		</dd>

		<dt>マルチペイメント共通</dt>
		<dd>
			<dl class="divisional">
				<dt><a href="SearchTradeMulti.php">取引照会</a></dt>
				<dd>SearchTradeMultiの呼び出しサンプルです。あるオーダーIDを指定し、その取引の現状態を取得します。</dd>
			</dl>
		</dd>
	</dl>
</div>

<div id="footer">
	<em>Copyright (c) 2008 GMO Payment Gateway,Inc. All Rights Reserved.</em>
</div>

</body>
</html>
