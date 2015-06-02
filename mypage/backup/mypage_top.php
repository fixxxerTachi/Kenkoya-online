<?php include __DIR__ . '/../templates/meta_front.php' ?>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
		<h2><?php echo $h2title ?></h2>
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
		<?php if(!empty($information)):?>
		<div>
			<h3>会員お知らせ</h3>
			<ul>
			<?php foreach($information as $info):?>
				<li><?php echo $info->title ?></li>
			<?php endforeach;?>
			</ul>
		</div>
		<?php endif;?>
		<div>
			<h3>注文履歴</h3>
			<a href='<?php echo site_url('mypage/mypage_order') ?>'>注文履歴を見る</a>
		</div>
		<div>
			<h3>アカウント情報</h3>
			<ul>
				<li><a href='<?php echo site_url('mypage/mypage_account/name') ?>'>基本情報の変更</a></li>
				<li><a href='<?php echo site_url('mypage/mypage_account/address') ?>'>電話番号,住所変更</a></li>
				<li><a href='<?php echo site_url('mypage/mypage_account/mail') ?>'>メールアドレスの変更</a></li>
				<li><a href='<?php echo site_url('mypage/mypage_account/maga') ?>'>メールマガジンの登録、解除</a></li>
				<li><a href='<?php echo site_url('mypage/change_auth/username') ?>'>ユーザーIDの変更</a></li>
				<li><a href='<?php echo site_url('mypage/change_auth/password') ?>'>パスワード変更</a></li>
			</ul>
		</div>
		<div>
			<h3>ポイント情報</h3>
			<a href='<?php echo site_url('mypage/mypage_point') ?>'>ポイント情報を見る</a>
			<ul>
				<li>ポイント獲得、獲得履歴</li>
				<li>ポイント景品交換</li>
			</ul>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>