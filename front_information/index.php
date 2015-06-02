<!DOCTYPE html>
<html lang = "ja">
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/advertise.css') ?>'>
</head>
<body>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<?php include __DIR__ . '/../templates/breadcrumb.php' ?>
<div id="container">
	<div id="body">
		<div class='content'>
			<h2><span class='logo_pink'>info</span> <?php echo $h2title ?></h2>
<?php if(!empty($info)):?>
			<p><?php echo $info->content ?></p>
	<?php if(!empty($info->image_name)):?>
			<p><img src='<?php echo base_url("images/information/{$info->image_name}") ?>' alt='<?php echo $info->description ?>'></p>
	<?php endif;?>
<?php else:?>
			<p>お知らせはありません</p>
<?php endif;?>
		</div>
	</div>
<?php include __DIR__ . '/../templates/side_front.php' ?>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
</html>
