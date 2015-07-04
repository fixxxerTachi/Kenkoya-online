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
			<form aciton='' method='post'>
				<table class='detail'>
					<tr><th><label for='name'>カテゴリ名</label></th><td><input type='text' id='name' name='name' size='50'  maxlength='50' value='<?php echo $form_data->name ?>'><td></tr>
					<tr><th><label for='show_name'>表示名</label></th><td><input type='text' id='show_name' name='show_name' size='50'  maxlength='50' value='<?php echo $form_data->show_name ?>'><td></tr>
					<tr><th class='no-border'></th><td><input type='submit' value='登録' class='input' name='submit'></td></tr>
				</table>
			</form>
		</div>
		<?php if(!empty($success_message)):?>
		<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
		<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
		<div class='contents'>
			<h2>登録されているカテゴリ</h2>
			<?php if($result): ?>
				<table>
					<tr><th>カテゴリ名</th><th>表示名</th></tr>
					<?php foreach($result as $row): ?>
					<tr>
						<td><?php echo $row->name ?></td>
						<td><?php echo $row->show_name ?></td>
						<td><a href='<?php echo site_url("/admin_product/edit_category/{$row->id}") ?>'>変更</a></td>
					<?php endforeach;?>
				</table>
			<?php else: ?>
				<p>登録されていません</p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>