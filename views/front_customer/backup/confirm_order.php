<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<?php include __DIR__ .'/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="body">
		<div class='content'>
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
				<h3>配達先</h3>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<?php echo $form_data->name ?>
						</td>
					</tr>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<?php echo $form_data->zipcode1 ?>-<?php echo$form_data->zipcode2 ?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>住所</label></th>
						<td>
							<?php echo $form_data->prefecture ?><?php echo $form_data->address1 ?><?php echo $form_data->address2 ?>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>電話番号</label></th>
						<td>
							<?php echo $form_data->tel ?>
						</td>
					</tr>
				</table>
				<h3>配達日</h3>
				<table class='detail' cellpadding='0' cellspacing='10'>
		<?php if(!empty($form_data->delivery_date)):?>
			<?php $date = new DateTime($form_data->delivery_date) ?>
					<tr><td><?php echo $date->format('m月d日') ?></td></tr>
		<?php else:?>
					<tr><td><?php echo '通常配送' ?></td></tr>
		<?php endif;?>
				</table>
				<h3>お支払方法</h3>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr><td><?php echo $form_data->payment ?></td></tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('front_customer/add_customer') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</body>
</html>
<?php var_dump($form_data);?>
