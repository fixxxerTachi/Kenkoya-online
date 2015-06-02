<?php include __DIR__ . '/../templates/meta_front.php' ?>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
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
<?php if($no_member):?>
			<p>ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明</p>
<?php else:?>
			<p>会員規約の説明ーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明ポリシーの説明</p>
<?php endif;?>
			<?php echo validation_errors('<p class="error">','</p>');?>
			<form method='post' action=''>
				<table>
					<tr><td><input type='checkbox' name='agree' id='agree' value='1'><label for='agree'><?php if($no_member):?>ポリシーに同意する<?php else:?>会員規約に同意する<?php endif;?></label></tr>
					<tr><td><input type='submit' name='submit' value='次へ'></td></tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>