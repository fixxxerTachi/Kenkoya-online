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
					<h2><span class='logo_pink'>QA</span> <?php echo $h2title ?></h2>
					<?php echo form_open('admin_admin/list_contact',array('name'=>'no-reply')) ?>
					<p><input type='checkbox' name='no-reply' value='1' id='no-reply'><label for='no-reply'>未返信のみ表示</label></p>
					<input type='hidden' name='no-reply-param' value='1' id='no-reply-param'>
					</form>
					<p class='pagination'><?php echo $pages ?></p>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php if(!empty($result)): ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr><th>カテゴリ</th><th>お名前</th><th>内容</th><th>投稿日</th><th>返信</th><th></th></tr>
							<?php foreach($result as $row): ?>
							<tr>
								<td><?php echo $categories[$row->category_id] ?></td>
								<td><?php echo $row->name ?></td>
								<td><a href='<?php echo site_url("admin_admin/detail_contact/{$row->id}")?>'><?php echo mb_strimwidth($row->content,0,50) ?>...</a></td>
								<td><?php echo $row->create_datetime ?></td>
								<td><?php if($row->reply_flag == 0):?><a class='edit' href='<?php echo base_url("admin_admin/reply_contact/{$row->id}") ?>'>未返信</a><?php else:?>済<?php endif;?></td>
							</tr>
							<?php endforeach;?>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
var f = document.forms['no-reply'];
var c = $('#no-reply');
var n = $('#no-reply-param');
var param;
var url = location.href;
var arr = url.split('/');
if(arr[5] == 'no-reply'){
	c.attr('checked',true);
}else{
	c.attr('checked',false);
}

c.on('change',function(){
	if(c.prop('checked')){
		param = 'no-reply';
	}else{
		param = 'all';
	}
	console.log(param);
	f.action = '<?php echo site_url('admin_admin/list_contact') ?>/' + param;
	f.submit();
});

</script>
</html>