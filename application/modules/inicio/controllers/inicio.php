<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends MX_Controller {
			
	function inicio()
	{
		parent::__construct();
	}

	function index()
	{
		//Genera metatags
        $uno = $this->uri->segment(1);
        $op['op'] = $this->data_model->cargarOptimizacion($uno);
		
        $op['menu'] = $this->data_model->cargarMenuHeader();
        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
        
		//Vista//
		$this->load->view('inicio-view', $op);
	}
	
}


