<!doctype html>
<html lang ='ja'>
<head>
<?php include __DIR__ . '/../templates/meta_front.php' ?>
<link rel='stylesheet' href='<?php echo base_url('css/takuhai_charge.css')?>'>
</head>
<body>
<div id='wrapper'>
<?php include __DIR__ . '/../templates/header_front_no_main.php' ?>
<?php include __DIR__.'/../templates/nav_front.php' ?>
<div id="container">
	<div id='container-inner'>
		<div class='content'>
			<h2><span class='logo_pink'>charge</span> 配達料金について</h2>
			<div class='content'>
				<h3>アイスクリームをご購入されるお客様へのお願い</h3>
				<div id='ice_notice'>
					<img src='<?php echo base_url('images/honten-ice.jpg');?>' alt='アイスをお買い上げの皆さまへの発送に関する注意事項' width='756' height='1040'>
				</div>
			</div>
			<div class='content'>
				<h3>配送について</h3>
					<div class='content-inner'>
						<div class='inner'>
							<p>【配送について】</p>
							<ul>
								<li>配送はヤマト運輸でお届けいたします。</li>
								<li>ご注文確認（前払いの場合はご入金確認）後の2～3営業日以内の発送を　心がけておりますが、ご出荷が遅れる場合、入荷にお時間をいただく商品をご注文のお客様には、別途メールでご連絡致します。</li>
								<li>時間指定も承ります。</li>
								<li>商品のお届け形態について 当店では、常温・冷蔵商品を併せてご購入された場合、セール品が含まれている場合を除き商品は冷蔵で発送させていただきます。</li>
								<li>常温・冷蔵商品を冷凍商品と同梱する、または冷蔵商品を常温形態での配送・お取り扱いは致しておりません。</li>
								<li>常温・冷蔵・冷凍に配送区分がされており、配送方法によって個別に商品を頂戴いたします。</li> 
								<li>また、送料無料の商品とそうでない商品を一緒にご購入いただきました場合でも配送形態が異なる場合は別途配送料金をいただきますのでご了承くださいませ。</li>
								<li>送料無料商品とセール品を同時にご注文頂きました場合は別途セール品に対する送料を頂戴致します。</li>
								<li>可能な限り、同一ご注文者様からのご注文は同梱をさせていただきます。</li>
							</ul>
						</div>
						<div class='inner'>
							<p>宅配便</p> 
							<p>【業者】ヤマト運輸</p>
							<p>【商品発送のタイミング】特にご指定がない場合、</p>
							<ul>
								<li> 銀行振込、⇒ご入金確認後、2～3営業日以内に発送いたします。</li>
								<li>　代金引換　⇒ご注文確認後、2～3営業日以内に発送いたします。<br> 
								　●ただし時間を指定された場合でも、渋滞状況に伴う遅延事情等により指定時間内に配達ができない事もございます。
								　　週末・祝日をはさむ際は余裕をもって日時をご指定ください。</li>
							</ul>
						</div>

						<div class='inner'>	
							<p>【商品の配送方法】</p>
							<ul>
								<li>当店では、常温・冷蔵商品を併せてご購入された場合、セール品が含まれている場合を除き商品は冷蔵で発送させていただきます。</li>
								<li>常温・冷蔵商品を冷凍商品と同梱する、または冷蔵商品を常温形態での配送・お取り扱いは致しておりません。</li>
								<li>また、送料無料の商品とそうでない商品を一緒にご購入いただきました場合でも配送形態が異なる場合は別途配送料金をいただきますのでご了承くださいませ。</li> 
							</ul>
						</div>
						
						<div class='inner'>
							<p>・商品の配送事故に関しまして 商品がお手元に届きましたら必ずチェックをお願いします。</p>
							<p>商品が潰れていた、注文したものとは異なる商品が届いたなどの問題が発生した場合は、お客様のご注文情報を必ず添えて当店までメールにてご連絡をお願いします。</p>
							<p>商品は破損した商品と交換と言う形を取らせていただきますので破損していても お客様の判断で処分しない様お願いします。</p>
						</div>

						<div class='inner'>	
							<p>【沖縄県・離島への送料無料商品配送】</p>
							<ul>
								<li>送料無料商品は送料無料商品であっても、沖縄県・離島へのお届けには別途料金が発生する場合がございます。</li>
								<li>後ほど当店より送らせていただくご注文確認メールでご確認をお願い致します。 </li>
							</ul>
						</div>
	
						<div class='inner-red'>
							<p>◎重要：【保管期間内(※1)にご連絡なく長期不在等でお受け取りになられなかったお客様へは、理由の如何を問わず、発送時に発生いたしております費用(※2)を御請求させていただきます。】</p>　　 
							<p>(※1)保管期間は商品発送日から1週間までとなります。 </p>
							<p>(※2)費用の内訳は以下のとおりです。</p> 
							<p>商品代金、往復の正規運賃（具体的な送料は商品、お届け先によって異なります。また、送料込みの商品も含みます。）、手数料（代金引換手数料・梱包材費用・棚戻手数料）の実費をご請求させていただきます。</p>
						</div>	
					</div>
			</div>
			<div class='content'>
			<h3>配送料金表</h3>
				<table class='contact_form'>
					<tr>
						<th></th>
		<?php for($i=0; $i < 11; $i++): ?>
						<th><?php echo $blocks[$i]->area_name ?></th>
		<?php endfor;?>
					</tr>
					<tr>
						<th>地域詳細</th>
		<?php for($i=1; $i <= 11; $i++):?>
						<th class='prefecture'><?php echo $Takuhai_charge->get_prefname_by_block_id($i);?></th>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[0]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(1,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[1]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(2,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[2]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(3,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[3]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(4,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[4]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(5,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[5]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(6,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[6]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(7,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[7]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(8,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[8]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(9,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
					<tr>
						<th><?php echo $classes[9]->text ?></th>
		<?php for($i=1; $i <= 11; $i++):?>
						<td><?php echo number_format($Takuhai_charge->get_charge_price_by_temp_id(10,$i)->charge);?>円</td>
		<?php endfor;?>
					</tr>
				</table>
				<div class='inner'>
					<ul>
						<li>送料分消費税: この料金には消費税が 含まれています。</li>
						<li>離島他の扱い: 離島・一部地域は追加送料がかかる場合があります。</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../templates/footer_front.php' ?>
</div>
</body>
</html>