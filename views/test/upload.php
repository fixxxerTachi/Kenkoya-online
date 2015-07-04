<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<?php if(!empty($message)):?>
		<p><?php echo $message ?></p>
		<?php endif;?>
		<?php if(!empty($success_message)):?>
		<p class='success'><?php echo $success_message; ?></p>
		<?php endif; ?>
		<?php if(!empty($error_message)):?>
		<p class='error'><?php echo $error_message ?></p>
		<?php endif; ?>
		<div class='contents'>
			<form action='' method='post' enctype='multipart/form-data'>
			<dl>
				<dd>商品コード	<input type='text' name='code'></dd>
				<dd><input type='file' name='image'></dd>
				<dt></dt>
				<dd><input type='submit' name='submit' value='登録' class='input'><a class='edit_back' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>戻る</a></dd>
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