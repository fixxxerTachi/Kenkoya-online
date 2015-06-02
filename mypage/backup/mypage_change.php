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
			<?php echo validation_errors('<p class="error">','</p>');?>
			<form action='' method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
			<?php if($type=='name'):?>
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<input type='text' id='name' name='name' value='<?php echo $form_data->name ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ</label></th>
						<td>
							<input type='text' id='furigana' name='furigana' value='<?php echo $form_data->furigana ?>' size='60' maxlength='60'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'mail'):?>
					<tr>
						<th><label for='email_confirm'>メールアドレス</label></th>
						<td>
							<input type='text' name='email_confirm' id='email_confirm' value='<?php echo $form_data->email_confirm ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='email'>メールアドレス(確認)</label></th>
						<td>
							<input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='60' maxlength='60'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'address'):?>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td><?php echo $form_data->zipcode ?><input type='hidden' name='zipcode' value='<?php echo $form_data->zipcode ?>'>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<?php echo $form_data->prefecture ?><input type='hidden' name='prefecture' value='<?php echo $form_data->prefecture ?>'>
						</td>
					</tr>
					<tr>
						<th><label for='street'>住所</label></th>
						<td>
							<?php echo $form_data->address1 ?><input type='text' name='street' id='street' value='<?php echo $form_data->street ?>' size='60' maxlength='60'>
							<input type='hidden' name='address1' value='<?php echo $form_data->address1 ?>'>
						</td>
					</tr>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td>
							<input type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<input type='text' name='tel' id='tel' value='<?php echo $form_data->tel ?>' size='15' maxlength='15'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'maga'):?>
					<tr>
						<th><label for='maga'>メルマガ登録</label></th>
						<td>健康屋からのメールマガジンの購読を希望する場合はチェックしてください<br><input type='checkbox' name='maga' id='maga' value='1' <?php if(!empty($form_data->mail_magazine) && $form_data->mail_magazine == '1') echo "checked=checked"; ?>><label for='maga'>メールマガジンの購読を希望する</label>
					</tr>
			<?php endif;?>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='変更する'><a class='edit_back' href='<?php echo site_url('mypage/mypage') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>