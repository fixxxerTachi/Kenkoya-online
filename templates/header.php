<div id='header' class='clearfix'>
	<h1><a href='<?php echo site_url() ?>'><img src='<?php echo base_url() ?>images/kenkoya_logo.jpg' alt='宅配スーパー健康屋' width='129' height='56'></a></h1>
	<?php if(empty($user)):?>
	<p class='login'><a class='edit' href='<?php echo base_url('admin_admin/login')?>'>ログイン</a></p>
	<?php else: ?>
	<p class='login'><a class='edit' href='<?php echo base_url('admin_admin/logout') ?>'>ログアウト</a></p>
	<?php endif; ?>
	<p class='login'><a class='edit' href='https://docs.google.com/forms/d/1e0LL5ZNDF1Iu0jv5eQDqC7dXoMK7vxi1H7oKTTQZZH0/viewform?usp=send_form' target='blank'>要望フォーム</a></p>
</div>
<div id='nav'>
	<div id='nav-inner'>
		<ul>
			<li <?php if($current == 'admin_order') echo "class=current" ?>><a href="<?php echo base_url('/admin_order/') ?>">受注管理</a></li>
			<li <?php if($current == 'admin_customer') echo "class=current" ?>><a href="<?php echo base_url('/admin_customer/') ?>">会員管理</a></li>
			<li <?php if($current == 'admin_contents') echo "class=current" ?>><a href="<?php echo base_url('/admin_contents/') ?>">コンテンツ管理</a></li>
			<li <?php if($current == 'admin_advertise') echo "class=current" ?>><a href="<?php echo base_url('/admin_advertise/') ?>">広告管理</a></li>
			<li <?php if($current == 'admin_product') echo "class=current" ?>>
				<a href="<?php echo base_url('/admin_product/') ?>">商品管理</a>
			</li>
			<li <?php if($current == 'admin_admin') echo "class=current" ?>><a href="<?php echo base_url('/admin_admin/') ?>">管理メニュー</a></li>
		</ul>
	</div>
</div>
