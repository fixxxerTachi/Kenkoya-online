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
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<p class='note'>宅配スーバー健康屋会員の方はこちらからユーザー名、パスワードを入力してログインしてください </p>
		<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
		<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
		<?php echo validation_errors('<p class="error">','</p>');?>
					<tr>
						<th><label for='username'>ユーザー名</label></th>
						<td><input type='text' id='username' name='username' value='<?php if(!empty($form_data->username)):?><?php echo $form_data->username ?><?php endif;?>' size='40' maxlength='40'></td>
					</tr>
					<tr>
						<th><label for='password'>パスワード</label></th>
						<td><input type='password' name='password' id='password' value='' size='40' maxlength='40'></td>
						<input type='hidden' name='ref' value='<?php echo $ref ?>'>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='ログイン'></td>
					</tr>
					<tr>
						<td><a class='button' href='<?php echo base_url("front_customer/set_username");?>'>ユーザー名を忘れた場合はこちら</a></td>
						<td><a class='button' href='<?php echo base_url("front_customer/set_password");?>'>パスワードを忘れた場合はこちら</a></td>
					</tr>
				</table>
			</form>
			<table class='contact_form'>
				<tr>
						<p class='note'>既に会員登録がお済で、初めてWEBから注文される方は、メールアドレスとパスワードの登録が必要です
						<a class='edit' href='<?php echo base_url('front_customer/renew_user') ?>'>メールアドレスとパスワードの設定</a>
				</tr>
			</table>
<?php if(empty($user_view)):?>
			<h2><span class='logo_pink'>member</span> 初めて利用される方、会員登録(無料)されていない方はこちら</h2>
			<?php echo form_open('front_customer/login_action') ?>
				<table class='member_form'>
					<p>会員登録を希望される方は「会員登録する」、会員登録しないで購入を希望される方は、「会員登録しない」をチェックして「次へ」クリックしてください。</p>
					<tr><td><input type='radio' name='member' id='memeber' value='1' <?php if($form_data->member=='1') echo 'checked=checked' ?>><label for='memeber'>会員登録する</label></tr>
					<tr><td><input type='radio' name='member' id='no-memeber' value='0' <?php if($form_data->member=='0') echo 'checked=checked' ?>><label for='no-memeber'>会員登録しない</label></tr>
					<tr><td><input type='submit' name='save_member' value='次へ'></td></tr>
				</table>
			</form>
<?php endif;?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
