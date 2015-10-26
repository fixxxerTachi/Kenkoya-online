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
					<?php if(!empty($h2title)):?>
					<h2><span class="material-icons logo_pink">search</span> <?php echo $h2title ?></h2>
					<?php endif; ?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php echo form_open('',array('class'=>'col s12')) ?>
						<table class='detail'>
							<tr><th><label for='shop_id'>店舗</label></th><td><?php echo form_dropdown('shop_id',$shops,$selected,"class='browser-default' id='shop_id'") ?></td></tr>
							<tr>
								<th><label for='cource_id'>コースコード</label></th>
								<td><?php echo form_dropdown('cource_id',$cource_list,$form_data->cource_id,'id="cource_id"') ?></td>
							</tr>
							<tr><th><label for='code'>お客様番号</label></th><td><input type='text' name='code' value='<?php echo $form_data->code ?>'></td></tr>
							<tr><th><label for='name'>お客様名</label></th><td><input type='text' name='name' value='<?php echo $form_data->name ?>'></td></tr>
							<tr><th><label for='tel'>お電話番号</label></th><td><input type='text' name='tel' value='<?php echo $form_data->tel ?>'></td></tr>
							<tr>
								<th><label>住所</label></th>
								<td><label for='address1'>住所</label> <input type='text' name='address1' value='<?php echo $form_data->address1 ?>'></td>
								<td><label for='address2'>建物名</label> <input type='text' name='address2' value='<?php echo $form_data->address2 ?>'></td>
							</tr>
							<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
						</table>
					</form>
				<?php if($result): ?>
					<?php if(!empty($links)):?><p class='links'><?php echo $links ?></p><?php endif;?>
					<h2><span class='logo_pink'>member</span> 会員一覧</h2>
					<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
						<tr><th>店舗</th><th>配達地域</th><th>お客様番号</th><th>名前</th><th colspan='2'>住所</th><th>tel</th><th></th><th></th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							
							<td><?php echo $row->shop_name ?></td>
							<td><?php echo $row->cource_name ?></td>
							<td><?php echo $row->customer_code ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/detail_customer/{$row->id}") ?>'><?php echo html_escape($row->name) ?></a></td>
							<td><?php echo substr($row->zipcode,0,3) . '-' . substr($row->zipcode,3,4) ?></td>
							<td><?php echo $row->address1 ?></td>
							<td><?php echo $row->tel ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/edit_customer/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->name ?>" , <?php echo $row->id ?>)'>削除</a></td>
							<td><a class='edit' href='<?php echo site_url("admin_customer/list_order/{$row->id}") ?>'>注文履歴</a></td>
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
				</div>
		<?php if(!empty($show_detail)):?>
				<div class='container'>
					<table class='detail' cellpadding='0' cellspacing='10'>
						<tr><th>ID</th><td><?php echo $detail_result->id ?></td></tr>
						<tr><th>名前</th><td><?php echo $detail_result->name ?></td></tr>
						<tr><th>郵便番号</th><td><?php echo substr((string)$detail_result->zipcode,0,3) ?>-<?php echo substr((string)$detail_result->zipcode,3,4)  ?></td></tr>
						<tr><th>住所</th><td><?php echo  $detail_result->address1?> <?php echo $detail_result->address2 ?></td></tr>
						<tr><th>メールアドレス</th><td><?php echo $detail_result->email ?></td></tr>
						<tr><th>電話番号</th><td><?php echo $detail_result->tel ?></td></tr>
					</table>
				</div>
		<?php endif; ?>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(name , id){
	var name = name;
	var id = id;
	if(window.confirm(name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_customer/delete_customer") ?>' + '/' + id;
		return false;
	}
}
</script>
<script>
$('#shop_id').on('change',function()
{
	$('#cource_id').empty();
	var id = $(this).val();
	$.getJSON(
		'<?php echo site_url('admin_admin/show_cource')?>' + '/' + id,
		function(data){
			var items = [];
			$.each(data,function(k,v){
				items.push('<option value="' + k + '">' + v + '</option>');
			});
			$('#cource_id').append(items.join());
		}
	);
});
</script>
</html>