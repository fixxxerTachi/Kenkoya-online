<!doctype html>
<html lang ='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/contact.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>info</span> <?php echo $h2title ?></h2>
			<div class='content-inner'>
			<?php if(!empty($info->image_name)):?>
				<p><img src='<?php echo base_url($info->image_name) ?>' alt='<?php echo $info->image_description ?>'></p>
			<?php endif;?>
				<p><?php echo $info->content ?></p>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>