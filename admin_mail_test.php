<?php include 'templates/meta.php' ?>
<body>
<?php include 'templates/header.php' ?>
<div id="container">
<?php include 'templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<div>
			<form aciton='' method='post'>
			<dl>
				<dt class='message'><?php echo $message ?></dt>
				<dd><input type='text' name='mail_address' size='50'  maxlength='50'></dd>
				<dt></dt>
				<dd><input type='submit' value='送信' class='input' name='submit'></dd>
			</dl>
			</form>
			<?php if(isset($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(isset($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include 'templates/footer.php' ?>
</body>
</html>