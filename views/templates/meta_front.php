	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="robots" content="noarchive">
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<title>宅配スーパー健康屋 | <?php echo $title ?></title>
	<link rel='stylesheet' href='<?php echo base_url('css/main.css') ?>'>
	<script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery-1.11.0.min.js') ?>"></script>
	<link rel="shortcut icon" href="<?php echo base_url('images/icon/favicon.ico') ?>" >
	<script>
	$(function(){
		$("#header-menu a img, #nav-inner a img").each(function(){
			$('<img>').attr('src',$(this).attr('src').replace('_on','_off'));
		});
		$('#header-menu a img,  #nav-inner a img').bind('mouseenter',function(){
			$(this).attr('src',$(this).attr('src').replace('_on','_off'));
		}).bind('mouseleave',function(){
			$(this).attr('src',$(this).attr('src').replace('_off','_on'));
		});
	});
	</script>
