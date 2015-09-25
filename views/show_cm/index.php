<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
<style type='text/css'>
table{
	padding: 0 0 0 25px;
}
</style>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>CM</span> <?php echo $h2title ?></h2>
			<p class='note'>宅配スーパー健康屋ではテレビでCMを放送しております。</p>
			<table>
				<tr>
					<td><iframe width="480" height="270" src="https://www.youtube.com/embed/sUaXYTHfZkg" frameborder="0" allowfullscreen></iframe></td>
					<td><iframe width="480" height="270" src="https://www.youtube.com/embed/xcPjG2pSIgk" frameborder="0" allowfullscreen></iframe></td>
				</tr>
				<tr>
					<td><iframe width="480" height="270" src="https://www.youtube.com/embed/_hDCMtCXkuw" frameborder="0" allowfullscreen></iframe></td>
					<td><iframe width="480" height="270" src="https://www.youtube.com/embed/tU4PmmTKulc" frameborder="0" allowfullscreen></iframe></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
