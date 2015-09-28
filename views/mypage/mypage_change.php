<!doctype html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css')?>'>
<script src="http://ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/ajaxzip3.js" charset="UTF-8"></script></head>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner-square">
		<div class='content'>
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
			<?php echo validation_errors('<p class="error">','</p>');?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
			<?php if($type=='name'):?>
					<tr>
						<th><label for='name' name='name'>お名前 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<input type='text' id='name' name='name' value='<?php echo $form_data->name ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='furigana'>フリガナ <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<input type='text' id='furigana' name='furigana' value='<?php echo $form_data->furigana ?>' size='60' maxlength='60'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'mail'):?>
					<tr>
						<th><label for='email_confirm'>メールアドレス <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
							<input type='text' name='email_confirm' id='email_confirm' value='<?php echo $form_data->email_confirm ?>' size='60' maxlength='60'>
						</td>
					</tr>
					<tr>
						<th><label for='email'>メールアドレス(確認) <span class='logo_pink'>必須</span> <span class='logo_green'>半角</span></label></th>
						<td>
							<input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='60' maxlength='60'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'address'):?>
				<?php if(!isset($no_address)):?>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td><?php echo substr($form_data->zipcode,0,3) . '-' . substr($form_data->zipcode,3,4) ?><input type='hidden' name='zipcode' value='<?php echo $form_data->zipcode ?>'></td>
						<div id='search_zip' style='display:none'></div>
					</tr>
					<tr>
						<th><label for='prefecture'>県名</label></th>
						<td>
							<?php echo $form_data->prefecture ?><input type='hidden' name='prefecture' value='<?php echo $form_data->prefecture ?>'>
						</td>
					</tr>
					<tr>
						<th><label for='street'>住所　 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<?php echo $form_data->address1 ?><input type='text' name='street' id='street' value='<?php echo $form_data->street ?>' size='60' maxlength='60'>
							<input type='hidden' name='address1' value='<?php echo $form_data->address1 ?>'>
						</td>
					</tr>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td>
							<input type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='60' maxlength='60'>
						</td>
					</tr>
				<?php else:?>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td><input type='text' name='zipcode1' id='zipcode1' value='<?php echo $form_data->zipcode1 ?>' size='3' maxlength='3'>-<input type='text' name='zipcode2' id='zipcode2' value='<?php echo $form_data->zipcode2 ?>' size='4' maxlength='4'> <a class='button' id='search_zip'>郵便番号で住所検索</a></td>
					</tr>
					<tr>
						<th><label for='address1'>住所・番地 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td><input type='text' name='address1' id='address1' value='<?php echo $form_data->address1 ?>' size='80' maxlength='80'></td>
					</tr>
					<tr>
						<th><label for='address2'>建物・アパート名</label></th>
						<td><input type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='80' maxlength='80'></td>
					</tr>
				<?php endif;?>
			<?php endif;?>
			<?php if($type == 'tel'):?>
					<tr>
						<th><label for='tel'>電話番号 <span class='logo_pink'>必須</span> <span class='logo_green'>全角</span></label></th>
						<td>
							<input type='text' name='tel' id='tel' value='<?php echo $form_data->tel ?>' size='14' maxlength='14'>
						</td>
					</tr>
					<tr>
						<th><label for='tel2'>携帯電話番号</label></th>
						<td>
							<input type='text' name='tel2' id='tel2' value='<?php echo $form_data->tel2 ?>' size='14' maxlength='14'>
						</td>
					</tr>
			<?php endif;?>
			<?php if($type == 'maga'):?>
					<tr>
						<th rowspan='2'><label for='maga'>メルマガ登録</label></th>
						<td>健康屋からのメールマガジンの購読を希望する場合はチェックしてください。<br>登録を解除する場合はチェックをはずしてください。</td>
					</tr>
					<tr>
						<td><input type='checkbox' name='maga' id='maga' value='1' <?php if(!empty($form_data->mail_magazine) && $form_data->mail_magazine == '1') echo "checked=checked"; ?>><label for='maga'>メールマガジンの購読を希望する</label>
					</tr>
			<?php endif;?>
			</table>
				<table id='menu'>
						<th class='no-back'></th>
						<td>
							<ul>
								<li><input type='submit' name='submit' id='submit' value='変更する'></li>
								<li><a class='edit_back' href='<?php echo site_url('mypage') ?>'>戻る</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
<script>
var search_ziop = document.getElementById('search_zip');
search_zip.onclick = function(){
	AjaxZip3.zip2addr('zipcode1','zipcode2','address1','address1')
};
</script>
</body>
</html>