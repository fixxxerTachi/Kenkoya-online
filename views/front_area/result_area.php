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
			<p class='note'>
	<?php if(!empty($result->zipcode)):?><?php echo '〒' . substr($result->zipcode,0,3).'-'.substr($result->zipcode,3) . ' ' . $result->prefecture.$result->city.$result->address ?><?php endif;?><br>
	<?php if($is_area): ?>
				<span style='color:orange'>○</span> 宅配スーパー配達可能エリアです。 (配達コース：　<?php echo $result->takuhai_day ?>)
	<?php else:?>
			宅配便でご希望の商品を発送致します。
	<?php endif;?>
			</p>
			<h3>健康屋宅配サービス利用にあったてのご注意</h3>
	<?php if($is_area):?>
			<p class='notice'>初めてWEBからご注文される方は、ご住所の確認やお支払方法の登録のため、弊社クローバーサポーター(配達員）が訪問し、詳しくご説明申し上げます。</p>
			<p class='notice'>健康屋宅配サービスは決まった曜日にご自宅まで商品をお届けします。指定外の曜日での配達を希望される際には、宅配便の配送となります。その際、送料をいただく場合がありますので、あらかじめご了承ください。</p>
	<?php else:?>
			<p class='notice'>宅配便でご希望の商品を発送致します。</p>
	<?php endif;?>
		</div>
		<div class='content'>
			<p><a class='button' href='<?php echo base_url('front_customer/show_policy/nav')?>'>会員登録を進める（会員規約表示)</a></p>
			<p><a class='edit' href='<?php echo base_url('/') ?>'>お買い物を続ける</a></p>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
<?php var_dump($result) ?>