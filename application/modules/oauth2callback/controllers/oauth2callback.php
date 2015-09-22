<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class oauth2callback extends MX_Controller {
			
	function oauth2callback()
	{
		parent::__construct();
	}

	function index(){
		
		$google = $this->load->library("google");
		$google->oauth2();
		redirect('compartir/enviarGoogle');
		
	}
	
}


