<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
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
				<?php echo form_open() ?>
					<table class='detail'>
						<tr>
							<th>お名前:</th><td><?php echo $form_data->name ?></td>
						</tr>
						<tr>
							<th>email:</th><td><?php echo $form_data->email ?></td>
						</tr>
						<tr>
							<th><label for='title'>タイトル</label></th>
							<td>
								<input type='title' id='title' name='title' value='<?php echo $title ?>' size='70' maxlength='200'>
							</td>
						</tr>
						<tr>
							<th><label for='content'>本文</label></th>
							<td><textarea id='content' name='content' rows='30' cols='100'><?php echo $content ?></textarea></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='送信する'><a class='edit_back' href='<?php echo site_url('admin_admin/list_contact') ?>'>戻る</a></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>