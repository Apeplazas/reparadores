<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {
	
	function test()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('test-view');
	}
	
}


