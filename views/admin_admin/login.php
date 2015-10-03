<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='container'>
					<h2><span class='logo_pink'>title</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php echo form_open() ?>
					<dl>
						<dt>
						<div class='mdl-textfield mdl-js-textfield'>
							<input class='mdl-textfield__input' type='text' id='username' size='40' maxlength='40'>
							<label class='mdl-textfield__label' for='username'>UserName</label>
						</div>
						</dt><dt>
						<div class='mdl-textfield mdl-js-textfield'>
							<input class='mdl-textfield__input' type='password' name='password' id='password' size='40' maxlength='40'>
							<label class='mdl-textfield__label' for='password'>Password</label>
						</div>
						</dt>
						<dt>
							<input type='submit' name='submit' value='login'>
						</dt>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>