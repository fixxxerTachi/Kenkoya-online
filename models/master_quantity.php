<?php
class Master_quantity{
	public $quantity;
	public function __construct()
	{
		$this->quantity = array();
		for($i=1; $i <= MAX_SALE_QUANTITY; $i++){
			$this->quantity[$i] = $i;
		}
	}
	
}