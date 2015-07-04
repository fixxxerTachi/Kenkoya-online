<?php include __DIR__ . '/../templates/meta_front.php' ?>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
		<h2><?php echo $h2title ?></h2>
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
			<form method='post' action='<?php echo site_url('front_customer/login_action') ?>'>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='username'>ユーザー名</label></th>
						<td><input type='text' id='username' name='username' value='' size='40' maxlength='40'></td>
					</tr>
					<tr>
						<th><label for='password'>パスワード</label></th>
						<td><input type='password' name='password' id='password' value='' size='40' maxlength='40'></td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='login'></td>
					</tr>
				</table>
			</form>
<?php if(empty($user_view)):?>
			<h2>初めて利用される方、会員登録(無料)されていない方はこちら</h2>
			<form method='post' action='<?php echo site_url('front_customer/login_action') ?>'>
				<table>
					<tr><td><input type='radio' name='member' id='memeber' value='1' <?php if($form_data->member=='1') echo 'checked=checked' ?>><label for='memeber'>会員登録する</label></tr>
					<tr><td><input type='radio' name='member' id='no-memeber' value='0' <?php if($form_data->member=='0') echo 'checked=checked' ?>><label for='no-memeber'>会員登録しない</label></tr>
					<tr><td><input type='submit' name='save_member' value='次へ'></td></tr>
				</table>
			</form>
<?php endif;?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>