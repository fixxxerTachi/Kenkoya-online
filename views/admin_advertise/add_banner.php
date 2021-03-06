<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='container'>
					<h2><span class='logo_pink'>banner</span> <?php echo $h2title ?></h2>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>	
					<?php echo form_open_multipart() ?>
						<table class='mdl-data-table mdl-js-data-table'>
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
								<td><input type='text' id='end_date' name='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>日
								<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'));?>時</td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='登録する'></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="container">
					<h2><span class='logo_pink'>banner</span> バナー登録リスト</h2>
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
					<?php echo form_open() ?>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr><th>画像</th><th>掲載開始日時</th><th>掲載終了日時</th><th>URL</th><th>掲載順</th><th></th><th></th><th></th></tr>
					<?php foreach($result as $row):?>
						<tr>
							<td><a href='<?php echo base_url() . BANNER_IMAGE_PATH . $row->image_name ?>' id='image_modal' data-lightbox='<?php echo $row->image_name ?>'><img src='<?php echo base_url() . BANNER_IMAGE_PATH . $row->image_name ?>' width='100' height='80'></a></td>
		<?php $start_date = new DateTime($row->start_datetime)?>
		<?php $end_date = new DateTime($row->end_datetime) ?>
							<td><?php echo $start_date->format('Y/m/d H-i-s');?></td>
							<td><?php echo $end_date->format('Y/m/d H-i-s');?></td>
							<td><?php echo $row->url ?></td>
							<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
							<?php $option = "id='showflag_{$row->id}'";?>
							<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_banner/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->image_name ?>" , <?php echo $row->id ?>)'>削除</a></td>
							</tr>
					<?php endforeach;?>
						<tr class='no-back'><td></td><td></td><td></td><td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td></tr>
					</table>
					</form>
					<?php else:?>
					<p>登録されていません</p>
					<?php endif;?>
				</div>
			</div>
		</main>
	</div>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_banner") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_banner') ?>' + '/' + str_id + '/' + data ;
});
</script>
</body>
</html>