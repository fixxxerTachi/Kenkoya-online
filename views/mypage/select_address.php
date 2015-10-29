<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
<script src='//code.jquery.com/jquery-2.1.3.min.js'></script>
<script src="//ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/ajaxzip3.js" charset="UTF-8"></script></head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>address</span> 登録されている配送先</h2>
			<div id='content'>
				<div class='note'>
	<?php if(!empty($addresses)):?>
				<?php echo form_open() ?>
					<table class='inner_table'>
		<?php foreach($addresses as $item):?>
						<tr>
							<td id='table_address'><?php echo $item->address1 ?><?php echo $item->address2?> <?php echo $item->name ?>　様</td>
							<td><a class='button' href='<?php echo site_url("mypage/edit_address/{$item->id}") ?>'>編集する</a></td>
							<td><a class='button' href='<?php echo site_url("mypage/del_address/{$item->id}") ?>'>削除する</a></td>
						</tr>
		<?php endforeach;?>
					</table>
				</form>
	<?php else:?>
					別の配送先は登録されていません
	<?php endif;?>
				</div>
			</div>
			<h2><span class='logo_pink'>address</span> <?php echo $h2title ?></h2>
			<p><span class='logo_pink'>必須</span>は必ず入力してください</p>
	<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
	<?php endif; ?>
	<?php if(!empty($error_message)):?>
			<p class='error'><?php echo $error_message ?></p>
	<?php endif; ?>	
	<?php if(validation_errors()):?>
			<?php echo validation_errors() ?>
	<?php endif;?>
		</div>
		<div class='content'>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<tr>
						<th><label for='name' name='name'>お名前 <span class='logo_pink'>必須</span></label></th>
						<td>
							<input type='text' id='name' name='name' value='<?php echo $form_data->name ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ <span class='logo_pink'>必須</span></label></th>
						<td>
							<input type='text' id='furigana' name='furigana' value='<?php echo $form_data->furigana ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
					<tr>
						<th><label for='zipcode'>郵便番号 <span class='logo_pink'>必須</span></label></th>
						<td>
							<input type='text' name='zipcode1' id='zipcode1' value='<?php echo $form_data->zipcode1 ?>' size='3' maxlength='3'>
							-<input type='text' name='zipcode2' value='<?php echo $form_data->zipcode2 ?>' size='4' maxlength='4'>
							<a id='search_zip' class='button'>郵便番号で住所を検索</a>
						</td>
					</tr>
					<tr>
						<th><label for='street'>住所,番地 <span class='logo_pink'>必須</span></label></th>
						<td>
							<input type='text' name='address1' id='address1' value='<?php echo $form_data->address1 ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td>
							<input type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>電話番号 <span class='logo_pink'>必須</span></label></th>
						<td>
							<input type='text' name='tel' id='tel' value='<?php echo $form_data->tel ?>' size='20' maxlength='20'>
							<br>商品のお届けに問題が起きた場合にご連絡できるよう、電話番号を入力してください。
						</td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td>
<?php if(empty($edit_flag)):?>
							<input type='submit' name='submit' value='確認画面へ'>
<?php else:?>
							<input type='submit' name='submit' value='登録する'>
<?php endif;?>
							<a class='edit_back' href='<?php echo site_url('mypage') ?>'>戻る</a>
						</td>

						</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
var search_zip = document.getElementById('search_zip');
search_zip.onclick = function(){
	AjaxZip3.zip2addr('zipcode1','zipcode2','address1','address1');
};
</script>
</html>