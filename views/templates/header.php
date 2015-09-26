<header>
	<nav class='top-nav'>
		<div class='container'>
			<div class='nav-wrapper'>
				<div class='nav'>
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
					<ul id="nav-mobile" class="right hide-on-med-and-down">
				<?php if(empty($user)):?>
						<li><a href='<?php echo base_url('admin_admin/login')?>'>ログイン</a></li>
						<?php else: ?>
						<li><a href='<?php echo base_url('admin_admin/logout') ?>'>ログアウト</a></li>
						<?php endif; ?>
						<li><a href='https://docs.google.com/forms/d/1e0LL5ZNDF1Iu0jv5eQDqC7dXoMK7vxi1H7oKTTQZZH0/viewform?usp=send_form' target='blank'>要望フォーム</a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<ul id='nav-mobile' class='side-nav fixed' style='width:230px; left; -250px'>
			<h1><li class='logo'><a target='blank' href='<?php echo site_url() ?>'><img src='<?php echo base_url() ?>images/kenkoya_logo.jpg' alt='宅配スーパー健康屋' width='198' height='80'></a></li></h1>
		<?php if(!empty($side)):?>
		<?php //asort($side);?>
		<?php foreach($side as $key => $value):?>
			<?php if($key == 'admin'):?>
			<?php foreach($value as $k => $v):?>
			<li class='bold <?php if(strpos($k,$this->router->method)) echo ' current_side';?>'>
				<a href="<?php echo str_replace('?','',$k) ?>"><?php echo $v ?></a>
			</li>
			<?php endforeach; ?>
			<?php else: ?>
			<li class='bold <?php if(strpos($key,$this->router->method)) echo ' current_side';?>'>
				<a href="<?php echo str_replace('?','',$key) ?>"><?php echo $value ?></a>
			</li>
			<?php endif;?>
		<?php endforeach; ?>
		<?php endif;?>	
	</ul>
</header>
