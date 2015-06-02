<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<p><?php if(isset($message)) echo $message ?></p>
					<table class='detail'>
						<tr>
							<th>カテゴリ</th>
							<td><?php echo $result->category ?></td>
						</tr>
						<tr>
							<th>質問</th>
							<td><?php echo nl2br($result->question) ?></td>
						</tr>
						<tr>
							<th>質問の答え</th>
							<td><?php echo nl2br($result->question) ?></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo site_url('/admin_contents/list_contents_question/' . $result->id)?>'>登録商品リストへ戻る</a></td>
						</tr>
					</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>