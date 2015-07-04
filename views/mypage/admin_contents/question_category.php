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
			<?php if($result): ?>
				<table  class='list'>
					<?php foreach($result as $row): ?>
					<tr>
						<td><?php echo $row->id ?></td>
						<td><?php echo $row->name ?></td>
						<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_question_category/{$row->id}") ?>'>変更</a></td>
					<?php endforeach;?>
				</table>
			<?php else: ?>
				<p>登録されていません</p>
			<?php endif; ?>
		</div>
		<div class='contents'>
			<table class='detail'>
				<form method='post'>
					<tr>	
						<th>カテゴリ名</th><td><input type='text' name='name' id='name' value='<?php echo $form_data->name ?>'></td>
					</tr>
					<tr>
						<th class='no-border'></th><td><input type='submit' name='submit' value='登録'></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>