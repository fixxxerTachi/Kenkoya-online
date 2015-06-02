<?php include __DIR__ . '/../templates/meta.php' ?>
<body>
<?php include __DIR__ . '/../templates/header.php' ?>
<div id="container">
<?php include __DIR__ . '/../templates/side.php' ?>
	<div id="body">
		<div class='contents'>
			<h2><?php echo $h2title ?></h2>
				<?php if(!empty($message)):?>
				<p><?php echo $message ?></p>
				<?php endif;?>
				<?php if(!empty($success_message)):?>
				<p class='success'><?php echo $success_message; ?></p>
				<?php endif; ?>
				<?php if(!empty($error_message)):?>
				<p class='error'><?php echo $error_message ?></p>
				<?php endif; ?>
				<form aciton='' method='post'>
					<table>
						<tr>
							<th>商品コード</th>
							<td><input type='text' name='product_code' value='<?php echo $form_data->product_code ?>'></td>
						</tr>
						<tr>
							<th>枝番</th>
							<td><input type='text' name='branch_code' value='<?php echo $form_data->branch_code ?>'></td>
						</tr>
						<tr>
							<th>商品名</th>
							<td><input type='text' name='product_name' value='<?php echo $form_data->product_name ?>'></td>
						</tr>
						<tr>
							<th>略名</th>
							<td><input type='text' name='short_name' value='<?php echo $form_data->short_name ?>'></td>
						</tr>
						<tr>
							<th>販売単価</th>
							<td><input type='text' name='sale_price' value='<?php echo $form_data->sale_price ?>'></td>
						</tr>
						<tr>
							<th>仕入単価</th>
							<td><input type='text' name='cost_price' value='<?php echo $form_data->cost_price ?>'></td>
						</tr>
						<tr>
							<th>販売中</th>
							<td>
								<select name='on_sale'>
									<option value=1 <?php if($form_data->on_sale == 1) echo 'selected = selected' ?>>販売中</option>
									<option value=0 <?php if($form_data->on_sale == 0) echo 'selected = selected' ?>>販売中止</option>
								</select>
						</tr>
						<tr>
							<th></th>
							<td><input type='submit' name='submit' value='登録する'></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer.php' ?>
</body>
</html>