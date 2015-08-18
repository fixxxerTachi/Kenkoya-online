<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
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
				<?php echo form_open() ?>
				<table class='detail'>
				<tr><th><label for='order_number'>注文番号</label></th><td><input type='text' name='order_number' id='order_number' value='<?php echo $form_data->order_number ?>'></td></tr>
				<tr>
					<th><label for='customer_name'>お客様名</label></th><td><input type='text' name='customer_name' id='customer_name' value='<?php echo $form_data->customer_name ?>'></td>
					<th><label for='customer_code'>お客様コード</label></th><td><input type='text' name='customer_code' id='customer_code' value='<?php echo $form_data->customer_code ?>'></td>
				</tr>
				<tr>
					<th>受注日</th>
					<td><label for='start_date'>開始</label><input type='text' name='start_date' id='start_date' value='<?php echo $form_data->start_date ?>'></td>
					<th style='background: #fff'>~</th>
					<td><label for='end_date'>終了</label><input type='text' name='end_date' id='end_date' value='<?php echo $form_data->end_date ?>'></td>
				</tr>
				<tr>
					<th>配達予定日</th>
					<td><label for='deliver_start_date'>開始</label><input type='text' name='deliver_start_date' id='deliver_start_date' value='<?php echo $form_data->deliver_start_date ?>'></td>
					<th style='background: #fff'>~</th>
					<td><label for='deliver_end_date'>終了</label><input type='text' name='deliver_end_date' id='deliver_end_date' value='<?php echo $form_data->deliver_end_date ?>'></td>
					<td><input type='checkbox' name='no_deli_date' id='no_deli_date' value='1' checked='checked'></td>
					<td><label for='no_deli_date'>日付指定なしを含める</label></td>
				</tr>
				<tr><th>受付状態</th><td>
				<input type='checkbox' name='status[]' id='wait' value='0' <?php if(in_array(0,$form_data->status_arr)) echo 'checked=checked' ?>><label for='wait'>受付中</label>
				<input type='checkbox' name='status[]' id='done' value='1'  <?php if(in_array(1,$form_data->status_arr)) echo 'checked=checked' ?>><label for='done'>受付済</label>
				<input type='checkbox' name='status[]' id='cancel' value='2'  <?php if(in_array(2,$form_data->status_arr)) echo 'checked=checked' ?>><label for='cancel'>キャンセル</label>
				</td></tr>
				<tr>
					<td></td>
					<td><input type='submit' name='submit' value='検索'></td>
					<td><input type='submit' name='makecsv' value='CSV作成'></td>
					<td></td>
					<td></td>
					<td><input type='submit' name='makeOrderItems' value='注文明細書作成'></td>
				</tr>
				</table>
				</form>
				<?php if(!empty($result)): ?>
				<!--
				<p>売上高:<?php echo number_format($total_price) ?>円</p>
				-->
					<?Php $count = count($result);?>
				<table>
					<tr>
						<th>購入日</th><th>注文番号</th><th>お客様コード</th><th>お客様名</th><th>配送先</th>
					</tr>
					<tr>
						<th>商品コード</th><th>枝番</th><th>商品名</th><th>数量</th><th>販売単価</th>
					</tr>
					<tr>
						<th>購入金額</th><th>お支払方法</th><th>状態</th><th>変更</th>
					</tr>
					<?php for($i=0;$i < $count; $i++): ?>
						<?php $create_date = new DateTime($result[$i]->create_date);?>
						<?php if(($i == 0) || ($i != 0 && $result[$i]->order_number != $result[$i-1]->order_number)):?>
					<tr>
						<td><?php echo $create_date->format('Y/m/d');?></td>
						<td><?php echo $result[$i]->order_number ?></td>
						<td><?php echo $result[$i]->customer_code ?></td>
						<td><?php echo $result[$i]->name ?></td>
						<td><?php echo $result[$i]->address ?></td>
					</tr>
						<?php endif;?>
					<tr>
						<td><?php echo $result[$i]->product_code ?></td>
						<td><?php echo $result[$i]->branch_code ?></td>
						<td><?php echo $result[$i]->product_name ?></td>
						<td><?php echo $result[$i]->quantity ?>個</td>
						<td><?php echo $result[$i]->sale_price ?>円</td>
					</tr><tr>
						<td><?php echo number_format($result[$i]->quantity * $result[$i]->sale_price) ?>円</td>
						<td><?php echo $payments[$result[$i]->payment]->method_name ?></td>
						<td><?php echo $order_status[$result[$i]->status_flag] ?></td>
						<td><a class='edit' href='<?php echo base_url("/admin_order/edit_order/{$row->order_id}") ?>'>変更</a></td>
					</tr>
					<?php endfor;?>
				</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>