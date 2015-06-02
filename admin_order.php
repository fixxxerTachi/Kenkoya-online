<?php include 'templates/meta.php' ?>
<body>
<?php include 'templates/header.php' ?>
<div id="container">
<?php include 'templates/side.php' ?>
	<div id="body">
		<h2><?php echo $h2title ?></h2>
		<div>
			<form method='post' action=''>
			<p>
				本日の受注リストをダウンロードします。
			</p>
			<div id='list'>
				<table cellpadding='0' cellspacing='0'>
					<?php if(count($result) > 0):?>
					<tr><th>商品名</th><th>価格</th></tr>
					<?php foreach($result as $row):?>
						<tr>
							<td><?php echo $row['product_name']	?></td>
							<td><?php echo $row['sale_price'] ?></td> 
						</tr>		
					<?php endforeach;?>
					<?php else: ?>
						<p class='error'>表示できるデータがありません</p>
					<?php endif; ?>
				</table>
			</div>
			<?php if(count($result) > 0):?>
			<p><input type='submit' value='csvダウンロード' name='submit'  id='submit'></p>
			<?php endif; ?>
			</form>
		</div>
	</div>
</div>
<?php include 'templates/footer.php' ?>
</body>
</html>