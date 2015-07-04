<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
</head>
<body>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="body">
		<div class='content'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<form method='post'>
				<table class='detail'>
				<tr><th>日付指定</th><td><label for='start_date'>開始</label><input type='text' name='start_date' id='start_date' value='<?php echo $form_data->start_date ?>'> ~ <label for='end_date'>終了</label><input type='text' name='end_date' id='end_date' value='<?php echo $form_data->end_date?>'></td></tr>
				<tr><td></td><td><input type='submit' name='submit' value='検索'><input type='button' id='clear' value='日付をクリア'></td></tr>
				</table>
				</form>
				<?php if($orders): ?>
					<table  class='list'>
						<?php foreach($orders as $order): ?>
						<?php $create_date = new DateTime($order->create_date)?>
						<?php $delivery_date = new DateTime($order->delivery_date) ?>
						<?php $count = count($order->details) ?>
						<tr>
							<th rowspan='<?php echo $count ?>'>
								ご注文番号：<?php echo $order->order_number ?><br>
								注文日：<?php echo $create_date->format('Y年m月d日') ?><br>
								配達日：<?php echo $delivery_date->format('Y年m月d日') ?>
							</th>
							<?php foreach($order->details as $row):?>
							<td>
								<img src='<?php show_product_image($row->image_name) ?>' width='60' height='60'></td><?php endif;?>
							</td>
							<td>
								<?php echo $row->product_name ?><br>商品単価：<?php echo $row->sale_price ?><br>商品個数：<?php echo $row->quantity ?>
							</td>
							<td>
							</td>
					<?php if(!$order->csv_flag == 1): ?>
							<td><a class='edit' href='<?php echo site_url("/mypage/change_quantity/{$row->order_detail_id}/") ?>'>数量変更</a></td>	
							<td><a class='edit' href='<?php echo site_url("/mypage/cancel/{$row->order_detail_id}/") ?>'>注文キャンセル</a></td>	
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
</body>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
$('#clear').on('click',function(){
	$('#start_date').val('');
	$('#end_date').val('');
});
</script>
</html>