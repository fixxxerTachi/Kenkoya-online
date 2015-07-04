<!doctype html>
<html lang = 'ja'>
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
		<h2><span class='logo_pink'>login</span> <?php echo $h2title ?></h2>
			<p class='note'>既に宅配サービスをご利用の方で、WEBで宅配サービスをご利用になりたい方は、メールアドレスとパスワードの登録が必要です。</p>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message; ?></p>
			<?php endif; ?>
		<?php if(validation_errors()):?>
				<?php echo validation_errors() ?>
		<?php endif;?>
			<?php echo form_open("front_customer/reset_password?key={$key}") ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='username'>ご登録ユーザー名</label></th>
						<td><input type='text' name='username' id='username' value='<?php echo $username ?>' size='50' maxlength='50'></td>
					</tr>
					<tr>
						<th><label for='password'>パスワード</label></th>
						<td>パスワードは8文字以上16文字以内で入力してください<br><input type='password' name='password' id='password' value='' size='16' maxlength='16'></td>
					</tr>
					<tr>
						<th><label for='password_confirm'>パスワード(確認用)</label></th>
						<td><input type='password' name='password_confirm' id='password_confirm' value='' size='16' maxlength='16'></td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='パスワードを再設定'></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
<script>
var check = document.getElementById('user_email');
var email = document.getElementById('email_confirm');
var username = document.getElementById('username');
check.addEventListener('click',function(){
	if(check.checked == true){
		username.value = email.value;
	}else{
		username.value = '';
	}
},false);
</script>
</html>
