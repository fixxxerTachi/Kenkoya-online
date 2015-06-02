<!DOCTYPE html>
<html lang="ja">
<head>
<?php include __DIR__.'/../templates/meta_front.php' ?>
	<link rel='stylesheet' href='<?php echo base_url('css/style2.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/main.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/category.css') ?>'>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.easing.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.touchSwipe.min.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/script.js') ?>"></script>
	<script src='<?php echo base_url('js/mainvisual.js')?>'></script>
</head>
<body>
<div id='wrapper'>
<?php include  __DIR__.'/../templates/header_front.php' ?>
<?php include  __DIR__.'/../templates/nav_front.php' ?>
<?php include  __DIR__.'/../templates/breadcrumb.php' ?>
<div id="container" class='clearfix'>
		<div id="main">
			<div class='information clearfix'>
			<h2><span class='logo'><?php echo $h2icon ?></span> <?php echo $h2title ?></h2>
	<?php if(!empty($links)):?>
				<p class='links'><?php echo $links ?></p>
	<?php endif;?>
	<?php if(!empty($result)):?>
		<?php foreach($result as $row):?>
				<div class='outer-information'>
					<ul class='inner-information'>
						<li><span class='logo_pink_large'><?php echo $row->title ?> 掲載</span></li>
						<li><a href='<?php echo base_url("index/detail_product/{$row->id}")?>'><img src='<?php echo base_url(show_image($row->product_code, 150)) ?>' width = '150' height='150'></a></li>
						<li class='logo number'><?php echo $row->code ?></li>
						<li><?php echo $row->maker ?></li>
						<li><?php echo $row->product_name ?></li>
						<li>税抜 <?php echo number_format($row->sale_price) ?>円</li>
					</ul>
				</div>
		<?php endforeach;?>
	<?php else:?>
			<p>商品がありません</p>
	<?php endif;?>
			</div>
	<?php if(!empty($links)):?>
				<p class='links_bottom'><?php echo $links ?></p>
	<?php endif;?>
		</div>
		<?php include  __DIR__.'/../templates/side_front.php' ?>
</div>
<?php include  __DIR__.'/../templates/footer_front.php' ?>
</div>
</body>
</html>