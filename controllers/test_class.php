<?php
include __DIR__.'/../libraries/define.php';
class Test_class extends CI_Controller {
	public function test_myclass()
	{
		$this->load->library('my_class');
		$this->my_class->_test();
	}
}
