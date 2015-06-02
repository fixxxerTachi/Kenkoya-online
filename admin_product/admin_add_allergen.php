<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2>登録されているアレルゲン</h2>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>		
				<?php if($list_result): ?>
					<table class='list'>
						<tr><th>表示名</th><th>説明</th><th>画像</th><th></th></tr>
						<?php foreach($list_result as $row): ?>
						<tr>
							<td><a href='<?php echo base_url("/admin_product/list_allergen/detail/{$row->id}") ?>'><?php echo html_escape($row->name) ?></a></td>
							<td><?php echo $row->description ?></td>
							<td><?php if($row->icon):?><img src='<?php echo base_url("images/icon/{$row->icon}") ?>' width='15' height='15'><?php endif;?></td>
							<td><a class='edit' href='<?php echo base_url("/admin_product/edit_allergen/{$row->id}") ?>'>変更</a></td>
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	
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
			<?php echo form_open_multipart() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='template_name'>アレルゲン名</label></th>
						<td><input type='text' id='name' name='name' value='<?php echo $form_data->name ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='description'>説明</label></th>
						<td>
							<textarea name='description' rows='10' cols='60'><?php echo $form_data->description ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for='icon'>表示画像</label></th>
						<td><input type='file' id='icon' name='icon' size='70' maxlength='70'></td>
					</tr>
<?php if($form_data->icon):?>
					<tr>
						<th></th>
						<td><img src='<?php echo base_url() ?>images/<?php echo ICON_PATH . $form_data->icon ?>' width='70' height='70'></td>
<?php endif; ?>					
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_product/add_allergen') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>