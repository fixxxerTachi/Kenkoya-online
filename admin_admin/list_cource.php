<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2><?php echo $h2title ?></h2>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php if($result): ?>
					<table class='list'>
						<tr><th>コースID</th><th>コース名</th><th>配達日</th><th>配達者</th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo $row->cource_id ?></a></td>
							<td><?php echo $row->cource_name ?></a></td>
							<td><?php echo $row->takuhai_day ?></a></td>
							<td><?php echo $row->delivery_person_name ?></a></td>
							<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_cource/{$row->id}") ?>'>変更</a></td>
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>