<?php if(!empty($informations)):?>
<div class='information'>
	<!--<h2><img src='<?php echo base_url('images/information.jpg') ?>' alt='宅配スーパー健康屋からのおしらせ'></h2>-->
		<h2><span class='logo'>information</span>&nbsp;&nbsp;宅配スーパー健康屋からのお知らせ</h2>
		<ul>
<?php foreach($informations as $info):?>
	<?php $datetime = new DateTime($info->start_datetime) ?>
		<?php if(!empty($info->url)):?>
			<li><a class='info_link' href='<?php echo site_url($info->url) ?>'><?php echo $datetime->format('Y年m月d日') ?>&nbsp;&nbsp;<?php echo $info->title ?></a></li>
		<?php else:?>
			<li><a class='info_link' href='<?php echo base_url("index/information/{$info->id}") ?>'><?php echo $datetime->format('Y年m月d日') ?>&nbsp;&nbsp;<?php echo $info->title ?></a></li>
		<?php endif;?>
			<?php endforeach;?>
		</ul>
</div>
<?php endif;?>
