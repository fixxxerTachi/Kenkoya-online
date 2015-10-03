<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="wrapper">
			<div class='container'>
				<h2><span class='logo_pink'>title</span> <?php echo $h2title ?></h2>
				<?php if(!empty($message)):?>
				<p class='message'><?php echo $message ?></p>
				<?php endif;?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php echo form_open() ?>
					<table class='detail'>
						<tr>
							<th><label for='username'>ユーザー名</label></th>
							<td><input type='text' id='username' name='username' value='' size='40' maxlength='40'></td>
						</tr>
						<tr>
							<th><label for='password'>パスワード</label></th>
							<td><input type='password' name='password' id='password' value='' size='40' maxlength='40'></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='login'></td>
						</tr>
					</table>
				</form>
			</div>
</div>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_admin_urls") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>