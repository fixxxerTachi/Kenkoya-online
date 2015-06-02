<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/policy.css') ?>'>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>policy</span> <?php echo $h2title ?></h2>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<p class='note'><?php echo $message ?><p>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
<?php if($no_member):?>
			<iframe src='<?php echo base_url('front_customer/no_member_policy') ?>' name='in'></iframe>
<?php else:?>
			<iframe src='<?php echo base_url('front_customer/member_policy') ?>' name='in'></iframe>
<?php endif;?>
			<?php echo validation_errors('<p class="error">','</p>');?>
			<?php echo form_open() ?>
				<table id='cart_menu' class='contact_form'>
					<tr>
						<td><input type='checkbox' name='agree' id='agree' value='1'><label for='agree'><?php if($no_member):?>利用規約に同意する<?php else:?>以上の会員規約に同意する<?php endif;?></label><input id='submit_button' type='submit' name='submit' value='次へ進む'>
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
