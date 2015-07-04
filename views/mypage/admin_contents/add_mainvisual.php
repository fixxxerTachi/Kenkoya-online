<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet">
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet" />
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>js/lightbox.min.js"></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
		<h2>メインビジュアル登録リスト</h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<?php if(count($result) > 0):?>
			<form method='post' action=''>
			<table class='list'>
			<tr><th>画像</th><th>掲載開始日時</th><th>掲載終了日時</th><th>url</th><th>掲載順</th><th></th><th></th><th></th></tr>
			<?php foreach($result as $row):?>
				<?php $s_date = new DateTime($row->start_datetime) ?>
				<?php $e_date = new DateTime($row->end_datetime)?>
				<tr>
					<td><a href='<?php echo base_url() . MAINVISUAL_IMAGE_PATH . $row->image_name ?>' id='image_modal' data-lightbox='<?php echo $row->image_name ?>'><img src='<?php echo base_url() . MAINVISUAL_IMAGE_PATH . $row->image_name ?>' width='100' height='80'></a></td>
					<td><?php echo $s_date->format('Y/m/d H:i:s');?></td>
					<td><?php echo $e_date->format('Y/m/d H:i:s');?></td>
					<td><?php echo $row->url ?></td>
					<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
					<?php $option = "id='showflag_{$row->id}'";?>
					<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
					<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_mainvisual/{$row->id}") ?>'>変更</a></td>
					<td><a class='edit' onclick='del_confirm("<?php echo $row->image_name ?>" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
			<?php endforeach;?>
				<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td></tr>
			</table>
			</form>
			<?php else:?>
			<p>登録されていません</p>
			<?php endif;?>
		</div>
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<form action='' method='post' enctype='multipart/form-data'>
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
<?php $start_date = new DateTime($form_data->start_datetime)?>
<?php $end_date = new DateTime($form_data->end_datetime) ?>
					<tr>
						<th><label for='start_date'>掲載開始日時</label></th>
						<td><input type='text' id='start_date' name='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>日
						<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'));?>時</td>
					</tr>
					<tr>
						<th><label for='end_date'>掲載終了日時</label></th>
						<td><input type='text' id='end_date' name='end_date' value='<?php echo $end_date->format('Y/m/d')?>'>日
						<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'));?>時前まで</td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
$('#start_date,#end_date').datepicker({
	dateFormat:'yy/mm/dd',
	monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    dayNames: ['日', '月', '火', '水', '木', '金', '土'],
    dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
});
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_mainvisual") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_mainvisual') ?>' + '/' + str_id + '/' + data ;
});
</script>
</body>
</html>