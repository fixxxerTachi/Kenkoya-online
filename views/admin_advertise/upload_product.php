<?php include __DIR__ . '/../templates/doctype.php' ?>
<head>
<?php include __DIR__ . '/../templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
	<?php include __DIR__ . '/../templates/header.php' ?>
		<main class="mdl-layout__content mdl-color--grey-100">
			<div class="mdl-grid demo-content">
				<div class='container'>
					<h2><span class='logo_pink'>advertise</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<div class='container'>
						<?php echo form_open_multipart() ?>
						<dl class='csv_menu'>
							<dd><input type='checkbox' name='trancate' id='trancate'>
								<label for='trancate'>既存のデータを破棄して新たにデータを作成します。既存のデータに追加する場合チェックをはずしてください</label>
							</dd>
							<dd>csvファイルの項目</dd>
							<dt>A0:注文番号,B1:(商品コード),C2:メーカー,D3:商品名,E4:規格,F5:原価,G6:売価,H7:利益,I8:利益率,J9:賞味期限,K10:添加物,L11:アレルゲン,M12:カロリー,N13:備考,O14:備考,P15:備考,Q16:イメージグループ,R17:x座標,S18:Y座標,T19:width,U20:height,V21:掲載ページ,W22:カテゴリマスタ番号</dt>
							<dd><input type='file' name='csvfile'></dd>
							<dt></dt>
							<dd><input type='submit' value='登録' class='input' style='margin-right: 30px'><a class='edit_back' href='<?php echo base_url('admin_advertise/add_advertise') ?>'>戻る</a></dd>
						</dl>
						</form>
						<?php if(isset($upload_message)):?>
						<p class='success'><?php echo $upload_message; ?></p>
						<?php endif; ?>	
						<?php if(isset($db_message)):?>
						<p class='success'><?php echo $db_message; ?></p>
						<?php endif; ?>
						<?php if(isset($error_message)):?>
						<p class='error'><?php echo $error_message ?></p>
						<?php endif; ?>
						<?php if(!empty($success_message)):?>
						<p class='success'><?php echo $success_message ?></p>
						<?php endif;?>
					</div>
				</div>
			</div>
		</main>
	</div>
</body>
</html>