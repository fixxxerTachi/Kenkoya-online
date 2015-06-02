<div id='order-outer'>
	<div id='order-inner'>
		<ul class='clearfix'>
				<li <?php if(!empty($flow_login)) echo "class='current'" ?>>ログイン・配送先情報入力</li>
				<li <?php if(!empty($flow_info)) echo "class='current'" ?>>お支払・配達情報入力</li>
				<li <?php if(!empty($flow_confirm)) echo "class='current'" ?>>ご注文の確認</li>
				<li class='last <?php if(!empty($flow_complete)) echo 'current' ?>'>ご注文完了</li>
		</ul>
	</div>
</div>
