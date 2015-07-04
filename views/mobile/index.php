<!DOCTYPE html>
<html lang="ja">
<head>
	<?php include 'templates/meta_front.php' ?>
	<?php if($agent != 'others'):?>
	<link rel='stylesheet' href='<?php echo base_url('css/top-mobile.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('swiper/css/swiper.min.css') ?>'>
	<script src='<?php echo base_url('swiper/js/swiper.min.js') ?>'></script>
	<?php else:?>
	<link rel='styesheet' href='<?php echo base_url('css/top.css') ?>'>
	<?php endif;?>
	<style type='text/css'>
		.swiper-container{
			width: 100%;
			height: 300px;
		}
	</style>
	<script>
		$(document).ready(function(){
			var mySwiper = new Swiper('.swiper-container',{
				slidesPerView: 3,
				spaceBetween: 10
			})
		});
	</script>
</head>
<body>
<div id='wrapper'>
<?php include 'templates/header_front_no_main.php' ?>
<?php include 'templates/mobile_nav.php' ?>
<div id="container">
	<div id="main">
		<?php include 'templates/form_area.php' ?>
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