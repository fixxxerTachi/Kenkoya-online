<?php
class My_exception{
	public $ci;
	public function __construct()
	{
		$this->ci = get_instance();
	}
	
	public function log_info()
	{
		return $this->ci->router->class.'/'.$this->ci->router->method . '/';
	}
}