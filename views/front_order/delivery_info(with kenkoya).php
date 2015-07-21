<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/order.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<?php include __DIR__ . '/../templates/order_flow.php'?>
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
				<?php if(is_array($error_message)):?>
					<?php foreach($error_message as $message):?>
			<p class='error'><?php echo $message ?></p>
					<?php endforeach;?>
				<?php else:?>
			<p class='error'><?php echo $error_message ?></p>
					<?php endif;?>
			<?php endif; ?>	
			<?php echo validation_errors('<p class="error">','</p>');?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th>ご請求先</th>
						<td colspan ='2'>
							<?php echo $customer->address1 ?> <?php echo $customer->name ?> 様
						</td>
					</tr>
					<tr>
						<th>配達先</th>
						<td colspan='2'>
							<?php echo $address ?>
						</td>
					</tr>
	<?php if(!empty($cource_info)):?>
					<tr class='kenkoya_menu'>
						<th><span class='logo_pink_middle'>宅配スーパー健康屋</span><br>配達曜日</th>
						<td><?php echo $cource_info->takuhai_day ?></td>
					</tr>
	<?php endif;?>
	<?php //宅配可能エリアの場合 ?><?php if(!empty($select_days)):?>
					<tr class='kenkoya_menu'>
						<th><span class='logo_pink_middle'>宅配スーパー健康屋</span><br>次回配達日</th>
					<?php $date = new DateTime($select_days->first_date) ?>
					<?php $w = $date->format('w') ?>
						<td><?php echo $date->format('m月d日');?>(<?php echo $days[$w] ?>)</td>
					</tr>
					<tr class='kenkoya_menu'>
						<th><span class='logo_pink_middle'>宅配スーパー健康屋</span><br>配達日を指定する <span class='logo_orange_middle'>ご希望の場合選択</span></th>
						<td><?php echo form_dropdown('select_date',$select_days->select,$form_data->takuhai_select_date,'id="takuhai_select"') ?></td>
					</tr>
					<tr>
						<th rowspan='2' id='select_takuhai'>上記配達日以外をご希望の場合<br><span class='logo_green_middle'>宅急便</span>での配達を希望する場合</th>
						<td><?php echo form_checkbox('takuhai','takuhai',$form_data->takuhai,'id="takuhai"') ?><label for='takuhai'>　上記宅配スーパー健康屋のお届け日以外を希望する場合はチェックをいれてください。<br><span class='logo_green_middle'>宅急便</span>での配送となります。</label></td>
					</tr>
					<tr>
						<td><a class='new-window' href='<?php echo base_url('index/show_charge') ?>' target='_blank'>配送方法・送料を確認する</a></td>
					</tr>
					<tr class='takuhai_menu'>
						<th><span class='logo_green_middle'>宅急便</span>の配送先</th><td>ご登録の配送先 &nbsp;<a class='button' href='<?php echo site_url('addresses/select_address') ?>'>別の配送先を選択</a></td>
					</tr>
					<tr class='takuhai_menu'>
						<th rowspan='4'><span class='logo_green_middle'>宅急便</span>での配達日</th>
					</tr>
					<tr class='takuhai_menu'>
						<td><?php echo form_radio('delivery',0,$form_data->delivery == 0,'id="delivery_0"') ?><label for='delivery_0'>通常配送</label></td>
					</tr>
					<tr class='takuhai_menu'>
						<td><?php echo form_radio('delivery',1,$form_data->delivery == 1,'id="delivery_1"') ?><label for='delivery_1'>配達日を指定する</label></td>
					</tr>
					<tr class='takuhai_menu'>
						<td><?php echo form_dropdown('takuhai_select_date',$select_days_takuhai->select,$form_data->takuhai_select_date,'id="dropdown"') ?></td>
					</tr class='takuhai_menu'>
					<tr class='takuhai_menu'>
						<th><span class='logo_green_middle'>宅急便</span>での<br>希望配達時間</th>
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
						<th rowspan='5'>配達日</th>
						<td>宅急便での配送</td>
					</tr>
					<tr>
						<td><?php if(empty($no_address_flag)):?><a class='button' href='<?php echo site_url('addresses/select_address') ?>'>別の配送先を選択</a><?php endif;?></td>
					</tr>
					<tr>
						<td><?php echo form_radio('delivery',0,$form_data->delivery == 0,'id="delivery_0"') ?>&nbsp;<label for='delivery_0'>通常配送</label></td>
					</tr>
					<tr>
						<td><?php echo form_radio('delivery',1,$form_data->delivery == 1,'id="delivery_1"') ?>&nbsp;<label for='delivery_1'>配達日を指定する</label></td>
					</tr>
					<tr>
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
						<th rowspan='10'>
							お支払方法 <span class='logo_pink'>必須</span><br>
							 詳細は<a class='new-window' href='<?php echo base_url('front_question/detail/1') ?>' target='_blank'>こちらから</a>
						</th>
		<?php foreach($payments as $key => $val):?>
						<td><?php echo form_radio('payment', $key, isset($form_data->payment) && $form_data->payment == $key, "id='{$key}'") ?> <label for="<?php echo $key ?>"><?php echo $val->method_name ?></label></td>
					</tr>
					<tr class='payment_inner'>
						<?php if($key != PAYMENT_MEMBER):?>
							<td><?php echo nl2br($val->notice);?></td>
						<?php else:?>
							<td><?php if(empty($new_member)){ echo nl2br($val->notice); }else{ echo '配達員が口座振替依頼書を持参します。必要事項をご記入の上、配達員にお渡しください';} ?></td>
						<?php endif;?>
					</tr>
		<?php endforeach;?>
		<?php if(!isset($payments[PAYMENT_MEMBER])): /*エリア外のお客様は口座登録していないので表示を崩さないように空タグ挿入 */?>
						<td></tr><tr><td></td></tr>
		<?php endif;?>
					<tr>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='ご注文の最終確認画面へ'><a class='edit_back' href='<?php echo site_url("front_customer/login_action/proceed") ?>'>戻る</a></td>
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
	//宅急便の項目を非表示にする
	$('.takuhai_menu').hide();
	//宅急便がチェックされていたら項目表示
	if($('#takuhai').prop('checked') == true){
		$('.kenkoya_menu').hide();
		//$('#takuhai_select').val('0');
		$('.takuhai_menu').show();
	}
	//宅急便を希望する場合メニュー表示
	$('#takuhai').on('click',function(){
		if($(this).prop('checked') == true){
			$('.kenkoya_menu').hide();
			$('#takuhai_select').val('0');
			$('.takuhai_menu').fadeIn();
	//宅急便を希望しない場合メニュー非表示
		}else{
			$('.takuhai_menu').hide();
			$('#dropdown').val('0');
			$('.kenkoya_menu').fadeIn();
		}
	});
</script>
</html>
<?php echo 'carts:';var_dump($this->session->userdata('carts')); ?>
<?php echo 'card_info:';var_dump($this->session->userdata('card_info'));echo '<br>';?>
<?php echo 'order_info:';var_dump($this->session->userdata('order_info')); echo '<br>';?>
<?php echo 'customer:';var_dump($this->session->userdata('customer')); echo '<br>';?>
<?php echo 'destination:';var_dump($this->session->userdata('destination')); echo '<br>';?>
<?php echo 'no-member:';var_dump($this->session->userdata('no-member')); echo '<br>';?>
