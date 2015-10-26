<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<script src='<?php echo base_url('js/alert.js') ?>'></script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='content'>
						<?php if(!empty($h2title)):?>
						<h2><span class='logo_pink'>orders</span> <?php echo $h2title ?></h2>
						<?php endif; ?>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message; ?></p>
						<?php endif; ?>
						<?php if(!empty($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>
						<?php echo form_open() ?>
						<table class='contact_form'>
						<tr><th>日付指定</th><td><label for='start_date'>開始</label> <input type='text' name='start_date' id='start_date' value='<?php echo $form_data->start_date ?>'> ~ <label for='end_date'> 終了</label><input type='text' name='end_date' id='end_date' value='<?php echo $form_data->end_date?>'><input type='submit' name='submit' value='検索'></td><td><input type='button' id='clear' value='日付をクリア'></td></tr>
						</table>
						</form>
						<?php if($orders): ?>
							<table class='mdl-data-table mdl-js-data-table'>
								<?php foreach($orders as $order): ?>
								<?php $create_date = new DateTime($order->create_date)?>
								<?php $count = count($order->details) ?>
								<tr>
									<th rowspan='<?php echo $count ?>'>
										<table class='order_info'>
											<tr><th>ご注文番号</th><td><?php echo $order->order_number ?></td></tr>
											<tr><th>注 文 日</th><td><?php echo $create_date->format('Y年m月d日') ?></td></tr>
											<tr><th>お支払方法</th><td><?php echo $payments[$order->payment]->method_name ?></td></tr>
											<tr><th>配達予定日</th><td><?php echo format_date($order->delivery_date,'日付指定なし') ?> <?php echo $takuhai_hours[$order->delivery_hour] ?></td></tr>
											<tr><th>ご請求額</th><td><?php echo number_format((int)$order->total_price + (int)$order->tax + (int)$order->delivery_charge) ?>円(税　<?php echo number_format($order->tax) ?>円)</td></tr>
										</table>
									</th>
									<td rowspan='<?php echo $count ?>'>
										<ul>
											<li><?php echo $order_status[$order->status_flag] ?></li>
										<?php if($order->payment == PAYMENT_BANK): //銀行振込の場合入金情報を表示 ?>
											<li><?php echo $paid_flags[$order->paid_flag] ?></li>
										<?php endif;?>
									<?php if($order->status_flag == NOORDER): //受付中はキャンセルボタン表示 ?>
											<li><a class='edit_menu' href='<?php echo site_url("/admin_customer/cancel/{$order->id}/{$order->customer_id}") ?>'>注文キャンセル</a></li>
											<li><a class='edit_menu' href='<?php echo site_url("/mypage/receipt/{$order->id}") ?>' target='blank'>ご注文明細の表示</a></li>
									<?php endif;?>
									<?php if($order->status_flag == RECIEVED): //受付済みは明細ボタン表示 ?>
											<li><a class='edit_menu' href='<?php echo site_url("/mypage/receipt/{$order->id}") ?>' target='blank'>ご注文明細の表示</a></li>
									<?php endif;?>
									<?php if($order->status_flag == ORDERED): //発注済みは明細ボタン表示 ?>
											<li><a class='edit_menu' href='<?php echo site_url("/mypage/receipt/{$order->id}") ?>' target='blank'>ご注文明細の表示</a></li>
									<?php endif;?>
									<?php if($order->status_flag == DELIVERED): //発注済みは明細ボタン表示 ?>
											<li><a class='edit_menu' href='<?php echo site_url("/mypage/receipt/{$order->id}") ?>' target='blank'>ご注文明細の表示</a></li>
									<?php endif;?>
										</ul>
									</td>
									<?php foreach($order->details as $row):?>
									<td>
										<img src='<?php echo base_url(show_image($row->product_code)) ?>' width='60' height='60'>
									</td>
									<td class='product'>
										<?php echo $row->product_name ?><br>商品単価：<?php echo number_format($row->sale_price) ?>円<br>注文個数：<?php echo $row->quantity ?>
									</td>
								</tr>
									<?php endforeach;?>
								<?php endforeach;?>
							</table>
						<?php else: ?>
							<p class='error'>購入履歴がありません</p>
						<?php endif; ?>	
				</div>
			</div>
		</main>
	</div>
</body>
</html>