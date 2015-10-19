<?php include __DIR__.'/templates/doctype.php' ?>
<head>
<?php include __DIR__.'/templates/meta_materialize.php' ?>
</head>
<body>
	<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
<?php include 'templates/header.php' ?>
<main>
	<div class="mdl-grid demo-content">
		<div class='container'>
			<h2><span class='logo_pink'>title</span> <?php echo $h2title ?></h2>
			<p>
				<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored"><i class="material-icons">add</i></button>
				左メニューを選択してください
			</p>
		</div>
	</div>
</main>
</div>
</body>
</html>