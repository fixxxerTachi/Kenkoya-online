<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
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
					<table class='list'>
						<?php foreach($orders as $order): ?>
						<?php $create_date = new DateTime($order->create_date)?>
						<?php $count = count($order->details) ?>
						<tr>
							<th rowspan='<?php echo $count ?>'>
								ご注文番号：<?php echo $order->order_number ?><br>
								注 文 日 ：<?php echo $create_date->format('Y年m月d日') ?><br>
							</th>
							<?php foreach($order->details as $row):?>
							<td><img src='<?php echo base_url(show_image($row->product_code)) ?>' width='60' height='60'></td>
							</td>
							<td class='product'>
								<?php echo $row->product_name ?><br>商品単価：<?php echo number_format($row->sale_price) ?>円<br>注文個数：<?php echo $row->quantity ?>
							</td>
					<?php if($row->delivery_date == '0000-00-00 00:00:00'):?>
						<?php $delivery_date = '日付指定なし' ?>
					<?php else:?>
						<?php $delivery_date = new DateTime($row->delivery_date) ?>
						<?php $delivery_date = $delivery_date->format('Y/m/d'); ?>
					<?php endif;?>
							<td>配達予定日:<br> <?php echo $delivery_date ?><br><?php echo $takuhai_hours[$row->delivery_hour] ?></td>
							<td>
								<?php echo $order_status[$row->status_flag] ?>
							</td>
					<?php if(!$order->csv_flag == 1): ?>
							<td><a class='edit' href='<?php echo site_url("/front_customer/change_quantity/{$row->order_detail_id}/") ?>'>数量変更</a></td>
							<td><a class='edit' href='<?php echo site_url("/front_customer/cancel/{$row->order_detail_id}/") ?>'>注文キャンセル</a></td>
					<?php endif;?>
						</tr>
							<?php endforeach;?>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>	
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
$('#start_date,#end_date').datepicker({
	dateFormat:'yy/mm/dd',
	monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    dayNames: ['日', '月', '火', '水', '木', '金', '土'],
    dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
});
$('#clear').on('click',function(){
	$('#start_date').val('');
	$('#end_date').val('');
});
</script>
</html>