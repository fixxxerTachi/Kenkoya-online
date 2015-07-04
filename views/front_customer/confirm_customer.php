<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>confirm</span> <?php echo $h2title ?></h2>
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
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<?php echo html_escape($form_data->name)?>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ</label></th>
						<td>
							<?php echo html_escape($form_data->furigana) ?>
						</td>
					</tr>
					<tr>
						<th><label for='email'>メールアドレス</label></th>
						<td>
							<?php echo html_escape($form_data->email) ?>
						</td>
					</tr>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<?php echo html_escape(substr($form_data->zipcode,0,3) . '-' . substr($form_data->zipcode,3,4)) ?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<?php echo html_escape($form_data->prefecture) ?>
						</td>
					</tr>
					<tr>
						<th><label for='address1'>住所</label></th>
						<td>
							<?php echo html_escape($form_data->address1) ?>
						</td>
					</tr>
					<?php if(!empty($form_data->address2)): ?>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td>
							<?php echo html_escape($form_data->address2) ?>
						</td>
					</tr>
					<?php endif;?>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<?php echo html_escape($form_data->tel) ?>
						</td>
					</tr>
					<tr>
						<th><label for='tel2'>携帯電話番号</label></th>
						<td><?php echo html_escape($form_data->tel2) ?>
					</tr>
	<?php if(!empty($form_data->month)):?>
					<tr>
						<th><label for='birthday'>生年月日</label></th>
						<td>
							<?php echo html_escape("{$form_data->year}年{$form_data->month}月{$form_data->day}日") ?>
						</td>
					</tr>
	<?php endif;?>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='<?php echo $button_message ?>'><a class='edit_back' href='<?php echo base_url('front_customer/add_customer') ?><?php if($no_member == 'no-member'): ?>/no-member<?php endif;?>'>戻る</a></td>
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