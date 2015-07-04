<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2><?php echo $h2title ?></h2>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php if($result): ?>
					<table>
						<?php foreach($result as $row): ?>
						<tr>
							<td><img src='<?php echo base_url() . DELIVERY_IMAGE_PATH . $row->image ?>' width='80' height='100'></a></td>
							<td><?php echo $row->name ?></a></td>
							<td><?php echo $row->introduction ?></a></td>
							<td><a class='edit' href='<?php echo site_url("/admin_admin/edit_delivery_person/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->name ?>" , <?php echo $row->id ?>)'>削除</a></td>
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
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_delivery_person") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>