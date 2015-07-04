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
		<h2><span class='logo_pink'>address</span> <?php echo $h2title ?></h2>
			<?php echo form_open() ?>
				<input type='hidden' name='address_id' value='<?php echo $form_data->id ?>'>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='name' name='name'>お名前</label></th>
						<td>
							<?php echo html_escape($form_data->name)?>
						</td>
					</tr>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<?php echo html_escape(substr($form_data->zipcode,0,3) . '-' . substr($form_data->zipcode,3,4)) ?>
						</td>
					</tr>
					<tr>
						<th><label for='address1'>住所</label></th>
						<td>
							<?php echo html_escape($form_data->address) ?>
						</td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='<?php echo $button_message ?>'> <a class='edit_back' href='<?php echo site_url('mypage/select_address') ?>'>戻る</a></td>
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