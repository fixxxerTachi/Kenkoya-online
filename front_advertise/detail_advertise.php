<!doctype html>
<html lang = 'ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/detail_advertise.css')?>'></head>
<link rel='stylesheet' href='<?php echo base_url('ad_gallery/lib/jquery.ad-gallery.css')?>'>
<script src='<?php echo base_url('js/jquery-1.11.0.min.js') ?>'></script>
<script src='<?php echo base_url('ad_gallery/lib/jquery.ad-gallery.min.js')?>'></script>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>yotsuba</span> <?php echo $h2title ?></h2>
<?php if(!empty($result)):?>
			<div id='gallery' class='ad-gallery'>
				<div class='ad-nav'>
					<div class='ad-thumbs'>
						<ul class='ad-thumb-list'>
	<?php foreach($result  as $row):?>
							<li class='gallery-image'>
								<a href='<?php echo base_url(AD_IMAGE_PATH . $row->image_name)?>' title='<?php echo $row->description ?>' target='<?php echo base_url("front_advertise/get_image_info/{$row->id}/{$row->start_page}/{$row->end_page}") ?>'><img src='<?php echo base_url(AD_IMAGE_PATH . $row->image_name) ?>' width='200' height='140' alt='<?php echo $row->title . ' ' . $row->description ?>'></a>
								<p class='caption'><?php echo nl2br($row->description) ?></p>
							</li>
	<?php endforeach;?>
						</ul>
					</div>
				<div class = 'ad-image-wrapper clearfix'></div>
				</div>
			</div>
<?php else:?>
			<p>有効なチラシはありません</p>
<?php endif;?>
		</div>
	</div>
	<div id='cover'></div>
	<div id='show_item'></div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
<script>
$(function(){
	//初期化
	$('#show_item').hide();
	$('#cover').hide();
	//adGalleryの処理
	var galleries = $('.ad-gallery').adGallery({
		width: 1024,
		height: 716,
		thumb_opacity:0.5,
		enable_keyboard_move: false
	});
	//adGallery不要な要素を隠す
	$('.ad-prev').hide();
	$('.ad-next').hide();
	
	
	//サムネイル画像をhoverした際の処理
	/*
	$('.ad-thumb-list li a').hover(
		function(){
			$(this).next('p.caption').css({'opacity':'0.3'});
		},
		function(){
			$(this).next('p.caption').css({'opacity':'0.8'})
		}
	);
	*/
	//サムネイル画像のキャプションの処理
	$('.ad-thumb-list li a.ad-active').next('p.caption').css({'opacity':'0.3'});
	console.log($('.ad-thumb-list li a.ad-active').next('p.caption'));

	
	//1ページ目表示
	var advertise_id = <?php echo $result[0]->id ?>;
	//var href = '<?php echo base_url("front_advertise/get_image_info/{$result[0]->id}/{$result[0]->start_page}/{$result[0]->end_page}") ?>';
	//get_json(href,advertise_id);

	///サムネイルをクリックした処理
	//advertise_idを取得
	//console.log(advertise_id);
	$('.ad-thumb-list a').on('click',function(){
		$('div.image_link').remove();
	//	console.log($(this).attr('target'));
		var href = $(this).attr('target');
		get_json(href,advertise_id);
	});
	//リンクボックスをクリックした処理
	$('div.ad-image-wrapper').on('click','a.image_alink',function(e){
	//	console.log($(this)[0].href);
		var href = $(this)[0].href;
		$('#show_item').load(href);
		$("#show_item").css({
			'position':'absolute',
			'top':$(window).scrollTop() + 10 + 'px',
			'left':Math.floor(($(window).width() - 520)/2) + 'px'
		});
		$('#cover').css({
			'width':$(document.body).width(),
			'height':$(document.body).height()
			//'height':$("show_item").height()
		}).fadeIn('slow');
		$("#show_item").css({
			'position':'absolute',
			'top':$(window).scrollTop() + 10 + 'px',
			'left':Math.floor(($(window).width() - 520)/2) + 'px'
		})
		.appendTo('#cover')
		.fadeIn('slow');
		var height =$(window).scrollTop() + $(document.body).height();
		$('#cover').css({
			'height':height + 'px'
		});
		//$('#footer-wrapper').css({'position':'fixed','bottom':0,'z-index':0});
	});
});

function get_json(href,advertise_id){
		var href = href;
		var advertise_id = advertise_id;
		$.getJSON(href, function(data){
	//		console.log(data);
			$.each(data,function(k,v){
	//			console.log(v);
				$('<div class="image_link">')
				.attr('id','link' + v.id.toString())
				.css({
					'position':'absolute',
					'top':v.page_y + 'px',
					'left':v.page_x + 'px',
					'width':v.width + 'px',
					'height':v.height + 'px',
					'z-index':'10',
				})
				.appendTo('.ad-image-wrapper');
				
				$('<a class="image_alink" onclick="return false">')
				.attr('href','<?php echo base_url('front_advertise/show_item') ?>' + '/' + v.image_group + '/' + advertise_id)
				.attr('id','alink' + v.id.toString())
				.css({
					'display':'block',
					'width': v.width + 'px',
					'height': v.height + 'px'
				})
				.appendTo('#link' + v.id.toString());
			});
		});
}
</script>
</html>