<?php echo include __DIR__.'/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
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
		<div class='row'>
		<?php echo form_open('',array('class'=>'col s12')) ?>
			<div class='row'>
					<div class='input-field col s12'>
						<input type='text' id='title' name='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'>
						<label for='title'>題名</label>
					</div>
					<div class='input-field col s12'> 
						<label for='contents'>内容</label></th>
						<textarea class='materialize-textarea' name='contents' rows='20' cols='60'><?php echo $form_data->contents ?></textarea>
					</div>
			</div>
			<div class='row'>
					<div class='input-field col s6'>
						<label for='start_date'>掲載開始日時</label>
						<?php $start_date = new DateTime($form_data->start_datetime) ?>
						<?php $end_date = new DateTime($form_data->end_datetime) ?>
						<input type='text' name='start_date' id='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>
					</div>
					<div class='input-field col s5'>
						<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'),"class='browser-default'");?>
					</div>
					<div class='col s1'>時</div>
			</div>
			<div class='row'>
					<div class='input-field col s6'>
						<label for='end_date'>掲載終了日時</label>
						<input type='text' name='end_date' id='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>
					</div>
					<div class='input-field col s5'>
						<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'),"class='browser-default'");?>
					</div>
					<div class='col s1'>時</div>
					<div class='input-field col s12'>
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