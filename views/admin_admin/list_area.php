<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='class="mdl-cell mdl-cell--6-col'>
					<h2><span class='logo_pink'>area</span> <?php echo $h2title ?></h2>
				</div>
				<div class="mdl-cell mdl-cell--6-col">
					<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored">
						<i class="material-icons">add</i>
					</button><a href='<?php echo site_url('admin_admin/add_area') ?>'>新規追加</a>
					<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored">
						<i class="material-icons">add</i>
					</button><a href='<?php echo site_url('admin_admin/upload_area') ?>'>ＣＳＶから一括追加</a>
				</div>
				<div class="mdl-cell mdl-cell--12-col">
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
								<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_area/{$row->id}/{$city_name}") ?>'>変更</a></td>
							</tr>
							<?php endforeach;?>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
			</div>
		</main>
	</div>
</body>
</html>