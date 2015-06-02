<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/cart.css') ?>'>
</head>
<body>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="body">
		<div class='content'>
			<h2><?php echo $h2title ?></h2>
				<p><?php if(isset($message)) echo $message ?></p>
				<?php echo form_open() ?>
				<table>
					<tr>
						<td><img src='<?php echo base_url(show_image($result->product_code)) ?>' width='50' height='50'></td>
						<td class='long'><?php echo $result->product_name ?></td>
						<td><?php echo number_format($result->sale_price) ?>円</td>
						<form method='post'>
						<td>購入数量</td>
						<td><?php echo form_dropdown('quantity',$select_quantity,$quantity,'id="quantity"') ?></td>
					</tr>
					</table>
					<table id='cart_menu'>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo site_url('front_cart/show_cart')?>'>カートに戻る</a></td>
							<td><input type='submit' name='submit' value='変更する'></a></td>
						</tr>
						</form>
					</table>
				</form>
		</div>
	</div>
<?php include __DIR__ . '/../templates/side_front.php' ?>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
</html>
<?php echo var_dump($result) ?>