<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='mdl-cell mdl-cell--12-col'>
					<h2><span class='logo_pink'>payment</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<p>注意）お支払方法は決済画面のお支払/配送情報登録画面に表示されます。変更した場合、よくある質問のお支払についてを修正して下さい。</p>
					<?php if(!empty($result)): ?>
						<table class='mdl-data-table mdl-js-data-table'>
						<tr><th>ID</th><th>表示名</th><th></th><th></th><th></th></tr>
						<?php foreach($result as $item):?>
							<tr>
								<td><?php echo $item->id ?></td>
								<td><?php echo $item->method_name ?></td>
								<td><a class='edit' href='<?php echo site_url("/admin_admin/payment/{$item->id}") ?>'>変更</a></td>
								<td>
<?php if($item->show_flag == SHOW_ITEM) echo '<span style="color:orange;">公開</span>';?>
<?php if($item->show_flag == HIDE_ITEM) echo '<span>非公開</span>';?>
								</td>
								<td><a class='edit' onclick ='del_confirm("<?php echo $item->method_name ?>","<?php echo $item->id ?>")'>削除</a></td>
							</tr>
						<?php endforeach;?>
						</table>
					<?php else: ?>
						<p>登録されていません</p>
					<?php endif; ?>
				</div>
				<div class='mdl-cell mdl-cell--12-col'>
					<h2><span class='logo_pink'>payment</span> お支払方法<?php if(!isset($edit)):?>新規登録<?php else:?>更新<?php endif;?></h2>
					<?php echo form_open() ?>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">表示名</th>
							<td><input type='text' name='method_name' value='<?php echo $form_data->method_name ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">簡単な説明（決済方法の欄に表示されます)</th>
							<td><textarea name='notice' rows='10' cols='70'><?php echo $form_data->notice ?></textarea></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">説明(決済方法の詳しい説明に表示されます)</th></th>
							<td>
								<textarea name='description' rows='20' cols='70'><?php echo $form_data->description ?></textarea>
							</td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">掲載・非軽鎖</span>
							<td>
								<?php echo form_dropdown('show_flag',$show_flag,$form_data->show_flag) ?>
							</td>
						<tr>
							<th class="mdl-data-table__cell--non-numeric"></th>
							<td>
								<input type='submit' name='submit' class='submit_button' value='<?php if(!isset($edit)) echo '登録する'; else echo '更新する'; ?>'>
								<a class='edit_back' href='<?php echo site_url('admin_admin/payment') ?>'>戻る</a>
							</td>
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
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_payment") ?>' + '/' + id;
		return false;
	}
}
</script>
</body>
</html>
