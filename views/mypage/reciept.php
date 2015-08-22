<?php include __DIR__ . '/../../libraries/define_flag.php' ?>
<!doctype html>
<html lang='ja'>
<head>
<meta charset='utf-8'>
<link rel='stylesheet' href='<?php echo base_url('css/mypage-receipt.css') ?>' media='screen'>
<link rel='stylesheet' href='<?php echo base_url('css/mypage-print.css') ?>' media='print'>
</head>
<body>
<div id='wrapper'>
<div id="container">
	<div id='header' class='clearfix'>
		<ul>
			<li><img src="<?php echo base_url('images/kenkoya_logo.jpg')?>" width="198" height="80" alt="宅配スーパー健康屋"></li>
			<li><a class='button' href='javascript:void(0)' onclick="window.print()">印刷する</a></li>
			<li><a class='button' href='javascript:void(0)' onclick="window.close()">閉じる</a></li>
		</ul>
	</div>
	<div id="container-inner">
		<div class='content'>
			<h2><?php echo $h2title ?></h2>
			<?php if($order): ?>
			<div>
				<?php $create_date = new DateTime($order->create_date)?>
				<?php $today = new DateTime() ?>
				<div id='info' class='clearfix'>
					<table class='order_info'>
						<tr><th>発行日</th><td><?php echo $today->format('Y年m月d日') ?></td></tr>
						<tr><th>ご注文日</th><td><?php echo $create_date->format('Y年m月d日') ?></td></tr>
						<tr><th>ご注文番号</th><td><?php echo $order->order_number ?></td></tr>
					</table>
					<table class='order_info'>
						<tr id='price'><th>合計請求金額</th><td><?php echo number_format((int)$order->total_price + (int)$order->tax + (int)$order->delivery_charge) ?>円</td></tr>
					</table>
				</div>
				<div>
					<table id='detail'>
						<caption>内 訳</caption>
					<?php foreach($details as $row):?>
						<tr>
							<td><?php echo $row->product_name ?></td>
							<td><?php echo $row->quantity ?>個</td>
							<td><?php echo number_format($row->sale_price * $row->quantity) ?>円</td>
						</tr>
				<?php endforeach;?>
						<tr><td>送料  :</td><td></td><td><?php echo number_format($order->delivery_charge)?>円</td></tr>
						<tr><td>消費税:</td><td></td><td><?php echo number_format($order->tax) ?>円</td></tr>
					</table>
				</div>
			</div>
			<?php else: ?>
			<p class='error'>購入履歴がありません</p>
			<?php endif; ?>	
		</div>
	</div>
</div>
</div>
	<div id='myinfo'>
		<p id='shopname'><?php echo $shop_info->shop_name ?></p>
		<p id='url'>url: <?php echo site_url(); ?></p>
	</div>
</body>
</html>