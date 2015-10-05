<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="container">
					<h2><?php echo $h2title ?></h2>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>	
					<form action='' method='post' enctype='multipart/form-data'>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th><label for='image'>画像</label></th>
								<td><input type='file' id='image' name='image'></td>
							</tr>
							<tr>
								<th><label for='image_name'>画像名</label></th>
								<td><input type='text' name='image_name' id='image_name' value='<?php echo $form_data->image_name ?>'>
								<?php if(!empty($form_data->image_name)):?>
								<img src='<?php echo base_url() . BANNER_IMAGE_PATH . $form_data->image_name ?>' width='100' height='80'></td>
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
								<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px;'><a class='edit_back' href='<?php echo site_url('admin_contents/add_banner') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>