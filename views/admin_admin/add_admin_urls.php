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
					<h2><span class='logo_pink'>url</span> <?php echo $h2title ?></h2>
						<?php if(!empty($message)):?>
						<p class='message'><?php echo $message ?></p>
						<?php endif;?>
						<?php echo form_open() ?>
							<table class='mdl-data-table mdl-js-data-table'>
								<tr>
									<th><label for='controller'>対象ページ</label></th>
									<td><?php echo form_dropdown('controllers',$controllers,$form_data->controller) ?><td>
								</tr>
								<tr>
									<th><label for='url'>URL</label></th>
									<td><?php echo site_url() ?><input type='text' id='url' name='url' value='<?php echo $form_data->url ?>' size='60' maxlength='60'></td>
								</tr>
								<tr>
									<th><label for='name'>サイト名</label></th>
									<td><input type='text' name='name' id='name' value='<?php echo $form_data->name ?>' size='40' maxlength='40'></td>
								</tr>
								<tr>
									<th class='no-border'></th>
									<td>
				<?php if(!$edit_flag):?>
										<input type='submit' name='submit' value='登録する' class='submit_button'>
				<?php else:?>
										<input type='submit' name='edit_submit' value='変更' class='submit_button'>
				<?php endif;?>
										
										<a class='edit_back' href='<?php echo site_url('admin_admin/add_admin_urls') ?>'>戻る</a>
									</td>
								</tr>
							</table>
					<h2><span class='logo_pink'>url</span> 管理サイトURL一覧</h2>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<?php if(count($result) > 0):?>
					<?php form_open('admin_admin/add_admin_urls') ?>
					<table class='mdl-data-table mdl-js-data-table'>
					<tr style = 'background: #fff'><td><input type='submit' name='change_order' value='順序変更'></td><td></td><td></td><td></td></tr>
					<tr><th>対象ページ</th><th>url</th><th colspan='2'>サイト名</th></tr>
					<tr><th>並び順</th><th>メニュー表示</th><th>編集</th><th>削除</th></tr>
					<?php foreach($result as $row):?>
						<tr>
						<td><?php echo $controllers[$row->controller] ?></td>
						<td><?php echo $row->url ?></td>
						<td colspan='2'><?php echo $row->name ?></td>
						</tr><tr class='rowborder'>
						<td><input type='text' name='sort_order<?php echo $row->id ?>' value='<?php echo $row->sort_order ?>' size='3' maxlength='3'></td>
						<?php $option = "id='showflag_{$row->id}'";?>
						<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
						<td><a class='edit' href='<?php echo site_url("admin_admin/add_admin_urls/{$row->id}") ?>'>編集</a></td>
						<td><a class='edit' onclick='del_confirm("<?php echo $row->name ?>" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
					<?php endforeach;?>
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
		location.href='<?php echo site_url("/admin_admin/delete_admin_urls") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_admin/change_show_flag_urls') ?>' + '/' + str_id + '/' + data ;
});
</script>
</html>