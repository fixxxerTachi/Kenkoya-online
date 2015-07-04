<?php
function getDb(){
	try{
		$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
		$user = DB_USER;
		$password = DB_PASSWORD;
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		); 
		return $dbh = new PDO($dsn, $user, $password, $options);
	}catch(Exception $e){
		$e->getMessage();
	}
}

function convertCsvToDb($filename){	
	$file = $filename;
	$data = file_get_contents($file);
	$data = mb_convert_encoding($data,'UTF-8','sjis-win');
	$temp = tmpfile();
	fwrite($temp,$data);
	rewind($temp);
	while(($data = fgetcsv($temp,0,',')) !== FALSE){
		$csv[]=$data;
	}
	fclose($temp);
	return $csv;
}

function uploadCsv($file = 'csvfile'){
	$message = '';
	if(is_uploaded_file($_FILES[$file]['tmp_name'])){
		if(move_uploaded_file($_FILES[$file]['tmp_name'], 'uploaded_csv/' . $_FILES[$file]['name'])){
			$message =  'ファイルをアップロードしました';
			//var_dump($_FILES);
		}else{
			throw new Exception('ファイルをアップロードできませんでした');
		}
	}else{
		throw new Exception('ファイルが選択されていません');
	}
	return array(
		'uploaded_file_name' => 'uploaded_csv/' . $_FILES[$file]['name'],
		'message' => $message,
	);
}

function uploadImageAdd($product_obj,$data=array(),$posts_allergen=false,$on_sale,$image_description)
{
	if(is_uploaded_file($_FILES['image']['tmp_name'])){
		if(move_uploaded_file($_FILES['image']['tmp_name'],'images/products/' . $_FILES['image']['name'])){
			$result=$product_obj->save($data);
			$product_id = $product_obj->last_insert_id();
			$data=array(
				'product_id'=>$product_id,
				'image_name'=>$_FILES['image']['name'],
				'image_description'=>$image_description,
				'on_sale' => $on_sale,
				'create_date' => date('Y-m-d H:i:s'),
			);
			$result = $product_obj->save_image($data);
			$product_obj->save_allergen($posts_allergen,$product_id);
		}else{
			unlink($_FILES['image']['tmp_name']);
			throw new Exception('ファイルのアップロードに失敗しました');
		}
	}else{
		unlink($_FILES['image']['tmp_name']);
		throw new Exception('ファイルのアップロードに失敗しました');
	}	

}
