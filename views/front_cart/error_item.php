<!DOCTYPE html>
<html lang = "ja">
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/cart.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container" class='clearfix'>
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>cart</span> <?php echo $h2title ?></h2>
			<table>
				<tr><img src='<?php echo base_url('images/sorry.jpg') ?>' width='150' height='150' alt='申し訳ございませんこちらの商品はただいまお取扱いできません'></tr>
				<tr><p>申し訳ございません。こちらの商品はただいまお取扱いしておりません。</tr>
			</table>
			<table id='cart_menu'>
				<tr>
					<td>
						<a class='edit' href='<?php echo base_url("index/index")?>'>お買い物を続ける</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?php include __DIR__ . '/../templates/side_front.php' ?>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
