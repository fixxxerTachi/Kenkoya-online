<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<h2><?php echo $h2title ?></h2>
			<?php echo form_open_multipart() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='image'>画像</label></th>
						<td><input type='file' id='image' name='image'></td>
					</tr>
					<tr>
						<th><label for='image_name'>画像名</label></th>
						<td><input type='text' name='image_name' id='image_name' value='<?php echo $form_data->image_name ?>'>
						<?php if(!empty($form_data->image_name)):?>
						<img src='<?php echo base_url() . MAINVISUAL_IMAGE_PATH . $form_data->image_name ?>' width='100' height='80'></td>
						<?php endif;?>
					</tr>
					<tr>
						<th><label for='url'>URL</label></th>
						<td><input type='checkbox' name='inside_url' id='inside_url' value=1 <?php if($form_data->inside_url == 1){ echo 'checked=checked';} ?>'><label for='inside_url'>内部コンテンツ <?php echo base_url() ?></label><br><input type='text' name='url' id='url' value='<?php echo $form_data->url ?>' size='70'></td>
					</tr>
					<tr>
						<th><label for='description'>画像の説明</label></th>
						<td><input type='text' id='description' name='description' value='<?php echo $form_data->description ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='thumb_image'>サムネイル画像</label></th>
						<td><input type='file' id='thumb_image' name='thumb_image'></td>
					</tr>
					<tr>
						<th><label for='thumb_image_name'>サムネイル画像名</label></th>
						<td>
							<input type='text' name='thumb_image_name' id='thumb_image_name' value='<?php echo $form_data->thumb_image_name ?>' width='100' height='38'>
							<?php if(!empty($form_data->thumb_image_name)):?>
							<img src="<?php echo base_url(MAINVISUAL_IMAGE_PATH . $form_data->thumb_image_name) ?>" width='100' height='38'>
							<?php endif;?>
						</td>
					</tr>
					<tr>
						<th><label for='thumb_image_description'>サムネイル画像の説明</label></th>
						<td><input type='text' id='thumb_image_description' name='thumb_image_description' value='<?php echo $form_data->thumb_image_description ?>' size='70' maxlength='70'></td>
					</tr>
<?php $start_date = new DateTime($form_data->start_datetime)?>
<?php $end_date = new DateTime($form_data->end_datetime) ?>
					<tr>
						<th><label for='start_date'>掲載開始日時</label></th>
						<td><input type='text' id='start_date' name='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>日
						<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'));?>時</td>
					</tr>
					<tr>
						<th><label for='end_date'>掲載終了日時</label></th>
						<td><input type='text' id='end_date' name='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>日
						<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'));?>時</td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_contents/add_mainvisual') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
</script>
</body>
</html>