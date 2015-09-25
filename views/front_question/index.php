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
				<h3><span class='qa_logo'></span><?php echo $this->data->h3title ?></h3>
			<?php if(!empty($questions)):?>
			<?php foreach($questions as $info):?>
				<ul class = 'inner-information'>
					<li>
						<a href='<?php echo site_url("question/detail/{$info->id}") ?>'><span class='logo_q'>Q</span> <?php echo $info->question ?></a>
					</li>
				</ul>
			<?php endforeach;?>
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