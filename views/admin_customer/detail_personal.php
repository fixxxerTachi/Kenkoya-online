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
			<table class='detail' cellpadding='0' cellspacing='10'>
				<tr><th>ID</th><td><?php echo $detail_result->id ?></td></tr>
				<tr><th>ユーザー名</th><td><?php echo $detail_result->username ?></td></tr>
				<tr><th>ポイント</th><td><?php echo $detail_result->point  ?></td></tr>
				<tr><th>ランク</th><td><?php echo  $detail_result->rank ?></td></tr>
				<tr><th>振込銀行名</th><td><?php echo $detail_result->bank_name ?></td></tr>
				<tr><th>口座種別</th><td><?php echo $detail_result->type_account ?></td></tr>
				<tr><th>口座番号</th><td><?php echo $detail_result->account_number ?></td></tr>
				<tr>
					<th><label for='mail_magazine'>メルマガ購読</label></th>
					<td><?php echo $merumaga_select[$detail_result->mail_magazine] ?></td>
				</tr>
		<tr><th class='no-border'></th><td><a href='<?php echo site_url("/admin_customer/list_personal")?>'>会員詳細情報リストにもどる</a></td></tr>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>