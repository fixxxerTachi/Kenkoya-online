<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id="container-inner">
		<div class='content'>
		<h2><span class='logo_pink'>area</span> <?php echo $h2title ?></h2>
			<p class='note'>初めてご注文される方、会員登録をされていない方は、郵便番号を入力するか、住所を検索して配達方法を調べてください。</p>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<?php echo form_open() ?>
				<table class='contact_form' cellpadding='0' cellspacing='10'>
					<p>お客様のご住所の郵便番号を入力して検索ボタンを押してください</p>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>	
					<?php echo validation_errors('<p class="error">','</p>');?>
					<tr>
						<th><label for='zipcode'>郵便番号</label></th>
						<td>
							<input type='text' name='zipcode1' id='zipcode1' value='<?php echo $form_data->zipcode1 ?>' size='3' maxlength='3'>-
							<input type='text' name='zipcode2' id='zipcode2' value='<?php echo $form_data->zipcode2 ?>' size='4' maxlength='4'>
							<input type='submit' name='search_zip' value='検索'>
						</td>
					</tr>
<?php if(isset($is_area)):?>
	<?php if($is_area):?>
					<tr>
						<th><label for='prefecture'>判定</label></th>
						<td>
							<span>宅配スーパー健康屋の配達可能エリアです</span><br>
							<?php echo $result->prefecture ?> <?php echo $result->city ?><?php echo $result->address ?> (お届け日: <?php echo $result->takuhai_day ?>)
						</td>
					</tr>
					<tr>
						<td class='no-border'></td>
		<?php /*配達エリアの内の場合のリンク表示*/ if(!empty($member_link->recommend_text)):?>
						<td><?php echo $member_link->recommend_text ?></td>
		<?php endif;?>
	<?php /* 配達エリア外のリンク表示 */ else: ?>
					<tr>
						<th class='result'><label>判定</label></th>
						<td><span>宅配便でご希望の商品を配送いたします。<td>
	<?php endif;?>

					</tr>
					<tr>
						<td class='no-border'></td>
						<td><a class='button' href='<?php echo base_url($member_link->member_url) ?>'><?php echo $member_link->member_text ?></td>
					</tr>
		<?php if(!empty($member_link->url)):?>
					<tr>
						<td class='no-border'></td>
						<td><a class='button' href='<?php echo base_url($member_link->url) ?>'><?php echo $member_link->text ?></td>
					</tr>
		<?php endif;?>
<?php endif;?>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
	document.getElementById('zipcode1').onkeyup = function(){
		if(this.value.length == 3){
			document.getElementById('zipcode2').focus();
		}
	}
</script>
</html>
