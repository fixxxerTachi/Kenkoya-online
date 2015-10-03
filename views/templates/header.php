      <header class="demo-header mdl-layout__header mdl-color--white mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_order/') ?>">受注管理</a>
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_customer/') ?>">会員管理</a>
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_contents/') ?>">コンテンツ管理</a>
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_advertise/') ?>">広告管理</a>
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_product/') ?>">商品管理</a>
			<a class='mdl-navigation__link' href="<?php echo base_url('/admin_admin/') ?>">管理メニュー</a>
			<div class="mdl-layout-spacer"></div>
	<?php if(empty($user)):?>
			<a class="mdl-navigation__link" href='<?php echo base_url('admin_admin/login')?>'>ログイン</a>
	<?php else: ?>
			<a class="mdl-navigation__link" href='<?php echo base_url('admin_admin/logout') ?>'>ログアウト</a>
	<?php endif; ?>
			<a class="mdl-navigation__link" href='https://docs.google.com/forms/d/1e0LL5ZNDF1Iu0jv5eQDqC7dXoMK7vxi1H7oKTTQZZH0/viewform?usp=send_form' target='blank'>要望フォーム</a>
        </div>
      </header>
      <div class="mdl-layout__drawer">
		  <header class="demo-drawer-header">
			  <h1><a href='<?php echo site_url() ?>'><img src="<?php echo base_url("images/kenkoya_logo.jpg")?>" alt='宅配スーパー健康屋' width='198' height='80' class='demo-avatar' target='_blank'></a></h1>
		  </header>
		  <nav class="mdl-navigation">
			<?php if(!empty($side)):?>
			<?php foreach($side as $key => $value):?>
				<?php if($key == 'admin'):?>
				<?php foreach($value as $k => $v):?>
				
					<a class="mdl-navigation__link <?php if(strpos($k,$this->router->method)) echo 'cjurrent' ?>" href="<?php echo str_replace('?','',$k) ?>"><?php echo $v ?></a>
				<?php endforeach; ?>
				<?php else: ?>
				
					<a class="mdl-navigation__link <?php if(strpos($key,$this->router->method)) echo 'current' ?>" href="<?php echo str_replace('?','',$key) ?>"><?php echo $value ?></a>
				<?php endif;?>
			<?php endforeach; ?>
			<?php endif;?>
			</nav>
       </div>