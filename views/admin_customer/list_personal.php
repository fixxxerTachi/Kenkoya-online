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
					<table class='list'>
						<tr><th>コード</th><th>お客様</th><th>お支払登録</th><th>ログイン情報登録</th><th>メルマガ</th><th>お支払情報追加</th><th>ログイン情報発行</th><th>メルマガ購読</th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo html_escape($row->code) ?></td>
							<td><?php echo html_escape($row->name)?></td>
						<td>
						<?php if(!empty($row->bank_name)):?>
							登録済
						<?php else:?>
							未登録
						<?php endif;?>
						</td>
						<td>
						<?php if(!empty($row->password)):?>
							登録済
						<?php else:?>
							未登録
						<?php endif; ?>
						</td>
						<td>
						<?php if($row->mail_magazine == 1):?>
							購読する
						<?php else:?>
							購読しない
						<?php endif; ?>
						</td>
<!--						<td><a class='edit' href='<?php echo site_url("/admin_customer/add_personal/{$row->id}")?>'>会員詳細情報追加</a></td> -->
							<td><p class='edit'><a href='<?php echo site_url("/admin_customer/add_personal_payment/{$row->id}")?>'>お支払情報<br>追加変更</a></p></td>
						<?php if(empty($row->password)):?>
							<td><p class='edit'><a href='<?php echo site_url("/admin_customer/add_personal_login_info/{$row->id}")?>'>ログイン<br>情報発行</a></p></td>
						<?php else:?>
							<td>発行済</td>
						<?php endif; ?>
							<td><p class='edit'><a href='<?php echo site_url("/admin_customer/add_personal_mail_magazine/{$row->id}") ?>'>メルマガ登録<br>情報変更</a></p></td>
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