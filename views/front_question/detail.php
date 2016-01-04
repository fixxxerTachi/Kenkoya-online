<!DOCTYPE html>
<html lang="ja">
<head>
<?php include __DIR__.'/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/question.css') ?>'>
</head>
<body>
<div id='wrapper'>
	<?php include __DIR__.'/../templates/header_front_no_main.php' ?>
	<?php include __DIR__.'/../templates/nav_front.php' ?>
	<?php include __DIR__.'/../templates/breadcrumb.php' ?>
	<div id='container' class='clearfix'>
		<div id='container-inner'>
			<div class='content'>
				<h2><span class='logo_pink_large'>FAQ</span> よくあるご質問(FAQ)</h2>
			<?php if(!empty($result)):?>
				<h3><span class='logo_q'>Q</span> <?php echo $result->question ?></h3>
				<div class='answer'>
					<span class='logo_a'>A</span> <?php echo nl2br(sub_str($search,$replace,$result->answer)) ?>
				</div>
			<?php else:?>
				<p>ご質問はございません</p>
			<?php endif;?>
			</div>
		</div>
	<?php include __DIR__.'/../templates/side_front_question.php' ?>
	</div>
	<?php include __DIR__.'/../templates/footer_front.php' ?>
</div>
</body>
</html>