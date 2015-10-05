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
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr><th><label for='name'>カテゴリ名</label></th><td><input type='text' id='name' name='name' size='50'  maxlength='50' value='<?php echo $form_data->name ?>'><td></tr>
							<tr><th><label for='show_name'>表示名</label></th><td><input type='text' id='show_name' name='show_name' size='50'  maxlength='50' value='<?php echo $form_data->show_name ?>'><td></tr>
							<tr><th class='no-border'></th><td><input type='submit' value='登録' class='input' name='submit'></td></tr>
						</table>
					</form>
				</div>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<div class='container'>
					<h2><span class='logo_pink'>advertise</span> 登録されているカテゴリ</h2>
					<?php if($result): ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr><th>ID</th><th>カテゴリ名</th><th>表示名</th><th></th></tr>
							<?php foreach($result as $row): ?>
							<tr>
								<td><?php echo $row->id ?></td>
								<td><?php echo $row->name ?></td>
								<td><?php echo $row->show_name ?></td>
								<td><a href='<?php echo site_url("/admin_advertise/edit_category/{$row->id}") ?>'>変更</a></td>
							<?php endforeach;?>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
			</div>
		</main>
	</div>
</body>
</html>