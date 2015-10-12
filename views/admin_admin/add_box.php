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
					<h2><span class='logo_pink'>box</span> <?php echo $h2title ?></h2>
					<?php if(!empty($message)):?>
					<p class='message'><?php echo $message ?></p>
					<?php endif;?>
					<?php if(!empty($success_message)):?>
					<p class='success'><?php echo $success_message; ?></p>
					<?php endif; ?>
					<?php if(!empty($error_message)):?>
					<p class='error'><?php echo $error_message ?></p>
					<?php endif; ?>
					<table class='list'>
						<tr><th>ID</th><th>温度帯</th><th>表示名</th><th>サイズ</th><th></th><th></th></tr>
					<?php foreach($list as $item):?>
						<tr>
							<td><?php echo $item->id ?></td>
							<td><?php echo $zones[$item->temp_zone_id] ?></td>
							<td><?php echo $item->name ?></td>
							<td><?php echo $item->height ?>×<?php echo $item->width ?>×<?php echo $item->depth ?></td>
							<td><a class='edit' href='<?php echo base_url("admin_admin/edit_box/{$item->id}") ?>'>更新</a></td>
							<td><a class='edit' onclick ='del_confirm("<?php echo $item->name ?>","<?php echo $item->id ?>")'>削除</a></td>
						</tr>
					<?php endforeach;?>
					</table>
				</div>
				<div class='container'>
					<?php echo form_open() ?>
						<table class='detail' cellpadding='0' cellspacing='10'>
							<tr>
								<th><label for='temp_zone_id'>温度帯</label>
								<td><?php echo form_dropdown('temp_zone_id',$zones,$form_data->temp_zone_id) ?></td>
							</tr>
							<tr>
								<th><label for='name'>表示名</label></th>
								<td><input type='text' name='name' id='name' value='<?php echo $form_data->name ?>' size='20'></td>
							</tr>
							<tr>
								<th><label for='size'>箱サイズ</label></th>
								<td>
									幅<input type='text' name='width' id='width' value='<?php echo $form_data->width ?>' size='5' maxlength='5'>mm
									高さ<input type='text' name='height' id='height' value='<?php echo $form_data->height ?>' size='5' maxlength='5'>mm
									奥行<input type='text' name='depth' id='depth' value='<?php echo $form_data->depth ?>' size='8' maxlength='8'>mm
									= 体積<input type='text' name='volume' id='volume' value='<?php echo $form_data->volume ?>' size='6' maxlength='6'>㎣
									3辺合計<input type='text' name='total' id='total'>mm
								</td>
							</tr>
							<tr>
								<th><label for='weight'>重量</label></th>
								<td><input type='text' name='weight' id='weight' value='<?php echo $form_data->weight ?>' size='10' maxlength='10'>g 注）箱サイズに合った重量を入れてください ex)5000 or 10000 or 15000
								</td>
							</tr>
							<tr>
								<th><label for='note'>説明</label></th>
								<td><input type='text' name='note' id='note' value='<?php echo $form_data->note ?>' size='60' maxlength='60'>
							</tr>
							<tr>
								<th class='no-border'></th>
			<?php if(isset($edit_flag)):?>
								<td><input type='submit' name='edit_submit' value='更新する' class='submit_button'><a class='edit_back' href='<?php echo site_url('admin_admin/add_box') ?>'>戻る</a></td>
			<?php else:?>
								<td><input type='submit' name='submit' value='登録する' class='submit_button'><a class='edit_back' href='<?php echo site_url('admin_admin/add_box') ?>'>戻る</a></td>
			<?php endif;?>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</main>
	</div>
<script>
var calc = function(){
	var height = document.getElementById('height').value;
	var width = document.getElementById('width').value;
	var depth = document.getElementById('depth').value;
	var volume = Number(height) * Number(width) * Number(depth);
	var total = Number(height) + Number(width) + Number(depth);
	document.getElementById('volume').value = volume;
	document.getElementById('total').value = total;
};
document.getElementById('height').onchange = function(){
	calc()
};
document.getElementById('width').onchange = function(){
	calc()
};

document.getElementById('depth').onchange = function(){
	calc()
};
calc();
</script>
<script>
function del_confirm(template_name , id){
	var template_name = template_name;
	var id = id;
	if(window.confirm(template_name + '削除してもよろしいですか')){
		location.href='<?php echo site_url("/admin_admin/delete_box") ?>' + '/' + id;
		return false;
	}
}
</script>
</body>
</html>
