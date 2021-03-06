<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<?php if(!empty($h2title)):?>
				<h2><?php echo $h2title ?></h2>
				<?php endif; ?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<?php echo form_open() ?>
				<table class='detail'>
					<tr>
						<th><label for='shop_code'>種別</label></th>
						<td><?php echo form_dropdown('shop_code',$shops,$selected) ?></td><td><input type='submit' name='search' value='検索'></td>
					</tr>
					<tr><td></td></tr>
				</table>
				<?php if($result): ?>
					<?php if(!empty($links)):?><p class='links'><?php echo $links ?></p><?php endif;?>
					<table class='list'>
						<tr><input type='submit' name='download' value='csvダウンロード'></tr>
						<tr><th>変更区分</th><th>店舗</th><th>配達地域</th><th>お客様番号</th><th>名前</th><th colspan='2'>住所</th><th>ポイント</th><th>ランク</th><th></th><th></th></tr>
						<?php foreach($result as $row): ?>
						<tr>
							<td><?php echo $info[$row['change_info']] ?></td>
							<td><?php if($row['shop_code'] != 0):?><?php echo $shops[$row['shop_code']] ?><?php else:?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><?php if($row['cource_code'] != 0):?><?php echo $row['cource_name'] ?><?php else:?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><?php echo $row['code'] ?><?php if(empty($row['code'])):?><span style='color: red'>未登録</span><?php endif;?></td>
							<td><a href='<?php echo site_url("/admin_customer/detail_customer/{$row['id']}") ?>'><?php echo html_escape($row['name']) ?></a></td>
							<td><?php echo substr($row['zipcode'],0,3) . '-' . substr($row['zipcode'],3,4) ?></td>
							<td><?php echo $row['address1'].$row['address2'] ?></td>
							<td><?php echo $row['point'] ?></td>
							<td><?php echo $row['rank'] ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/edit_customer/{$row['id']}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->name ?>" , <?php echo $row['id'] ?>)'>削除</a></td>
				
						</tr>
						<?php endforeach;?>
					</table>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
		</form>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
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