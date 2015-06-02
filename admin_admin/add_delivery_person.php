<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<?php if(!empty($message)):?>
		<p><?php echo $message ?></p>
		<?php endif;?>
		<?php if(!empty($success_message)):?>
		<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
		<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
		<div class='contents'>
			<form action='' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='advertise_id' value='<?php echo $ad_result->id ?>'>
			<table class='detail'>
				<tr>
					<th>画像</th>
					<td><input type='file' name='image'></td>
				</tr>
				<?php if(!empty($form_data->image)):?>
				<tr>
					<th></th>
					<td><img src='<?php echo base_url() . DELIVERY_IMAGE_PATH . $form_data->image ?>' width='80' height='100'></td>
				</tr>
				<?php endif; ?>
				<tr>
					<th>担当者名</th>
					<td>
						<input type='text' name='name' size='20' maxlength='20' value='<?php echo $form_data->name ?>'>
					</td>
				</tr>
				<tr>
					<th>紹介文</th>
					<td><textarea name='introduction' rows='10' cols='40'><?php echo $form_data->introduction ?></textarea></td>
				</tr>
				<tr><th class='no-border'></th><td><input type='submit' value='登録' name='submit'></td></tr>
			</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>