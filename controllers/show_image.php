<?php
class show_image extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function display()
	{
		header('Content-type: image/jpeg');
		
		$product_code = $this->uri->segment(3);
		$width = 150;
		$height = 150;
		$url = base_url("images/products/ak{$product_code}.jpg");
		list($image_x,$image_y) = getimagesize($url);
		$canvas = imagecreatetruecolor($width,$height);
		$image = imagecreatefromjpeg($url);
		imagecopyresampled(
			$canvas,
			$image,
			0,
			0,
			0,
			0,
			$width,
			$height,
			$image_x,
			$image_y
		);
		imagejpeg($canvas,null,100);
		
		/*
		$prduct_code = $this->uri->segment(3);
		$url = base_url("images/products/ak{$product_code}.jpg");
		readfile($url);
		*/
	}
}