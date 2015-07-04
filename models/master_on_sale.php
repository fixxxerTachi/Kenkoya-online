<?php
class Master_on_sale{
	public $on_sale;
	public function __construct()
	{
		$this->on_sale = array(
			'1'=>'販売中',
			'0'=>'販売中止',
		);
	}
	
}