<!DOCTYPE html>
<html lang = "ja">
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo site_url('css/cart.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container" class='clearfix'>
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>cart</span> <?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
			<?php if(!empty($list_product)):?>
			<table>
			<?php foreach($list_product as $k => $p):?>
			<tr>
			<!--
				<td><img src='<?php echo base_url(show_image($p->product_code,50)) ?>' width='50' height='50'></td>
			-->
				<td><img src='<?php echo base_url("images/products/ak{$p->product_code}.jpg") ?>' width='50' height='50'></td>
				<td class='long'><?php echo $p->product_name ?></td>
				<td><?php echo number_format($p->sale_price) ?>円</td>
				<td>数量:<?php echo $p->quantity ?></td>
				<td>
					<a class='edit' href='<?php echo site_url("cart/change_quantity/{$k}")?>'>数量を変更する</a>
					<a class='edit' href='<?php echo site_url("cart/delete_item/{$k}")?>'>カートから削除する</a>
				</td>
			</tr>
			<?php endforeach;?>
			</table>
			<?php else:?>
				<p>何も入っていません</p>
			<?php endif;?>
			<table id='cart_menu'>
		<?php if(!empty($list_product)):?>
				<tr>
					<td>
						<a class='edit' href='<?php echo site_url("index/index")?>'>お買い物を続ける</a>
					</td>
					<td>
						<a class='edit' href='<?php echo site_url('cart/empty_cart')?>'>カートを空にする</a>
					</td>
					<td>
						<a class='cart_proceed' href='<?php echo site_url('customer/login_action/process')?>'>購入処理に進む</a>
					</td>
				</tr>
		<?php else:?>
				<tr><td><a class='edit' href='<?php echo site_url("index/index")?>'>お買い物を続ける</a></td></tr>
		<?php endif;?>
			</table>
		</div>
	</div>
<?php include __DIR__ . '/../templates/side_front.php' ?>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
