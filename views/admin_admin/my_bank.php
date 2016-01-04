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
					<?php echo form_open() ?>
					<table class='mdl-data-table mdl-js-data-table'>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">銀行名</th>
							<td><input type='text' name='name' value='<?php echo $bank->name ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">銀行名(フリガナ)</th>
							<td><input type='text' name='furigana' value='<?php echo $bank->furigana ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">支店名</th>
							<td><input type='text' name='branch_name' value='<?php echo $bank->name ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">支店名(フリガナ)</th>
							<td><input type='text' name='branch_furigana' value='<?php echo $bank->name ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">種別</th>
							<td><input type='text' name='type' value='<?php echo $bank->type ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">口座番号</th>
							<td><input type='text' name='account' value='<?php echo $bank->account ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">口座名義</th>
							<td><input type='text' name='account_name' value='<?php echo $bank->account_name ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">口座名義(フリガナ)</th>
							<td><input type='text' name='account_furigana' value='<?php echo $bank->account_furigana ?>' size='70' maxlength='100'></td>
						</tr>
						<tr>
							<th class="mdl-data-table__cell--non-numeric"></th>
							<td>
								<input type='submit' name='submit' class='submit_button' value='更新する'>
								<a class='edit_back' href='<?php echo site_url('admin_admin') ?>'>戻る</a>
							</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
</html>
