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
				<?php echo form_open_multipart() ?>
					<table class='detail'>
						<tr>
							<th>商品分類名</th>
							<td><?php echo $form_data->category_name ?></td>
						<tr>
							<th>商品コード</th>
							<td><?php echo $form_data->product_code ?></td>
						</tr>
						<tr>
							<th><label for='branch_code'>枝番</label></th>
							<td><input type='text' name='branch_code' id='branch_code' value='<?php echo $form_data->branch_code ?>' size='2' maxlength='2'></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><?php echo $form_data->product_name ?></td>
						</tr>
						<tr>
							<th>略名</th>
							<td><?php echo $form_data->short_name ?></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><?php echo $form_data->sale_price ?></td>
						</tr>
						<tr>
							<th>仕入単価</th>
							<td><?php echo $form_data->cost_price ?></td>
						</tr>
						<tr>
							<th><label for='show_name'>表示名</label></th>
							<td><input type='text' name='show_name' id='show_name' value='<?php echo $form_data->show_name ?>'></td>
						<tr>
							<th><label for='on_sale'>販売中</label></th>
							<td>
								<select id='on_sale' name='on_sale'>
									<?php foreach($on_sale as $key => $value):?>
									<option value=<?php echo $key?> <?php if($key == $form_data->on_sale) echo 'selected=selected' ?>>
										<?php echo $value ?>
									</option>
									<?php endforeach; ?>
								</select>
						</tr>
						<tr>
							<th>アレルゲン</th>
							<td>
								<?php foreach($allergens as $a):?>
								<input type='checkbox' name='allergen[]' value='<?php echo $a->id ?>' id='allergen_<?php echo $a->id ?>'
								<?php if(!empty($form_data->allergen)):?>
									<?php if(in_array($a->id , $form_data->allergen)) echo ' checked=checked' ?>
								<?php endif;?>
								>
								<label for='allergen_<?php echo $a->id ?>'><?php echo $a->name ?></label>
								<?php endforeach; ?>
							</td>
						</tr>
						<?php if(!empty($form_data->image_name)):?>
						<tr>
							<th>商品画像</th>
							<td><img src='<?php echo base_url() ?>images/<?php echo IMAGE_PATH ?><?php echo $form_data->image_name ?>' width='100' height='100'></td>
						</tr>
						<?php endif; ?>
						<tr>
							<th><label for='image' >画像<br>追加・変更</label></th>
							<td><input type='file' name='image' id='image'></td>
						</tr>
						<tr>
							<th><label for='image_description'>画像の説明</label></th>
							<td><input type='text' name='image_description'  id='image_description' value='<?php echo $form_data->image_description ?>'></td>
						</tr>
						<tr>
							<th><label for='contents'>商品の内容</label></th>
							<td><textarea name='contents' rows='2' cols='70' id='contents'><?php echo $form_data->contents ?></textarea></td>
						</tr>
						<tr>
							<th><label for='jan_code'>JANコード</label></th>
							<td><input type='text' name='jan_code'  id='jan_code' value='<?php echo $form_data->jan_code ?>'></td>
						</tr>
<!--						<tr>
							<th>修正価格</th>
							<td>
								<label for='price1'>価格1</label><input type='text' name='price1' id='price1' value='<?php echo $form_data->price1 ?>' size='4' maxlength='4'>円
								<label for='price2'>価格2</label><input type='text' name='price2' id='price2' value='<?php echo $form_data->price2 ?>' size='4' maxlength='4'>円
								<label for='price3'>価格3</label><input type='text' name='price3' id='price3' value='<?php echo $form_data->price3 ?>' size='4' maxlength='4'>円
							</td>
						</tr>
-->
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo site_url('admin_product/list_product') ?>'>戻る</a></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>