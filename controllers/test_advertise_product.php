<?php
header('Content-Type: text/html; charset=utf-8');
class Test_advertise_product extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->load->library(array('session','form_validation','pagination','unit_test'));
		$this->load->model('Advertise_product');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
	}
	
	public function index()
	{
		/* get_max_sale_size */
		$id_15 = 236; //=>15個
		$id_30 = 237; //=>NULL
		$noid = 99999; //存在しないID
		$size = $this->Advertise_product->get_max_sale_quantity($id_15);
		echo $this->unit->run($size,15,'get_max_sale_size');
		$size = $this->Advertise_product->get_max_sale_quantity($id_30);
		echo $this->unit->run($size,30,'get_max_sale_size');
		try
		{
			$norecord = $this->Advertise_product->get_max_sale_quantity($noid);
		}
		catch(Exception $e)
		{
			echo $this->unit->run($e->getMessage(),'対象となる商品がありません。','get_max_sale_size');
		}
		
		/* validate_sale_target */
		$id_15;//=>広告が販売対象外
		try
		{
			$result = $this->Advertise_product->validate_sale_target($id_15);
		}
		catch(Exception $e)
		{
			echo $this->unit->run($e->getMessage(),'申し訳ございません。現在【キャベツ角切り】はお取扱いしておりません。','validate_sale_target');
		}
		$target_id = 2903; //販売可能
		$result = $this->Advertise_product->validate_sale_target($target_id);
		echo $this->unit->run($result,TRUE,'validate_sale_target');
		
		/** validate_max_sale_size **/
		//数量範囲内
		$result = $this->Advertise_product->validate_max_sale_size($id_15,15);
		echo $this->unit->run($result,TRUE,'validate_max_sale_size_15');
		//数量範囲外は例外
		try
		{
			$result = $this->Advertise_product->validate_max_sale_size($id_15,16,'validate_max_size_exception');
		}
		catch(Exception $e)
		{
			echo $this->unit->run($e->getMessage(),"申し訳ございません。キャベツ角切りは15までのご注文となっております。");
		}
		//数量がNULLの場合は初期値30個
		$result = $this->Advertise_product->validate_max_sale_size($id_30,30);
		echo $this->unit->run($result,TRUE,'validate_max_size_null');
	}
}
