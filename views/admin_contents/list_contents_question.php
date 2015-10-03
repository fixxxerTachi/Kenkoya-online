<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
				<div class='mdl-grid'>
					<div class='mdl-cell mdl-cell--12-col'>
						<h2><span class='logo_pink'>question</span> <?php echo $h2title ?></h2>
						<?php if(!empty($message)):?>
						<p class='message'><?php echo $message ?></p>
						<?php endif;?>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message; ?></p>
						<?php endif; ?>
						<?php if(!empty($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>	
					</div>
				</div>
				<?php echo form_open() ?>
				<div class='mdl-grid'>
						<div class='mdl-cell mdl-cell--6-col'>
							<p><?php echo form_dropdown('category_id',$category_list ,$category_id) ?><input type='submit' name='category_search' value='検索'></p>
						</div>
						<div class='mdl-cell mdl-cell--6-col' id='add_new'>
							<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored">
								<i class="material-icons">add</i>
							</button>　<a href='<?php echo site_url('admin_contents/add_contents_question')?>'>よくある質問の新規追加
						</div>
				</div>
					<?php if(count($result) > 0):?>
				<div class='mdl-grid'>
					<div class='mdl-cell mdl-cell--12-col'>
						<table  class='mdl-data-table mdl-js-data-table'>
						<tr><th>カテゴリ</th><th>質問</th><th>答え</th><th>表示順</th><th></th><th></th><th></th><th></th></tr>
						<?php foreach($result as $row):?>
							<tr>
								<td><?php echo $category_list[$row->category_id] ?></td>
								<td><a href='<?php echo site_url("admin_contents/detail_contents_question/{$row->id}") ?>'><?php echo mb_strimwidth($row->question,0,30,'...') ?></a></td>
								<td><a href='<?php echo site_url("admin_contents/detail_contents_question/{$row->id}") ?>'><?php echo mb_strimwidth($row->answer,0,30,'...') ?></td>
								<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></a></td>
								<?php $option = "id='showflag_{$row->id}'";?>
								<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
								<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_contents_question/{$row->id}") ?>'>変更</a></td>
								<td><a class='edit' onclick='del_confirm("<?php echo $row->question ?>" , <?php echo $row->id ?>)' href='javascript:void(0)'>削除</a></td>
								</tr>
						<?php endforeach;?>
							<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td><td></td><td></td><td></td></tr>
						</table>
						<?php else:?>
						<p>登録されていません</p>
						<?php endif;?>
						</form>
					</div>
				</div>
		</main>
	</div>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm('「' + template_name + '」' + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_contents_question") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_question') ?>' + '/' + str_id + '/' + data ;
});
</script>
</body>
</html>