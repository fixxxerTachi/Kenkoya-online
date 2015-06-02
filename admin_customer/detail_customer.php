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
			<table class='detail' cellpadding='0' cellspacing='10'>
				<tr><th>ID</th><td><?php echo $detail_result->id ?></td></tr>
				<tr><th>店舗</th><td><?php if($detail_result->shop_code != 0):?><?php echo $shops[$detail_result->shop_code] ?><?php else:?>未登録<?php endif;?></td>
				<tr><th>配達地域</th><td><?php if($detail_result->cource_code != 0):?><?php echo $detail_result->cource_name ?><?php else:?>未登録<?php endif;?></td>
				<tr><th>お客様番号</th><td><?php if(!empty($detail_result->code)):?><?php echo $detail_result->code ?><?php else: ?>未登録<?php endif;?></td></tr>
				<tr><th>名前</th><td><?php echo $detail_result->name ?></td></tr>
				<tr><th>郵便番号</th><td><?php echo substr((string)$detail_result->zipcode,0,3) ?>-<?php echo substr((string)$detail_result->zipcode,3,4)  ?></td></tr>
				<tr><th>住所</th><td><?php echo  $detail_result->address1?> <?php echo $detail_result->address2 ?></td></tr>
				<tr><th>メールアドレス</th><td><?php echo $detail_result->email ?></td></tr>
				<tr><th>電話番号</th><td><?php echo $detail_result->tel ?></td></tr>
				<tr><th>電話番号2</th><td><?php echo $detail_result->tel2 ?></td></tr>
				<tr><th>ポイント</th><td><?php echo $detail_result->point ?></td></tr>
				<tr><th>ランク</th><td><?php echo $detail_result->rank ?></td></tr>
				<tr><th>銀行名</th><td><?php echo $detail_result->bank_name ?></td></tr>
				<tr><th>種別</th><td><?php echo $detail_result->type_account ?></td></tr>
				<tr><th>口座番号</th><td><?php echo $detail_result->account_number ?></td></tr>
				<tr><th class='no-border'></th><td><a class='edit' href='<?php echo site_url("admin_customer/list_customer") ?>'>リストに戻る</a></td></tr>
			</table>
		</div>
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