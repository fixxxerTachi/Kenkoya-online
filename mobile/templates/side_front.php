<div id='side'>
	<div class='side_inner'>
		<p class='side_title'><span class='logo_pink'>search</span> 商品をさがす</p>
		<p>商品名を入力してください</p>
		<form method='get' action='<?php echo base_url('index/search_product') ?>'>
			<input class='input_search' type='text' name='product' size='20' maxlength='30'>
			<input class='input_search' type='submit' value='検索'>
		</form>
		<!--<p class='side_notice'>カテゴリから検索する</p>-->
	</div>
<!--- start:catetory -->
<?php if(!empty($categories)):?>
	<div class='side_inner'>
		<p class='side_title'><span class='logo_pink'>category</span> カテゴリから検索</p>
		<ul class='list_inner'>
	<?php foreach ($categories as $item):?>
			<li  class='category_link'><a href='<?php echo base_url("index/category/{$item->id}") ?>'><?php echo $item->show_name ?></a></li>
	<?php endforeach;?>	
		</ul>
	</div>
<?php endif;?>
<!--- end:category --->

<!--  start: top10 
<?php if(!empty($top10)):?>
	<div class='side_inner'>
		<p class='side_title'><span class='logo_pink'>TOP3</span> 売れ筋TOP3</p>
		<ul class='list_inner'>
	<?php $count = 1; ?>
	<?php foreach($top10 as $item):?>
		<?php if(!empty($item->advertise_id)):?>
			<li>
				<img src='<?php echo base_url("images/icon/rank0" . $count) . ".gif" ?>' width='28' height='48'>
				<a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_product_image($item->p_image_name)) ?>' width='30' height='30' alt='<?php echo $item->ad_pro_product_name ?>'>
				<?php echo $item->ad_pro_product_name ?></a>
			</li>
		<?php else:?>
			<li>
				<img src='<?php echo base_url("images/icon/rank0" . $count) . ".gif" ?>' width='28' height='48'>
				<a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_product_image($item->p_image_name)) ?>' width='30' height='30' alt='<?php echo $item->ad_pro_product_name ?>'>
				<?php echo $item->p_product_name ?></a>
			</li>
		<?php endif;?>
		<?php $count+=1;?>
	<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>


<?php if(!empty($recommend)):?>
	<div class='side_inner'>
		<p class='side_title'><span class='logo_pink'>RECOMMEND</span> おすすめ</p>
		<ul class='list_inner'>
	<?php foreach($recommend as $item):?>
		<?php if(!empty($item->advertise_id)):?>
			<li>
				<a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_product_image($item->p_image_name)) ?>' width='30' height='30' alt='<?php echo $item->ad_pro_product_name ?>'>
				<?php echo $item->ad_pro_product_name ?></a>
			</li>
		<?php else:?>
			<li>
				<a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_product_image($item->p_image_name)) ?>' width='30' height='30' alt='<?php echo $item->ad_pro_product_name ?>'>
				<?php echo $item->p_product_name ?></a>
			</li>
		<?php endif;?>
		<?php $count+=1;?>
	<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>
end:recommend -->
<!--- start:banner -->
<?php if(!empty($banner)):?>
	<div id='side_banner' class='mobile-hidden'>
		<ul class='list_inner'>
	<?php foreach($banner as $item):?>
			<li><a href='<?php if($item->inside_url == '1'):?><?php echo base_url() ?><?php endif;?><?php echo $item->url ?>' target='blank'><img src='<?php echo base_url("images/banner/{$item->image_name}") ?>' alt='<?php echo $item->description?>' width='274'></a></li>
	<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>
<!--- end:banner -->
</div>
