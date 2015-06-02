<?php include __DIR__ . '/../templates/meta.php' ?>
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
			<form aciton='' method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='username'>ユーザー名</label></th>
						<td><input type='text' id='username' name='username' value='<?php echo $form_data->username ?>' size='20' maxlength='20'></td>
					</tr>
					<tr>
						<th><label for='password'>パスワード</label></th>
						<td><input type='text' id='password' name='password' size='20' maxlength='20'></td>
					</tr>
					<tr>
						<th><label for='point'>ポイント数</label></th>
						<td><input type='text' id='point' name='point' value='<?php echo $form_data->point ?>' size='20' maxlength='20'></td>
					</tr>
					<tr>
						<th><label for='rank'>ランク</label></th>
						<td><input type='text' id='rank' name='rank' value='<?php echo $form_data->rank ?>' size='20' maxlength='20'></td>
					</tr>
					<tr>
						<th><label for='bank_name'>振込銀行名</label></th>
						<td>
							<input type='text' name='bank_name' id='bank_name' value='<?php echo $form_data->bank_name ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='type_account'>口座種別</label></th>
						<td>
							<input type='text' name='type_account' id='type_account' value='<?php echo $form_data->type_account ?>' size='8' maxlength='8'>
						</td>
					</tr>
					<tr>
						<th><label for='account_number'>口座番号</label></th>
						<td>
							<input type='text' name='account_number' id='account_number' value='<?php echo $form_data->account_number ?>' size='10' maxlength='10'>
						</td>
					</tr>
					<tr>
						<th><label for='mail_magazine'>メルマガ購読</label></th>
						<td><?php echo form_dropdown('mail_magazine',$merumaga_select,$form_data->mail_magazine,'id="mail_magazine"'); ?></td>
					</tr>

					<tr>
						<th class='no-border'></th>
						<td><input type='submit' name='submit' value='登録する'></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>