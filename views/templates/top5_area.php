<?php if(!empty($top10)):?>
<div class='information clearfix'>
	<!--<h2><img src='<?php echo base_url('images/information.jpg') ?>' alt='宅配スーパー健康屋からのおしらせ'></h2>-->
		<h2><span class='logo'>TOP10</span>&nbsp;&nbsp;宅配スーパー健康屋：人気商品</h2>
	<?php $count = 1;?>
	<?php foreach($top10 as $item):?>
		<ul class='inner-information'>
		<?php if(!empty($item->advertise_id)):?>
			<?php if($count == 1 || $count == 2 || $count == 3):?><li><img src='<?php echo base_url("images/icon/rank0" . $count) . ".gif" ?>' width='28' height='48'></li><?php endif;?>
			<?php if($count == 4 || $count == 5):?><li class='rank'><?php echo $count ?><span>位</span></li><?php endif;?>
			<li><?php if(!empty($item->title)):?><span class='logo_pink_large'><?php echo $item->title ?> 掲載</span><?php endif;?></li>
			<!--<li><a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_image($item->product_code, 120)) ?>' width='120' height='120' alt='<?php echo $item->ad_pro_product_name ?>'></a></li>-->
			<li><a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url("images/products/ak{$item->product_code}.jpg") ?>' width='120' height='120' alt='<?php echo $item->ad_pro_product_name ?>'></a></li>
			<li><?php echo $item->maker ?></li>
			<li><?php echo $item->ad_pro_product_name ?></li>
			<li><?php echo number_format($item->ad_pro_sale_price) ?>円</li>
		<?php else:?>
			<li><img src='<?php echo base_url("images/icon/rank0" . $count) . ".gif" ?>' width='28' height='48'></li>
			<li><a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_product_image($item->p_image_name,120)) ?>' width='120' height='120' alt='<?php echo $item->ad_pro_product_name ?>'></a></li>
			<li><?php echo $item->p_product_name ?></li>
			<li><?php echo number_format($item->ad_pro_sale_price) ?>円</li>
		<?php endif;?>
		<?php $count+=1;?>
		</ul>
	<?php endforeach;?>
</div>
<?php endif;?>