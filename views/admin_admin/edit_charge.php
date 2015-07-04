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
<?php if(!empty($result)):?>			
			<?php echo form_open() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
			<?php $count = 1; ?>
			<?php foreach($result as $key => $row):?>
						<th><label for="charge_<?php echo $row->id ?>"><?php echo $row->pref_name?></label>
						<td><input type='text' name="charge_<?php echo $row->id?>" id="charge_<?php echo $row->id ?>" value='<?php echo $row->charge ?>' size='5' maxlength='5'></td>
			<?php if($count%3 == 0):?>
						</tr><tr>
			<?php endif;?>
			<?php $count++;?>
		<?php endforeach;?>
					<tr>
						<th class='no-border'></th>
						<td colspan='3'><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_admin/delivery_charge') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
<?php else:?>
				<p>登録されていません</p>
<?php endif;?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script src='<?php echo base_url('js/jquery-1.11.0.min.js') ?>'></script>
<script>
$('table.detail tr td:nth-child(3n)').each(function(){
	$(this).after('');
});
</script>
</body>
</html>