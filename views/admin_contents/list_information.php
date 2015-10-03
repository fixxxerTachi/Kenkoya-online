<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet" />
<script src="<?php echo base_url() ?>js/lightbox.min.js"></script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="container">
					<h2><span class='logo_pink'>information</span> <?php echo $h2title ?></h2>
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
						<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
						<tr><th>タイトル</th><th>掲載開始日時</th><th>掲載終了日時</th><th>掲載順</th><th></th><th></th><th></th></tr>
						<?php foreach($result as $row):?>
							<tr>
								<td><a href='<?php echo site_url("admin_contents/list_information/{$row->id}") ?>'><?php echo $row->title ?></a></td>
								<td><?php echo date('Y/m/d日H時',strtotime($row->start_datetime));?></td>
								<td><?php echo date('Y/m/d日H時',strtotime($row->end_datetime));?></td>
								<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
								<?php $option = "id='showflag_{$row->id}'";?>
								<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
								<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_information/{$row->id}") ?>'>変更</a></td>
								<td><a class='edit' onclick='del_confirm("<?php echo $row->title ?>" , <?php echo $row->id ?>)'>削除</a></td>
								</tr>
						<?php endforeach;?>
							<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td></tr>
						</table>
					</form>
					<?php else:?>
					<p>登録されていません</p>
					<?php endif;?>
				</div>
				<?php if($show_detail):?>
				<div class='container'>
					<h2><span class='logo_pink'>information</span> <?php echo $h2title ?></h2>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
							<tr>
								<th>タイトル</th>
								<td><?php echo $show_detail->title ?></td>
							</tr>
							<tr>
								<th>内容</th>
								<td><?php echo $show_detail->content ?></td>
							</tr>
						<?php if(!empty($show_detail->image_name)):?>
							<tr>
								<th>画像</th>
								<td><img src='<?php echo base_url() . INFORMATION_IMAGE_PATH . $show_detail->image_name ?>'>
									<?php echo $show_detail->image_name ?></td>
							</tr>
							<tr>
								<th>画像の説明</th>
								<td><?php echo $show_detail->image_description ?></td>
							</tr>
						<?php endif; ?>
							<tr>
								<th>掲載開始日</th>
								<td><?php echo $show_detail->start_datetime ?></td>
							</tr>
							<tr>
								<th>掲載終了日</th>
								<td><?php echo $show_detail->end_datetime ?></td>
							</tr>
						</table>
					</form>
				</div>
				<?php else:?>
				<div class='container'>
					<h2><span class='logo_pink'>information</span> おしらせ追加</h2>
					<?php echo form_open_multipart() ?>
						<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='title'>タイトル</label></th>
								<td><input type='text' name='title' id='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='content'>内容</label>
								<td><textarea name='content' id='content' cols='60' rows='5'><?php echo $form_data->content ?></textarea></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='url'>リンク先URL</label>
								<td>*urlを登録しない場合せお知らせ専用のページを表示します<br><?php echo site_url() ?><input type='text' name='url' id='url' value='<?php echo $form_data->url ?>' size='70'></td>
							</tr>	
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='image'>画像</label></th>
								<td><input type='file' id='image' name='image'></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='image_name'>画像名</label></th>
								<td><input type='text' name='image_name' id='image_name' value='<?php echo $form_data->image_name ?>'>
								<?php if(!empty($form_data->image_name)):?>
								<img src='<?php echo base_url() . INFORMATION_IMAGE_PATH . $form_data->image_name ?>' width='100' height='80'></td>
								<?php endif;?>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='image_description'>画像の説明</label></th>
								<td><input type='text' id='image_description' name='image_description' value='<?php echo $form_data->image_description ?>' size='70' maxlength='70'></td>
							</tr>
		<?php $start_date = new DateTime($form_data->start_datetime)?>
		<?php $end_date = new DateTime($form_data->end_datetime) ?>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='start_date'>掲載開始日時</label></th>
								<td><input type='text' id='start_date' name='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>日
								<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'));?>時</td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='end_date'>掲載終了日時</label></th>
								<td><input type='text' id='end_date' name='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>日
								<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'));?>時</td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px'><a class='edit_back' href='<?php echo site_url('admin_contents/list_information') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
				<?php endif;?>
			</div>
		</main>
	</div>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_information") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_information') ?>' + '/' + str_id + '/' + data ;
});
</script>
</body>
</html>