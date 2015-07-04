<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php echo form_open() ?>
				<table class='detail'>
					<tr><th><label for='name'>お客様名</label></th><td><input type='text' name='name' value=''></td></tr>
					<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
				</table>
				</form>
				<?php if($result): ?>
					<?php echo form_open() ?>
					<table class='list'>
						<?php $count=1;?>
						<?php foreach($result as $row): ?>
						<tr>
							<td>
								<input type='text' name='code_<?php echo $count ?>'>
								<input type='hidden' name='id_<?php echo $count ?>' value='<?php echo $row->id ?>'>
							</td>
							<td><?php echo html_escape($row->name) ?></td>
							<td><?php echo $row->furigana ?></td>
							<td><?php echo $row->prefecture ?></td>
							<td><?php echo $row->address1 ?></td>
							<td><?php echo $row->address2 ?></td>
							<input type='hidden' name='count' value='<?php echo $count ?>'>
						</tr>
						<?php $count++;?>
						<?php endforeach;?>
						<tr class='no-back'><th><input type='submit' name='submit' value='登録する'></th><td></td></tr>
					</table>
					</form>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
function del_confirm(name , id){
	var name = name;
	var id = id;
	if(window.confirm(name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_customer/delete_customer") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>
