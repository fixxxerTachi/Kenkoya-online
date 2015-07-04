<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/order.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<?php include __DIR__.'/../templates/order_flow.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="container-inner">
		<div class='content'>
				<h2><span class='logo_pink'>complete</span> 注文を確定しました</h2>
				<h3>ご注文ありがとうございました。</h3>
				<p>受注番号:<?php echo $order_number ?>にてご注文を承りました。</p>
				<p>ご注文明細のメールを送信しましたので。ご確認ください。</p>
				<p>今後とも宅配スーパー健康屋をよろしくお願いいたします。</p>
				<p><a class='edit' href='<?php echo base_url() ?>'>Topに戻る</a></p>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>