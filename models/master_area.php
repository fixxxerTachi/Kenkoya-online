<?php
class Master_area{
	public $list_area;
	public $list_area_id;
	public $list_select;
	
	public function __construct()
	{
		$this->list_area = array(
			''=>'エリアを選択してください',
			'0'=>'未登録',
			'172'=>'金沢地区',
			'171'=>'能登地区',
		);
		
		$this->list_area_id = array(
			'2'=>'金沢地区',
			'1'=>'能登地区',
		);
		
		$this->list_select = array(
			''=>'全て',
			'0'=>'配達エリア外',
			'172'=>'金沢地区',
			'171'=>'能登地区',
		);
		
	}
	
}