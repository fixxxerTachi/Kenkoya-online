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
						<th><label for='title'>タイトル</label></th>
						<td><input type='text' name='title' id='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'></td>
					</tr>
					<tr>
						<th><label for='content'>内容</label>
						<td><textarea name='content' id='content' cols='60' rows='5'><?php echo $form_data->content ?></textarea></td>
					</tr>
					<tr>
						<th><label for='url'>リンク先URL</label>
						<td>urlを登録しない場合せお知らせ専用のページを表示します<br><?php echo site_url() ?><input type='text' name='url' id='url' value='<?php echo $form_data->url ?>' size='70'></td>
					</tr>	
					<tr>
						<th><label for='image'>画像</label></th>
						<td><input type='file' id='image' name='image'></td>
					</tr>
					<tr>
						<th><label for='image_name'>画像名</label></th>
						<td><input type='text' name='image_name' id='image_name' value='<?php echo $form_data->image_name ?>'>
						<?php if(!empty($form_data->image_name)):?>
						<img src='<?php echo base_url() . INFORMATION_IMAGE_PATH . $form_data->image_name ?>' width='100' height='80'></td>
						<?php endif;?>
					<tr>
						<th><label for='image_description'>画像の説明</label></th>
						<td><input type='text' id='image_description' name='image_description' value='<?php echo $form_data->image_description ?>' size='70' maxlength='70'></td>
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
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_contents/list_information') ?>'>戻る</a></td>
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
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_mainvisual") ?>' + '/' + id;
		return false;
	}
}
</script>
</body>
</html>