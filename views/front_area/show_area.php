<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<?php include __DIR__.'/../templates/breadcrumb.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>area</span> <?php echo $h2title ?></h2>
		<p class='note'>お住まいの市区町村名をクリックしてください。</p>
		<p class='links'>
<?php foreach($cities as $city):?>
			<a href='<?php echo base_url("front_area/show_area/{$area_id}/{$city->city}")?>'><?php echo $city->city ?></a>
<?php endforeach;?>
		</p>
		</div>
<?php if(!empty($result)):?>
		<div id='area_content_wrapper'>
		<h3 id='city_name'><?php echo $city_name ?></h3>
		<?php foreach($kana as $key => $value):?>
			<div id='area_content' class='clearfix'> 
				<h3><?php echo $key ?>行から始まる地名</h3>
				<?php foreach($value as $v):?>
						<?php $lists = $model->list_area_by_area($city_name,$v);?>
						<?php foreach($lists as $list):?>
						<ul>
						<?php if(!empty($lists)):?>
							<li class='kana'><a href='<?php echo base_url("front_area/result_area/{$list->zipcode}") ?>'><?php echo $list->furigana_area ?></a></li>
							<li class='address'><a href='<?php echo base_url("front_area/result_area/{$list->zipcode}") ?>'><?php echo $list->address ?></a></li>
						<?php endif;?>
						</ul>
						<?php endforeach;?>
				<?php endforeach;?>
			</div>
			<?php endforeach;?>
		</div>
<?php endif;?>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
<?php echo 'address:'; var_dump($this->session->userdata('address'));?>