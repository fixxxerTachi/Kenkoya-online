<?php include __DIR__ . '/../templates/meta_front.php' ?>
<body>
<?php include __DIR__ . '/../templates/header_front.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
		<h2><?php echo $h2title ?></h2>
			<p>配達エリアの説明のテキスト。配達エリアの説明のテキスト。配達エリアの説明のテキスト。配達エリアの説明のテキスト。</p>
			<?php if(!empty($message)):?>
			<p class='message'><?php echo $message ?></p>
			<?php endif;?>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
			<?php endif; ?>	
			<?php echo validation_errors('<p class="error">','</p>');?>
			<form aciton='' method='post'>
				<table class='detail' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<input type='text' name='zipcode1' id='zipcode1' value='<?php echo $form_data->zipcode1 ?>' size='3' maxlength='3'>-
							<input type='text' name='zipcode2' id='zipcode2' value='<?php echo $form_data->zipcode2 ?>' size='4' maxlength='4'>
						</td>
						<td><input type='submit' name='search_zip' value='検索'></td>
					</tr>
					<?php if($result && $result != 'no'):?>
					<tr>
						<th><label for='prefecture'>判定</label></th>
						<td><span>配達可能エリアです</span></td>
						<td><?php echo $result->prefecture ?> <?php echo $result->city ?><?php echo $result->address ?></td>
					</tr>
					<tr>
						<th class='no-border'></th>
						<td><a class='edit' href='<?php echo site_url($member_link->url) ?>'><?php echo $member_link->text ?></a></td>
					</tr>
					<?php elseif($result == 'no'): ?>
					<tr>
						<th><label>判定</label></th>
						<td><span>申し訳ありません配達エリア外です<a class='edit' href='<?php echo site_url('front_customer/login_action') ?>'>戻る</a></td>
					</tr>
					<?php endif;?>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>