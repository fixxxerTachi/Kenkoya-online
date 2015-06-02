<?php include __DIR__ . '/../templates/meta_front.php' ?>
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
			<form method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
			<?php if($type == 'name'):?>
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<?php echo $form_data->name ?>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ</label></th>
						<td>
							<?php echo $form_data->furigana ?>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'mail'):?>
					<tr>
						<th><label for='email'>メールアドレス</label></th>
						<td>
							<?php echo $form_data->email ?>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'address'):?>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<?php echo $form_data->zipcode1 ?>-<?php echo $form_data->zipcode2 ?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<?php echo $form_data->prefecture ?>
						</td>
					</tr>
					<tr>
						<th><label for='address1'>住所</label></th>
						<td>
							<?php echo $form_data->address1 ?>
						</td>
					</tr>
					<?php if(!empty($form_data->address2)): ?>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td>
							<?php echo $form_data->address2 ?>
						</td>
					</tr>
					<?php endif;?>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<?php echo $form_data->tel ?>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type=='maga'):?>
					<tr>
						<th><label for='mail_magazine'>メールマガ登録</label></th>
						<td><?php if(!empty($form_data->mail_magazine) && $form_data->mail_magazine = '1'):?>購読する<?php else :?>購読しない<?php endif;?></td>
					</tr>
			<?php endif;?>
					<tr>
						<th class='no-border'></th>
						<td>
							<?php if($type=='address'):?>
							<a class='edit' href='<?php echo site_url("front_customer/check_area/mypage")?>'>住所を変更する</a>
							<?php else:?>
							<a class='edit' href='<?php echo site_url("mypage/mypage_change/{$type}")?>'>変更する</a>
							<?php endif;?>
							<a class='edit_back' href='<?php echo site_url('mypage/mypage') ?>'>戻る</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>