<?php include __DIR__ . '/../templates/meta.php' ?>
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
			<?php if(!empty($list_category)):?>
			<?php endif;?>
			<form method='post'>
			<table class='detail'>
				<tr>
					<th><label for='category_id'>カテゴリ</label></th><td colspan='3'><?php echo form_dropdown('category_id',$list_category,$form_data->category_id,false,'id="category_id"') ?></td>
				</tr>
				<tr>
					<th><label for='code'>商品番号</label></th><td><input type='text' name='code' id='code' value='<?php echo $form_data->code ?>'></td>
					<th><label for='product_name'>商品名</label></th><td><input type='text' id='product_name' name='product_name' value='<?php echo $form_data->product_name ?>'></td>
				</tr>
				<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
			</table>
			<?php if(!empty($result) > 0):?>
				<p class='links'><?php echo $links ?></p>
				<table class='list'>
					<tr><th>画像</th><th>商品番号</th><th>商品コード</th><th>枝番</th><th>商品名</th><th>販売価格</th><th></th><th></th></tr>
					<?php foreach($result as $row): ?>
					<tr>
						<td><img src='<?php echo base_url(show_image($row->product_code))?>' width='25' height='25'></td>
						<td><?php echo $row->code?></td>
						<td><?php echo $row->product_code ?></td>
						<td><?php echo $row->branch_code ?></td>
						<td>
							<a href='<?php echo base_url("/admin_advertise/detail_product/{$row->id}/{$ad_id}") ?>'><?php echo mb_strimwidth($row->product_name,0,30,'...') ?></a>
						</td>
						<td><?php echo number_format($row->sale_price) ?>円</td>
						<td><span <?php if($row->on_sale == 0):?>style='color:orange'<?php endif;?>><?php echo $on_sale[$row->on_sale] ?></span></td>
						<td><a class='edit' href='<?php echo base_url("/admin_advertise/edit_product/{$row->id}/{$ad_id}") ?>'>変更</a></td>
					</tr>
					<?php endforeach;?>
					<tr class='no-back'><td></td><td><a class='edit' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>リストに戻る</a></td>
				</table>
			<?php else: ?>
				<p>登録されていません</p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>