<?php
class Master_quantity{
	public $quantity;
	public function __construct()
	{
		$this->quantity = array();
		for($i=1; $i <= 10; $i++){
			$this->quantity[$i] = $i;
		}
	}
	
}