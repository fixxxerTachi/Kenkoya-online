	<div id='side'>
		<ul>
			<?php if(!empty($side)):?>
			<?php foreach($side as $key => $value):?>
				<?php if($key == 'admin'):?>
				<ul class='under_side'>
				<?php foreach($value as $k => $v):?>
				<li <?php if(strpos($k,$this->router->method)) echo 'class=current_side' ?>>
					<a href="<?php echo str_replace('?','',$k) ?>"><?php echo $v ?></a>
				</li>
				<?php endforeach; ?>
				</ul>
				<?php else: ?>
				<li <?php if(strpos($key,$this->router->method)) echo 'class=current_side' ?>>
					<a href="<?php echo str_replace('?','',$key) ?>"><?php echo $value ?></a>
				</li>
				<?php endif;?>
			<?php endforeach; ?>
			<?php endif;?>
		</ul>
	</div>
