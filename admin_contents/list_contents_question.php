<?php include __DIR__ . '/../templates/meta.php' ?>
<link href="<?php echo base_url() ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet">
<link href="<?php echo base_url() ?>css/lightbox.css" rel="stylesheet" />
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url() ?>js/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>js/lightbox.min.js"></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
		<h2><?php echo $h2title ?></h2>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<?php echo form_open() ?>
			<p><?php echo form_dropdown('category_id',$category_list ,$category_id) ?><input type='submit' name='category_search' value='検索'></p>
			<?php if(count($result) > 0):?>
			<table  class='list'>
			<tr><th>カテゴリ</th><th>質問</th><th>答え</th><th>表示順</th><th></th><th></th><th></th><th></th></tr>
			<?php foreach($result as $row):?>
				<tr>
					<td><?php echo $row->category ?></td>
					<td><a href='<?php echo site_url("admin_contents/detail_contents_question/{$row->id}") ?>'><?php echo mb_strimwidth($row->question,0,30,'...') ?></a></td>
					<td><a href='<?php echo site_url("admin_contents/detail_contents_question/{$row->id}") ?>'><?php echo mb_strimwidth($row->answer,0,30,'...') ?></td>
					<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></a></td>
					<?php $option = "id='showflag_{$row->id}'";?>
					<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
					<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_contents_question/{$row->id}") ?>'>変更</a></td>
					<td><a class='edit' onclick='del_confirm("<?php echo $row->question ?>" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
			<?php endforeach;?>
				<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td></tr>
			</table>
			<?php else:?>
			<p>登録されていません</p>
			<?php endif;?>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
<script>
$('#start_date').datepicker({dateFormat:'yy/mm/dd'});
$('#end_date').datepicker({dateFormat:'yy/mm/dd'});
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