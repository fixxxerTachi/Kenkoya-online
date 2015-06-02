<!doctpye html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner-square">
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
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
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
			<!--
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<?php echo $form_data->zipcode1 ?>-<?php echo$form_data->zipcode2 ?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<?php echo $form_data->prefecture ?>
						</td>
					</tr>
				-->
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
			<?php endif;?>
			<?php if($type == 'tel'):?>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<?php echo $form_data->tel ?>
						</td>
					</tr>
					<tr>
						<th><label for='tel1'>携帯電話番号</label></th>
						<td><?php echo $form_data->tel2 ?>
					</tr>
			<?php endif;?>
			<?php if($type=='maga'):?>
					<tr>
						<th><label for='mail_magazine'>メールマガ登録</label></th>
						<td><?php if(!empty($form_data->mail_magazine) && $form_data->mail_magazine = '1'):?>購読する<?php else :?>購読しない<?php endif;?></td>
					</tr>
			<?php endif;?>
			</table>
				<table id='menu'>
					<tr>
						<th class='no-back'></th>
						<td>
							<ul>
							<?php if($type=='address'):?>
								<li><a class='edit_menu' href='<?php echo site_url("front_area/check_area/mypage")?>'>住所を変更する</a></li>
							<?php else:?>
								<li><a class='edit_menu' href='<?php echo site_url("mypage/mypage_change/{$type}")?>'>変更する</a></li>
							<?php endif;?>
								<li><a class='edit_back' href='<?php echo site_url('mypage') ?>'>戻る</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>