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
			<form action='' method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='cource_id'>コースID</label></th>
						<td>
							<input type='text' id='cource_id' name='cource_id' value='<?php echo $form_data->cource_id ?>' size='7' maxlength='7'>
						</td>
					</tr>
					<tr>
						<th><label for='cource_name'>コース名</label></th>
						<td>
							<input type='text' id='cource_name' name='cource_name' value='<?php echo $form_data->cource_name ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='takuhai_day'>配達曜日</label></th>
						<td>
							<input type='text' id='takuhai_day' name='takuhai_day' value='<?php echo $form_data->takuhai_day ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='delivery_person_id'>配達者</label></th>
						<td>
							<?php echo form_dropdown('delivery_person_id',$list_delivery_person,$form_data->delivery_person_id) ?>
						</td>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_admin/list_cource') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>