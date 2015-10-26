<!doctype html>
<html lang='ja'>
<head>
<meta charset='utf-8'>
</head>
<body>
<div id="container">
	<div id="body">
		<h2>銀行振込　入金確認</h2>
		<p>注文番号：<?php echo $order_number ?></p>
		<div>
			<?php echo form_open() ?>
			<div id='list'>
				<ul>
					<li><input type='radio' name='paid' value='0' id='nopaid' <?php if($param == 0) echo 'checked=checked';?>><label for='nopaid'>未入金</label></li>
					<li><input type='radio' name='paid' value='1' id='paid' <?php if($param == 1) echo 'checked=checked';?>><label for='paid'>入金確認済み</label></li>
					<li><input type='submit' name='submit' value='登録'><input type='button' name='button' value='閉じる' onclick='window.close()'></li>
				</ul>
			</div>
			</form>
		</div>
	</div>
</div>
</body>
<style type='text/css'>
ul{
	list-style-type: none;
}
.success{
	color: orange;
	text-align: center;
	border: solid 1px orange;
	border-radius: 5px;
}

input[name="submit"]{
	padding: 5px 10px 5px 10px;
	margin-right: 20px;
}
input[name="button"]{
	margin-left: 10px;
}
</style>
</html>
