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
			<?php if(count($result) > 0):?>
			<form method='post' action=''>
			<table  class='list'>
			<tr><th>タイトル</th><th>掲載開始日時</th><th>掲載終了日時</th><th>掲載順</th><th></th><th></th><th></th></tr>
			<?php foreach($result as $row):?>
				<tr>
					<td><a href='<?php echo site_url("admin_contents/detail_information/{$row->id}") ?>'><?php echo $row->title ?></a></td>
					<td><?php echo date('Y/m/d日H時',strtotime($row->start_datetime));?></td>
					<td><?php echo date('Y/m/d日H時',strtotime($row->end_datetime));?></td>
					<td><input type='text' name="sort_order<?php echo $row->id ?>" id="sort_order<?php echo $row->id ?>" value='<?php echo $row->sort_order ?>' size='2' maxlength='2'></td>
					<?php $option = "id='showflag_{$row->id}'";?>
					<td><?php echo form_dropdown("show_flag",$show_flag,$row->show_flag,$option) ?></td>
					<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_information/{$row->id}") ?>'>変更</a></td>
					<td><a class='edit' onclick='del_confirm("<?php echo $row->title ?>" , <?php echo $row->id ?>)'>削除</a></td>
					</tr>
			<?php endforeach;?>
				<tr class='no-back'><td></td><td></td><td></td><td><input type='submit' name='change_order' value='掲載順変更'></td></tr>
			</table>
			</form>
			<?php else:?>
			<p>登録されていません</p>
			<?php endif;?>
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
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_information") ?>' + '/' + id;
		return false;
	}
}
$('select[name=show_flag]').change(function(){
			var data = $(this).val();
			var str_id = $(this).attr('id');
			console.log(data + '/' + str_id);
			location.href='<?php echo site_url('admin_contents/change_show_flag_information') ?>' + '/' + str_id + '/' + data ;
});
</script>
</body>
</html>