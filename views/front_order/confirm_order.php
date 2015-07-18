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
<?php include __DIR__.'/../templates/order_flow.php' ?>
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
			<?php if(!empty($error_messages)):?>
			<?php foreach($error_messages as $err):?>
			<p class='error'><?php echo $err ?></p>
			<?php endforeach;?>
			<?php endif;?>
			<?php echo form_open('front_order/order_process') ?>
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
						<a class='button' href='<?php echo base_url("front_cart/change_quantity/{$k}/confirm")?>'>数量を変更する</a>
						<a class='button' href='<?php echo base_url("front_cart/delete_item/{$k}/confirm")?>'>カートから削除する</a>
					</td>
				</tr>
				<?php endforeach;?>
				<tr>
					<td></td><td></td><td></td><td>小計</td><td><?php echo number_format($total->total_price) ?>円</td>
<!------ ポイント処理は後回し　
	<?php if(isset($point)):?>
					<td>
						<a class='button' href='<?php echo base_url('front_order/use_point') ?>'>ポイントを使う</a> ご利用可能ポイント残高: <?php echo $point ?>ポイント
					</td>
				</tr>
				<tr><td></td><td></td><td></td><td>ポイント使用</td><td><?php echo $total->use_point ?>ポイント</td></tr>
	<?php endif;?>
------------------------->
				</tr>
				<tr><td></td><td></td><td></td><td>消費税</td><td><?php echo number_format($total->tax_price) ?>円</td></tr>
				<tr><td></td><td></td><td></td><td>配達料金</td><td><?php echo number_format($total->charge_price) ?>円</td><td>(<?php echo $boxnames ?>)</td></tr>
				<tr><td></td><td></td><td></td><td>合計</td><td><?php echo number_format($total->amount) ?>円</td></tr>
				<!--<tr><td></td><td></td><td></td><td>獲得予定ポイント</td><td><?php echo $total->get_point ?>ポイント</td></tr>-->
				</table>
<?php else: ?>
				<p>何も入っていません</p>
<?php endif;?>
				<h3>配達先</h3>
	<?php //宅急便と宅配を区別するため'takuhai'パラメータを送信する ?>
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
					<tr>
						<td><?php if($form_data->takuhai):?>宅急便での配送<?php else:?>宅配スーパー健康屋からの発送<?php endif;?></td>
			<?php if(!empty($form_data->delivery_date)):?>
					<?php $date = new DateTime($form_data->delivery_date) ?>
					<?php $w = $date->format('w') ?>
						<td><?php echo $date->format('m月d日') ?>(<?php echo $days[$w] ?>) <?php echo $takuhai_hours[$form_data->delivery_hour] ?></td>
			<?php else:?>
						<td>日付指定しない</td><td><?php echo $takuhai_hours[$form_data->delivery_hour] ?></td>
			<?php endif;?>
						<td><a class='button' href='<?php echo base_url("front_order/delivery_info")?>'>配達日を変更する</a></td>
					</tr>
				</table>
				<h3>お支払方法</h3>
				<table class='contact_form'  id='table_bottom'  cellpadding='0' cellspacing='10'>
					<tr>
						<td><?php echo $payments[$form_data->payment]->method_name ?></td>
			<!-- 決済ごとに表示を変更 -->
			<?php if($form_data->payment ==PAYMENT_CREDIT):?>
					<tr><td>カード番号: <?php echo $card_number ?></td>
			<?php endif;?>
						<td><a class='button' href='<?php echo base_url("front_order/delivery_info") ?>'>お支払方法を変更する</a></td>
					</tr>
				</table>
				<table class='contact_form' id='cart_menu'>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='購入する' ><a class='edit_back' href='<?php echo base_url("front_order/delivery_info{$param}") ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
<div id='cover'><div id='clover'><img src='<?php echo base_url('images/clover.gif') ?>'></div></div>
</body>
<script>
$('input[type=submit]').on('click',function(){
	$('#clover').css({
		'position':'absolute',
		'top':$(window).scrollTop() + 150 + 'px',
		'left':Math.floor(($(window).width() - 400)/2) + 'px'
	});
	$("#cover").css({
		'width':$(document).width(),
		'height':$(document).height(),
		'background':'#fff',
		'opacity':'0.5',
		'top':'0px',
		'left':'0px',
		'z-index':'99'
	}).fadeIn('slow');
});
</script>
</html>
<?php //echo 'carts:';var_dump($this->session->userdata('carts'));echo '<br>'; ?>
<?php //foreach($this->session->userdata('carts') as $cart):?>
<?php //var_dump(unserialize($cart));?>
<?php //endforeach;?>
<?php //echo 'card_info:';var_dump($this->session->userdata('card_info'));echo '<br>';?>
<?php echo 'order_info:';var_dump($this->session->userdata('order_info')); echo '<br>';?>
<?php //echo 'customer:';var_dump($this->session->userdata('customer')); echo '<br>';?>
<?php //echo 'destination:';var_dump($this->session->userdata('destination')); echo '<br>';?>
<?php //echo 'no-member:';var_dump($this->session->userdata('no-member')); echo '<br>';?>
