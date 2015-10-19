<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<script>
window.onload=function(){
	var url = location.href;
	console.log(url);
	var arr = url.split('/');
	if(arr[5]){
		$('#form_table').css({
			'padding': '10px',
			'border':'2px solid #FAAC58',
			'border-radius':'5px',
		});
	}
}
</script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='mdl-cell mdl-cell--6-col'>
					<h2><span class='logo_pink'>span</span> 配達日間隔一覧</h2>
					<?php if($result): ?>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr><th>宅急便指定日時の間隔</th><th>宅配次回お届日間隔</th></tr>
						<tr>
							<td><?php echo $result->span ?>日</td>
							<td><?php echo $result->takuhai_span ?>日</td>
							<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_span/{$result->id}") ?>'>変更</a></td>
						</tr>
					</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
	<?php if($edit_flag):?>
				<div class='mdl-cell mdl-cell--6-col'>
					<h2><span class='logo_pink'>span </span> 配達日間隔変更</h2>
					<div style='border:solid 2px orange;padding:10px;'>
						<?php echo form_open() ?>
							<table class='mdl-data-table mdl-js-data-table'>
								<tr><th>宅急便指定日時の間隔</th><th>宅配次回お届日間隔</th><th></th></tr>
								<tr>
									<td><input type='text' name='span' value='<?php echo $result->span ?>' size='2' maxlength='2'>日</td>
									<td><input type='text' name='takuhai_span' value='<?php echo $result->takuhai_span ?>' size='2' maxlength='2'>日</td>
									<td><input type='submit' name='submit' value='変更'></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
	<?php endif;?>
			</div>
		</main>
	</div>
</body>
</html>