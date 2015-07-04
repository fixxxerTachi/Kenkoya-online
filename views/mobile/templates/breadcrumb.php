<?php if(!empty($breads)):?>
<div id='bread-outer'>
	<div id='bread-inner'>
		<ul class='clearfix'>
	<?php foreach($breads as $item):?>
		<?php if($item != null):?>
			<?php if(!empty($item->link)):?>
				<li><a href='<?php echo $item->link ?>'><?php echo $item->text ?></a></li>
			<?php else:?>
				<li><?php echo $item->text ?></li>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach;?>
		</ul>
	</div>
</div>
<?php endif;?>
