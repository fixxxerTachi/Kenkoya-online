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
			<?php echo form_open() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='title'>タイトル</label></th>
						<td><input type='text' id='title' name='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='contents'>内容</label></th>
						<td>
							<textarea name='contents' rows='10' cols='60'><?php echo $form_data->contents ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for='start_date'>掲載開始日時</label></th>
						<?php $start_date = new DateTime($form_data->start_datetime) ?>
						<?php $end_date = new DateTime($form_data->end_datetime) ?>

						<td>
							<input type='text' name='start_date' id='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>日
							<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'));?>時
						</td>
					</tr>
					<tr>
						<th><label for='end_date'>掲載終了日時</label></th>
						<td>
							<input type='text' name='end_date' id='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>日
							<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'));?>時
						</td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_customer/list_info') ?>'>戻る</a></td>
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