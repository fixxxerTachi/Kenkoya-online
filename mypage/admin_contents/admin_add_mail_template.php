<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
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
			<form aciton='' method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='template_name'>表示名</label></th>
						<td><input type='text' id='template_name' name='template_name' value='<?php echo $form_data->template_name ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='for_customer'>お客様送付<br>管理者送付</label></th>
						<td>
							<?php echo form_dropdown('for_customer',$reciever,$form_data->for_customer,'id=for_customer');?>
						</td>
					</tr>
					<tr>
						<th><label for='mail_title'>件名</label></th>
						<td><input type='text' id='mail_title' name='mail_title' value='<?php echo $form_data->mail_title ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='body'>メール本文</label></th>
						<td><textarea name='mail_body' id='mail_body' cols=80 rows=30><?php echo $form_data->mail_body ?></textarea></td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('/admin_contents/list_mail_template') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>