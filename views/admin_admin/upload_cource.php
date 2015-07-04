<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<div>
			<?php echo form_open_multipart() ?>
			<dl>
				<dt class='message'><?php echo $message ?></dt>
				<dd><input type='checkbox' checked=checked name='trancate' id='trancate'>
					<label for='trancate'>既存のデータを破棄して新たにデータを作成します。既存のデータに追加する場合チェックをはずしてください</label></dd>
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
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message ?></p>
			<?php endif;?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>