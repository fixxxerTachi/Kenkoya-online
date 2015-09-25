<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/contact.css')?>'></head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>contact</span> <?php echo $h2title ?></h2>
			<p><span class='logo_pink'>必須</span>は必ず入力してください</p>
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
					<tr><th>お名前 <span class='logo_pink'>必須</span></th><td><input type='text' name='name'  id='name' value='<?php echo $form_data->name ?>' size='30' maxlength='30'></td></tr>
					<tr>
						<th rowspan='2'>メールアドレス <br><span class='logo_pink'>必須</span></th>
						<td><input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='70' maxlength='70'></td><br>
					</tr>
					<tr>
						<td>ご確認のため再度入力してください<br><input type='text' name='email_confirm' id='email_confirm' value='<?php echo $form_data->email_confirm ?>' size='70' maxlength='70'></td>
					</tr>
					<tr><th>カテゴリ</th><td><?php echo form_dropdown('category_id',$category_list,$form_data->category_id) ?></td></tr>
					<tr>
						<th>質問内容 <span class='logo_pink'>必須</span></th><td><textarea name='content' rows='10' cols='100' id='content'><?php echo $form_data->content ?></textarea></td>
					</tr>
					<tr>
						<td class='no-border'></td><td><input type='submit' name='submit' id='submit_button' value='入力内容を確認する'><a class='edit_back' href='<?php echo site_url('contact') ?>'>戻る</a></td>
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