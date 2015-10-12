<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="mdl-cell mdl-cell--12-col">
					<h2><span class='logo_pink'>advertise</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
				</div>
				<div class="mdl-cell mdl-cell--6-col">
					<?php echo form_open_multipart() ?>
					<table class='detail detail-left'>
						<caption>商品台帳</caption>
						<tr>
							<th>注文番号</th>
							<td><input type='text' name='code' value='<?php echo $form_data->code ?>'></td>
						</tr>
						<tr>
							<th>商品コード</th>
							<td><input type='text' name='product_code' value='<?php echo $form_data->product_code ?>'></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><input type='text' name='branch_code' value='<?php echo $form_data->branch_code ?>'></td>
						</tr>
						<tr>
							<th>製造元</th>
							<td><input type='text' name='vendor_code' value='<?php echo $form_data->maker ?>'>
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
							<th>仕入原価</th>
							<td><input type='text' name='cost_price' value='<?php echo $form_data->cost_price ?>'></td>
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
							<th>温度帯</th>
							<td><?php echo form_dropdown('temp_zone',$temp_names,$form_data->temp_zone_id,"id='temp_zone'");?></td>
						</tr>
						<tr>
							<th>重量</th>
							<td><input type='text' name='weight' value='<?php echo $form_data->weight ?>'></td>
						</tr>
						<tr>
							<th>幅</th>
							<td><input type='text' name='width' value='<?php echo $form_data->width ?>'></td>
						</tr>
						<tr>
							<th>高さ</th>
							<td><input type='text' name='height' value='<?php echo $form_data->height ?>'></td>
						</tr>
						<tr>
							<th>奥行</th>
							<td><input type='text' name='depth' value='<?php echo $form_data->depth ?>'></td>
						</tr>
						<tr>
							<th>体積</th>
							<td><?php echo number_format($form_data->volume) ?>g</td>
						</tr>
					</table>
				</div>
				<div class="mdl-cell mdl-cell--6-col">
					<table class='detail detail-right'>
						<caption>その他情報</caption>
						<tr>
							<th><label for='max_quantity'>最大販売数量</label></th>
							<td><input type='text' name='max_quantity' id='max_quantity' size='4' maxlength='4' value='<?php echo $form_data->max_quantity ?>'></td>
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
							<td><input type='file' name='image'></td>
						</tr>
						<tr>
							<th>画像の説明</th>
							<td><input type='text' name='image_description' value='<?php echo $form_data->image_description ?>'></td>
						</tr>
						<tr>
							<th>販売開始日時</th>
<?php $ssdt = $form_data->sale_start_datetime ? new DateTime($form_data->sale_start_datetime) : null;?>
<?php $ssdate = $ssdt ? $ssdt->format('Y/m/d') : ''; ?>
<?php $sstime = $ssdt ? $ssdt->format('H:i:s') : ''; ?>
							<td>
								<input type='text' id='sale_start_date' name='sale_start_date' value='<?php echo $ssdate ?>'>
								<?php echo form_dropdown('sale_start_time',$hour_list,$sstime) ?>:00から
							</td>
						</tr>
						<tr>
							<th>販売終了日時</th>
<?php $sedt = $form_data->sale_end_datetime ? new DateTime($form_data->sale_end_datetime) : null;?>
<?php $sedate = $sedt ? $sedt->format('Y/m/d') : '';?>
<?php $setime = $sedt ? $sedt->format('H:i:s') : '';?>

							<td>
								<input type='text' id='sale_end_date' name='sale_end_date' value='<?php echo $sedate?>'>
								<?php echo form_dropdown('sale_end_time',$hour_list,$setime) ?>:00前に終了	
							</td>
						</tr>
						<tr>
							<th>配送開始日時</th>
<?php $dsdt = $form_data->delivery_start_datetime ? new DateTime($form_data->delivery_start_datetime) : null;?>
<?php $dsdate = $dsdt ? $dsdt->format('Y/m/d') : '' ;?>
<?php $dstime = $dsdt ? $dsdt->format('H:i:s'): '' ;?>
							<td>
								<input type='text' id='delivery_start_date' name='delivery_start_date' value='<?php echo $dsdate ?>'>
								<?php echo form_dropdown('delivery_start_time',$hour_list,$dstime) ?>:00から
							</td>
						</tr>
						<tr>
							<th>配送終了日時</th>
<?php $dedt = $form_data->delivery_end_datetime ? new DateTime($form_data->delivery_end_datetime) : null; ?>
<?php $dedate = $dedt ? $dedt->format('Y/m/d') : '' ?>
<?php $detime = $dedt ? $dedt->format('H:i:s') : '' ?>
							<td>
								<input type='text' id='delivery_end_date' name='delivery_end_date' value='<?php echo $dedate ?>'>
								<?php echo form_dropdown('delivery_end_time',$hour_list,$detime) ?>:00前に終了
							</td>
						</tr>	
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='登録する' class='submit_button'><a class='edit_back' href='<?php echo base_url('admin_advertise/list_product/' . $form_data->advertise_id) ?>'>戻る</a></td>
						</tr>
					</table>
<!--
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
								<select name='p_on_sale'>
									<?php foreach($on_sale as $key => $value):?>
									<option value=<?php echo $key?> <?php if($key == $form_data->p_on_sale) echo 'selected=selected' ?>>
										<?php echo $value ?>
									</option>
									<?php endforeach; ?>
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
						</tr
						<tr>
							<th class='no-border'></th>
							<td><input type='submit' name='submit' value='登録する'><a class='edit_back' href='<?php echo base_url('admin_advertise/list_product/' . $ad_id) ?>'>戻る</a></td>
						</tr>
					</table>
-->
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>
