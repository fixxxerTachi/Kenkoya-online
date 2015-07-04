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
			<p class='note'>すでにに宅配サービスをご利用の方で、WEBで宅配サービスをご利用になりたい方は、メールアドレスとパスワードの登録が必要です。</p>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error_validation'><?php echo $error_message ?></p>
			<?php endif;?>
		<?php if(validation_errors()):?>
				<?php echo validation_errors() ?>
		<?php endif;?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<p>すでに宅配スーバー健康屋をご利用の方は、御請求明細書もしくは領収書記載のご登録番号と、お客様の電話番号を入力して、メールアドレスとパスワードの設定を行ってください。</p>
					<tr>
						<th><label for='code'>ご登録番号</label></th>
						<td>
							<input type='text' id='code' name='code' value='<?php echo $form_data->code ?>' size='3' maxlength='3'>-
							<input type='text' id='code' name='code1' value='<?php echo $form_data->code1 ?>' size='4' maxlength='4'>-
							<input type='text' id='code' name='code2' value='<?php echo $form_data->code2 ?>' size='3' maxlength='3'>-
							<input type='text' id='code' name='code3' value='<?php echo $form_data->code3 ?>' size='6' maxlength='6'>-
							<input type='text' id='code' name='code4' value='<?php echo $form_data->code4 ?>' size='2' maxlength='2'>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>お電話番号</label></th>
						<td><input type='tel' name='tel' id='tel' size='15' maxlength='15'></td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='ログイン'></td>
					</tr>
				</table>
			</form>
			<div>
				<img src='<?php echo base_url('images/account_number.jpg')?>'>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>