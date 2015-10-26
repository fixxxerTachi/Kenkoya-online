<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
<script src="//ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/ajaxzip3.js" charset="UTF-8"></script></head>
<script src='<?php echo base_url('js/jquery.ah-placeholder.js') ?>'></script>
<script src='<?php echo base_url('js/placeholder.js')?>'></script>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
			<h2><span class='logo_pink'>member</span> <?php echo $h2title ?></h2>
			<p class='note'><?php echo $message ?></p>
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
						<th><label for='name' name='name'>お名前 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<input placeholder='例）山田太郎' type='text' id='name' name='name' value='<?php echo $form_data->name ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<input placeholder='例)ヤマダタロウ' type='text' id='furigana' name='furigana' value='<?php echo $form_data->furigana ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='email_confirm'>メールアドレス <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
							<input placeholder='例)taro@youremail.com' type='text' name='email_confirm' id='email_confirm' value='<?php echo $form_data->email_confirm ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='email'>メールアドレス(確認) <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
							<input placeholder='例)taro@youremail.com' type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='zipcode'>郵便番号 <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
	<?php if($is_master_area):?>
							<?php echo substr($form_data->zipcode,0,3) . '-' . substr($form_data->zipcode,3,4) ?>
							<input type='hidden' name='zipcode1' value='<?php echo substr($form_data->zipcode,0,3) ?>'>
							<input type='hidden' name='zipcode2' value='<?php echo substr($form_data->zipcode,3,4)?>'>
							<input type='hidden' name='zipcode' value='<?php echo $form_data->zipcode?>'>
	<?php else:?>
							<input placeholder='例)100' type='text' name='zipcode1' id='zipcode1' value='<?php echo $form_data->zipcode1 ?>' size='3' maxlength='3'>-<input placeholder='例)0000' type='text' name='zipcode2' value='<?php echo $form_data->zipcode2 ?>' size='4' maxlength='4'><a id='search_zip' class='button'>郵便番号で住所を検索</a>
	<?php endif;?>
						</td>
					</tr>
					<tr>
						<th><label for='prefecture'>県名 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
	<?php if($is_master_area):?>
							<?php echo $form_data->prefecture ?><input type='hidden' name='prefecture' value='<?php echo $form_data->prefecture ?>'>
	<?php else:?>
							<input placeholder='例)東京都' type='text' name='prefecture' id='prefecture' value='<?php echo $form_data->prefecture ?>' size='4' maxlength='4'>
	<?php endif;?>
						</td>
					</tr>
					<tr>
						<th><label for='address1'>住所,番地 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
	<?php if($is_master_area):?>
							<?php echo $form_data->address ?><input type='text' name='address1' id='address1' value='<?php echo $form_data->address1 ?>' size='30' maxlength='30'>
							<input type='hidden' name='address' value='<?php echo $form_data->address ?>'>
	<?php else:?>
							<input placeholder='例)千代田区大手町９９－９９' type='text' name='address1' id='address1' value='<?php echo $form_data->address1 ?>' size='60' maxlength='60'>
	<?php endif;?>
						</td>
					</tr>
					<tr>
						<th><label for='address2'>建物・アパート名 <span class='logo_green'>全角</span></label></th>
						<td>
							<input placeholder='例)大手町マンション２２２' type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='tel'>電話番号 <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
							<input placeholder='例)03333333' type='text' name='tel' id='tel' value='<?php echo $form_data->tel ?>' size='15' maxlength='15'>
							<br>ご自宅の電話番号がもしくは携帯電話番号
						</td>
					</tr>
					<tr>
						<th><label for='tel'>携帯電話番号 <span class='logo_green'>半角</span></label></th>
						<td>
							<input placeholder='例)09099999999' type='text' name='tel2' id='tel2' value='<?php echo $form_data->tel2 ?>' size='15' maxlength='15	'>
						</td>
					</tr>
		<?php if(!$no_member == 'no_member' || $no_member == 'nav'):?>
					<tr>
						<th><label for='birthday'>生年月日 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<?php echo form_dropdown('year',$birthday->set_year(),$form_data->year) ?>月<?php echo form_dropdown('month',$birthday->set_month(),$form_data->month) ?>月<?php echo form_dropdown('day',$birthday->set_day(),$form_data->day)?>日
						</td>
					</tr>
		<?php endif;?>
					<tr>
						<td class='no-border'></td>
						<td><input type='submit' name='submit' value='確認画面へ'><a class='edit_back' href='<?php echo site_url('front_customer/login_action') ?>'>戻る</a></td>
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
	AjaxZip3.zip2addr('zipcode1','zipcode2','prefecture','address1')
};
</script>
</html>
