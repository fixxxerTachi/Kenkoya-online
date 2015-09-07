<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
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
				<?php if($order[0]->status_flag == 0): ?>
					<?php echo form_open() ?>
					<table class='contact_form'>
						<?php $create_date = new DateTime($order[0]->create_date)?>
						<?php $delivery_date = new DateTime($order[0]->delivery_date) ?>
						<?php $count = count($order); ?>
						<tr>
							<th rowspan='<?php echo $count ?>' class='no-back'>
								<table class='order_info'>
									<tr><th>ご注文番号</th><td><?php echo $order[0]->order_number ?></td>
									<tr><th>注 文 日</th><td><?php echo $create_date->format('Y年m月d日') ?></td>
									<tr><th>お支払方法</th><td><?php echo $payments[$order[0]->payment]->method_name ?></td>
									<tr><th>配達日</th><td><?php echo format_date($order[0]->delivery_date,'日付指定なし') ?>	<?php echo $takuhai_hours[$order[0]->delivery_hour] ?></td>
								</table>
							</th>
							<td rowspan='<?php echo $count ?>'>
								<ul>
									<li><input type='checkbox' name='cancel' id='cancel'><label for='cancel'><?php echo $label_message ?></label></li>
									<li><input type='submit' name='submit' value='<?php echo $button_message ?>'></li>
									<li><a class='edit' href='<?php echo site_url('mypage/mypage_order') ?>'>戻る</a></li>
								</ul>
							</td>
						<?php foreach($order as $item):?>
							<td>
								<img src='<?php echo base_url(show_image($item->product_code)) ?>' width='60' height='60'>
							</td>
							<td>
								<?php echo $item->product_name ?><br>商品単価：<?php echo number_format($item->sale_price) ?>円<br>商品個数：<?php echo $item->quantity ?>
							</td>
						</tr>
						<?php endforeach;?>
				<!--
							<td>
								<input type='checkbox' name='cancel' id='cancel'><label for='cancel'><?php echo $label_message ?></label>
							</td>
							<td><input type='submit' name='submit' value='<?php echo $button_message ?>'></td>
							<td><a class='edit' href='<?php echo site_url('mypage/mypage_order') ?>'>戻る</a></td>
				-->
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
</body>
</html>