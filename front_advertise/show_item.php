<?php if(!empty($result)): //販売可能商品がある場合?>
	<?php $today = new DateTime();?>
	<?php foreach($result as $row):?>
		<?php //商品ごとに販売期間が設定してある場合 ?>
		<?php if(!empty($row->sale_start_datetime) || !is_null($row->sale_start_datetime) || !empty($row->sale_end_datetime) || !is_null($row->sale_end_datetime)):?>
			<?php $ssdatetime = $row->sale_start_datetime ? new DateTime($row->sale_start_datetime) : new DateTime('1999-01-01 00:00:00');?>
			<?php $sedatetime = $row->sale_end_datetime ? new DateTime($row->sale_end_datetime) : new DateTime('9999-12-31 23:59:59');?>
			<?php if($ssdatetime <= $today && $today < $sedatetime)://販売期限内?>
		<div class='information clearfix'>
			<p class='success' id="success_<?php echo $row->id?>">カートに入れました</p>
			<p class='detail-information2'><a href='javascript:void(0)' class='close'>閉じる</a></p>
			<ul class='detail-information1'>
				<li><img src='<?php echo base_url(show_image($row->product_code)) ?>' alt='<?php echo $row->product_name ?>' width='150' height='150'></li>
			</ul>
			<table class='detail-information3'>
				<tr><th><span class='logo'>NO.</span></th><td><?php echo $row->code ?></td></tr>
				<tr><th><span class='logo'>メーカー―</span></th><td><?php echo $row->maker ?></td></tr>
				<tr><th><span class='logo'>商品名</span></th><td><?php echo $row->product_name ?></td></tr>
				<tr><th><span class='logo'>内容量</span></th><td><?php echo $row->size ?></td></tr>
				<tr><th><span class='logo'>賞味期限</span></th><td><?php echo $row->freshness_date ?></td></tr>
				<tr><th><span class='logo'>カロリー</span></th><td><?php echo $row->note ?></td></tr>
				<tr><th><span class='logo'>アレルゲン</span></th><td><?php echo $row->allergen ?></td></tr>
				<tr><th><span class='logo'>価格(税抜)</span></th><td><?php echo number_format($row->sale_price) ?>円</td></tr>
			</table>
			<ul class='cart clearfix'>
				<?php echo form_open('front_cart/input_cart') ?>
					<input type='hidden' name='product_id' value='<?php echo $row->id ?>' id='product_id_<?php echo $row->id ?>'>
					<input type='hidden' name='advertise_id' value='<?php echo $row->advertise_id ?>' id='advertise_id_<?php echo $row->id ?>'>
					<input type='hidden' name='sale_price' value='<?php echo $row->sale_price ?>' id='sale_price_<?php echo $row->id ?>'>
					<input type='hidden' name='product_code' value='<?php echo $row->product_code ?>' id='product_code_<?php echo $row->id ?>'>
					<input type='hidden' name='branch_code' value='<?php echo $row->branch_code ?>' id='branch_code_<?php echo $row->id ?>'>
					<li>数量 <?php echo form_dropdown('quantity',$select_quantity,'', "id='quantity_{$row->id}'") ?></li>
					<li><input class='cart_button' name='submit' type='submit' value='カートに入れる' onclick='return false' id='submit_<?php echo $row->id ?>'></li>
				</form>
			</ul>
		</div>
			<?php else://販売期限外 ?>
			<div class='information clearfix'>
				<p class='detail-information2'><a href='javascript:void(0)' class='close'>閉じる</a></p>
				<ul class='detail-information1'>
					<li><img src='<?php echo base_url(show_image($row->product_code)) ?>' alt='<?php echo $row->product_name ?>' width='150' height='150'></li>
				</ul>
				<table class='detail-information3'><tr><th>申し訳ございません。<br>こちらの商品は現在お取扱いしておりません。</th></tr></table>
			</div>
			<?php endif;//販売期限チェック終了?>
		<?php else: //販売期間が全く設定されていない場合 ?>
		<div class='information clearfix'>
			<p class='success' id="success_<?php echo $row->id?>">カートに入れました</p>
			<p class='detail-information2'><a href='javascript:void(0)' class='close'>閉じる</a></p>
			<ul class='detail-information1'>
				<li><img src='<?php echo base_url(show_image($row->product_code)) ?>' alt='<?php echo $row->product_name ?>' width='150' height='127'></li>
			</ul>
			<table class='detail-information3'>
				<tr><th><span class='logo'>NO.</span></th><td><?php echo $row->code ?></td></tr>
				<tr><th><span class='logo'>メーカー―</span></th><td><?php echo $row->maker ?></td></tr>
				<tr><th><span class='logo'>商品名</span></th><td><?php echo $row->product_name ?></td></tr>
				<tr><th><span class='logo'>内容量</span></th><td><?php echo $row->size ?></td></tr>
				<tr><th><span class='logo'>賞味期限</span></th><td><?php echo $row->freshness_date ?></td></tr>
				<tr><th><span class='logo'>カロリー</span></th><td><?php echo $row->note ?></td></tr>
				<tr><th><span class='logo'>アレルゲン</span></th><td><?php echo $row->allergen ?></td></tr>
				<tr><th><span class='logo'>価格(税抜)</span></th><td><?php echo number_format($row->sale_price) ?>円</td></tr>
			</table>
			<ul class='cart clearfix'>
				<?php echo form_open('front_cart/input_cart') ?>
					<input type='hidden' name='product_id' value='<?php echo $row->id ?>' id='product_id_<?php echo $row->id ?>'>
					<input type='hidden' name='advertise_id' value='<?php echo $row->advertise_id ?>' id='advertise_id_<?php echo $row->id ?>'>
					<input type='hidden' name='sale_price' value='<?php echo $row->sale_price ?>' id='sale_price_<?php echo $row->id ?>'>
					<input type='hidden' name='product_code' value='<?php echo $row->product_code ?>' id='product_code_<?php echo $row->id ?>'>
					<input type='hidden' name='branch_code' value='<?php echo $row->branch_code ?>' id='branch_code_<?php echo $row->id ?>'>
					<li>数量 <?php echo form_dropdown('quantity',$select_quantity,'', "id='quantity_{$row->id}'") ?></li>
					<li><input class='cart_button' name='submit' type='submit' value='カートに入れる' onclick='return false' id='submit_<?php echo $row->id ?>'></li>
				</form>
			</ul>
		</div>
		<?php endif;?>
	<?php endforeach;?>
