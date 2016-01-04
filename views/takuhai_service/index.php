<!doctype html>
<html lang ='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/takuhai_service.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<?php include __DIR__.'/../templates/breadcrumb.php' ?>
<div id='container'>
	<div class='content'>
		<div id='takuhai_main'>
			<img src='<?php echo base_url('images/takuhai_service_main.jpg') ?>' width='1024px' height='300px' alt='宅配スーパー健康屋は健康と幸福をお届けします'>
		</div>
		<div class='content-inner'>
			<h2><span class='logo_pink'>happiness & health</span> 宅配牛乳と宅配商品をご自宅までお届けします。</h2>
			<div class='inner'>
				<h3>宅配サービスのご案内</h3>
				<p>宅配スーパー健康屋では宅急便で商品をお届けする以外にも、当店の配達員が直接商品を定期的お届けする宅配サービスを行っております。</p>
				<p>当WEBサイトからお申込みいただくか、お電話,FAXなどでも申し込みを受け付けております。</p>
				<p><span style='color:red'>宅配サービスを初めてご利用になられる場合、商品配送前に宅配スーパー健康屋スタッフが訪問させていただきます。</span></p>
				<h3 class='no-back'><span class='logo'>AREA</span> 健康屋宅配サービスご利用可能地域の確認</h3>
				<p class='indent'><a class='new-window' href='<?php echo site_url('area/search_area') ?>' target='_blank'>宅配サービスの配達エリアを確認する。</a></p>
				<h3 class='no-back'><span class='logo'>TEL</span> お電話での宅配サービス会員登録お申込み</h3> 
				<p class='indent'><span class='tel'>0120-383-333</span> &nbsp;営業時間 : 月曜日～金曜日（9:00 ~ 18:00）</p>
				<h3 class='no-back'><span class='logo'>MAIL</span> メールでのお問い合わせ</h3>
				<p class='indent'><a class='button' href='<?php echo site_url('contact/contact') ?>'>お問い合わせフォーム</a></span>からお問い合わせください</p>			</div>
			<div class='inner'>
				<h3>ご購入の流れ</h3>
				<div>
					<img src='<?php echo base_url('images/takuhai_service_buy_flow.jpg') ?>' width='1024' height='248' alt='宅配スーパー健康屋　宅配サービスご利用の流れ'>
				</div>
			</div>
		</div>
		<div class='content-inner clearfix'>
			<div class='inner-small'>
				<h3>よつば通信・週刊クローバーの案内</h3>
				<p class='note'>新鮮なお野菜、お魚から日用品まで、あなたに必要なものが必ず見つかります。</p>
				<p class='note'>また毎週、当店スタッフが厳選したお買得商品を掲載した「週間クローバー」を発行しています。</p>
				<p><a href='<?php echo site_url("yotsuba") ?>'><img src='<?php echo base_url('images/advertise/yotsuba05_1_1.jpg') ?>' width='300' height='210' alt='よつば通信'></a></p>
			</div>
			<div class='inner-small'>
				<h3>宅配牛乳の案内</h3>
				<p class='note_single'>宅配牛乳の説明</p>
				<p><a href=''><img src='<?php echo base_url('images/banner/takuhai_milk.jpg') ?>' width='268' height='82' alt='宅配牛乳の案内'></a></p>
			</div>
			<div class='inner-small'>
				<h3>宅配サービスご利用可能地域について</h3>
				<p class='note'>宅配スーパー健康屋の宅配サービスのご利用可能地域の検索はこちらから。</p>
				<p class='note_one'>宅配サービスご利用可能地域以外の方は、宅急便で配送いたします。</p>
				<p><a href='<?php echo site_url('area/search_area') ?>'><img src='<?php echo base_url('images/map_small.jpg') ?>' width='300' height='216' alt='宅配サービスのお届けエリア'></a></p>
			</div>
			<div class='inner-small'>
				<h3>宅配サービスのご利用規約</h3>
				<p><a class='new-window' href='<?php echo site_url('takuhai_service/show_policy') ?>' target='_blank'>宅配スーパー健康屋の利用案内</a></p>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>