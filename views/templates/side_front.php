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

<!--- start:banner -->
<?php if(!empty($banner)):?>
	<div id='side_banner'>
		<ul class='list_inner'>
	<?php foreach($banner as $item):?>
			<li><a href='<?php if($item->inside_url == '1'):?><?php echo base_url() ?><?php endif;?><?php echo $item->url ?>' target='blank'><img src='<?php echo base_url("images/banner/{$item->image_name}") ?>' alt='<?php echo $item->description?>' width='274'></a></li>
	<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>
<!--- end:banner -->
</div>
