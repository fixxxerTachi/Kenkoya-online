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
			<img src='<?php echo base_url('images/guidance_main.jpg') ?>' width='1024px' height='300px' alt='宅配スーパー健康屋は健康と幸福をお届けします'>
		</div>
		<div class='content-inner'>
			<h2><span class='logo_pink'>guidance</span> <?php echo $h2title ?></h2>
			<div class='inner cart-back'>
				<h3>ご注文方法</h3>
				<p class='note_one'>商品掲載ページ,webチラシなどから商品を選択してカートにいれるボタンをクリックしてください。</p>
				<p class='note'>カートの「購入処理に進む」ボタンをクリックすると、ログインページに進みます。宅配スーパー健康屋会員の方はログイン情報を入力してお支払・配送方法登録ページに進んでください。</p>
				<p class='note_one'>新規に会員登録をご希望される方は、<a class='edit' href='<?php echo site_url('front_area/check_area/nav') ?>'>新規会員登録</a>より必要事項を入力してください。</p>
				<p class='note_one'>会員登録されなくてもご利用いただけます。</p>
				<p><a class='new-window' target='blank' href='<?php echo site_url('front_customer/show_policy') ?>'>利用規約を確認する。</a></p>
			</div>
			<div class='inner member-back'>
				<h3>会員登録について</h3>
				<p class='note_one'>会員登録していただくことで、次回よりご住所の入力などが不要となります。</p>
				<p class='note_one'>宅配スーパー健康屋では宅急便での配送のほかに、宅配スーパー健康屋スタッフがお客様のご自宅に商品を配達する宅配サービスを行っています。</p>
				<p class='note_one'>宅配サービスを初めてご利用になられる場合、商品配送前に宅配スーパー健康屋スタッフが訪問させていただきます。</p>
				<p><a class='new-window' target='blank' href='<?php echo site_url('takuhai_service') ?>'>宅配サービスについて詳しく知りたい</a></p>
				<p><a class='new-window' target='blank' href='<?php echo site_url('front_area/search_area') ?>'>宅配サービス配達可能エリアを確認する</a></p>
				<p><a class='new-window' target='blank' href='<?php echo site_url('index/show_policy') ?>'>個人情報の取り扱いについて確認する。</a></p>
			</div>
			<div class='inner takuhai-back'>
				<h3>配送・配送料金について</h3>
				<p class='note_one'>宅配スーパー健康屋では宅急便での配送のほかに、宅配スーパー健康屋スタッフがお客様のご自宅に商品を配達する宅配サービスを行っています。</p>
				<p><a class='new-window' href='<?php echo site_url('index/show_charge') ?>'>配送料金について詳しく確認する</a></p>
			</div>
			<div class='inner payment-back'>
				<h3>お支払方法について</h3>
				<p><a class='new-window' target='blank' href='<?php echo site_url('front_question/detail/1')?>'>お支払方法について詳しく確認する。</a></p>
			</div>
			<div class='inner mail-back'>
				<h3>お問い合わせ</h3>
				<p class='note_one'>お電話：　0120-383-333　よりお問い合わせいただくか、<a class='edit' href='<?php echo site_url('front_contact/contact') ?>'>メールフォーム</a>よりお問い合わせください</p>
				<p><a class='new-window' target='blank' href='<?php echo site_url('front_question') ?>'>よくある質問を確認する</a></p>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>