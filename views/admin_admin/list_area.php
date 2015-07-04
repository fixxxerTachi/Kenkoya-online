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
				<p class='links'>
				<?php foreach($list_city as $city):?>
					<a href='<?php echo site_url("admin_admin/list_area/{$city->city}") ?>'><?php echo $city->city ?></a>
				<?php endforeach;?>
				</p>
				<p><?php echo form_open() ?><label for='zipcode'>郵便番号</label><input type='text' id='zipcode' name='zipcode' value='<?php echo $zipcode ?>' size='7' maxlength='7'><input type='submit' name='submit' value='検索'></p>
				<?php if(!empty($result)): ?>
					<table class='list'>
						<tr><th>郵便番号</th><th>県名</th><th>市区町村</th><th>住所</th><th>コース名</th><th>配達日</th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo $row->zipcode ?></a></td>
							<td><?php echo $row->prefecture ?></a></td>
							<td><?php echo $row->city ?></a></td>
							<td><?php echo $row->address ?></a></td>
							<td><?php echo $row->cource_name ?></a></td>
							<td><?php echo $row->takuhai_day ?></a></td>
							<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_area/{$row->id}") ?>'>変更</a></td>
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