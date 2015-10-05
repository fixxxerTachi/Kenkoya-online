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
				<h2><span class='logo_pink'>advertise</span> <?php echo $h2title ?></h2>
				<?php if(!empty($message)):?>
				<p><?php echo $message ?></p>
				<?php endif;?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<div class='container'>
					<?php echo form_open_multipart() ?>
					<dl class='csv_menu'>
						<dd><p>csvファイル　:　チラシ注文番号 , 商品コード-枝番</p></dd>
						<dd><input type='file' name='csvfile'></dd>
						<dt></dt>
						<dd><input type='submit' value='登録' class='input' style='margin-right:30px'><a class='edit_back' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>戻る</a></dd>
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