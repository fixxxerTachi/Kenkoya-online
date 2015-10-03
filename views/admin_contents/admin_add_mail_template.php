<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="container">
					<h2><span classs='logo_pink'> <?php echo $h2title ?></span></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>		
					<?php echo form_open() ?>
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
								<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px'><a class='edit_back' href='<?php echo site_url('/admin_contents/list_mail_template') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</main>
	</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>