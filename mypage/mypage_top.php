<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<?php include __DIR__ . '/../templates/breadcrumb.php' ?>
<div id="container">
	<div class='clearfix'>
	<div class='body info'>
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
			<?php echo validation_errors('<p class="error">','</p>');?>
		</div>
		<div id='mypage_information'>
			<h3><span class='logo'>information</span> 会員お知らせ</h3>
			<ul>
		<?php if(!empty($no_mail_message)):?>
				<li><?php echo $no_mail_message ?></li>
		<?php endif;?>
		<?php if(!empty($information)):?>
			<?php foreach($information as $info):?>
				<li><a href='<?php echo site_url("mypage/information/{$info->id}") ?>'><?php echo mb_strimwidth($info->title,0,120,'...') ?></a></li>
			<?php endforeach;?>
		<?php elseif(empty($no_mail_message) && empty($information)): ?>
				<li>お知らせはありません</li>
		<?php endif;?>
			</ul>
		</div>
	</div>
	
	<div class='body point'>
		<div class='content'>
			<div id='mypage_point'>
				<h2><span class='logo_pink'>point</span> 　獲得ポイント</h2>
				<p><span><?php echo $point->point ?></span> points</p>
			</div>
		</div>
	</div>
	</div>

	
	<div class='body clearfix'>
		<div class='content'>
			<h2><span class='logo_pink'>menu</span> 利用明細・変更手続き・ポイント交換</h2>
			<div class='mypage left'>
				<h3><span class='logo'>order</span> 注文履歴</h3>
				<ul>
					<li><a href='<?php echo site_url('mypage/mypage_order') ?>'>注文履歴を見る</a></li>
				</ul>
			</div>
			<div class='mypage'>
				<h3><span class='logo'>account</span> アカウント情報</h3>
				<ul>
					<li><a href='<?php echo site_url('mypage/mypage_account/name') ?>'>お名前の変更</a></li>
					<li><a href='<?php echo site_url('mypage/mypage_account/address') ?>'>住所の変更</a></li>
					<li><a href='<?php echo site_url('mypage/mypage_account/tel')?>'>電話番号の変更</a></li>
					<li><a href='<?php echo site_url('mypage/mypage_account/mail') ?>'>メールアドレスの変更</a></li>
					<li><a href='<?php echo site_url('mypage/select_address') ?>'>お届け先の登録・変更</a></li>
					<!--<li><a href='<?php echo site_url('mypage/mypage_account/maga') ?>'>メールマガジンの登録、解除</a></li>-->
					<li><a href='<?php echo site_url('mypage/change_auth/username') ?>'>ユーザーIDの変更</a></li>
					<li><a href='<?php echo site_url('mypage/change_auth/password') ?>'>パスワード変更</a></li>
				</ul>
			</div>
			<div class='mypage right'>
				<h3><span class='logo'>point</span> ポイント情報</h3>
				<ul>
					<li><a href='<?php echo site_url('mypage/mypage_point') ?>'>ポイント情報を見る</a></li>
					<li>ポイント獲得、獲得履歴</li>
					<li>ポイント景品交換</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>