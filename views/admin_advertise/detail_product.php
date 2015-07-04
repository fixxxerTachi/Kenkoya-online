<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<p><?php if(isset($message)) echo $message ?></p>
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
							<th>販売単価</th>
							<td><?php echo $result->sale_price ?></td>
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
							<th>販売中</th>
							<td><?php if($result->on_sale == ONSALE): ?>販売中<?php elseif($result->on_sale == DISCON):?><span style='color:orange'>販売中止</span><?php endif;?></td>
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
					</table>
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
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>