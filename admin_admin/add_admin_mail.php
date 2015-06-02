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
						<td><?php echo $row->email ?></td>
						<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_admin_mail/{$row->id}") ?>'>変更</a></td>
						<td><a class='edit' onclick='del_confirm("<?php echo $row->email ?>" , <?php echo $row->id ?>)'>削除</a></td>
					<?php endforeach;?>
				</table>
			<?php else: ?>
				<p>登録されていません</p>
			<?php endif; ?>
		</div>
		<div class='contents'>
			<table class='detail'>
				<?php echo form_open() ?>
					<tr>	
						<th>名前</th><td><input type='text' name='name' id='name' value='<?php echo $form_data->name ?>'></td>
					</tr>
					<tr>
						<th>メールアドレス</th><td><input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='70' maxlength='100'></td>
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
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_admin_mail") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>