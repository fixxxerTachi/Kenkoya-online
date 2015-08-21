<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/Classes/PHPExcel.php';
include __DIR__.'/../libraries/Classes/PHPExcel/IOFactory.php';
class Test_excel extends CI_Controller {
	public $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
	}
	
	public function index()
	{
		//テンプレートの読み込み
		//$file = __DIR__.'/../third_party/test.xls';
		$file = $this->config->item('excel_template');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$book = $reader->load($file);
		
		//シートの設定
		$book->setActiveSheetIndex(0);
		$sheet = $book->getActiveSheet();
		$sheet->setTitle('月');
		
		//セルを指定して書き込み
		$sheet->setCellValue('A1','test_A1');
		$sheet->setCellValueByColumnAndRow(1,1,'test_B1');
		
		/*** 出力処理 ***/
		$output = '発注明細書.xls';
		//$writer = PHPExcel_IOFactory::createWriter($book,'Excel5');
		//$writer->save($output);
		
		header('Content-Type: application/vnd.ms-excel');
		ob_end_clean();
		header("Content-Disposition: attachment;filename={$output}");
		header("Cache-Control: max-age=0");
		$writer = PHPExcel_IOFactory::createWriter($book,"Excel5");
		$writer->save('php://output');
	}
}
