<?php foreach($result as $row):?>
	<div style="position:absolute;top:<?php echo $row->page_y?>px;left:<?php echo $row->page_x?>px">
		<a href='#' onclick='return false';><img src='<?php echo base_url(AD_PRODUCT_IMAGE_PATH . $row->image_name)?>' id='<?php echo $row->id ?>' target='<?php echo $row->advertise_id ?>'></a>
	</div>
<?php endforeach ?>
<script>
$('.ad-image-wrapper a img').on('click',function(){
	$('#show_item').fadeOut('slow');
	var id = $(this).attr('id');
	var src = $(this).attr('src');
	var arr = src.split('/');
	var target = $(this).attr('target');
	var image_name = arr.pop();
//	console.log(image_name);
//	console.log(target);
	var href = '<?php echo base_url("front_advertise/show_item")?>' + '/' + image_name + '/' + target; 
	$('#show_item').load(href);
	$('#cover').css({
		'width':$(document.body).width(),
		'height':$(document.body).height()
	}).fadeIn('slow');
	$('#show_item').css({
		'position':'absolute',
		'top':$(window).scrollTop() + 60 + 'px',
		'left':Math.floor(($(window).width() - 520)/2) + 'px'
	})
	.appendTo('#cover')
	.fadeIn('slow');
});
</script>
