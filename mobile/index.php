<!DOCTYPE html>
<html lang="ja">
<head>
<?php include 'templates/meta_front.php' ?>
	<link rel='stylesheet' href='<?php echo base_url('css/style2.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('css/top-mobile.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('swiper/css/swiper.min.css') ?>'>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.easing.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.touchSwipe.min.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/script.js') ?>"></script>
	<script src='<?php echo base_url('js/mainvisual.js')?>'></script>
	<script src='<?php echo base_url('swiper/js/swiper.min.js') ?>'></script>
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
<div id="container">
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