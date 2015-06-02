<div id='nav-outer'>
	<div id='nav'>
		<div id='nav-inner'>
			<ul class='clearfix'>
				<li><img src='<?php echo base_url('images/menu/for_first_customer.jpg')?>'></li>
				<!--
				<li><a href='<?php echo base_url('index/buy_flow')?>'><img src='<?php echo base_url('images/menu/buyflow_on.jpg')?>'></a></li>
				<li><a href='<?php echo base_url('front_area/search_area')?>'><img src='<?php echo base_url('images/menu/delivery_area_on.jpg')?>'></a></li>
				<li><a href='<?php echo base_url('front_area/check_area/nav')?>'><img src='<?php echo base_url('images/menu/register_member_on.jpg')?>'></a></li>
				-->
				<li class='logout'><a href='<?php echo base_url('index/guidance')?>'>ご利用案内</a></li>
				<li class='logout'><a href='<?php echo base_url('takuhai_service')?>'>宅配サービスについて</a></li>
				<li class='logout'><a href='<?php echo base_url('front_area/check_area/nav')?>'>会員登録</a></li>				
<?php if(empty($customer) || $customer->username == 'no-member'): ?>
				<li><img src='<?php echo base_url('images/menu/for_member.gif')?>'></li>
				<!--
				<li><a href='<?php echo base_url('front_customer/login_action/user_view/')?>'><img src='<?php echo base_url('images/menu/login_on.jpg')?>' alt='ログイン'></a></li>
				<li><a href='<?php echo base_url('mypage') ?>'><img src='<?php echo base_url('images/menu/mypage_on.jpg')?>'></a></li>
				-->
				<li class='logout'><a href='<?php echo base_url('front_customer/login_action/user_view/')?>'>ログイン</a></li>
				<li class='logout'><a href='<?php echo base_url('mypage') ?>'>マイページ</a></li>
<?php elseif(!empty($customer)):?>
				<li id='customer_name'>ようこそ！<br><span><?php echo $customer->name ?> <span>さん</li>
				<li class='logout'><a href='<?php echo base_url('front_customer/logout_action/')?>'>ログアウト</a></li>
				<li class='logout'><a href='<?php echo base_url('mypage') ?>'>マイページ</a></li>
<?php endif;?>
				<li><a href='<?php echo base_url('front_cart/show_cart') ?>'><img src='<?php echo base_url('images/menu/cart.jpg')?>' width='90' height='47' alt='カートを見る'></a></li>
				<div id='cart_count'><?php echo $cart_count ?></div>
		</div>
	</div>
</div>
