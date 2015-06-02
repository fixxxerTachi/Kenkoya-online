<?php if(!empty($recommend)):?>
<div class='information'>
	<h2><span class='logo'>RECOMMEND</span>&nbsp;&nbsp;宅配スーパー健康屋：おすすめ商品</h2>
	<div class='swiper-container'>
		<div class='swiper-wrapper'>
		<?php foreach($recommend as $item):?>
			<div class='swiper-slide'>
				<ul class='inner-information'>
			<?php if(!empty($item->advertise_id)): ?>
					<li><?php if(!empty($item->title)):?><span class='logo_pink_large'><?php echo $item->title ?> 掲載</span><?php endif;?>
					<li><a href='<?php echo base_url("index/detail_product/{$item->product_id}") ?>'><img src='<?php echo base_url(show_image($item->product_code, 120)) ?>' width='120' height='120' alt='<?php echo $item->ad_pro_product_name ?>'></a></li>
					<li><?php echo $item->ad_pro_product_name ?></li>
					<li><?php echo number_format($item->ad_pro_sale_price) ?>円</li>
			<?php else:?>
					<li><a href='<?php echo base_url("index/detail_product/{$item->product_id}")?>'><img src='<?php echo base_url(show_image($item->product_code,120))?>' width='120' height='120' alt='<?php echo $item->ad_pro_product_name ?>'></a></li>
					<li><?php echo $item->p_product_name ?></li>
					<li><?php echo number_format($item->ad_pro_sale_price) ?>円</li>
			<?php endif;?>
				</ul>
			</div>
		<?php endforeach;?>
		</div>
	</div>
</div>
<?php endif;?>