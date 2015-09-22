<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class avisoprivacidad extends MX_Controller {
	
	function avisoprivacidad()
	{
		parent::__construct();
		
		$this->load->model('avisoprivacidad/avisoprivacidad_model');
	}
	
	function index()
	{
		//Optimizacion y conexion de tags para SEO//
		$opt = $this->uri->segment(1);
		$op['opt'] = $this->data_model->cargarOptimizacion($opt);
		
		//validacion para identificar tipo de usuario y desglosar info
		$user				= $this->session->userdata('user');
		$op['info']			= array();
		
		if ($user['uid'] != '') {
			$tipo = 'info_'.$user['tipoUsuario'];
			$op['info']	= $this->data_model->$tipo($user['uid']);
		}
		
		//Carga el javascript para jquery//
		$this->layouts->add_include('assets/js/ytmenu.js');
		
		//Vista//
		$this->layouts->index('avisoprivacidad-view' ,$op);
	}
	
}