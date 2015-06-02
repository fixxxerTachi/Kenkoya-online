<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<form aciton='' method='post'>
					<dl>
						<dt class='message'>
							<?php if(isset($message)) echo $message ?>
						</dt>
						<dd><input type='text' name='category_name' value='<?php echo $value ?>' size='50'  maxlength='50'></dd>
						<dt></dt>
						<dd><input type='submit' value='登録' class='input' name='submit'></dd>
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
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>