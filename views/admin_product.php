<?php include 'templates/meta.php' ?>
<body>
<?php include 'templates/header.php' ?>
<div id="container">
<?php include 'templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<div>
			<form action='' method='post' enctype='multipart/form-data'>
			<dl>
				<dt class='message'><?php echo $message ?></dt>
				<dd><input type='file' name='csvfile'></dd>
				<dt></dt>
				<dd><input type='submit' value='登録' class='input'></dd>
			</dl>
			</form>
			<?php if(isset($upload_message)):?>
			<p class='success'><?php echo $upload_message; ?></p>
			<?php endif; ?>			
			<?php if(isset($db_message)):?>
			<p class='success'><?php echo $db_message; ?></p>
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