<div id = 'header-wrapper'>
	<div id='header-inner-wrapper'>
		<div id='header' class='clearfix'>
			<div id='logo'>
				<h1><a href='/'><img src='<?php echo base_url('images/kenkoya_logo.jpg') ?>' width='124' height='55' alt='宅配スーパー健康屋'></a></h1>
			</div>
			<div id='header-menu'>
				<ul>
					<li><span class='logo_green_middle'>ログイン</span></li>
					<li><a href='<?php echo base_url('front_cart/show_cart') ?>'><img src='<?php echo base_url('images/menu/cart.jpg')?>' width='90' height='47' alt='カートを見る'></a></li>
					<div id='cart_count'><?php echo $cart_count ?></div>
				</ul>
			</div>
			<!--
			<div id='header-menu'>
				<ul>
					<li><a href='<?php echo base_url('front_advertise')?>'>よつば通信を見る</a></li>
					<li><a href='<?php echo base_url('front_question') ?>'>よくあるご質問</a></li>
					<li><a href='<?php echo base_url('front_contact') ?>'>お問い合わせ</a></li>
				</ul>
			</div>
			-->
		</div>
	</div>
</div>
