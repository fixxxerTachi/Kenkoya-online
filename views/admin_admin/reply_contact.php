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
				<h2><span class='logo_pink'>QA</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th class="mdl-data-table__cell--non-numeric">お名前:</th><td><?php echo $form_data->name ?></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric">email:</th><td><?php echo $form_data->email ?></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='title'>タイトル</label></th>
								<td>
									<input type='title' id='title' name='title' value='<?php echo $title ?>' size='70' maxlength='200'>
								</td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='content'>本文</label></th>
								<td><textarea id='content' name='content' rows='30' cols='100'><?php echo $content ?></textarea></td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='送信する' class='submit_button'><a class='edit_back' href='<?php echo site_url('admin_admin/list_contact') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>