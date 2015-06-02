<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/contact.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>confirm</span> <?php echo $h2title ?></h2>
			<p class='note'>入力内容をご確認ください。<br>「この内容で問い合わせる」をクリックするとこの内容が弊社に送信されます。</p>
			
			<div class='message_area'>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php echo validation_errors("<p class='error'>","</p>") ?>
			</div>
		</div>
		<div class='content'>
			<table class='contact_form'>
				<?php echo form_open() ?>
					<tr><th>お名前</th><td><?php echo $form_data->name ?></td></tr>
					<tr><th>メールアドレス</th><td><?php echo $form_data->email ?></td></tr>
					<tr><th>カテゴリ</th><td><?php echo $category_list[$form_data->category_id] ?></td></tr>
					<tr>
						<th>質問内容</th><td><?php echo nl2br($form_data->content) ?></td>
					</tr>
					<tr></tr>
					<tr>
						<td></td><td><input id='submit_button' type='submit' name='submit' value='この内容で問い合わせる'><a class='edit_back' href='<?php echo base_url('front_contact/contact') ?>'>入力内容を変更する</a></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>