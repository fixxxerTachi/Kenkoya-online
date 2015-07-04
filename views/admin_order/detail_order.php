<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>		
				<?php if($result): ?>
					<table>
					<caption>
						<tr><th>注文番号</th><td><?php echo $result[0]->order_number ?></td></tr>
						<tr><th>お名前</th><td><?php echo $result[0]->name ?></td></tr>
						<tr><th>注文日</th><td><?php echo $result[0]->create_date ?></td></tr>						
						<tr><th>商品コード</th><th>商品名</th><th>売価</th><th>数量</th><th>配達コース</th><th>配達日</th><th>キャンセル</th><th></th><th></th></tr>
					</caption>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo $row->product_code ?></td>
							<td><?php echo $row->product_name ?></td>
							<td><?php echo $row->sale_price ?></td>
							<td><?php echo $row->quantity ?></td>
							<td><?php echo $row->cource_name ?></td>
							<td><?php echo $row->deliver_date ?></td>
							<td><?php echo $order_status[$row->status_flag] ?></td>
							<td><a class='edit' href='<?php echo base_url("/admin_order/edit_order/{$row->order_id}") ?>'>変更</a></td>
						</tr>
						<?php endforeach;?>
					</table>
					<p><a class='edit' href='<?php echo base_url('/admin_order/list_order') ?>'>リストに戻る</a></p>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>