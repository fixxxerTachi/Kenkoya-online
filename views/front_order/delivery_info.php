<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/order.css') ?>'>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>js/datepicker-ja.js"></script>
<script src="<?php echo base_url('js/calender.js')?>"></script>
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
					<tr>
						<th rowspan='5'>配達日</th>
						<td>宅急便での配送</td>
					</tr>
					<input type='hidden' name='takuhai' value='takuhai'><?php //宅急便での配達はtakuhaiパラメータを仕込む ?>
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
					<tr>
						<th rowspan='8'>
							お支払方法 <span class='logo_pink'>必須</span><br>
							 詳細は<a class='new-window' href='<?php echo site_url('question/detail/1') ?>' target='_blank'>こちらから</a>
						</th>
		<?php foreach($payments as $key => $val):?>
						<td>
							<?php echo form_radio('payment', $key, isset($form_data->payment) && $form_data->payment == $key, "id='{$key}'") ?> <label for="<?php echo $key ?>"><?php echo $val->method_name ?></label>
						</td>
					</tr>
					<tr class='payment_inner'>
						<?php //if($key != PAYMENT_MEMBER):?>
							<td><?php echo nl2br(sub_str($search,$replace,$val->notice));?></td>
						<?php //endif;?>
					</tr>
		<?php endforeach;?>
			</table>
			<table class='contact_form'>
					<tr>
						<th class='no-back'></td>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='ご注文の最終確認画面へ' id='submit'><a class='edit_back' href='<?php echo site_url("customer/login_action/proceed") ?>'>戻る</a></td>
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
<script>
	var text = 'クレジットカード情報入力';
	var value = $('input[name="payment"]:checked').val();
	if(value == <?php echo PAYMENT_CREDIT ?>)
	{
		$("#submit").val(text);
	}

	$('input[name="payment"]').on('change',function(){
		var text = 'クレジットカード情報入力';
		var value = $('input[name="payment"]:checked').val();
		if(value == <?php echo PAYMENT_CREDIT ?>)
		{
			$('#submit').val(text);
		}
		else
		{
			$('#submit').val('ご注文の最終確認へ');
		}
	});
</script>
</html>