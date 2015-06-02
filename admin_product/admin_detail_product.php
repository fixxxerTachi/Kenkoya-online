<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<p><?php if(isset($message)) echo $message ?></p>
				<form aciton='' method='post'>
					<table class='detail'>
						<tr>
							<th>商品コード</th>
							<td><?php echo $result->product_code ?></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><?php echo $result->branch_code ?></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><?php echo $result->product_name ?></td>
						</tr>
						<tr>
							<th>略名</th>
							<td><?php echo $result->short_name ?></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><?php echo $result->sale_price ?></td>
						</tr>
						<tr>
							<th>仕入単価</th>
							<td><?php echo $result->cost_price ?></td>
						</tr>
						<?php if(!empty($result->show_name)):?>
						<tr>
							<th>表示名</th>
							<td><?php echo $result->show_name ?></td>
						</tr>
						<?php endif; ?>
						<?php if(!empty($result->image_name)):?>
						<tr>
							<th>商品画像</th>
							<td><img src='<?php echo base_url() ?>images/<?php echo IMAGE_PATH ?><?php echo $result->image_name ?>' width='100' height='100'></td>
						</tr>
						<tr>
							<th>画像の説明</th>
							<td><?php echo $result->image_description ?></td>
						</tr>
						<?php endif; ?>
						<?php if(!empty($allergen)):?>
						<tr>
							<th>アレルゲン</th>
							<td>
							<?php foreach($allergen as $a) :?>
								<?php echo $a->allergen_name ?>&nbsp;
							<?php endforeach;?>
							</td>
						</tr>
						<?php endif; ?>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo site_url('/admin_product/list_product')?>'>登録商品リストへ戻る</a></td>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>