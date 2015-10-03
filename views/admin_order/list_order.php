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
				<div class='container'>
					<?php if(!empty($h2title)):?>
					<h2><span class='logo_pink'>order</span> <?php echo $h2title ?></h2>
					<?php endif; ?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php echo form_open() ?>
					<table class='detail'>
						<tr>
							<th><label for='order_number'>注文番号</label></th><td><input type='text' name='order_number' id='order_number' value='<?php echo $form_data->order_number ?>'></td>
						</tr>
						<tr>
							<th><label for='customer_name'>お客様名</label></th><td><input type='text' name='customer_name' id='customer_name' value='<?php echo $form_data->customer_name ?>'></td>
							<th><label for='customer_code'>お客様コード</label></th><td><input type='text' name='customer_code' id='customer_code' value='<?php echo $form_data->customer_code ?>'></td>
						</tr>
						<tr>
							<th>受注日</th>
							<td><label for='start_date'>開始</label><input type='text' name='start_date' id='start_date' value='<?php echo $form_data->start_date ?>'></td>
							<th class='no-border' style='background: #fff'>~</th>
							<td><label for='end_date'>終了</label><input type='text' name='end_date' id='end_date' value='<?php echo $form_data->end_date ?>'></td>
						</tr>
						<tr>
							<th>配達予定日</th>
							<td><label for='deliver_start_date'>開始</label><input type='text' name='deliver_start_date' id='deliver_start_date' value='<?php echo $form_data->deliver_start_date ?>'></td>
							<th class='no-border' style='background: #fff'>~</th>
							<td><label for='deliver_end_date'>終了</label><input type='text' name='deliver_end_date' id='deliver_end_date' value='<?php echo $form_data->deliver_end_date ?>'>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td></td>
							<td></td>
							<td><input type='checkbox' name='no_deli_date' id='no_deli_date' value='1'><label for='no_deli_date'>日付指定なしを含める</label></td>
						</tr>
					</table>
					<table class='detail'>
						<tr>
							<th>受付状態</th>
							<td>
								<input type='checkbox' name='status[]' id='wait' value='0' <?php if(in_array(NOORDER,$form_data->status_arr)) echo 'checked=checked';?>><label for='wait'>受付中</label>
								<input type='checkbox' name='status[]' id='done' value='1'  <?php if(in_array(RECIEVED,$form_data->status_arr)) echo 'checked=checked';?>><label for='done'>受付済</label>
								<input type='checkbox' name='status[]' id='cancel' value='2'  <?php if(in_array(CANCELED,$form_data->status_arr)) echo 'checked=checked';?>><label for='cancel'>キャンセル</label>
								<input type='checkbox' name='status[]' id='ordered' value='3' <?php if(in_array(ORDERED,$form_data->status_arr)) echo 'checked=checked';?>><label for='ordered'>発注リスト発行済み</label>
								<input type='checkbox' name='status[]' id='shipped' value='4' <?php if(in_array(DELIVERED,$form_data->status_arr)) echo 'checked=checked';?>><label for='shipped'>出荷済み</label>
							</td>
						</tr>
						<tr>
							<table>
								<td width='100px'></td>
								<td><input type='submit' name='submit' value='検索'></td>
								<td width='200px'></td>
								<td><input type='submit' name='reg_order' value='受付登録'><br><a class='desc_btn' id='recieved_desc' href='javascript:void(0)'>説明</a></td>
								<td><input type='submit' name='makecsv' value='発注用CSV作成'><br><a class='desc_btn' id='order_desc' href='javascript:void(0)'>説明</a></td>
								<td><input type='submit' name='makeOrderItems' value='注文明細書作成'><br><a class='desc_btn' id='items_desc' href='javascript:void(0)'>説明</a></td>
								<td><input type='submit' name='save_shipped' value='出荷済み登録'><br><a class='desc_btn' id='shipped_desc' href='javascript:void(0)'>説明</a></td>
								<!--<td><a class='edit_back' href='<?php echo site_url('admin_order/list_order') ?>'>更新</a></td>
								<td><a class='edit_back' href='javascript:void(0)' id='remove_check'>全てのチェックをつける</a></td>-->
								<td><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" id='renew'>更新</button></td>
								<td><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" id='remove_check' onclick="return false">全てチェック</button></td>
							</table>
						</tr>
					</table>
					<?php if(!empty($result)): ?>
					<?php $count = count($result);?>
					<table class='list'>
						<tr class='base_info_header'>
							<th>購入日</th><th>お届け日</th><th>お届け時間帯</th><th>注文番号</th><th>お客様<br>コード</th><th>お客様名</th><th>配送先</th><th></th>
						</tr><tr class='base_info_header'>
							<th>配送料</th><th>合計<br>(税抜)</th><th>消費税</th><th>お支払方法</th><th>入金</th><th>状態</th><th>変更</th><th>状態変更</th>
						</tr>
						<tr class='product_info_header'>
							<th>商品コード</th><th>枝番</th><th>商品名</th><th>数量</th><th>販売単価</th><th>小計</th><th></th><th></th>
						</tr>
						<?php for($i=0;$i < $count; $i++): ?>
							<?php $create_date = new DateTime($result[$i]->create_date);?>
							<?php if(($i == 0) || ($i != 0 && $result[$i]->order_number != $result[$i-1]->order_number)):?>
						<tr class='base_info'>
							<td><?php echo $create_date->format('Y/m/d');?></td>
						<?php $d_date = new DateTime($result[$i]->delivery_date) ?>
						<?php $d_date = ($d_date > new DateTime('1900-01-1-01 00:00:00')) ? $d_date->format('Y/m/d') : '日付け指定なし'; ?>
							<td><?php echo $d_date ?></td>
							<td><?php echo $takuhai_hours[$result[$i]->delivery_hour] ?></td>
							<td><?php echo $result[$i]->order_number ?></td>
							<td><?php echo $result[$i]->customer_code ?></td>
							<td><?php echo $result[$i]->name ?></td>
							<td><?php echo $result[$i]->address ?></td>
							<td></td>
						</tr>
						<tr class='base_info'>
							<td><?php echo number_format($result[$i]->delivery_charge) ?>円</td>
							<td><?php echo number_format($result[$i]->total_price + $result[$i]->delivery_charge) ?>円</td>
							<td><?php echo number_format($result[$i]->tax) ?>円</td>
							<td>
								<?php echo $payments[$result[$i]->payment]->method_name ?>
								<?php if($result[$i]->payment == PAYMENT_BANK):?>
								<a class='edit' target='_blank' onclick="javascript:window.open('<?php echo site_url("admin_order/change_paid/{$result[$i]->orderId}/{$result[$i]->paid_flag}")?>','','width=400,height=200,toolbar=no')">入金確認</a>
								<?php endif;?>
							</td>
							<td>
								<?php if($result[$i]->payment == PAYMENT_CREDIT) echo $credit->get_statuses()[$credit->search_trade($result[$i]->order_number)->status]; ?>
								<?php if($result[$i]->payment == PAYMENT_BANK): ?>
									<?php if($result[$i]->paid_flag == NOPAID): ?><span style='color:orange;'><?php echo $paid_flags[$result[$i]->paid_flag] ?></span><?php endif;?>
									<?php if($result[$i]->paid_flag == PAID): ?><span><?php echo $paid_flags[$result[$i]->paid_flag] ?></span><?php endif;?>
								<?php endif;?>
							</td>
							<td><?php echo $order_status[$result[$i]->status_flag] ?></td>
							<td><a class='edit' href='<?php echo base_url("/admin_order/edit_order/{$result[$i]->orderId}") ?>'>変更</a></td>
							<td>
						<?php if($result[$i]->status_flag == NOORDER):?>
								<input type='checkbox' name='recieved[]' id='recieved_<?php echo $result[$i]->orderId ?>' value='<?php echo $result[$i]->orderId ?>' checked='checked' class='ckbox'>
								<label for='recieved_<?php echo $result[$i]->orderId ?>'>受付済みにする</label>
						<?php endif;?>
						<?php if($result[$i]->status_flag == RECIEVED):?>
							<?php //銀行振込で未入金は表示しない ?>
							<?php if($result[$i]->paid_flag != NOPAID):?>
								<input type='checkbox' name='ordered[]' id='ordered_<?php echo $result[$i]->orderId ?>' value='<?php echo $result[$i]->orderId ?>' checked='checked' class='ckbox'>
								<label for='ordered_<?php echo $result[$i]->orderId ?>'>発注済みにする</label>
							<?php endif;?>
						<?php endif;?>
						<?php if($result[$i]->status_flag == ORDERED):?>
								<input type='checkbox' name='shipped[]' id='shipped_<?php echo $result[$i]->orderId ?>' value='<?php echo $result[$i]->orderId ?>' class='ckbox'>
								<label for='shipped_<?php echo $result[$i]->orderId ?>'>出荷済みにする</label>
						<?php endif;?>
						<?php if($result[$i]->status_flag == DELIVERED):?>
								<?php $date = new DateTime($result[$i]->shipped_date) ?>
								<?php echo $date->format('Y年m月d日') ?>出荷済み
						<?php endif;?>
								</td>
						</tr>
						<?php endif;?>
						<tr class='product_info'>
							<td><?php echo $result[$i]->product_code ?></td>
							<td><?php echo $result[$i]->branch_code ?></td>
							<td><?php echo $result[$i]->product_name ?></td>
							<td><?php echo $result[$i]->quantity ?>個</td>
							<td><?php echo $result[$i]->sale_price ?>円</td>
							<td><?php echo number_format($result[$i]->quantity * $result[$i]->sale_price) ?>円</td>
							<td></td>
							<td></td>
						</tr>
						<?php endfor;?>
					</table>
					<?php else: ?>
						<p></p>
					<?php endif; ?>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
var button = $('#remove_check');
var items = $('.ckbox');
button.on('click',function(){
	if(button.hasClass('checked'))
	{
		items.prop({'checked':true});
		button.removeClass('checked');
		button.text('全てのチェックを外す');
	}
	else
	{
		items.prop({'checked':false});
		button.addClass('checked');
		button.text('全てのチェックを付ける');
	}
});
$('#renew').on('click',function(){
	location.href='<?php echo site_url('admin_order/list_order') ?>';
});
</script>
</html>