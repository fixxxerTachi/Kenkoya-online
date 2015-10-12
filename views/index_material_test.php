<!DOCTYPE html>
<html lang="ja">
<head>
<?php include 'templates/meta_front.php' ?>
	<link rel='stylesheet' href='<?php echo base_url('css/style2.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/top.css') ?>'>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.easing.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.touchSwipe.min.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/script.js') ?>"></script>
	<script src='<?php echo base_url('js/mainvisual.js')?>'></script>
</head>
<body>
<div id='wrapper'>
<?php include 'templates/header_front.php' ?>
<?php include 'templates/nav_front.php' ?>
<?php include 'templates/breadcrumb.php' ?>
<div id="container" class='clearfix'>
	<div id="main">
		<?php include 'templates/information_area.php' ?>
		<?php include 'templates/top5_area.php' ?>
		<?php include 'templates/recommend_area.php' ?>
	</div>
	<?php include 'templates/side_front.php' ?>
</div>
<?php include 'templates/footer_front.php' ?>
</div>
</body>
</html>