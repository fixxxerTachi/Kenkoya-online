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
					<h2><span class='logo_pink'>question</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php if($result): ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<?php foreach($result as $row): ?>
							<tr>
								<td><?php echo $row->id ?></td>
								<td><?php echo $row->name ?></td>
								<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_question_category/{$row->id}") ?>'>変更</a></td>
							<?php endforeach;?>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
					<table class='mdl-data-table mdl-js-data-table'>
						<?php echo form_open() ?>
							<tr>	
								<th>カテゴリ名</th><td><input type='text' name='name' id='name' value='<?php echo $form_data->name ?>'></td>
								<th class='no-border'></th><td><input type='submit' name='submit' value='登録'></td>
							</tr>
						</form>
					</table>
				</div>
			</div>
		</main>
	</div>
</body>
</html>