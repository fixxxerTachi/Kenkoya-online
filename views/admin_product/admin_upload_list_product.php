<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
			<?php if(count($result) > 0):?>
				<table>
					<?php foreach($result as $row): ?>
					<tr>
						<td><?php printf("%05d",$row->product_code) ?></td>
						<td>
							<a href='<?php echo site_url("/admin_product/detail_product/{$row->id}") ?>'><?php echo $row->product_name ?></a>
						</td>
						<td><a class='edit' href='<?php echo site_url("/admin_product/edit_product/{$row->id}") ?>'>変更</a></td>
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