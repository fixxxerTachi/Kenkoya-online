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
						<th><label for='text'>温度帯：表示名</label>
						<td colspan='5'><input type='text' name='text' id='text' value='<?php echo $form_data->text ?>' size='70' maxlength='120'></td>
					</tr>
					<tr>
						<th><label for='max_weight'>重量</label></th>
						<td colspan='5'><input type='text' name='max_weight' value='<?php echo $form_data->max_weight ?>' size='6' maxlength='6'>g</td>
					</tr>
					<tr>
						<th><label for='max_volume'>最大体積</label></th>	
						<td colsapn='5'><input type='text' name='max_volume' value='<?php echo $form_data->max_volume ?>' size='10' maxlength='10'>立方ミリメートル
						</td>
					</tr>
					<tr>
						<th><label for='description'>温度帯：説明文</label></th>
						<td colspan='5'><textarea name='description' rows='5' cols='70'><?php echo $form_data->description ?></textarea>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td colspan='5'><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_admin/delivery_charge') ?>'>戻る</a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
/*
var calc = function(){
	var height = document.getElementById('box_height').value;
	var width = document.getElementById('box_width').value;
	var depth = document.getElementById('box_depth').value;
	var volume = Number(height) * Number(width) * Number(depth);
	var total = Number(height) + Number(width) + Number(depth);
	document.getElementById('volume').value = volume;
	document.getElementById('total').value = total;
};
document.getElementById('box_height').onchange = function(){
	calc()
};
document.getElementById('box_width').onchange = function(){
	calc()
};

document.getElementById('box_depth').onchange = function(){
	calc()
};
calc();
*/
</script>
</body>
</html>
