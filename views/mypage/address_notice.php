<!doctpye html>
<html lang='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/mypage.css') ?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__ . '/../templates/nav_front.php' ?>
<?php include __DIR__ . '/../templates/breadcrumb.php' ?>
<div id="container">
	<div id="container-inner-square">
		<div class='content'>
			<h2><span class='logo_pink'>mypage</span> <?php echo $h2title ?></h2>
			<h3 class='information'>宅配スーパー配達可能エリア内での住所変更</h3>
			<div class='info_content_area'></div>
			<h3 class='information'>宅配スーパー配達可能エリア内から宅配スーパー配達可能エリア外（宅急便でのお届け）への住所変更の場合</h3>
			<div class='info_content_area'></div>
			<h3 class='information'>宅配スーパー配達可能エリア外から宅配スーパー配達可能エリア内への変更の場合</h3>
			<div class='info_content_area'></div>
			<table id='menu'>
				<tr>
					<th class='no-back'></th>
					<td>
						<ul>
							<li><a class='button' href='<?php echo site_url('mypage/mypage_account/address/agreed') ?>'>変更する</a></li>
							<li><a class='edit_back' href='<?php echo site_url('mypage/mypage_acocunt/address') ?>'>戻る</a></li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>