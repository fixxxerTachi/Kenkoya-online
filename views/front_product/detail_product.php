<!DOCTYPE html>
<html lang="ja">
<head>
<?php include __DIR__.'/../templates/meta_front.php' ?>
	<link rel='stylesheet' href='<?php echo base_url('css/style2.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/main.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/detail.css') ?>'>
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
<?php if(!empty($row)):?>
		<div class='information clearfix'>
		<h2><?php echo $h2title ?></h2>
			<ul class='detail-information1'>
				<li class='advertise'><span class='logo_pink_large'><?php echo $row->title ?> 掲載</span></li>
				<li><img src='<?php echo base_url(show_image($row->product_code)) ?>' width = '300' height='300'></li>
				<li class='logo number'><?php echo $row->code ?></li>
			</ul>
			<p class='detail-information2'>
				<?php if(!empty($row->description)):?>
					<?php echo $row->description ?>
				<?php endif;?>
			</p>
			<table class='detail-information3'>
				<tr><th><span class='logo'>メーカー</span></th><td><?php echo $row->maker ?></td></tr>
				<tr><th><span class='logo'>商品名</span></th><td><?php echo $row->product_name ?></td></tr>
				<tr><th><span class='logo'>内容量</span></th><td><?php echo $row->size ?></td></tr>
				<tr><th><span class='logo'>賞味期限</span></th><td><?php echo $row->freshness_date ?></td></tr>
				<tr><th><span class='logo'>カロリー</span></th><td><?php echo $row->note ?></td></tr>
				<tr><th><span class='logo'>アレルゲン</span></th><td><?php echo $row->allergen ?></td></tr>
				<tr><th><span class='logo'>価格(税抜)</span></th><td><?php echo number_format($row->sale_price) ?>円</td></tr>
				<tr><th><span class='logo'>配送</span></th><td><?php echo $short_names[$row->temp_zone_id] ?></td></tr>
			</table>
			<ul class='cart clearfix'>
			<?php echo form_open('front_cart/input_cart') ?>
				<input type='hidden' name='product_id' value='<?php echo $row->id ?>'>
				<input type='hidden' name='advertise_id' value='<?php echo $row->advertise_id ?>'>
				<input type='hidden' name='sale_price' value='<?php echo $row->sale_price ?>'>
				<input type='hidden' name='product_code' value='<?php echo $row->product_code ?>'>
				<input type='hidden' name='branch_code' value='<?php echo $row->branch_code ?>'>
	<?php //個別商品の販売期間が設定されているものはカートに入れるを表示しない?>
	<?php $today = new DateTime();?>
	<?php $ssdatetime = $row->sale_start_datetime ? new DateTime($row->sale_start_datetime) : new DateTime('1000-01-01 00:00:00');?>
	<?php $sedatetime = $row->sale_end_datetime ? new DateTime($row->sale_end_datetime) : new DateTime('9999-12-31 23:59:59');?>
	<?php if($ssdatetime <= $today && $sedatetime > $today):?>
				<li>数量 <?php echo form_dropdown('quantity',$select_quantity,'1','size=1') ?></li>
				<li><input class='cart_button' name='submit' type='submit' value='カートに入れる'></li>
	<?php else:?>
				<li>申し訳ありません。現在お取扱いしておりません。</li>
	<?php endif;?>
			</form>
			</ul>
		</div>
<?php else:?>
		<p>商品がありません</p>
<?php endif;?>
		</div>
	<?php include  __DIR__.'/../templates/side_front.php' ?>
</div>
<?php include  __DIR__.'/../templates/footer_front.php' ?>
</div>
</body>
</html>
