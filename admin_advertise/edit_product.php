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
					<table class='detail detail-left'>
						<caption>商品台帳</caption>
						<tr>
							<th>商品番号</th>
							<td><input type='text' name='code' value='<?php echo $form_data->code ?>'></td>
						<tr>
							<th>商品コード</th>
							<td><input type='text' name='product_code' value='<?php echo $form_data->product_code ?>'></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><input type='text' name='branch_code' value='<?php echo $form_data->branch_code ?>'></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><input type='text' name='product_name' value='<?php echo $form_data->product_name ?>'></td>
						</tr>
						<tr>
							<th>規格</th>
							<td><input type='text' name='size' value='<?php echo $form_data->size ?>'></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><input type='text' name='sale_price' value='<?php echo $form_data->sale_price ?>'></td>
						</tr>
						<tr>
							<th>賞味期限</th>
							<td><input type='text' name='freshness_date' value='<?php echo $form_data->freshness_date ?>'></td>
						</tr>
						<tr>
							<th>添加物</th>
							<td><input type='text' name='additive' value='<?php echo $form_data->additive; ?>'></td>
						</tr>
						<tr>
							<th>アレルゲン</th>
							<td><input type='text' name='allergen' value='<?php echo $form_data->allergen ?>'></td>
						</tr>
						<tr>
							<th>カロリー</th>
							<td><input type='text' name='calorie' value='<?php echo $form_data->calorie ?>'></td>
						</tr>
						<tr>
							<th><label for='on_sale'>販売中</label></th>
							<td>
								<select name='on_sale'>
									<?php foreach($on_sale as $key => $value):?>
									<option value=<?php echo $key?> <?php if($key == $form_data->on_sale) echo 'selected=selected' ?>>
										<?php echo $value ?>
									</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for='note'>商品説明</label></th>
							<td><textarea name='note' cols='40' rows='3'><?php echo $form_data->note ?></textarea>
						</tr>
						<tr>
							<th><label for='note1'>商品説明1</label></th>
							<td><textarea name='note1' cols='40' rows='3'><?php echo $form_data->note1 ?></textarea>
						</tr>
						<tr>
							<th><label for='note2'>商品説明2</label></th>
							<td><textarea name='note2' cols='40' rows='3'><?php echo $form_data->note2 ?></textarea>
						</tr>
						<tr>
							<th>商品画像</th>
							<td><img src='<?php echo base_url(show_image($form_data->product_code)) ?>' width='100' height='100'></td>
						</tr>
						<tr>
							<th>商品画像の追加</th>
							<td><input type='file' name='ad_image'></td>
						</tr>
						<tr>
							<th>画像の説明</th>
							<td><input type='text' name='image_description' value='<?php echo $form_data->image_description ?>'></td>
						</tr>
					</table>
					<table class='detail detail-right'>
						<caption>商品マスタ</caption>
						<tr>
							<th>分類コード</th>
							<td><?php echo $form_data->p_category_code ?></td>
						</tr>
						<tr>
							<th>分類名</th>
							<td><?php echo $form_data->p_category_name ?></td>
						</tr>
						<tr>
							<th>商品コード</th>
							<td><?php echo $form_data->p_product_code ?></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><?php echo $form_data->p_branch_code ?></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><?php echo $form_data->p_product_name ?></td>
						</tr>
						<tr>
							<th>略名</th>
							<td><?php echo $form_data->p_short_name ?></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><?php echo $form_data->p_sale_price ?></td>
						</tr>
						<tr>
							<th>仕入単価</th>
							<td><?php echo $form_data->p_cost_price ?></td>
						</tr>
						<tr>
							<th><label for='on_sale'>販売中</label></th>
							<td>
							<!--
								<select name='p_on_sale'>
									<?php foreach($on_sale as $key => $value):?>
									<option value=<?php echo $key?> <?php if($key == $form_data->p_on_sale) echo 'selected=selected' ?>>
										<?php echo $value ?>
									</option>
									<?php endforeach; ?>
								</select>
							-->	
								<?php $list = array(0=>'終売','販売中');?>
								<?php echo $list[$form_data->p_on_sale]; ?>
							</td>
						</tr>
						<tr><td></td><td style='text-align:center';>アレルゲン</td></tr>
						<tr>
							<th>アレルゲン</th>
							<td>
								<?php foreach($allergens as $a):?>
								<input type='checkbox' name='allergens[]' value='<?php echo $a->id ?>' id='allergen_<?php echo $a->id ?>'
								<?php if(!empty($form_data->allergens)):?>
									<?php if(in_array($a->id , $form_data->allergens)) echo ' checked=checked' ?>
								<?php endif;?>
								>
								<label for='allergen_<?php echo $a->id ?>'><?php echo $a->name ?></label>
								<?php endforeach; ?>
							</td>
						</tr>
<!--
						<?php if(!empty($form_data->p_image_name)):?>
						<tr>
							<th><label for='image' >画像<br>追加・変更</label></th>
							<td><img src='<?php echo base_url() ?>images/<?php echo IMAGE_PATH ?><?php echo $form_data->p_image_name ?>' width='100' height='100'></td>
						</tr>
						<?php endif; ?>
						<tr>
							<th>画像データの<br>追加・変更</th>
							<td><input type='file' name='image'></td>
						</tr>
						<tr>
							<th><label for='p_image_description'>画像の説明</label></th>
							<td><input type='text' name='p_image_description' id='p_image_description' value='<?php echo $form_data->p_image_description ?>'></td>
						</tr>
						<tr>
							<th><label for='contents'>商品の内容</label></th>
							<td><textarea name='p_contents' rows='2' cols='50' id='contents'><?php echo $form_data->p_contents ?></textarea></td>
						</tr>
						<tr>
							<th><label for='jan_code'>JANコード</label></th>
							<td><input type='text' name='jan_code'  id='jan_code' value='<?php echo $form_data->jan_code ?>'></td>
						</tr>

						<tr>
							<th>修正価格</th>
							<td>
								<label for='price1'>価格1</label><input type='text' name='price1' id='price1' value='<?php echo $form_data->price1 ?>' size='4' maxlength='4'>
								<label for='price2'>価格2</label><input type='text' name='price2' id='price2' value='<?php echo $form_data->price2 ?>' size='4' maxlength='4'>
								<label for='price3'>価格3</label><input type='text' name='price3' id='price3' value='<?php echo $form_data->price3 ?>' size='4' maxlength='4'>
							</td>
						</tr>
-->
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo base_url('admin_advertise/list_product/' . $ad_id) ?>'>戻る</a></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>