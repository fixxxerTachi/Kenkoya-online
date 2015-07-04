<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>login</span> <?php echo $h2title ?></h2>
<?php if($flag == 'password'):?>
			<p>ご登録されているメールアドレスにパスワード再設定用のメールを送信しました。</p>
			<p>メールに記載されているURLにアクセスして、パスワードを再設定してください。</p>
			<p>メールに記載のURLは2時間有効です。2時間以内にアクセスしていただけなかった場合は、再度パスワード再設定の手続きを行って下さい。</p>
<?php endif;?>
<?php if($flag =='username'):?>
			<p>ご登録されているメールアドレスに仮ユーザー名を記載したメールを送信しました。</p>
			<p>メールに記載されているURLにアクセスして、ユーザー名を再設定してください。</p>
<?php endif;?>
			<p><a class='edit' href='<?php echo base_url() ?>'>TOPに戻る</a></p>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>