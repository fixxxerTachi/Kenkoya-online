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
					<p><?php if(isset($message)) echo $message ?></p>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">カテゴリ</th>
							<td><?php echo $result->category ?></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">質問</th>
							<td><?php echo nl2br($result->question) ?></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">質問の答え</th>
							<td><?php echo nl2br($result->answer) ?></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo site_url('/admin_contents/list_contents_question/' . $result->id)?>'>登録商品リストへ戻る</a></td>
						</tr>
					</table>
				</div>
			</div>
		</main>
	</div>
</body>
</html>