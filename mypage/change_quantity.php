<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
				<?php if(!empty($h2title)):?>
				<h2><span class='logo_pink'>mypage</span> <?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($message)):?>
				<p class='message note'><?php echo $message; ?></p>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php if(!$order->csv_flag == 1): ?>
					<?php echo form_open() ?>
					<table class='contact_form'>
						<?php $create_date = new DateTime($order->create_date)?>
						<?php $delivery_date = new DateTime($order->delivery_date) ?>
						<tr>
							<th rowspan='<?php echo $count ?>'>
								ご注文番号：<?php echo $order->order_number ?><br>
								注文日：<?php echo $create_date->format('Y年m月d日') ?><br>
								配達日：<?php echo $delivery_date->format('Y年m月d日') ?>
							</th>
							<td>
								<img src='<?php echo base_url(show_image($order->product_code, 120)) ?>' width='60' height='60'>
							</td>
							<td>
								<?php echo $order->product_name ?><br>商品単価：<?php echo number_format($order->sale_price) ?>円<br>商品個数：<?php echo $order->quantity ?>
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
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>