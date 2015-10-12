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
					<h2><span class='logo_pink'>address</span> <?php echo $h2title ?></h2>
					<?php echo form_open_multipart() ?>
					<dl class='csv_menu'>
						<dt class='message'><?php echo $message ?></dt>
						<dd><input type='checkbox' name='trancate' id='trancate'>
							<label for='trancate'>既存のデータを破棄して新たにデータを作成します。既存のデータに追加する場合チェックをはずしてください</label>
						</dd>
						<dd>0:precture_id,1:郵便番号,2:県名,3:市区町村,4:地名</dd>
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
		</main>
	</div>
</body>
</html>