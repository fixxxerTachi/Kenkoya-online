<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<?php if(!empty($success_message)):?>
		<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
		<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
		<div>
			<form aciton='' method='post'>
			<dl>
				<dt class='message'><?php echo $message ?></dt>
				<dd><input type='text' name='mail_address' size='50'  maxlength='50'></dd>
				<dt></dt>
				<dd><input type='submit' value='送信' class='input' name='submit'></dd>
			</dl>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__. '/../templates/footer.php' ?>
</body>
</html>