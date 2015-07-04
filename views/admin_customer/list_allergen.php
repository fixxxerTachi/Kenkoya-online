<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>		
				<?php if($result): ?>
					<table>
						<?php foreach($result as $row): ?>
						<tr>
							<td><a href='<?php echo site_url("/admin_product/list_allergen/detail/{$row->id}") ?>'>
								<?php echo html_escape($row->familyname) ?> <?php echo html_escape($row->firstname) ?>
							</a></td>
							<td><a class='edit' href='<?php echo site_url("/admin_product/edit_allergen/{$row->id}") ?>'>変更</a></td>
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
		<?php if(!empty($show_detail)):?>
		<div class='contents'>
			<table class='detail' cellpadding='0' cellspacing='10'>
				<tr><th>アレルゲン名</th><td><?php echo $detail_result->name ?></td></tr>
				<?php if(!empty($detail_result->description)):?>
				<tr><th>説明</th><td><?php echo $detail_result->description ?></td></tr>'
				<?php endif;?>
				<?php if(!empty($detail_result->icon)):?>
				<tr><th>画像</th>
				<td><img src='<?php echo base_url() ?>/images/<?php echo ICON_PATH ?>/<?php echo $detail_result->icon ?>' width='100' height='100'>
				<?php endif; ?>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>