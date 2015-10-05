<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='container'>
					<h2><span class='logo_pink'>recommend</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table'>
								<th><label for='advertise_id'>対象広告</label></th>
								<td><?php echo form_dropdown('advertise_id',$ad_list,$form_data->advertise_id) ?></td>
							<tr>
								<th><label for='advertise_product_code'>広告記載商品コード</label></th>
								<td><input type='text' id='advertise_product_code' name='advertise_product_code' value='<?php echo $form_data->advertise_product_code ?>' size='4' maxlength='4'></td>
							</tr>
							<tr>
								<th><label for='comment'>コメント</label></th>
								<td>
									<textarea name='comment' rows='3' cols='60'><?php echo $form_data->comment ?></textarea>
								</td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px;'><a class='edit_back' href='<?php echo site_url('admin_contents/add_recommend') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="container">
					<h2><span class='logo_pink'>recommend</span> おすすめ商品リスト</h2>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
						<?php if(!empty($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>
<?php if(count($result) > 0):?>
					<?php echo form_open() ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr><th>画像</th><th>広告名</th><th>商品コード</th><th>商品名</th><th>価格</th><th>並び順</th><th></th><th></th><th></th></tr>
					<?php foreach($result as $row):?>
							<tr>
								<td><img src='<?php echo base_url(show_image($row->ad_pro_code)) ?>' width='25' height='25' alt='<?php echo $row->ad_pro_product_name ?>'></td>
								<td><?php echo $row->ad_title ?></td>
								<td><?php echo $row->ad_code ?></td>
								<td><a href='<?php echo site_url("admin_contents/detail_recommend/{$row->ad_pro_id}") ?>'><?php echo $row->ad_pro_product_name ?></a></td>
								<td><?php echo $row->ad_pro_sale_price ?></td>
								<td><input type='text' name='sort_order<?php echo $row->id ?>' value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
								<?php $option = "id='showflag_{$row->id}'";?>
								<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
								<td><a class='edit' href='<?php echo site_url("admin_contents/edit_recommend/{$row->id}") ?>'>編集</a></td>
								<td><a class='edit' onclick='del_confirm("「<?php echo $row->ad_pro_product_name ?>」" , <?php echo $row->id ?>)'>削除</a></td>
							</tr>
					<?php endforeach;?>
							<tr class='no-back'><td></td><td></td><td></td><td></td><td></td><td><input type='submit' name='change_order' value='順序変更'></td></tr>
							</table>
							</form>
<?php else:?>
				<p>登録されていません</p>
<?php endif; ?>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_recommend") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_recommend') ?>' + '/' + str_id + '/' + data ;
});
</script>
</html>