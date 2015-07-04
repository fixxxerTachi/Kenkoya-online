<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2>商品サイズ情報変更</h2>
			<?php echo form_open() ?>
				<table class='detail'>
					<tr>
						<th><label for='product_code'>商品コード</th>
						<td><input type='text' name='product_code' value='<?php echo $form_data->product_code ?>'></td>
						<td><input type='submit' name='search' value='検索'</td>
					</tr>
				</table>
			</form>
		</div>
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
			<?php echo form_open() ?>
				<table class='detail'>
					<tr>
						<th><label for='product_code'>商品コード</label></th>
						<td><input type='text' name='product_code' id='product_code' value='<?php echo $form_data->product_code ?>'></td>
					</tr>
				<?php if(!empty($form_data->porduct_name)) :?>
					<tr>
						<th><label for='product_name'>商品名</label></th>
						<td>
					</tr>
				<?php endif;?>
					<tr>
						<th><label for='temp_zone'>配送区分</label></th>
						<td><?php echo form_dropdown('temp_zone',$list_temp_zone,$form_data->temp_zone_id) ?></td>
					</tr>
					<tr>
						<th><label for='weight'>重量</label></th>
						<td><input type='text' name='weight' id='weight' value='<?php echo $form_data->weight ?>'>g</td>
					</tr>
					<tr>
						<th><label for='size'>商品サイズ</label></th>
						<td>
							幅<input type='text' name='width' id='width' value='<?php echo $form_data->width ?>' size='5' maxlength='5'>mm
							高さ<input type='text' name='height' id='height' value='<?php echo $form_data->height ?>' size='5' maxlength='5'>mm
							奥行<input type='text' name='depth' id='depth' value='<?php echo $form_data->depth ?>' size='8' maxlength='8'>mm
							= 体積<input type='text' name='volume' id='volume' value='<?php echo $form_data->volume ?>' size='6' maxlength='6'>㎣
						</td>
					<tr>
						<th class='no-border'></th>
				<?php if($update_flag):?>
						<td><input type='submit' name='update' value='更新する'><a class='edit_back' href='<?php echo site_url('admin_product/product_charge') ?>'>戻る</a></td>
				<?php else:?>
						<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_product/product_charge') ?>'>戻る</a></td>
				<?php endif;?>
					</tr>
				</table>
			</form>
		</div>
		<div class='contents'>
			<h2>商品コードからCSVファイルをアップロード</h2>
			<?php if(!empty($upload_file_message)):?><p><?php echo $upload_file_message ?></p><?php endif; ?>
			<p>1:商品コード 2:温度帯　3:重量 4:高さ 5:幅　6:深さ 7:体積</p>
			<table class='detail'>
				<?php echo form_open_multipart() ?>
					<tr>
						<td>
							<input type='file' name='csvfile'>
							<input type='submit' name='upload' value='登録'>
						</td>
					</tr>
				</form>
			</table>
		</div>
		<div class='contents'>
			<h2>商品台帳からCSVファイルをアップロード</h2>
			<?php if(!empty($upload_file_message)):?><p><?php echo $upload_file_message ?></p><?php endif; ?>
			<p><?php echo form_checkbox('truncate2','1') ?>既存のデータを削除して新たに登録する場合はチェックして下さい</p>
			<p>1:広告商品番号 2:温度帯　3:重量 4:高さ 5:幅　6:深さ 7:体積</p>
			<table class='detail'>
				<?php echo form_open_multipart() ?>
					<tr><td><?php echo form_dropdown('ad_id',$ad_list) ?></td></tr>
					<tr>
						<td>
							<input type='file' name='csvfile2'>
							<input type='submit' name='upload' value='登録'>
						</td>
					</tr>
				</form>
			</table>
		</div>
		<div class='contents'>
			<h2>商品サイズが未登録のデータをダウンロードする</h2>
			<table class='detail'>
				<?php echo form_open() ?>
				<tr>
					<td><?php echo form_dropdown('ad_id_no_size',$ad_list) ?></td>
					<td><input type='submit' name='no_size' value='csvダウンロード'></td>
				</tr>
				</form>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
var calc = function(){
	var height = document.getElementById('height').value;
	var width = document.getElementById('width').value;
	var depth = document.getElementById('depth').value;
	var volume = Number(height) * Number(width) * Number(depth);
	var total = Number(height) + Number(width) + Number(depth);
	document.getElementById('volume').value = volume;
	document.getElementById('total').value = total;
};
document.getElementById('height').onchange = function(){
	calc()
};
document.getElementById('width').onchange = function(){
	calc()
};

document.getElementById('depth').onchange = function(){
	calc()
};
calc();
</script>
</body>
</html>