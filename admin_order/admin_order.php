<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<div>
			<?php echo form_open() ?>
			<p>
				本日の受注リストをダウンロードします。
			</p>
			<div id='list'>
				<table class='list'>
					<?php if(count($result) > 0):?>
					<tr>
						<th>注文番号</th><th>顧客コード</th><th>注文者</th><th>商品コード</th><th>商品名</th><th>売価</th><th>購入数量</th><th>注文日</th><th>配達予定日</th>
					</tr>
					<?php foreach($result as $row):?>
						<tr>
							<td><?php echo $row['order_number'] ?></td>
							<td><?php echo $row['code'] ?></td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['product_code'] ?></td>
							<td><?php echo $row['product_name'] ?></td>
							<td><?php echo $row['sale_price'] ?></td>
							<td><?php echo $row['quantity'] ?></td>
						<?php $date = new DateTime($row['create_date'])?>
							<td><?php echo $date->format('Y/m/d') ?></td>
							<td><?php echo format_date($row['delivery_date'],'日付指定なし')?></td>
						</tr>		
					<?php endforeach;?>
					<?php else: ?>
						<p class='error'>表示できるデータがありません</p>
					<?php endif; ?>
				</table>
			</div>
			<?php if(count($result) > 0):?>
			<p><input type='submit' value='csvダウンロード' name='submit'  id='submit'></p>
			<p><a href='<?php echo site_url('/admin_order/download') ?>'>受注リストに戻る</a></p>
			<?php endif; ?>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>
<?php echo '<pre>' . print_r($result) . '</pre>';?>