<?php else://販売中止の場合?>
		<div class='information clearfix'>
			<p class='detail-information2'><a href='javascript:void(0)' class='close'>閉じる</a></p>
			<ul class='detail-information1'>
				<li><img src='<?php echo base_url('images/sorry.jpg') ?>' alt='申し訳ございません。こちらの商品はただいまお取扱いしておりません' width='150' height='150'></li>
			</ul>
			<table class='detail-information3'><tr><th>申し訳ございません。</br>こちらの商品は現在お取扱いしておりません。</th></tr></table>
		</div>
<?php endif;?>
<script>
$('.close').on('click',function(){
	$('#show_item').fadeOut();
	$('#cover').fadeOut();
});
$('p.success').hide();
$('input[name=submit]').on('click',function(){
	 var url = '<?php echo base_url("front_cart/input_cart") ?>';
	 var submit_id  = $(this).attr('id');
	 var id  = submit_id.split('_').pop();
	 var product_id = $('#product_id_' + id).val();
	 var ad_id = $('#advertise_id_' + id).val();
	 var quantity = $('#quantity_' + id).val();
	 var sale_price = $('#sale_price_' + id).val();
	 var product_code = $('#product_code_' + id).val();
	 var branch_code = $('#branch_code_' + id).val();
	 var submit = 'submit';
	 $.ajax({
		url: url,
		type: 'POST',
		dataType: 'html',
		data : { 
			"product_id":product_id,
			"advertise_id":ad_id,
			"quantity":quantity,
			"submit":submit,
			"sale_price":sale_price,
			"product_code":product_code,
			"branch_code":branch_code,
			"<?php echo $this->security->get_csrf_token_name() ?>":"<?php echo $this->security->get_csrf_hash() ?>"
		},
		timeout: 10000,
		success: function(data){
			$('p#success_' + id).text('カートに入れました');
			$('p#success_' + id).fadeIn('slow');
		},
		error: function(xmlobj,status,error){
			$('p#success_' + id).text('カートに入れることができません');
			$('p#success_' + id).fadeIn('slow');
		}
	});

});
</script>