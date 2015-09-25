<div id='side'>
<!--- start:recommend --->
<?php if(!empty($categories)):?>
	<div class='side_inner'>
		<div class='side_title_no_main'><span class='logo_pink'>FAQ</span> よくあるご質問・カテゴリ</div>
		<table class='list_inner'>
	<?php foreach($categories as $item):?>
			<tr>
				<th><span class='logo'><?php echo $item->short_name ?></span></th>
				<td><a href='<?php echo site_url("question/index/{$item->id}") ?>'><?php echo $item->name ?></a></td>
			</tr>
	<?php endforeach;?>
		</table>
	</div>
<?php endif;?>
<!--- end:recommend -->
</div>
