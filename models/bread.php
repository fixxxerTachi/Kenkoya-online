<?php
class Bread{
	public $link;
	public $text;
	public $home;
	
	public function __construct()
	{
		$this->home = new StdClass();
		$this->home->link = base_url();
		$this->home->text = 'home';
	}
	
	public function create_bread($obj1=null,$obj2=null,$obj3=null,$obj4=null){
		$arr = array();
		$arr[] = $obj1->home;
		$arr[] = $obj1;
		$arr[] = $obj2;
		$arr[] = $obj3;
		$arr[] = $obj4;
		return $arr;
	}
}