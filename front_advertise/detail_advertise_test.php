<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/detail_advertise.css')?>'></head>
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet">
<script src='<?php echo base_url('js/jquery-1.11.0.min.js') ?>'></script>
<script src="<?php echo base_url() ?>js/lightbox.min.js"></script>
</head>
<body>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='body'>
		<div class='content'>
			<h2><span class='logo_pink'>yotsuba</span> <?php echo $h2title ?></h2>
<?php if(!empty($result)):?>
			<div>
				<div class='ad-nav'>
					<div class='ad-thumbs'>
						<ul class='ad-thumb-list clearfix'>
	<?php foreach($result  as $row):?>
							<li>
								<a href='<?php echo base_url('front_advertise/show_page/1/1/2')?>' onclick='return false'><img src='<?php echo base_url(AD_IMAGE_PATH . $row->image_name) ?>' width='200' height='140' alt='<?php echo $row->title ?>'></a>
							</li>
	<?php endforeach;?>
						</ul>
					</div>
					<div class = 'ad-image-wrapper'>
					</div>
				</div>
			</div>
<?php else:?>
			<p>有効なチラシはありません</p>
<?php endif;?>
		</div>
	</div>
	<div id='cover'></div>
	<div id='show_item'></div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
<script>
$('div#show_item').hide();
$('div#cover').hide();
$('.ad-image-wrapper').load('<?php echo base_url('front_advertise/show_page/1/1/2')?>');
$('ul.ad-thumb-list li a').on('click',function(){
	$('.ad-image-wrapper').load($(this).attr('href'));
});
</script>
</html>
<?php echo '<pre>';print_r($result);echo '</pre>';?>