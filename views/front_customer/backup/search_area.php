<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/customer.css') ?>'>
</head>
<body>
<div id="wrapper">
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<?php include __DIR__.'/../templates/breadcrumb.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
		<h2><span class='logo_pink'>area</span> <?php echo $h2title ?></h2>
			<p class='note'>初めてご注文される方、会員登録をされていない方は、郵便番号を入力するか、住所を検索して配達方法を調べてください。</p>
			<?php if(!empty($success_message)):?>
			<p class='success'><?php echo $success_message; ?></p>
			<?php endif; ?>
			<h3>郵便番号から検索</h3>
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
						<td><span>無料配達可能エリアです</span></td>
						<td><?php echo $result->prefecture ?> <?php echo $result->city ?><?php echo $result->address ?></td>
					</tr>
					<tr>
						<td></td>
						<td><p><a class='kiyaku' href='<?php echo base_url('front_customer/show_policy')?>'>会員登録を進める<br>（会員規約表示）</a></p></td>
					</tr>
					<tr>
						<td class='no-border'></td>
						<td><a class='edit' href='<?php echo base_url('/') ?>'>Topへ戻る</a></td>
					</tr>
					<?php else: ?>
					<tr>
						<th class='result'><label>判定</label></th>
						<td><span>お客さまのご住所では健康屋宅配サービスをご利用いただけませんが、宅配便でご希望の商品を発送致します。</td>
					</tr>
					<tr>
						<td></td>
						<td><p><a class='kiyaku' href='<?php echo base_url('front_customer/show_policy')?>'>会員登録を進める（会員規約表示)</a></p></td>
					</tr>
					<?php endif;?>
			<?php endif;?>
				</table>
			</form>
			<h3>住所から検索</h3>
				<div id='map'>
					<img src='<?php echo base_url('images/map2.jpg') ?>' usemap='#map' width='1024' alt='配達エリア'>
					<map name='map'>
						<area href ='<?php echo base_url('front_customer/show_area/1')?>' shape='rect' alt='金沢地区' coords='50,420,200,480'>
						<area href ='<?php echo base_url('front_customer/show_area/2')?>' shape='rect' alt='能登地区' coords='100,210,250,270'>
					</map>
				</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>
<?php echo 'address:'; var_dump($this->session->userdata('address'));?>