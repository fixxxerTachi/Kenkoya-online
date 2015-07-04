<!doctype html>
<html lang ='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/contact.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>contact</span> 皆さまからのご要望・ご意見をお待ちしております</h2>
			<p class='note'>みなさまからよくいただくご質問やご意見へのお答えをご紹介しています。  <a class='button' href='<?php echo base_url('front_question') ?>'>よくあるご質問</a></p>
			<div class='content-inner'>
				<h3><span class='logo'>TEL</span> お電話でのお問い合わせ</h3>
				<p><span class='tel'>0120-383-333</span> &nbsp;営業時間 : 月曜日～金曜日（9:00 ~ 18:00)</p>
			</div>
			<div class='content-inner'>
				<h3><span class='logo'>MAIL</span> メールでのお問い合わせ</h3>
				<p><a class='button' href='<?php echo base_url('front_contact/contact') ?>'>お問い合わせフォーム</a></span>からお問い合わせください</p>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>