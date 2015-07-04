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
		<h2><span class='logo_pink'>payment</span> <?php echo $h2title ?></h2>
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
						<th><label for='credit_number'>カード番号</label></th>
						<td><input type='text' name='card_no' id='credit_number' size='16' maxlength='16' value='<?php echo $form_data->card_no ?>'></td>
					</tr>
					<tr>
						<th><label for='expire'>有効期限</label></th>
						<!--<td><input type='text' name='expire_month' size='2' maxlength='2' value='<?php echo $form_data->expire_month ?>'>/<input type='text' name='expire_year' size='2' maxlength='2' value='<?php echo $form_data->expire_year ?>'></td>-->
						<td><?php echo form_dropdown('expire_month',$monthes) ?>/<?php echo form_dropdown('expire_year',$years);?></td>
					</tr>
					<tr>
						<th><label for='security_code'>セキュリティーコード</label></th>
						<td><input type='text' name='security_code' size='4' maxlength='4' value='<?php echo $form_data->security_code ?>'></td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='確認画面へ'><a class='edit_back' href='<?php echo site_url("front_order/delivery_info") ?>'>戻る</a></td>
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
<?php echo var_dump($this->session->userdata('carts')); echo '<br>'; ?>
<?php// echo var_dump($this->session->userdata('card_info'));echo '<br>';?>
<?php echo var_dump($this->session->userdata('order_info'));?>