<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>
			<?php if(count($result) > 0):?>
				<table>
					<?php foreach($result as $row): ?>
					<tr>
						<td><?php echo $row->code?></td>
						<td>
							<a href='<?php echo base_url("/admin_advertise/detail_product/{$row->id}/{$ad_id}") ?>'><?php echo mb_strimwidth($row->product_name,0,30,'...') ?></a>
						</td>
						<?php if(!empty($row->image_name)): ?>
						<td><img src='<?php echo base_url() ?>images/<?php echo IMAGE_PATH ?><?php echo $row->image_name ?>' width='50' height='50'></td>
						<?php endif; ?>
						<form method='post' action='' enctype='mulipart/formdata'>
							<td><input type='file' name='image'></td>
							<td><input type='text' name='image_description' value='<?php echo $row->image_description ?>'></td>
							<td><input type='submit' name='submit'  value='登録'></td>
						</form>
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