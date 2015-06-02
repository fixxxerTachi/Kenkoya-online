<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
<div id="container">
	<div id="body">
		<div class='contents'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($message)):?>
				<p class='message'><?php echo $message; ?></p>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php if(!$order->csv_flag == 1): ?>
					<form method='post'>
					<table >
						<?php $create_date = new DateTime($order->create_date)?>
						<?php $delivery_date = new DateTime($order->delivery_date) ?>
						<tr>
							<th rowspan='<?php echo $count ?>'>
								ご注文番号：<?php echo $order->order_number ?><br>
								注文日：<?php echo $create_date->format('Y年m月d日') ?><br>
								配達日：<?php echo $delivery_date->format('Y年m月d日') ?>
							</th>
					<?php if($order->image_name):?><td><img src='<?php echo base_url(AD_PRODUCT_IMAGE_PATH.$order->image_name) ?>' width='60' height='60'></td><?php endif;?>
					<?php if(empty($order->image_name)):?><td><img src='<?php echo base_url('images/no-image.jpg') ?>' width='60' height='60'></td><?php endif;?>
							</td>
							<td>
								<?php echo $order->product_name ?><br>商品単価：<?php echo $order->sale_price ?><br>商品個数：<?php echo $order->quantity ?>
							</td>
							<td>
								<?php echo form_dropdown('quantity',$quantity,$order->quantity) ?>
							</td>
							<td><input type='submit' name='submit' value='変更'></td>
							<td><a class='edit' href='<?php echo site_url('mypage/mypage_order') ?>'>戻る</a></td>
					<?php else:?>
							<p>ご注文受付処理が完了しました。変更できません</p>
					<?php endif;?>
						</tr>
					</table>
					</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
</script>
</html>