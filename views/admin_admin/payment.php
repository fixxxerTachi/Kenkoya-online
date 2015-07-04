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
				<p class='links'>
				</p>
				<?php if(!empty($result)): ?>
					<table class='list'>
					<tr><th>ID</th><th>表示名</th><th></th><th></th></tr>
					<?php foreach($result as $item):?>
						<tr>
							<td><?php echo $item->id ?></td>
							<td><?php echo $item->method_name ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_admin/payment/{$item->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick ='del_confirm("<?php echo $item->method_name ?>","<?php echo $item->id ?>")'>削除</a></td>
						</tr>
					<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
		<div class='contents'>
			<h2>お支払方法<?php if(!isset($edit)):?>新規登録<?php else:?>更新<?php endif;?></h2>
			<?php echo form_open() ?>
			<table class='detail'>
				<tr>
					<th>表示名</th>
					<td><input type='text' name='method_name' value='<?php echo $form_data->method_name ?>' size='70' maxlength='100'></td>
				</tr>
				<tr>
					<th>簡単な説明（決済方法の欄に表示されます)</th>
					<td><textarea name='notice' rows='10' cols='70'><?php echo $form_data->notice ?></textarea></td>
				</tr>
				<tr>
					<th>説明(決済方法の詳しい説明に表示されます)</th></th>
					<td>
						<textarea name='description' rows='20' cols='70'><?php echo $form_data->description ?></textarea>
					</td>
				</tr>
				<tr>
					<th class='no-border'></th>
					<td>
						<input type='submit' name='submit' value='<?php if(!isset($edit)) echo '登録する'; else echo '更新する'; ?>'>
						<a class='edit_back' href='<?php echo site_url('admin_admin/payment') ?>'>戻る</a>
					</td>
			</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_payment") ?>' + '/' + id;
		return false;
	}
}
</script>
</body>
</html>
