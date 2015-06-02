<?php include __DIR__ . '/../templates/meta.php' ?>
<script src="<?php echo base_url() ?>js/jquery-ui/external/jquery/jquery.js"></script>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
				<h2><?php echo $h2title ?></h2>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>		
				<?php if($result): ?>
					<?php echo form_open() ?>
					<table class='list'>
						<tr><th>タイトル</th><th>掲載開始日時</th><th>掲載終了日時</th><th>表示順</th><th></th><th></th><th></th></tr>
						<?php foreach($result as $key => $row): ?>
						<tr>
							<td><a href='<?php echo site_url("/admin_customer/list_info/detail/{$row->id}") ?>'><?php echo html_escape($row->title) ?></a></td>
							<td><?php echo $row->start_datetime ?></td>
							<td><?php echo $row->end_datetime ?></td>
							<td><input type='text' name='sort_order<?php echo $row->id ?>' value='<?php echo $row->sort_order ?>' size='2' maxlength='2'>
							</td>
							<?php $option = "id='showflag_{$row->id}'";?>
							<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/edit_customer_info/{$row->id}") ?>'>変更</a></td>
							<td><a class='edit' onclick='del_confirm("<?php echo $row->title ?>" , <?php echo $row->id ?>)'>削除</a></td>
						</tr>
						<?php endforeach;?>
						<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' value='順序変更' name='change_order'></td><td></td><td></td></tr>
					</table>
					</form>
				<?php else: ?>
					<p>登録されていません</p>
				<?php endif; ?>
		</div>
		<?php if(!empty($detail_flag)):?>
		<div class='contents'>
			<table class='detail' cellpadding='0' cellspacing='10'>
				<tr><th>タイトル</th><td><?php echo $detail_result->title ?></td></tr>
				<tr><th>内容</th><td><?php echo $detail_result->contents ?></td></tr>
				<tr><th>掲載開始日</th><td><?php echo $detail_result->start_datetime ?></td></tr>
				<tr><th>掲載終了日</th><td><?php echo $detail_result->end_datetime ?></td></tr>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_customer/delete_customer_info") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_customer/change_show_flag_customer_info') ?>' + '/' + str_id + '/' + data ;
});
</script>
</html>