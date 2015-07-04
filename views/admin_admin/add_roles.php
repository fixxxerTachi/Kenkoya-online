<?php include __DIR__ . '/../templates/meta.php' ?>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2>管理ユーザー一覧</h2>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
			<?php if(count($result) > 0):?>
			<form method='post' action='<?php echo site_url('admin_admin/add_admin_urls') ?>'>
			<table class='list'>
			<?php foreach($result as $row):?>
				<td><?php echo $row->username ?></td>
				<td><a class='edit' href='<?php echo site_url("admin_admin/edit_roles/{$row->id}") ?>'>権限追加</a></td>
				<td><a class='edit' onclick='del_confirm("<?php echo $row->username ?>" , <?php echo $row->id ?>)'>削除</a></td>
			</tr>
			<?php endforeach;?>
			</table>
			</form>
			<?php else:?>
			<p>登録されていません</p>
			<?php endif; ?>
		</div>
<?php if(!empty($userdata->username)):?>
		<div class='contents'>
			<h2><?php echo $userdata->username ?> アクセス権限登録</h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php echo form_open() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
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
						<td><input type='submit' name='submit' value='登録する'></td>
					</tr>
				</table>
			</form>
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
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_admin_roles") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>