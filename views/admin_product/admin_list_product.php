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
			<?php echo form_open() ?>
			<table class='detail'>
				<tr>
					<th><label for='category_name'>商品分類名</label></th><td><?php echo form_dropdown('category_name',$list_category,$form_data->category_name) ?></td>
					<th><label for='product_code'>商品コード</label></th><td><input type='text' name='product_code' id='product_code' value='<?php echo $form_data->product_code ?>'></td>
					<th><label for='product_name'>商品名</label></th><td><input type='text' name='product_name' id='product_name' value='<?php echo $form_data->product_name ?>'></td>
				</tr>
				<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
			</table>
			</form>
			<?php if(!empty($result) > 0):?>
				<?php if(!empty($links)):?><p class='links'><?php echo $links ?></p><?php endif;?>
				<table class='list'>
					<tr><th>画像</th><th>商品コード</th><th>枝番</th><th>カテゴリ</th><th>商品名</th><th>販売価格</th><th>原価</th><th></th><th></th></tr>
					<?php foreach($result as $row): ?>
					<tr>
						<?php if(!empty($row->image_name)): ?>
						<td><img src='<?php echo base_url() ?>images/<?php echo IMAGE_PATH ?><?php echo $row->image_name ?>' width='25' height='25'></td>
						<?php else:?>
						<td></td>
						<?php endif;?>
						<td><?php printf("%05d",$row->product_code) ?></td>
						<td><?php echo $row->branch_code ?></td>
						<td><?php echo $row->category_name ?></td>
						<td>
							<a href='<?php echo site_url("/admin_product/detail_product/{$row->id}") ?>'><?php echo $row->product_name ?></a>
						</td>
						<td><?php echo number_format($row->sale_price) ?>円</td>
						<td><?php echo number_format($row->cost_price) ?>円</td>
						<td><span <?php if($row->on_sale == 0):?>style='color:pink'<?php endif;?>><?php echo $on_sale[$row->on_sale] ?></span></td>
						<td><a class='edit' href='<?php echo site_url("/admin_product/edit_product/{$row->id}") ?>'>変更</a></td>
					<?php endforeach;?>
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