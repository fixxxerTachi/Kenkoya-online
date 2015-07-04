<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2>登録されているメールテンプレート</h2>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>		
				<?php if($result): ?>
					<table class='list'>
						<?php foreach($result as $row): ?>
						<tr>
							<td><a href='<?php echo site_url("/admin_contents/list_mail_template/detail/{$row->id}") ?>'><?php echo html_escape($row->template_name) ?></a></td>
							<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_mail_template/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->template_name ?>" , <?php echo $row->id ?>)'>削除</a></td>
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
				<tr><th>表示名</th><td><?php echo $detail_result->template_name ?></td></tr>
					<tr><th>お客様<br>管理者</th>
					<td>
						<?php echo $reciever[$detail_result->for_customer] ?>
					</td></tr>
				<tr><th>件名</th><td><?php echo $detail_result->mail_title ?></td></tr>
				<tr><th>本文</th><td><?php echo nl2br($detail_result->mail_body) ?></td></tr>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_mail_template") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>