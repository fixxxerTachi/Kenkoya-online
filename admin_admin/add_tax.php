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
			<h2>消費税率</h2>
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
				<table>
					<tr><th>消費税率</th><th>適用開始日時</th><th>適用終了日時</th></tr>
					<?php foreach($result as $row): ?>
					<tr>
						<td><?php echo $row->tax * 100 ?>%</td>
						<td><?php echo $row->start_datetime ?></td>
						<td><?php echo $row->end_datetime ?></td>
						<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_tax/{$row->id}") ?>'>変更</a></td>
						<td><a class='edit' onclick='del_confirm("<?php echo $row->tax * 100 ?>%" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
					<?php endforeach;?>
				</table>
			<?php else: ?>
				<p>登録されていません</p>
			<?php endif; ?>
		</div>
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
			<table class='detail'>
				<?php echo form_open() ?>
					<tr>	
						<th>消費税率</th><td><input type='text' name='tax' id='tax' value='<?php echo $form_data->tax ?>'>%</td>
					</tr>
<?php  $start_date = new DateTime($form_data->start_datetime) ?>
<?php  $end_date = new DateTime($form_data->end_datetime) ?>
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
						<th class='no-border'></th><td><input type='submit' name='submit' value='登録'></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm('「' + template_name + '」' + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_tax") ?>' + '/' + id;
		return false;
	}
}
</script>
</body>
</html>