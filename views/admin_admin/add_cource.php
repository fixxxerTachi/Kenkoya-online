<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<script>
window.onload=function(){
	var url = location.href;
	console.log(url);
	var arr = url.split('/');
	if(arr[5]){
		$('#form_table').css({
			'padding': '10px',
			'border':'2px solid #FAAC58',
			'border-radius':'5px',
		});
	}
}
</script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="mdl-cell mdl-cell mdl-cell--6-col">
					<h2><span class='logo_pink'>cource</span> 登録コース一覧</h2>
		<?php if(!empty($result)):?>
					<table class='list'>
						<tr>
							<th>コースコード</th>
							<th>コース名</th>
							<th>店舗</th>
							<th>配達曜日</th>
							<th></th>
							<th></td>
						</tr>
						<?php foreach($result as $item):?>
						<tr>
							<td><?php echo sprintf('%05d',$item->cource_code) ?></td>
							<td><?php echo $item->cource_name ?></td>
							<td><?php echo $shops[$item->shop_id] ?></td>
							<td><?php if(!empty($item->cource_type_id)) echo $list_items[$item->cource_type_id]; else echo ''; ?></td>
							<td><a href='<?php echo site_url("admin_admin/add_cource/{$item->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("「<?php echo $item->cource_name ?>」",<?php echo $item->id ?>)'>削除</a></td>
						</tr>
						<?php endforeach;?>
					</table>
		<?php else:?>
						<p>登録されていません</p>
		<?php endif;?>
				</div>
				<div class="mdl-cell mdl-cell--6-col">
					<h2><span class='logo_pink'>cource</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<form action='' method='post'>
						<table class='detail' id='form_table'>
							<tr>
								<th><label for='shop'>店舗</label></th>
								<td>
									<?php echo form_dropdown('shop_id',$shops,$form_data->shop_id) ?>
							<tr>
								<th><label for='cource_id'>コースID</label></th>
								<td>
									<input type='text' id='cource_id' name='cource_code' value='<?php echo $form_data->cource_code ?>' size='7' maxlength='7'>
								</td>
							</tr>
							<tr>
								<th><label for='cource_name'>コース名</label></th>
								<td>
									<input type='text' id='cource_name' name='cource_name' value='<?php echo $form_data->cource_name ?>' size='10' maxlength='10'>
								</td>
							</tr>
							<tr>
								<th><label for='takuhai_day'>配達曜日</label></th>
								<td>
									<?php echo form_dropdown('takuhai_day',$list_items,$form_data->takuhai_day) ?>
								</td>
							</tr>
							<tr>
								<th class='no-border'></th>
								<td>
					<?php if(!$edit_flag):?>
									<input type='submit' name='submit' value='登録する' class='submit_button'>
					<?php else:?>
									<input type='submit' name='edit_sub' value='変更する' class='submit_button'>
					<?php endif;?>
									<a class='edit_back' href='<?php echo site_url('admin_admin/add_cource') ?>'>戻る</a>
								</td>
							</tr>
						</table>
					</form>
					<h2><span class='logo_pink'>cource</span> 配達コース一括登録</h2>
					<div>
						<?php echo form_open_multipart() ?>
						<dl class='csv_menu'>
							<dt>項目：1.コースコード,2.コース名,3.店舗ID,4.コースタイプID</dt>
							<dd><input type='checkbox' name='trancate' id='trancate'>
								<label for='trancate'>既存のデータを破棄して新たにデータを作成します。既存のデータに追加する場合チェックをはずしてください</label></dd>
							<dd><input type='file' name='csvfile'></dd>
							<dt></dt>
							<dd><input type='submit' value='登録' class='input'></dd>
						</dl>
						</form>
						<?php if(isset($upload_message)):?>
						<p class='success'><?php echo $upload_message; ?></p>
						<?php endif; ?>
						<?php if(isset($db_message)):?>
						<p class='success'><?php echo $db_message; ?></p>
						<?php endif; ?>
						<?php if(isset($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message ?></p>
						<?php endif;?>
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
		location.href='<?php echo site_url("admin_admin/delete_cource") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>