<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
<script src='<?php echo base_url('js/jquery.ah-placeholder.js') ?>'></script>
<script src='<?php echo base_url('js/placeholder.js')?>'></script>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class="container">
					<h2><span class='logo_pink'>member</span> <?php echo $h2title ?></h2>
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
						<table class='detail' cellpadding='0' cellspacing='10'>
							<tr>
								<th><label for='shop_id'>店舗コード</label> <span class='logo_pink'>必須</span></th>
								<td><?php echo form_dropdown('shop_id',$shops,$form_data->shop_id,'id="shop_id"') ?></td>
							</tr>
							<tr>
								<th><label for='cource_id'>コース名 <span class='logo_pink'>必須</span></label></th>
								<td><?php echo form_dropdown('cource_id',$cource_list,$form_data->cource_id,'id="cource_id"') ?></td>
							</tr>
							<tr>
								<th><label for='code'>顧客コード <span class='logo_pink'>必須</span></label></th>
								<td>
									<input type='text' name='code' id='code' value='<?php echo $form_data->code ?>' size='6' maxlength='6'>
								</td>
							</tr>
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
								<th><label for='email'>メールアドレス</label></th>
								<td>
									<input type='text' name='email' id='email' value='<?php echo $form_data->email ?>' size='60' maxlength='60'>
								</td>
							</tr>
							<tr>
								<th><label for='zipcode'>郵便番号 <span class='logo_pink'>必須</span></label></th>
								<td>
									<input type='text' name='zipcode' id='zipcode' value='<?php echo $form_data->zipcode ?>' size='7' maxlength='7'>(-なしの7ケタ)
								</td>
							</tr>
							<tr>
								<th><label for='address1'>住所</label> <span class='logo_pink'>必須</span></th>
								<td>
									<input type='text' name='address1' id='address1' value='<?php echo $form_data->address1 ?>' size='60' maxlength='60'>
								</td>
							</tr>
							<tr>
								<th><label for='address2'>建物名</label></th>
								<td><input type='text' name='address2' id='address2' value='<?php echo $form_data->address2 ?>' size='60' maxlength='60'>
							</tr>
							<tr>
								<th><label for='tel'>電話番号</label> <span class='logo_pink'>必須</span></th>
								<td>
									<input type='text' placeholder='0335430001' name='tel' id='tel' value='<?php echo $form_data->tel ?>' size='15' maxlength='15'>
								</td>
							</tr>
							<tr>
								<th><label for='tel'>携帯電話番号</label></th>
								<td>
									<input type='text' placeholder='0335430002' name='tel2' id='tel2' value='<?php echo $form_data->tel2 ?>' size='15' maxlength='15'>
								</td>
							</tr>
							<tr>
								<th><label for='birthday'>生年月日</label></th>
								<td>
									<input placeholder="2000-01-01 01"type='text' name='birthday' id='birthday' value='<?php echo $form_data->birthday ?>' size='10' maxlength='10'>
								</td>
							</tr>
							<!--
							<tr>
								<th><label for='point'>ポイント</label></th>
								<td>
									<input type='point' name='point' id='point' value='<?php echo $form_data->point ?>' size='4' maxlength='4'>
								</td>
							</tr>
							<tr>
								<th><label for='rank'>ランク</label></th>
								<td>
									<input type='rank' name='rank' id='rank' value='<?php echo $form_data->rank ?>' size='2' maxlength='2'>
								</td>
							</tr>
							<tr>
								<th><label for='bank_name'>銀行名</label></th>
								<td><input type='text' name='bank_name' id='bank_name' value='<?php echo $form_data->bank_name ?>'>
							</tr>
							<tr>
								<th>口座種別</th>
								<td>
									<input type='radio' name='type_account' id='touza' <?php if($form_data->type_account == 1):?> checked='checked' <?php endif ?> value='1'><label for='touza'>当座</label>
									<input type='radio' name='type_account' id='hutu' <?php if($form_data->type_account == 2):?> checked='checked' <?php endif ?> value='2'><label for='hutu'>普通</label>
								</td>
							</tr>
							<tr>
								<th><label for='account_number'>口座番号</label></th>
								<td><input type='text' name='account_number' id='account_number' value='<?php echo $form_data->account_number ?>'>
							</tr>
							-->
							<tr>
								<th class='no-border'></th>
								<td><input type='submit' name='submit' value='登録する' style='margin-right: 30px'> <a class='edit_back' href='<?php echo site_url('admin_customer/list_customer') ?>'>戻る</a></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</main>
	</div>
</body>
<script>
$('#shop_id').on('change',function()
{
	$('#cource_id').empty();
	var id = $(this).val();
	$.getJSON(
		'<?php echo site_url('admin_admin/show_cource')?>' + '/' + id,
		function(data){
			var items = [];
			$.each(data,function(k,v){
				items.push('<option value="' + k + '">' + v + '</option>');
			});
			$('#cource_id').append(items.join());
		}
	);
});
</script>
</html>