<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2>売れ筋商品リスト</h2>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
<?php if(count($result) > 0):?>
			<form method='post' action='<?php echo site_url('admin_contents/add_recommend') ?>'>
			<table>
			<tr><th>画像</th><th>広告名</th><th>商品コード</th><th>商品名</th><th>価格</th><th>並び順</th></tr>
	<?php foreach($result as $row):?>
			<tr>
		<?php if($row->product_code):?>
			<?php if($row->p_image_name):?>
				<td><img src='<?php echo base_url() . PRODUCT_IMAGE_PATH . $row->p_image_name ?>' width='60' height='80'></td>
			<?php else:?>
				<td></td>
			<?php endif;?>
				<td>-</td>
				<td><?php echo $row->p_product_code ?></td>
				<td><a href='<?php echo site_url("admin_contents/detail_top10/{$row->id}") ?>'><?php echo $row->p_product_name ?></a></td>
				<td><?php echo $row->p_sale_price ?></td>
		<?php endif; ?>
		<?php if($row->advertise_id):?>
			<?php if($row->ad_pro_image_name): ?>
				<td><img src='<?php echo base_url() . AD_PRODUCT_IMAGE_PATH . $row->ad_pro_image_name ?>' width='60' height='80'></td>
			<?php else: ?>
				<td></td>
			<?php endif; ?>
				<td><?php echo $row->ad_title ?></td>
				<td><?php echo $row->ad_code ?></td>
				<td><a href='<?php echo site_url("admin_contents/detail_top10/{$row->id}") ?>'><?php echo $row->ad_pro_product_name ?></a></td>
				<td><?php echo $row->ad_pro_sale_price ?></td>
		<?php endif;?>
				<td><input type='text' name='sort_order<?php echo $row->id ?>' value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
				<td><a class='edit' href='<?php echo site_url("admin_contents/edit_top10/{$row->id}") ?>'>編集</a></td>
				<td><a class='edit' onclick='del_confirm("" , <?php echo $row->id ?>)'>削除</a></td>
			</tr>
	<?php endforeach;?>
			<tr><td></td><td></td><td></td><td></td><td></td><td><input type='submit' name='change_order' value='順序変更'></td></tr>
			</table>
			</form>
<?php else:?>
			<p>登録されていません</p>
<?php endif; ?>
		</div>
		<div class='contents'>
		<h2><?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<table table class='detail' cellpadding='0' cellspacing='10'>
			<?php if($form_data->product_code):?>
				<tr><th>商品コード</th><td><?php echo $form_data->product_code ?></td></tr>
				<tr><th>商品名</th><td><?php echo $form_data->p_product_name ?></td></tr>
				<?php if($form_data->p_image_name):?>
				<tr><th>画像</th><td><img src='<?php echo base_url() . 'images/' . IMAGE_PATH . $form_data->p_image_name ?>' width='60' height='80'></td></tr>
				<?php endif;?>
			<?php endif;?>
			<?php if($form_data->advertise_id):?>
				<tr><th>対象広告</th><td><?php echo $form_data->ad_title ?></td></tr>
				<tr><th>商品名</th><td><?php echo $form_data->ad_pro_product_name ?></td></tr>
				<?php if($form_data->ad_pro_image_name):?>
				<tr><td><th>画像</th><td><img src='<?php echo base_url() . AD_PRODUCT_IMAGE_PATH . $form_data->ad_pro_image_name ?>' width='60' height='80'></td></tr>
				<?php endif;?>
			<?php endif;?>
				<tr><th>コメント</th><td><?php echo $form_data->comment ?></td></tr>
			</table>
	
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_recommend") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>