<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<p>クレジットカードでのお支払はの売り上げを確定します</p>
		<?php if(!empty($success_message)):?>
		<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
		<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>		
		<div>
			<?php echo form_open() ?>
			<div id='list'>
				<table class='list'>
					<?php if(count($result) > 0):?>
					<tr>
						<th></th>
						<th>注文番号</th>
						<th>店舗コード</th>
						<th>コースコード</th>
						<th>顧客コード</th>
						<th>配達予定日</th>
						<th>配達予定時間</th>
						<th>お支払い方法</th>
					</tr>
					<?php foreach($result as $row):?>
					<tr>
					<td><input type='checkbox' name='shipped[]' value='<?php echo $row->id ?>'></td>
						<td><?php echo $row->order_number ?></td>
						<td><?php echo $row->shop_code ?></td>
						<td><?php echo $row->cource_code ?></td>
						<td><?php echo $row->customer_code ?></td>
						<td><?php echo format_date($row->delivery_date,'時間指定しない') ?></td>
						<td><?php echo $hours[$row->delivery_hour] ?></td>
						<td><?php echo $payments[$row->payment]->method_name ?></td>
					</tr>		
					<?php endforeach;?>
					<?php else: ?>
						<p class='error'>表示できるデータがありません</p>
					<?php endif; ?>
					<tr class='no-back'><th colspan='2' class='no-back'><input type='submit' value='配送済みにする'></th></tr>
				</table>
			</div>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>
