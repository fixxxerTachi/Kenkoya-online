<?php
class Image_resize{
	public function resize($path,$product_code,$rate)
	{
		$original = imagecreatefromjpeg($path . '/ak' . $product_code . '.jpg');
		$x = iamgex($original);
		$y = imagey($original);
		$resize = imagecreatetruecolor(
	}
}