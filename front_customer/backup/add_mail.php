<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css')?>'>
</head>
<body>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="body">
		<div class='content'>
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
			<form action='' method='post'>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='email_confirm'>メールアドレス</label></th>
						<td>
							<input type='text' name='email_confirm' id='email_confirm' value='<?php echo $form_data->email_confirm ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='email'>メールアドレス(確認)</label></th>
						<td>
							<input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='60' maxlength='60'>
						</td>
					</tr>
			</table>
				<table id='menu'>
						<th class='no-back'></th>
						<td>
							<ul>
								<li><input type='submit' name='submit' id='submit' value='登録する'></li>
								<li><a class='edit_back' href='<?php echo site_url('front_customer/mypage') ?>'>戻る</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
</html>