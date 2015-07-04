<div class='information'>
	<h2><span class='logo_pink_large'>FAQ</span> よくあるご質問(FAQ)</h2>
<?php if(!empty($questions)):?>
<?php foreach($questions as $info):?>
	<ul class = 'inner-information'>
		<li><span class='logo_q'>Q</span> <?php echo $info->question ?></li>
		<li><span class='logo_a'>A</span> <?php echo nl2br($info->answer) ?></li>
		<p class='qa_logo'><?php echo $info->short_name ?></p>
	</ul>
<?php endforeach;?>
<?php else:?>
	<p>ご質問はございません</p>
<?php endif;?>
</div>
