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
			<?php echo validation_errors('<p class="error">','</p>');?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<p class='note'>ユーザー名をお忘れの方は登録済みのメールアドレスを入力し、画像内の文字列を入力して次に進むボタンをクリックして下さい<br>パスワード再設定用のページのリンクをEメールでお送りします。</p>
		<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
		<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
					<tr>
						<th><label for='email'>メールアドレス</label></th>
						<td><input type='text' id='email' name='email' value='' size='40' maxlength='40'></td>
					</tr>
					<tr>
						<th>画像</th>
						<td id='captcha'><?php echo $cap['image'] ?></td>
					</tr>
					<tr>
						<th>画像の文字を半角で入力してください</th>
						<td><input type='text' name='word'></td>
						<td><input type='hidden' name='captcha_id' value='<?php echo $captcha_id ?>'></td>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='確認'></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>