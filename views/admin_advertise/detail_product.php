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
					<?php if(isset($message)): ?>
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
					<table class='detail detail-left'>
						<caption>商品台帳</caption>
						<tr>
							<th>注文番号</th>
							<td><?php echo $result->code ?></td>
						<tr>
							<th>商品コード</th>
							<td><?php echo $result->product_code ?></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><?php echo $result->branch_code ?></td>
						</tr>
						<tr>
							<th>製造元</th>
							<td><?php echo $result->maker ?></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><?php echo $result->product_name ?></td>
						</tr>
						<tr>
							<th>規格</th>
							<td><?php echo $result->size ?></td>
						</tr>
						<tr>
							<th>仕入原価</th>
							<td><?php echo number_format($result->cost_price) ?>円</td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><?php echo number_format($result->sale_price) ?>円</td>
						</tr>
						<tr>
							<th>賞味期限</th>
							<td><?php echo $result->freshness_date ?></td>
						</tr>
						<tr>
							<th>添加物</th>
							<td><?php echo $result->additive ?></td>
						</tr>
						<tr>
							<th>アレルゲン</th>
							<td><?php echo $result->allergen ?></td>
						</tr>
						<tr>
							<th>カロリー</th>
							<td><?php echo $result->calorie ?></td>
						</tr>
						<tr>
							<th>温度帯</th>
							<td><?php echo $temp_names[$result->temp_zone_id];?></td>
						</tr>
						<tr>
							<th>重量</th>
							<td><?php echo $result->weight ?></td>
						</tr>
						<tr>
							<th>幅</th>
							<td><?php echo $result->width ?></td>
						</tr>
						<Tr>
							<th>高さ</th>
							<td><?php echo $result->height ?></td>
						</tr>
						<tr>
							<th>奥行</th>
							<td><?php echo $result->depth ?></td>
						</tr>
						<tr>
							<th>体積</th>
							<td><?php echo $result->volume ?></td>
						</tr>
					</table>
				</div>
				<div class="mdl-cell mdl-cell--6-col">
					<table class='detail detail-right'>
						<caption>その他情報</caption>
						<tr>
							<th>最大販売数量</th>
							<td><?php echo $result->max_quantity ?>個</td>
						</tr>
						<tr>
							<th>販売中</th>
							<td><?php if($result->on_sale == ONSALE): ?>販売中<?php elseif($result->on_sale == DISCON):?><span style='color:orange'>販売中止</span><?php endif;?></td>
						</tr>
						<tr>
							<th>販売開始日時</th>
							<?php $sdt = $result->sale_start_datetime ? new DateTime($result->sale_start_datetime) : '';?>
							<td><?php if(!empty($sdt)) echo $sdt->Format('Y/m/d H時'); ?></td>
						</tr>
						<tr>
							<th>販売終了日時</th>
							<?php $edt = $result->sale_end_datetime ? new DateTime($result->sale_end_datetime) : '';?>
							<td><?php if(!empty($edt)) echo $edt->Format('Y/m/d H時'); ?></td>
						</tr>
						<tr>
							<th>配達開始日時</th>
							<?php $sdt = $result->delivery_start_datetime ? new DateTime($result->delivery_start_datetime) : '';?>
							<td><?php if(!empty($sdt)) echo $sdt->Format('Y/m/d H時'); ?></td>
						</tr>
						<tr>
							<th>配達終了日時</th>
							<?php $edt = $result->delivery_end_datetime ? new DateTime($result->delivery_end_datetime) : '';?>
							<td><?php if(!empty($edt)) echo $edt->Format('Y/m/d H時'); ?></td>
						</tr>
						<tr>
							<th>商品説明</th>
							<td><?php echo nl2br($result->note) ?></td>
						</tr>
						<tr>
							<th>商品説明1</th>
							<td><?php echo nl2br($result->note1) ?></td>
						</tr>
						<tr>
							<th>商品説明2</th>
							<td><?php echo nl2br($result->note2) ?></td>
						</tr>
						<tr>
							<th>画像</th>
							<td><img src='<?php echo base_url(show_image($result->product_code)) ?>' width='100' height='100'></td>
						</tr>
						<tr>
							<th class='no-border'></th>
							<td><a class='edit' href='<?php echo base_url('/admin_advertise/list_product/' . $ad_result->id)?>'>登録商品リストへ戻る</a></td>
							<td><a class='edit' href='<?php echo base_url("/admin_advertise/edit_product/{$result->id}/{$ad_result->id}")?>'>変更</a></td>
						</tr>
					</table>
				</div>
							<!--
							<table class='detail detail-right'>
								<caption>商品マスタ</caption>
								<tr>
									<th>カテゴリコード</th>
									<td><?php echo $result->p_category_code ?></td>
								</tr>
								<tr>
									<th>カテゴリ名</th>
									<td><?php echo $result->p_category_name ?></td>
								</tr>
								<tr>
									<th>取引先コード</th>
									<td><?php echo $result->p_vendor_code ?></td>
								</tr>
								<tr>
									<th>取引先名</th>
									<Td><?php echo $result->p_vendor_name ?></td>
								</tr>
								<tr>
									<th>商品コード</th>
									<td><?php echo $result->p_product_code ?></td>
								</tr>
								<tr>
									<th>枝番</th>
									<td><?php echo $result->p_branch_code ?></td>
								</tr>
								<tr>
									<th>商品名</th>
									<td><?php echo $result->p_product_name ?></td>
								</tr>
								<tr>
									<th>略名</th>
									<td><?php echo $result->p_short_name ?></td>
								</tr>
								<tr>
									<th>販売単価</th>
									<td><?php echo $result->p_sale_price ?></td>
								</tr>
								<tr>
									<th>原価</th>
									<td><?php echo $result->p_cost_price ?></td>
								</tr>
								<tr>
									<th>健康屋システムマスタ登録日</th>
									<td><?php echo $result->p_adddate ?></td>
								</tr>
								<Tr>
									<th>健康屋システムマスタ更新日</th>
									<td><?php echo $result->p_moddate ?></td>
								</tr>
								<Tr>
									<th>商品マスタ登録日</th>
									<td><?php echo $result->p_create_date ?></td>
								</tr>
								<tr>
									<th>商品マスタ更新日</th>
									<td><?php echo $result->p_update_date ?></td>
								</tr>
								<?php if(!empty($allergen)):?>
								<tr>
									<th>商品マスタ　アレルゲン</th>
									<td>
									<?php foreach($allergen as $a) :?>
										<?php echo $a->allergen_name ?>&nbsp;
									<?php endforeach;?>
									</td>
								</tr>
								<?php endif; ?>
								<tr>
									<th class='no-border'></th>
									<td><a class='edit' href='<?php echo base_url('/admin_advertise/list_product/' . $ad_result->id)?>'>登録商品リストへ戻る</a></td>
									<td><a class='edit' href='<?php echo base_url("/admin_advertise/edit_product/{$result->id}/{$ad_result->id}")?>'>変更</a></td>
							</table>
							-->
			</div>
		</main>
	</div>
</body>
</html>
