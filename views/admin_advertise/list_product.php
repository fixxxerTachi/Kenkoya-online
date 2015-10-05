<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content-large">
				<div class='class="mdl-cell mdl-cell--12-col"'>
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
					<?php if(!empty($list_category)):?>
					<?php endif;?>
					<form method='post'>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr>
							<th class="mdl-data-table__cell--non-numeric"><label for='category_id'>カテゴリ</label></th><td colspan='3'><?php echo form_dropdown('category_id',$list_category,$form_data->category_id,false,'id="category_id"') ?></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric"><label for='code'>注文番号</label></th><td><input type='text' name='code' id='code' value='<?php echo $form_data->code ?>'></td>
							<th class="mdl-data-table__cell--non-numeric"><label for='product_code'>商品コード</th><td><input type='text' name='product_code' id='product_code' value='<?php echo $form_data->product_code ?>'></td>
							<th class="mdl-data-table__cell--non-numeric"><label for='product_name'>商品名</label></th><td><input type='text' id='product_name' name='product_name' value='<?php echo $form_data->product_name ?>'></td>
						</tr>
						<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
					</table>
					<?php if(!empty($result) > 0):?>
						<p class='links'><?php echo $links ?></p>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th class="mdl-data-table__cell--non-numeric">画像</th>
								<th class="mdl-data-table__cell--non-numeric" style='width:80px;'>商品番号</th>
								<th class="mdl-data-table__cell--non-numeric" style='width:80px;'>商品コード</th>
								<th class="mdl-data-table__cell--non-numeric" style='width:80px;'>枝番</th>
								<th class="mdl-data-table__cell--non-numeric">商品名</th>
								<th class="mdl-data-table__cell--non-numeric">販売価格</th>
								<th class="mdl-data-table__cell--non-numeric">サイズ登録</th>
								<th class="mdl-data-table__cell--non-numeric">販売開始</th>
								<th class="mdl-data-table__cell--non-numeric">販売終了</th>
								<th class="mdl-data-table__cell--non-numeric">お届け開始</th>
								<th class="mdl-data-table__cell--non-numeric">お届け終了</th>
								<th class="mdl-data-table__cell--non-numeric"></th>
								<th class="mdl-data-table__cell--non-numeric"></th>
							</tr>
							<?php foreach($result as $row): ?>
							<tr>
								<td><img src='<?php echo base_url(show_image($row->product_code))?>' width='25' height='25'></td>
								<td style='width:80px;'><?php echo $row->code?></td>
								<td style='width:80px;'><?php echo $row->product_code ?></td>
								<td style='width:80px;'><?php echo $row->branch_code ?></td>
								<td>
									<a href='<?php echo base_url("/admin_advertise/detail_product/{$row->id}/{$ad_id}") ?>'><?php echo mb_strimwidth($row->product_name,0,30,'...') ?></a>
								</td>
								<td><?php echo number_format($row->sale_price) ?>円</td>
								<td><?php if($row->weight){ echo $row->weight; }else{ echo '<span style="color:orange">未登録</span>';} ?></td>
					<?php $ssflg = $row->sale_start_datetime ? new DateTime($row->sale_start_datetime) : null;?>
					<?php $seflg = $row->sale_end_datetime ? new DateTime($row->sale_end_datetime) : null;?>
					<?php $dsflg = $row->delivery_start_datetime ? new DateTime($row->delivery_start_datetime) : null;?>
					<?php $deflg = $row->delivery_end_datetime ? new DateTime($row->delivery_end_datetime) : null;?>
								<td><?php if($ssflg):?><span style='color:orange'><?php echo $ssflg->format('Y/m/d H') ?>時<?php else:?>未設定<?php endif;?></td>
								<td><?php if($seflg):?><span style='color:orange'><?php echo $seflg->format('Y/m/d H') ?>時<?php else:?>未設定<?php endif;?></td>
								<td><?php if($dsflg):?><span style='color:orange'><?php echo $dsflg->format('Y/m/d H') ?>時<?php else:?>未設定<?php endif;?></td>
								<td><?php if($deflg):?><span style='color:orange'><?php echo $deflg->format('Y/m/d H') ?>時<?php else:?>未設定<?php endif;?></td>
								<td><span <?php if($row->on_sale == 0):?>style='color:orange'<?php endif;?>><?php echo $on_sale[$row->on_sale] ?></span></td>
								<td><a class='edit' href='<?php echo base_url("/admin_advertise/edit_product/{$row->id}/{$ad_id}") ?>'>変更</a></td>
							</tr>
							<?php endforeach;?>
							<tr class='no-back'><td></td><td><a class='edit' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>リストに戻る</a></td>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
			</div>
		</main>
	</div>
</body>
</html>
