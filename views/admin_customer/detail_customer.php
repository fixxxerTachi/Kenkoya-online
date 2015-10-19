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
					<h2><span class='logo_pink'>member</span> <?php echo $h2title ?></h2>
					<?php endif; ?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<table class='detail striped'>
						<tr><th>顧客コード</th><td><?php echo $detail_result->code ?></td></tr>
						<tr><th>店舗</th><td><?php echo $detail_result->shop_name ?></td>
						<tr><th>配達コース名</th><td><?php echo $detail_result->cource_name ?></td>
						<tr><th>名前</th><td><?php echo $detail_result->name ?></td></tr>
						<tr><th>フリガナ</th><td><?php echo $detail_result->furigana ?></td></tr>
						<tr><th>郵便番号</th><td><?php echo substr((string)$detail_result->zipcode,0,3) ?>-<?php echo substr((string)$detail_result->zipcode,3,4)  ?></td></tr>
						<tr><th>住所</th><td><?php echo  $detail_result->address1?> <?php echo $detail_result->address2 ?></td></tr>
						<tr><th>メールアドレス</th><td><?php echo $detail_result->email ?></td></tr>
						<tr><th>電話番号</th><td><?php echo $detail_result->tel ?></td></tr>
						<tr><th>電話番号2</th><td><?php echo $detail_result->tel2 ?></td></tr>
						<tr><th>誕生日</th><td><?php echo $detail_result->birthday ?></td></tr>
						<tr><th>ログインユーザー名</th><td><?php echo $detail_result->username ?></td></tr>
						<tr><th class='no-border'></th><td><a class='edit_back' href='<?php echo site_url("admin_customer/list_customer") ?>'>リストに戻る</a></td></tr>
					</table>
				</div>
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
</html>