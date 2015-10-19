<div id = 'header-wrapper'>
	<div id='header-inner-wrapper'>
		<div id='header' class='clearfix'>
			<div id='logo'><a href='/'><img src='<?php echo base_url('images/kenkoya_logo.jpg') ?>' width='248' height='101' alt='宅配スーパー健康屋'></a></div>
			<div id='header-menu' class='clearfix'>
				<ul>
				<!--
					<li><a href='<?php echo site_url('index') ?>'><img src='<?php echo base_url('images/menu/kenkoya_on.jpg') ?>' alt='健康屋宅配サイトとは'></a></li>
					<li><a href='<?php echo site_url('advertise')?>'><img src='<?php echo base_url('images/menu/advertise_on.jpg') ?>' alt='チラシを見る'></a></li>
					<li><a href='<?php echo site_url('question') ?>'><img src='<?php echo base_url('images/menu/qa_on.jpg') ?>' alt='よくあるご質問'></a></li>
					<li><a href='<?php echo site_url('contact') ?>'><img src='<?php echo base_url('images/menu/question_on.jpg') ?>' alt='お問い合わせ'></a></li>
				-->
					<li><a href='<?php echo site_url('yotsuba')?>'>よつば通信を見る</a></li>
					<li><a href='<?php echo site_url('question') ?>'>よくあるご質問</a></li>
					<li><a href='<?php echo site_url('contact') ?>'>お問い合わせ</a></li>
				</ul>
			</div>
			<h1>宅配ス―パー健康屋は宅配サービスを通して笑顔を幸福をお届けします。</h1>
			<div id='tel'><img src='<?php echo base_url('images/tel.jpg')?>' alt='0120-383-333'></div>
		</div>
	</div>
	<div id="jslidernews1" class="lof-slidecontent">
		<div class="preload">
			<div></div>
		</div>
		<div class="main-slider-content">
			<div id='mainvisual'>
				<ul class="sliders-wrap-inner">
<?php foreach($mainvisual as $image):?>
					<li>
						<a href='<?php if($image->inside_url == '1'):?><?php echo base_url() ?><?php endif;?><?php echo $image->url ?>'><img src='<?php echo base_url("images/mainvisual/{$image->image_name}") ?>' width='800' height='300' alt='<?php echo $image->description ?>'></a>
					</li>
<?php endforeach;?>
				</ul>
				<div class="navigator-content">
					  <div class="navigator-wrapper">
							<ul class="navigator-wrap-inner">
<?php foreach($mainvisual as $image):?>
								<li>
									<div>
	<?php if(!empty($image->thumb_image_name)):?>
										<img src='<?php echo base_url("images/mainvisual/{$image->thumb_image_name}") ?>' alt='<?php echo $image->thumb_image_description ?>'>
	<?php else:?>
										<img src='<?php echo base_url("images/mainvisual/{$image->image_name}") ?>' alt='<?php echo $image->image_description ?>'>
	<?php endif;?>
									</div>
								</li>
<?php endforeach;?>
							</ul>
					  </div>
				 </div> 
			</div>
		</div>
	</div>
</div>