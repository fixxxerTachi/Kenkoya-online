<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>confirm</span> <?php echo $h2title ?></h2>
		<p>お客様のメールアドレスにログインIDとパスワードを記載したメール送信いたしました。</p>
		<p>メールに記載されいるURLアドレスにアクセスしてパスワードを変更くださいますようお願い申し上げます。</P>
		<P>メールが届かない場合、恐れ入りますが<a href='<?php echo base_url('front_contact') ?>'>お問い合わせフォーム</a>でご連絡くださいますようお願い申し上げます。</p>
		<p><a class='edit' href='<?php echo base_url() ?>'>Topに戻る</a></p>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>