<!DOCTYPE html>
<html lang = "ja">
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/advertise.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container" class="clearfix">
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>yotsuba</span> <?php echo $h2title ?></h2>
<?php if(!empty($result)):?>
			<div class='ad_list clearfix'>
	<?php foreach($result as $row):?>
				<ul class='ad_left'>
					<li><a href='<?php echo base_url("front_advertise/detail_advertise/{$row->id}") ?>'><img src='<?php echo base_url(AD_IMAGE_PATH . $row->image_name) ?>' width='300' height='210' alt='<?php echo $row->title ?>'></a></li>
				</ul>
				<ul class='ad_right'>
					<li><h3><?php echo $row->title ?></h3></li>
					<li><?php echo $row->description ?></li>
		<?php $s_date = new DateTime($row->start_datetime) ?>
		<?php $e_date = new DateTime($row->end_datetime) ?>
					<li><span class='logo'>有効期間</span> <?php echo $s_date->format('Y/m/d') ?> ~ <?php echo $e_date->format('Y/m/d') ?></li>
				</ul>
	<?php endforeach;?>
			</div>
<?php else:?>
				<p>購入可能なチラシ情報はございません</p>
<?php endif;?>
		</div>
	</div>
<?php include __DIR__ . '/../templates/side_front.php' ?>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
<?php echo '<pre>'; print_r($result); echo '</pre>';?>