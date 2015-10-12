<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='mdl-cell mdl-cell--6-col'>
					<h2><span class='logo_pink'>user</span> 管理ユーザー一覧</h2>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php if(count($result) > 0):?>
					<table class='mdl-data-table mdl-js-data-table'>
					<?php foreach($result as $row):?>
						<td><?php echo $row->username ?></td>
						<td><a class='edit' href='<?php echo site_url("admin_admin/edit_roles/{$row->id}") ?>'>権限追加</a></td>
						<td><a class='edit' onclick='del_confirm("<?php echo $row->username ?>" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
					<?php endforeach;?>
					</table>
					<?php else:?>
					<p>登録されていません</p>
					<?php endif; ?>
					<?php echo form_open() ?>
						<h2><span class='logo_pink'>user</span> 管理ユーザー追加</h2>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th><label for='username'>ユーザー名</label></th>
								<td><input type='text' id='username' name='username' value='<?php echo $form_data->username ?>' size='40' maxlength='40'></td>
							</tr>
							<tr>
								<th><label for='password'>パスワード</label></th>
								<td><input type='password' name='password' id='password' value='<?php echo $form_data->password ?>' size='40' maxlength='40'></td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='登録する'></td>
							</tr>
						</table>
					</form>
				</div>
<?php if(!empty($userdata->username)):?>
				<div class='mdl-cell mdl-cell--6-col'>
					<h2><span class='logo_pink'>user</span> <?php echo $userdata->username ?> アクセス権限登録</h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th></th>
								<td>
		<?php foreach($controllers as $k => $v):?>
								<?php echo $v;?>
			<?php foreach($urls as $url):?>
				<?php if($k == $url->controller):?>
								<li><input type='checkbox' name='urls[]' value='<?php echo $url->id ?>' id='urls<?php echo $url->id ?>' 
								<?php if(in_array($url->id ,$checked_controllers)) echo 'checked=checked' ?>>
								<label for='urls<?php echo $url->id ?>'><?php echo $url->name ?></label></li>
				<?php endif;?>
			<?php endforeach;?>
		<?php endforeach ;?>
								</td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='edit_submit' value='登録する'></td>
							</tr>
						</table>
					</form>
				</div>
<?php endif; ?>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_admin_roles") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>