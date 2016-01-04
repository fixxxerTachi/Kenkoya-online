<?php
function show_product_image($product_image = null,$image_name = null)
{
	if(!empty($product_image)){
		return "images/products/{$product_image}";
	}elseif(!empty($image_name)){
		return "images/ad_products/{$image_name}";
	}else{
		return "images/no-image.jpg";
	}
}


function show_image($product_code,$size = null){
	$path = '';
	if(empty($size)){
		$path = "show_image/display/{$product_code}";
	}else{
		$path = 'images/products/' . 'ak' . $product_code . '.jpg';
	}
	return $path;
/*
	if(file_exists($path)){
		return $path;
	}else{
		return "images/no-image.jpg";
	}
*/
}

/***　日付をフォーマットして返す
* @param object $datetime
* @return string 
*/

function format_date($datetime,$no_date_message=''){
	$str_date = '';
	if($datetime < '1900-00-00 00:00:00')
	{
		$str_date = $no_date_message;
	}else{
		$datetime = new DateTime($datetime);
		$str_date = $datetime->format('Y/m/d');
	}
	return $str_date;
}

/*** テキストを置換する ***/
function sub_str(array $s,array $d,$target)
{
	return str_replace($s,$d,$target);
}