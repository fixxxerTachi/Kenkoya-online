<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url() ?>css/admin_order.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>js/datepicker-ja.js"></script>
<script src="<?php echo base_url('js/calender.js')?>"></script>
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
				<table>
					<tr class='base_info_header'>
						<th>購入日</th><th>お届け日</th><th>お届け時間帯</th><th>注文番号</th><th>お客様<br>コード</th><th>お客様名</th><th>配送先</th>
					</tr>
					<tr class='base_info_header'>
						<th>配送料</th><th>合計(税抜)</th><th>消費税</th><th>お支払方法</th><th>状態</th>
					</tr>
					<tr class='product_info_header'>
						<th>商品コード</th><th>枝番</th><th>商品名</th><th>数量</th><th>販売単価</th><th>小計</th><th></th>
					</tr>
					<?php foreach($result as $item): ?>
						<?php $create_date = new DateTime($item->create_date);?>
					<tr class='base_info'>
						<td><?php echo $create_date->format('Y/m/d');?></td>
					<?php $d_date = new DateTime($item->delivery_date) ?>
					<?php $d_date = ($d_date > new DateTime('1900-01-1-01 00:00:00')) ? $d_date->format('Y/m/d') : '日付け指定なし'; ?>
						<td><input id='start_date' type='text' name='delivery_date' value='<?php echo $d_date ?>'></td>
						<td><?php echo form_dropdown('delivery_hour',$takuhai_hours,$item->delivery_hour,'id=dropdown') ?></td>
						<td><?php echo $item->order_number ?></td>
						<td><?php echo $item->customer_code ?></td>
						<td><?php echo $item->name ?></td>
						<td><input type='text' name='address' value='<?php echo $item->address ?>' size='50' maxlength='200'></td>
					</tr><tr class='base_info'>
						<td><input type='text' name='delivery_charge' value='<?php echo number_format($item->delivery_charge) ?>' size='4' maxlength='4'>円</td>
						<td><?php echo number_format($item->total_price + $item->delivery_charge) ?>円</td>
						<td><?php echo number_format($item->tax) ?>円</td>
						<td><?php echo form_dropdown('payment',$payments_arr,$item->payment) ?></td>
						<td><?php echo form_dropdown('status_flag',$order_status,$item->status_flag) ?></td>
					</tr>
					<?php $i = 0; ?>
					<?php foreach($products as $product):?>
					<tr class=product_info>
						<td><?php echo $product->product_code ?></td>
						<td><?php echo $product->branch_code ?></td>
						<td><?php echo $product->product_name ?></td>
						<td><input type='text' name="quantity_<?php echo $i ?>" value='<?php echo $product->quantity ?>' size='3' maxlength='3'>個</td>
						<td><input type='text' name="sale_price_<?php echo $i ?>" value='<?php echo $product->sale_price ?>' size='5' maxlength='5'>円</td>
						<td><?php echo number_format($product->quantity * $product->sale_price) ?>円</td>
						<td><input type='hidden' name="order_detail_id_<?php echo $i ?>" value='<?php echo $product->order_detail_id ?>'></td>
						<td><input type='hidden' name='count' value='<?php echo $i ?>'>
					<?php $i++;?>
					</tr>
					<?php endforeach;?>
					<?php endforeach;?>
					<tr>
						<td></td><td><input type='submit' name='submit' value='変更する'></td>
						<td></td><td><a href='<?php echo site_url('admin_order/list_order') ?>' class='edit'>戻る</a></td>
					</tr>
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
</html>
