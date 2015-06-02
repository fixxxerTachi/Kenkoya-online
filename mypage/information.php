<!doctpye html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<?php include __DIR__ . '/../templates/breadcrumb.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>mypage</span> <?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<ul class='note'>
			<?php if(!empty($informations)):?>
			<?php foreach($informations as $item):?>
				<li><a href='<?php echo site_url("mypage/information/{$item->id}")?>'><?php echo $item->title ?></a></li>
			<?php endforeach;?>
			<?php endif;?>
			</ul>
			<?php if(!empty($info)): ?>
			<h3 class='information'><?php echo $info->title ?></h3>
			<div class='info_content_area'><?php echo $info->contents ?></div>
			<?php else:?>
			<div class='info_content_area'>おしらせはありません</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>