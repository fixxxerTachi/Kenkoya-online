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
			<form method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
				<?php if($type == 'username'):?>
					<tr>
						<th><label for='current_pw' name='current_pw'>現在のパスワードを入力してください</label></th>
						<td>
							<input type='password' name='current_pw' id='current_pw'>
						</td>
					</tr>
					<tr>
						<td colspan='2'><input type='checkbox' name='user_email' id='user_email' value='1' <?php if(!empty($form_data->user_email) && $form_data->user_email == '1') echo "checked=checked"; ?>><label for='user_email'>ユーザーIDにメールアドレスを使用する</label>
					</tr>
					<tr>
						<th>現在のユーザー名</th>
						<td><?php echo $form_data->current_username ?></td>
					</tr>
					<tr>
						<th><label for='username'>新しいユーザーID</label></td>
						<td><input type='text' name='username' id='username' value='<?php echo $form_data->username ?>' size='50' maxlength='50'></td>
					</tr>
				<?php endif;?>
				<?php if($type == 'password'):?>
					<tr>
						<th><label for='current_pw' name='current_pw'>現在のパスワードを入力してください</label></th>
						<td>
							<input type='password' name='current_pw' id='current_pw'>
						</td>
					</tr>
					<tr>
						<th><label for='pw_confirm' name='pw_confirm'>新しいパスワードを入力してください</label></th>
						<td>
							<input type='password' name='pw_confirm' id='pw_confirm'>
						</td>
					</tr>
					<tr>
						<th><label for='new_pw' name='new_pw'>新しいパスワードを入力してください(確認)</label></th>
						<td>
							<input type='password' name='new_pw' id='new_pw'>
						</td>
					</tr>
				<?php endif;?>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='変更する'><a class='edit_back' href='<?php echo site_url('mypage/mypage') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
$('#user_email').on('change',function(){
	if($(this).prop('checked')){
		$('#username').val('<?php echo $email ?>');
	}else{
		$('#username').val('');
	}
/*
	if($(this).val() == '1'){
		$('#username').val('<?php echo $email ?>');
		alert($(this).val());
	}
*/
});
</script>
</html>