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
									<?php echo $categories[$form_data->category_id] ?>
								</td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric"><label for='content'>本文</label></th>
								<td><?php echo nl2br($form_data->content) ?></td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td>
									<a class='edit_back' href='<?php echo site_url("admin_admin/reply_contact/{$form_data->id}") ?>'>返信する</a>
									<a class='edit_back' href='<?php echo site_url('admin_admin/list_contact') ?>'>戻る</a>
								</td>
							</tr>
						</table>
				</div>
			</div>
		</main>
	</div>
</body>
</html>