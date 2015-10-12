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
					<h2><span class='logo_pink'>charge</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<p class='links'>
					</p>
					<?php if(!empty($zone_list)): ?>
						<table class='list'>
						<tr><th>ID</th><th>表示名</th><th></th><th></th></tr>
						<?php foreach($zone_list as $item):?>
							<tr>
								<td><?php echo $item->id ?></td>
								<td><?php echo $item->text ?></td>
								<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_zone/{$item->id}") ?>'>基本情報変更</a></td>
								<td><a class='edit' href='<?php echo site_url("admin_admin/edit_charge/{$item->id}") ?>'>住所別配送料金変更</a></td>
							</tr>
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
