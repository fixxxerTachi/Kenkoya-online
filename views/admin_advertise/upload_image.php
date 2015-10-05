<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/lightbox.min.js"></script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<h2><span class='logo_pink'>advertise</span> <?php echo $h2title ?></h2>
				<?php if(!empty($message)):?>
				<p><?php echo $message ?></p>
				<?php endif;?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<div class='container'>
				<?php if(count($image_result) > 0):?>
				<?php $counter = 0;?>
					<div class='clearfix'>
					<?php foreach($image_result as $row):?>	
						<div class='image_wrapper'>
							<ul>
								<li><a href='<?php echo base_url() .AD_IMAGE_PATH. $row->image_name?>' data-lightbox='<?php echo $row->image_name ?>'><img src='<?php echo base_url() .AD_IMAGE_PATH. $row->image_name?>' width='100' height='70'></a></li>
								<li><?php echo $row->start_page ?>p<?php if(!empty($row->end_page)): ?>~<?php echo $row->end_page ?>p<?php endif;?></li>
								<li>
									<a href='<?php echo base_url("/admin_advertise/edit_image/{$row->id}/{$ad_result->id}")?>' class='btn'>修正</a>
									<a href='javascript:void(0)' class='btn' onclick='del_confirm("P<?php echo $row->start_page ?>~",<?php echo $row->id ?>, <?php echo $ad_result->id ?>);'>削除</a>
								</li>
							</ul>
						</div>
					<?php $counter++; ?>
					<?php endforeach; ?>
						</div>
					<?php else:?>
						<p>登録されていません</p>
						<?php endif;?>
				</div>
				<div class='container'>
				<?php echo form_open_multipart() ?>
				<input type='hidden' name='advertise_id' value='<?php echo $ad_result->id ?>'>
				<table class='mdl-data-table mdl-js-data-table'>
					<tr>
						<th>画像</th>
						<td><input type='file' name='image'>jpg形式 1024 x 716</td>
					</tr>
					<tr>
						<th>画像名</th>
						<td>
							<input type='text' name='image_name' size='20' maxlength='20' value='<?php echo $form_data->image_name ?>'>例)yotuba05　拡張子不要<br>同じチラシに複数の画像を登録する際は統一してください
						</td>
					<tr>
						<th>ページ範囲</th>
						<td>
							<input type='text' name='start_page' size='2' maxlength='2' value='<?php echo $form_data->start_page ?>'> ~ <input type='text' name='end_page' size='2' maxlength='2' value='<?php echo $form_data->end_page ?>'>
							&nbsp;注)表紙は　1 ～ 1　としてください。
						</td>
					</tr>
					<tr>
						<th>画像の説明</th>
						<td><textarea name='description' rows='10' cols='40'><?php echo $form_data->description ?></textarea>リンクのtitle属性と画像のalt属性になります。カテゴリなど</td>
					</tr>
					<tr><th class='no-border'></th><td><input type='submit' value='登録' name='submit' style='margin-right:30px;'><a class='edit_back' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>戻る</a></td></tr>
				</table>
				</form>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(template_name , id, result_id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("admin_advertise/delete_image") ?>' + '/' + id + '/' + result_id
		return false;
	}
}
</script>
</html>