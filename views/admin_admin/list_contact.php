<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2><?php echo $h2title ?></h2>
				<?php if(!empty($message)):?>
				<p class='message'><?php echo $message ?></p>
				<?php endif;?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php if(!empty($result)): ?>
					<table class='list'>
						<tr><th>カテゴリ</th><th>お名前</th><th>内容</th><th>投稿日</th><th>返信</th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo $categories[$row->category_id] ?></td>
							<td><?php echo $row->name ?></td>
							<td><?php echo $row->content ?></td>
							<td><?php echo $row->create_datetime ?></td>
							<td><?php if($row->reply_flag == 0):?><a class='edit' href='<?php echo base_url("admin_admin/reply_contact/{$row->id}") ?>'>未返信</a><?php else:?>済<?php endif;?></td>
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