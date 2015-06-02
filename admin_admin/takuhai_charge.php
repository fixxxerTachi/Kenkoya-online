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
			<?php echo form_open() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='text'>温度帯：表示名</label>
						<td><input type='text' name='text' id='text' value='<?php echo $form_data->text ?>'></td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_admin/delivery_charge') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>