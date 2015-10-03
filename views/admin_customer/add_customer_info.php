<?php include __DIR__.'/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='container'>
					<h2><span class='logo_pink'>info</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>	
					<?php echo form_open('',array('class'=>'col s12')) ?>
						<table class='mdl-data-table mdl-js-data-table'>
							<tr>
								<th><label for='title'>題名</label></th>
								<td><input type='text' id='title' name='title' value='<?php echo $form_data->title ?>' size='70' maxlength='70'></td>
							</tr>
							<tr>
								<th><label for='contents'>内容</label></th>
								<td><textarea class='materialize-textarea' name='contents' rows='20' cols='60'><?php echo $form_data->contents ?></textarea></td>
							</tr>
							<tr>
								<th><label for='start_date'>掲載開始日時</label></th>
								<?php $start_date = new DateTime($form_data->start_datetime) ?>
								<?php $end_date = new DateTime($form_data->end_datetime) ?>
								<td>
									<input type='text' name='start_date' id='start_date' value='<?php echo $start_date->format('Y/m/d') ?>'>
									<?php echo form_dropdown('start_time',$hour_list,$start_date->format('H:i:s'),"class='browser-default'");?>時
								</td>
							</tr>
							<tr>
								<th><label for='end_date'>掲載終了日時</label></td>
								<td>
									<input type='text' name='end_date' id='end_date' value='<?php echo $end_date->format('Y/m/d') ?>'>
									<?php echo form_dropdown('end_time',$hour_list,$end_date->format('H:i:s'),"class='browser-default'");?>時前まで
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type='submit' name='submit' value='登録する' style='margin-right: 30px;'>
									<a class='edit_back' href='<?php echo site_url('admin_customer/add_info') ?>'>戻る</a>
								</td>
							</tr>
						</table>
				</div>
				<div class='container'>
					<h2><span class='logo_pink'>info</span> 会員おしらせ一覧</h2>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr>
							<th class='mdl-data-table__cell--non-numeric'>タイトル</th>
							<th class='mdl-data-table__cell--non-numeric'data-field='startdatetime'>掲載開始日時</th>
							<th class='mdl-data-table__cell--non-numeric'data-field='enddatetime'>掲載終了日時</th>
							<th class='mdl-data-table__cell--non-numeric'data-field='sortorder'>表示順</th><th></th><th></th><th></th>
						</tr>
						<?php foreach($result as $key => $row): ?>
						<tr>
							<td><a class='edit' href='<?php echo site_url("/admin_customer/list_info/detail/{$row->id}") ?>'><?php echo html_escape($row->title) ?></a></td>
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
				</div>
			</div>
		</main>
	</div>
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
</body>
</html>