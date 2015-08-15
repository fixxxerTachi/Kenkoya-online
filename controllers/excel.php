<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';
include __DIR__.'/../libraries/Classes/PHPExcel.php';
include __DIR__.'/../libraries/Classes/PHPExcel/IOFactory.php';

class Excel extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$excel = new PHPExcel();
		// シートの設定
		$excel->setActiveSheetIndex(0);
		$sheet = $excel->getActiveSheet();
		$sheet->setTitle('sheet name');
		// セルに値を入れる
		$sheet->setCellValue('A1', 'hoge');
		// Excel2007 形式で出力
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer->save("output.xlsx");
	}
}