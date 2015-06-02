<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
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
				<?php echo form_open() ?>
					<table class='detail'>
						<tr><th>注文番号</th><td colspan='2'><?php echo $result->order_number ?></td></tr>
				<?php $date = new DateTime($result->create_date)?>
						<tr><th>注文日</th><td><?php echo $date->format('Y/m/d') ?></td></tr>
						<tr><th>お名前</th><td><?php echo $result->name ?></td></tr>
						<tr><th>商品名</th><td><?php echo $result->product_name ?></td></tr>
						<tr><th>数量</th><td><input type='text' name='quantity' value='<?php echo $result->quantity ?>' size='3' maxlength='3'></td></tr>
				<?php $date = new DateTime($result->delivery_date)?>
						<tr><th>配達可能日</th><td><?php echo $result->takuhai_day ?></td></tr>
						<tr><th>配達予定日</th><td><input type='text' name='delivery_date' id='deliver_date' value='<?php echo $date->format('Y/m/d') ?>'></td></tr>
						<tr><th>配達予定時間</th><td><?php echo form_dropdown('delivery_hour',$takuhai_hours,$result->delivery_hour) ?></td></tr>
						<tr><th>キャンセル</th><td><?php echo form_dropdown('status_flag',$status_flag,$result->status_flag)?></td></tr>
						<tr><td></td><td><input type='submit' name='submit' value='登録'></td><td></td><td></td><td><a class='edit_back' href='<?php echo site_url('/admin_order/list_order') ?>'>戻る</a></td></tr>
					</table>
				</form>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
$('#deliver_date').datepicker({
	dateFormat:'yy/mm/dd',
});
</script>
</html>
<?php var_dump($result)?>