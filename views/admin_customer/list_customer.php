<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="wrapper">
		<div class='container'>
				<?php if(!empty($h2title)):?>
				<h2><span class="small material-icons">search</span><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php echo form_open('',array('class'=>'col s12')) ?>
				<table class='detail'>
					<tr><th><label for='shop_code'>店舗</label></th><td><?php echo form_dropdown('shop_code',$shops,$selected,"class='browser-default'") ?></td></tr>
					<tr><th><label for='code'>お客様番号</label></th><td><input type='text' name='code' value='<?php echo $form_data->code ?>'></td></tr>
					<tr><th><label for='name'>お客様名</label></th><td><input type='text' name='name' value='<?php echo $form_data->name ?>'></td></tr>
					<tr><th class='no-border'></th><td><input type='submit' name='search' value='検索'></td></tr>
				</table>
				</form>
				<?php if($result): ?>
					<?php if(!empty($links)):?><p class='links'><?php echo $links ?></p><?php endif;?>
					<h2><span class='logo_pink'>member</span> 会員一覧</h2>
					<table class='striped'>
						<tr><th>店舗</th><th>配達地域</th><th>お客様番号</th><th>名前</th><th colspan='2'>住所</th><th>tel</th><th>ポイント</th><th>ランク</th><th></th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							
							<td><?php if($row->shop_code != 0):?><?php echo $shops[$row->shop_code] ?><?php else:?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><?php if($row->cource_code != 0):?><?php echo $row->cource_name ?><?php else:?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><?php echo $row->code ?><?php if(empty($row->code)):?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/detail_customer/{$row->id}") ?>'><?php echo html_escape($row->name) ?></a></td>
							<td><?php echo substr($row->zipcode,0,3) . '-' . substr($row->zipcode,3,4) ?></td>
							<td><?php echo $row->address1 ?></td>
							<td><?php echo $row->tel ?></td>
							<td><?php echo $row->point ?></td>
							<td><?php echo $row->rank ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/edit_customer/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->name ?>" , <?php echo $row->id ?>)'>削除</a></td>
				
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
		<?php if(!empty($show_detail)):?>
		<div class='contents'>

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
</html>