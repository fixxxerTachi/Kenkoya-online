<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<p><?php if(isset($message)) echo $message ?></p>
				<form aciton='' method='post'>
					<table class='detail'>
						<tr>
							<th>タイトル</th>
							<td><?php echo $result->title ?></td>
						</tr>
						<tr>
							<th>内容</th>
							<td><?php echo $result->content ?></td>
						</tr>
					<?php if(!empty($result->image_name)):?>
						<tr>
							<th>画像</th>
							<td><img src='<?php echo base_url() . INFORMATION_IMAGE_PATH . $result->image_name ?>'>
								<?php echo $result->image_name ?></td>
						</tr>
						<tr>
							<th>画像の説明</th>
							<td><?php echo $result->image_description ?></td>
						</tr>
					<?php endif; ?>
						<tr>
							<th>掲載開始日</th>
							<td><?php echo $result->start_datetime ?></td>
						</tr>
						<tr>
							<th>掲載終了日</th>
							<td><?php echo $result->end_datetime ?></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit_back' href='<?php echo site_url('/admin_contents/list_information/' . $result->id)?>'>登録商品リストへ戻る</a></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>