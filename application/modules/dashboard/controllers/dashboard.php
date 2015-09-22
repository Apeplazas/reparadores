<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MX_Controller {
	
	function dashboard(){
		
		parent::__construct();
		$this->data_model->is_logged_in();
		$this->load->model('dashboard_model');
		
	}
	
	function index(){
		
		//Genera metatags
        $url		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
		
		$usuario	= $this->session->userdata('usuario');		
			
		$op['usuarioDatos']		= $this->dashboard_model->cargarDatosUsuario($usuario['usuarioID'],$usuario['tipoUsuario']);
		$op['usuarioPerfil']	= $usuario;
	
		//Vista//
		$this->layouts->index('dashboard-vista', $op);
		
	}
	
}