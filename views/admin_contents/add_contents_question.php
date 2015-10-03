<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="container">
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
				<div class='contents'>
					<table class='mdl-data-table mdl-js-data-table'>
						<?php echo form_open() ?>
							<tr><th class="mdl-data-table__cell--non-numeric">カテゴリ</th><td><?php echo form_dropdown('category_id',$category_list,$form_data->category_id) ?></td></tr>
							<tr>	
								<th class="mdl-data-table__cell--non-numeric">質問内容</th>
								<td><textarea name='question' rows='3' cols='70'><?php echo $form_data->question ?></textarea></td>
							</tr>
							<tr>
								<th class="mdl-data-table__cell--non-numeric">質問の答え</th>
								<td><textarea name='answer' rows='20' cols='70'><?php echo $form_data->answer ?></textarea></td>
								<td>使えるタグ: <br>h4以下、img,table.payment_answer,ul.payment_answer,span.notice</td>
							</tr>
							<tr>
								<th class='no-border'></th><td><input type='submit' name='submit' value='登録' style='margin-right: 30px'><a class='edit_back' href='<?php echo site_url('admin_contents/list_contents_question') ?>'>戻る</a></td>
							</tr>
						</form>
					</table>
				</div>
			</div>
		</main>
	</div>
</body>
</html>