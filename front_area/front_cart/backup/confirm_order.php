<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/order.css') ?>'>
<script src='<?php echo base_url('js/jquery-1.11.0.min.js') ?>'></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner-square">
		<div class='content'>
		<h2><span class='logo_pink'>order</span> <?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<?php echo form_open('front_cart/order_process') ?>
			<?php if(!empty($list_product)):?>
				<h3>カートに入れた商品</h3>
				<table class='contact_form'>
				<?php foreach($list_product as $k => $p):?>
				<tr>
					<td><img src='<?php echo base_url(show_image($p->product_code)) ?>' width='60' height='60'></td>
					<td><?php echo $p->product_name ?></td>
					<td><?php echo number_format($p->sale_price) ?>円</td>
					<td><?php echo $p->quantity ?>個</td>
					<td><?php echo number_format($p->subtotal) ?>円</td>
					<td>
						<a class='edit' href='<?php echo base_url("front_cart/change_quantity/{$k}")?>'>数量を変更する</a>
						<a class='edit' href='<?php echo base_url("front_cart/delete_item/{$k}")?>'>カートから削除する</a>
					</td>
				</tr>
				<?php endforeach;?>
				<tr><td></td><td></td><td></td><td>獲得ポイント</td><td><?php echo $point ?></td></tr>
				<tr><td></td><td></td><td></td><td>小計</td><td><?php echo number_format($total_price) ?>円</td></tr>
				<tr><td></td><td></td><td></td><td>消費税</td><td><?php echo number_format($tax_price) ?>円</td></tr>
				<tr><td></td><td></td><td></td><td>合計</td><td><?php echo number_format($total) ?>円</td></tr>
				</table>
			<?php else: ?>
				<p>何も入っていません</p>
			<?php endif;?>
				<h3>配達先</h3>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<?php echo $form_data->name ?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>住所</label></th>
						<td>
							<?php echo $form_data->address1 ?>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<?php echo $form_data->tel ?>
						</td>
					</tr>
				</table>
				<h3>配達日</h3>
				<table class='contact_form'cellpadding='0' cellspacing='10'>
			<?php if(!empty($form_data->delivery_date)):?>
					<?php $date = new DateTime($form_data->delivery_date) ?>
					<?php $w = $date->format('w') ?>
					<tr>
						<td><?php if($form_data->takuhai):?>宅急便での配送<?php else:?>宅配スーパー健康屋からの発送<?php endif;?></td>
						<td><?php echo $date->format('m月d日') ?>(<?php echo $days[$w] ?>) <?php echo $takuhai_hours[$form_data->delivery_hour] ?></td>
			<?php else:?>
					<tr>
						<td>日付指定しない</td><td><?php echo $takuhai_hours[$form_data->delivery_hour] ?></td>
			<?php endif;?>
						<td><a class='edit' href='<?php echo base_url("front_customer/delivery_info")?>'>配達日を変更する</a></td>
					</tr>
				</table>
				<h3>お支払方法</h3>
				<table class='contact_form'  id='table_bottom'  cellpadding='0' cellspacing='10'>
			<?php if($form_data->payment == 'bank'):?>
					<tr><td>銀行引き落とし</td></tr>
					<tr>
				<?php if(!empty($form_data->bank_name)):?>
					<td><?php echo $form_data->bank_name ?>&nbsp;<?php echo $account[$form_data->type_account] ?>&nbsp;*******</td>
				<?php else:?>
						<td><p><?php echo '配達員が口座振替依頼書を持参します。必要事項をご記入の上、配達員にお渡しください' ?></p></td>
				<?php endif;?>
					</tr>
			<?php endif;?>
			<?php if($form_data->payment != 'bank'):?>
					<tr><td><?php echo $form_data->payment ?></td></tr>
			<?php endif;?>
				</table>
				<table class='contact_form' id='cart_menu'>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='購入する'><a class='edit_back' href='<?php echo base_url("front_customer/delivery_info{$param}") ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<div id='cover'><div id='clover'><img src='<?php echo base_url('images/clover.gif') ?>'></div></div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
$('input[type=submit]').on('click',function(){
	$('#clover').css({
		'position':'absolute',
		'top':$(window).scrollTop() + 150 + 'px',
		'left':Math.floor(($(window).width() - 400)/2) + 'px'
	});
	$("#cover").css({
		'width':$(document.body).width(),
		'height':$(document.body).height(),
		'background':'#fff',
		'opacity':'0.5',
		'top':'0px',
		'left':'0px',
		'z-index':'99'
	}).fadeIn('slow');
});
</script>
</html>
<?php //echo '<pre>'; print_r($this->session->userdata('order_info')); echo '</pre>';?>
<?php //echo '<pre>'; print_r($this->session->userdata('customer')); echo '</pre>'; ?>
<?php //echo '<pre>'; print_r($this->session->userdata('carts')); echo '</pre>'; ?>