<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<script>
window.onload=function(){
	var url = location.href;
	console.log(url);
	var arr = url.split('/');
	if(arr[5]){
		$('#form_table').css({
			'padding': '10px',
			'border':'2px solid #FAAC58',
			'border-radius':'5px',
		});
	}
}
</script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
					<div class='mdl-cell mdl-cell--6-col'>
						<h2><span class='logo_pink'>tax</span> 消費税率</h2>
						<?php if($result): ?>
							<table class='mdl-data-table mdl-js-data-table'>
								<tr><th>消費税率</th><th>適用開始日時</th><th>適用終了日時</th></tr>
								<?php foreach($result as $row): ?>
								<tr>
									<td><?php echo $row->tax * 100 ?>%</td>
									<td><?php echo $row->start_datetime ?></td>
									<td><?php echo $row->end_datetime ?></td>
									<td><a class='edit' href='<?php echo site_url("/admin_admin/add_tax/{$row->id}") ?>'>変更</a></td>
									<td><a class='edit' onclick='del_confirm("<?php echo $row->tax * 100 ?>%" , <?php echo $row->id ?>)'>削除</a></td>
								</tr>
								<?php endforeach;?>
							</table>
						<?php else: ?>
							<p>登録されていません</p>
						<?php endif; ?>
					</div>
					<div class='mdl-cell mdl-cell--6-col'>
						<h2><span class='logo_pink'>tax</span> <?php echo $h2title ?></h2>
						<?php if(!empty($message)):?>
						<p class='message'><?php echo $message ?></p>
						<?php endif;?>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message; ?></p>
						<?php endif; ?>
						<?php if(!empty($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>
						<table class='detail' id='form_table'>
							<?php echo form_open() ?>
								<tr>
									<?php $tax = $form_data->tax * 100 ?>
									<th>消費税率</th><td><input type='text' name='tax' id='tax' value='<?php echo $tax ?>'>%</td>
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
									<th class='no-border'></th>
									<td>
			<?php if(!$edit_flag):?>
										<input type='submit' name='submit' value='登録' class='submit_button'>
			<?php else:?>
										<input type='submit' name='edit_submit' value='変更' class='submit_button'>
			<?php endif;?>
										<a class='edit_back' href='<?php echo site_url('admin_admin/add_tax')?>'>戻る</a>
									</td>
								</tr>
							</form>
						</table>
					</div>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm('「' + template_name + '」' + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_tax") ?>' + '/' + id;
		return false;
	}
}
</script>

</html>