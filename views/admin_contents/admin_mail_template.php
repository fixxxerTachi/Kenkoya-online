<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
					<div class='mdl-cell--12-col mdl-grid'>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message; ?></p>
						<?php endif; ?>
						<?php if(!empty($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>		
						<?php if($result): ?>
					</div>
						<div class='mdl-cell--6-col mdl-grid'>
							<h2><span class='logo_pink'>mail</span> 登録されているメールテンプレート</h2>
							<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
								<?php foreach($result as $row): ?>
								<tr>
									<td><?php echo $row->id ?></td>
									<td><a href='<?php echo site_url("/admin_contents/list_mail_template/detail/{$row->id}") ?>'><?php echo html_escape($row->template_name) ?></a></td>
									<td><a class='edit' href='<?php echo site_url("/admin_contents/edit_mail_template/{$row->id}") ?>'>変更</a></td>
									<td><a class='edit' onclick='del_confirm("<?php echo $row->template_name ?>" , <?php echo $row->id ?>)'>削除</a></td>
								</tr>
								<?php endforeach;?>
							</table>
						<?php else: ?>
							<p>登録されていません</p>
						<?php endif; ?>
						</div>
					<?php if(!empty($show_detail)):?>
						<div class='mdl-cell--6-col mdl-grid'>
							<h2><span class='logo_pink'>mail</span> メールテンプレート詳細</h2>
							<div class='demo-card-square mdl-card mdl-shadow--2dp'>
								<table class='detail' cellpadding='0' cellspacing='10'>
									<tr><th>表示名</th><td><?php echo $detail_result->template_name ?></td></tr>
										<tr><th>お客様<br>管理者</th>
										<td>
											<?php echo $reciever[$detail_result->for_customer] ?>
										</td></tr>
									<tr><th>件名</th><td><?php echo $detail_result->mail_title ?></td></tr>
									<tr><th>本文</th><td><?php echo nl2br($detail_result->mail_body) ?></td></tr>
								</table>
							</div>
						</div>
					<?php else:?>
						<div class='mdl-cell--6-col mdl-grid'>
							<?php echo form_open() ?>
								<h2><span class='logo_pink'>mail</span> メールテンプレート追加</h2>
								<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
									<tr>
										<th class="mdl-data-table__cell--non-numeric"><label for='template_name'>表示名</label></th>
										<td><input type='text' id='template_name' name='template_name' value='<?php echo $form_data->template_name ?>' size='50' maxlength='70'></td>
									</tr>
									<tr>
										<th class="mdl-data-table__cell--non-numeric"><label for='for_customer'>お客様送付<br>管理者送付</label></th>
										<td>
											<?php echo form_dropdown('for_customer',$reciever,$form_data->for_customer,'id=for_customer');?>
										</td>
									</tr>
									<tr>
										<th class="mdl-data-table__cell--non-numeric"><label for='mail_title'>件名</label></th>
										<td><input type='text' id='mail_title' name='mail_title' value='<?php echo $form_data->mail_title ?>' size='50' maxlength='70'></td>
									</tr>
									<tr>
										<th class="mdl-data-table__cell--non-numeric"><label for='body'>メール本文</label></th>
										<td><textarea name='mail_body' id='mail_body' cols='60' rows='30'><?php echo $form_data->mail_body ?></textarea></td>
									</tr>
									<tr>
										<th class="mdl-data-table__cell--non-numeric"></th>
										<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px'><a class='edit_back' href='<?php echo site_url('/admin_contents/list_mail_template') ?>'>戻る</a></td>
									</tr>
								</table>
							</form>
						</div>
					<?php endif; ?>
			</div>
		</main>
	</div>
</body>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + 'を削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_contents/delete_mail_template") ?>' + '/' + id;
		return false;
	}
}
</script>
</html>