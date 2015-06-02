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
			<p><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
			<?php if(count($result) > 0):?>
				<table class='list'>
					<tr><th>ID</th><th>タイトル</th><th>販売開始</th><th>販売終了</th><th>登録画像数</th><th>登録商品数</th><th></th><th></th><th></th><th></th></tr>
<?php foreach($result as $row):?>
					<tr>
<?php $s_datetime = new DateTime($row->start_datetime)?>
<?php $e_datetime = new DateTime($row->end_datetime)?>
						<td><?php echo $row->id ?></td>
						<td><?php echo $row->title ?></td>
						<td><?php echo $s_datetime->format('Y/m/d H')?>時</td>
						<td><?php echo $e_datetime->format('Y/m/d H')?>時</td>
						<td><?php echo $Advertise_image->count_advertise_image($row->id) ?> | <a href='<?php echo base_url("/admin_advertise/upload_image/{$row->id}")?>'>画像登録</a></td>
						<td><?php echo $Advertise_image->count_advertise_products($row->id)?> | <a href='<?php echo base_url("/admin_advertise/upload_product/{$row->id}")?>'>商品登録</a></td>
						<td><a href='<?php echo base_url("/admin_advertise/upload_code/{$row->id}")?>'>商品コード登録</a></td>
						<td><a href='<?php echo base_url("/admin_advertise/edit_advertise/{$row->id}")?>'>チラシデータ修正</a></td>
						<td><a href='<?php echo base_url("/admin_advertise/list_product/{$row->id}")?>'>登録商品リスト</a></td>
						<td><a class='edit' href='<?php echo base_url("/admin_advertise/delete_advertise/{$row->id}")?>'>削除</a></td>
					</tr>
					<?php endforeach;?>
				</table>
<?php else:?>
				<p>登録されていません</p>
<?php endif;?>
		</div>
		<div class='contents'>
			<?php echo form_open() ?>
				<table class='detail'>
					<tr>
						<th>チラシタイトル</th>
						<td><input type='text' name='title' value='<?php echo $form_data->title ?>'></td>
					</tr>
					<tr>
						<th>詳細内容</th>
						<td><textarea name='description' rows='2' cols='50'><?php echo $form_data->description ?></textarea>
					</tr>
					<tr>
						<th>掲載開始日時</th>
						<td>
							<input id='release_start' type='text' name='release_start_datetime' value='<?php echo $form_data->release_start_datetime ?>'>
							<?php echo form_dropdown('release_start_time',$hour_list,$form_data->release_start_time) ?>:00から
						</td>
					</tr>
					<tr>
						<th>掲載終了日時</th>
						<td>
							<input id='release_end' type='text' name='release_end_datetime' value='<?php echo $form_data->release_end_datetime ?>'>
							<?php echo form_dropdown('release_end_time',$hour_list,$form_data->release_end_time) ?>:00前に終了
						</td>
					</tr>
					<tr>
						<th>販売受付期間：開始</th>
						<td>
							<input type='text' name='start_datetime' id='start_datetime' value='<?php echo $form_data->start_datetime ?>'>
							<?php echo form_dropdown('start_time',$hour_list,$form_data->start_time) ?>:00から
						</td>
					</tr>
					<tr>
						<th>販売受付期間：終了</th>
						<td>
							<input type='text' name='end_datetime' id='end_datetime' value='<?php echo $form_data->end_datetime ?>'>
							<?php echo form_dropdown('end_time',$hour_list,$form_data->end_time) ?>:00前に終了
						</td>
					</tr>
			<!--
					<tr>
						<th>配達日：開始</th>
						<td>
							<input type='text' name='deliver_start' id='deliver_start' value='<?php echo $form_data->deliver_start ?>'>
						</td>
					</tr>
					<tr>
						<th>配達日：終了</th>
						<td>
							<input type='text' name='deliver_end' id='deliver_end' value='<?php echo $form_data->deliver_end ?>'>
						</td>
					</tr>
			-->
					<tr>
						<td></td>
						<td><input type='submit' name='submit' value='登録する'>
						<a class='edit_back' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
$('#start_datetime,#end_datetime,#deliver_start,#deliver_end,#release_start,#release_end').datepicker({
	dateFormat:'yy/mm/dd',
	monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    dayNames: ['日', '月', '火', '水', '木', '金', '土'],
    dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
});
</script>
</html>