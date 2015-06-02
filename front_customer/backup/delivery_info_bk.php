<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>delivery</span> <?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<?php echo validation_errors('<p class="error">','</p>');?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
	<?php if(!empty($cource_info)):?>
					<tr>
						<th>宅配スーパー健康屋<br>配達曜日</th>
						<td><?php echo $cource_info->takuhai_day ?></td>
					</tr>
	<?php endif;?>
	<?php //宅配可能エリアの場合 ?><?php if(!empty($select_days)):?>
					<tr>
						<th>次回配達日</th>
					<?php $date = new DateTime($select_days->first_date) ?>
					<?php $w = $date->format('w') ?>
						<td><?php echo $date->format('m月d日');?>(<?php echo $days[$w] ?>)</td>
					<tr>
						<th>配達日を指定する</th>
						<td><?php echo form_dropdown('select_date',$select_days->select) ?></td>
					</tr>
					<tr>
						<th>宅急便での配達を希望する場合</th>
						<td><?php echo form_checkbox('takuhai','takuhai',isset($form_data->takuhai),'id="takuhai"') ?><label for='takuhai'>宅急便での配達を希望する</label></td>
					</tr>
					<tr>
						<th rowspan='4'>宅急便での配達日</th>
						<td>宅急便での配送</td>
					</tr><tr>
						<td><?php echo form_radio('delivery',0,$form_data->delivery == 0,'id="delivery_0"') ?><label for='delivery_0'>通常配送</label></td>
					</tr><tr>
						<td><?php echo form_radio('delivery',1,$form_data->delivery == 1,'id="delivery_1"') ?><label for='delivery_1'>配達日を指定する</label></td>
					</tr><tr>
						<td><?php echo form_dropdown('takuhai_select_date',$select_days_takuhai->select,$form_data->takuhai_select_date,'id="dropdown"') ?></td>
					</tr>
					<tr>
						<th>宅急便での<br>希望配達時間</th>
						<td>
		<?php foreach($takuhai_hours as $k => $v):?>
			<?php if($k == 0){ $checked = True;} else { $checked = '';} ?>
			<?php if($form_data->delivery_hour == $k){ $checked = True;} ?>
							<div class='takuhai_hour'><?php echo form_radio('delivery_hour',$k,$checked,"id='hour_{$k}' class='takuhai_hour'") ?> <?php echo form_label($v,"hour_{$k}") ?></div>
		<?php endforeach;?>
						</td>
					</tr>
	<?php //宅配エリア外の場合 ?><?php else:?>
					<tr>
						<th rowspan='4'>配達日</th>
						<td>宅急便での配送</td>
					</tr><tr>
						<td><?php echo form_radio('delivery',0,$form_data->delivery == 0,'id="delivery_0"') ?><label for='delivery_0'>通常配送</label></td>
					</tr><tr>
						<td><?php echo form_radio('delivery',1,$form_data->delivery == 1,'id="delivery_1"') ?><label for='delivery_1'>配達日を指定する</label></td>
					</tr><tr>
						<td><?php echo form_dropdown('takuhai_select_date',$select_days_takuhai->select,$form_data->takuhai_select_date,'id="dropdown"') ?></td>
					</tr>
					<tr>
						<th>配達時間</th>
						<td>
		<?php foreach($takuhai_hours as $k => $v):?>
			<?php if($k == 0){ $checked = True;} else { $checked = '';} ?>
			<?php if($form_data->delivery_hour == $k){ $checked = True;} ?>
							<div class='takuhai_hour'><?php echo form_radio('delivery_hour',$k,$checked,"id='hour_{$k}' class='takuhai_hour'") ?> <?php echo form_label($v,"hour_{$k}") ?></div>
		<?php endforeach;?>
						</td>
					</tr>
	<?php endif;?>
					<tr>
						<th rowspan='4'>お支払方法</th>
						<td><?php echo form_radio('payment', 'bank', isset($form_data->payment) && $form_data->payment == 'bank' , 'id="bank"') ?><label for='bank'>口座振込</label></td>
					</tr><tr>
						<td><?php echo form_radio('payment', 'credit', isset($form_data->payment) && $form_data->payment == 'credit', 'id="credit"') ?><label for='credit'>クレジットカート</label></td>
					</tr><tr>
						<td><?php echo form_radio('payment', 'transfer', isset($form_data->payment) && $form_data->payment == 'transfer', 'id="transfer"') ?><label for='transfer'>口座振替</label></td>
					<tr>
			<?php if(!empty($order_info->bank_name)):?>
						<td><?php echo $order_info->bank_name ?>&nbsp;<?php echo $account[$order_info->type_account] ?>&nbsp;*******</td>
			<?php else:?>
						<td><p><?php echo '配達員が口座振替依頼書を持参します。必要事項をご記入の上、配達員にお渡しください' ?></p></td>
			<?php endif;?>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='確認画面へ'><a class='edit_back' href='<?php echo site_url("front_customer/add_customer/{$param}") ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
	var select = document.getElementById('dropdown');
	select.onchange = function(){
		document.getElementById('delivery_1').checked = 'checked';
	}
</script>
</html>