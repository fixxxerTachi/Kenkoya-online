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
			<?php echo form_open() ?>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<input type='text' id='zipcode' name='zipcode' value='<?php echo $form_data->zipcode ?>' size='7' maxlength='7'><input type='submit' name='post_zip' value='検索'>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<input type='text' name='prefecture' id='prefecture' value='<?php echo $form_data->prefecture ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='city'>市区町村</label></th>
						<td>
							<input type='text' name='city' id='city' value='<?php echo $form_data->city ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='address'>住所</label></th>
						<td>
							<input type='text' name='address' id='address' value='<?php echo $form_data->address ?>' size='50' maxlength='50'>
						</td>
					</tr>
					<tr>
						<th><label for='cource_id'>コースID</label></th>
						<!--<td><?php echo form_dropdown('cource_id',$cource_list,$form_data->cource_id) ?></td>-->
						<td><input type='text' name='cource_id' id='cource_id' value='<?php echo $form_data->cource_id ?>' size='3' maxlength='3'>
					</tr>
					<tr>
						<th><label for='cource_name'>コース名</label></th>
						<td><input type='text' name='cource_name' id='cource_name' value='<?php echo $form_data->cource_name ?>'></td>
					</tr>
					<tr>
						<th><label for='takuhai_day'>宅配曜日</label></th>
						<td><input type='text' name='takuhai_day' id='takuhai_day' value='<?php echo $form_data->takuhai_day ?>'></td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>