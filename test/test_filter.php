<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<p><?php if(!empty($name)) echo html_escape($name); ?></p>
		<?php echo form_open() ?>
			<input type='text' name='name'><input type='submit' value='post'>
		</form>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>