<?php include __DIR__ . '/../templates/meta_front.php' ?>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
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
				<p><?php if(isset($message)) echo $message ?></p>
				<form action='' method='post'>
					<table class='detail'>
						<?php if(!empty($result->image_name)): ?>
						<td><img src='<?php echo base_url() ?><?php echo AD_PRODUCT_IMAGE_PATH ?><?php echo $result->image_name ?>' width='50' height='50'></td>
						<?php endif; ?>
						<tr>
							<th>商品番号</th>
							<td><?php echo $result->code ?></td>
						<tr>
							<th>商品コード</th>
							<td><?php echo $result->product_code ?></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><?php echo $result->product_name ?></td>
						</tr>
						<tr>
							<th>規格</th>
							<td><?php echo $result->size ?></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><?php echo $result->sale_price ?></td>
						</tr>
						<tr>
							<th>賞味期限</th>
							<td><?php echo $result->freshness_date ?></td>
						</tr>
						<tr>
							<th>アレルゲン</th>
							<td><?php echo $result->allergen ?></td>
						</tr>
						<form method='post'>
						<tr>
							<th>購入個数</th>
							<td><?php echo form_dropdown('quantity',$select_quantity,$quantity) ?></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo site_url('front_advertise/list_product/' . $result->advertise_id )?>'>登録商品リストへ戻る</a></td>
							<td><input type='submit' name='submit' value='カートに入れる'></a></td>
						</tr>
						</form>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>