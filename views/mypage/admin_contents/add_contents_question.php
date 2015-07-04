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
		</div>
		<div class='contents'>
			<table class='detail'>
				<form method='post'>
					<tr><th>カテゴリ</th><td><?php echo form_dropdown('category_id',$category_list,$form_data->category_id) ?></td></tr>
					<tr>	
						<th>質問内容</th><td><textarea name='question' rows='3' cols='70'><?php echo $form_data->question ?></textarea></td>
					</tr>
					<tr>
						<th>質問の答え</th><td><textarea name='answer' rows='3' cols='70'><?php echo $form_data->answer ?></textarea></td>
					<tr>
						<th class='no-border'></th><td><input type='submit' name='submit' value='登録'><a class='edit_back' href='<?php echo site_url('admin_contents/list_contents_question') ?>'>戻る</a></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>