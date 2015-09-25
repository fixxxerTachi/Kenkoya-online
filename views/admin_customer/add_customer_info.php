<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<script src='<?php echo base_url('js/datepicker-ja.js') ?>'></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="wrapper">
	<div class='container'>
		<h2><span class='logo_pink'>member</span> <?php echo $h2title ?></h2>
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
				<div class='row'>
					<div class='col s12'>
						<label for='title'>タイトル</label>
						<input type='text' id='title' name='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'>
					</div>
					<div class='col s12'> 
						<label for='contents'>内容</label></th>
						<textarea name='contents' rows='20' cols='60'><?php echo $form_data->contents ?></textarea>
					</div>
					<div class='col s12'>
						<label for='start_date'>掲載開始日時</label>
						<?php $start_date = new DateTime($form_data->start_datetime) ?>
						<?php $end_date = new DateTime($form_data->end_datetime) ?>
						<input type='text' name='start_date' id='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>
					</div>
					<div class='col s12'>
						<label for='start_time'>時</label><?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'),"id='start_time'");?>
					</div>
					<div class='col s12'>
						<label for='end_date'>掲載終了日時</label>
						<input type='text' name='end_date' id='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>
					</div>
					<div class='col s12'>
						<label for='end_time'>時</label><?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'),"id='end_time'");?>
					</div>
					<div class='col s12'>
						<input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_customer/list_info') ?>'>戻る</a>
					</div>
				</div>
			</form>
	</div>
</div>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
</script>
</body>
</html>