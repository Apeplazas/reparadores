<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends MX_Controller {
	
	function configuracion()
	{
		parent::__construct();
	}

	function index(){
		
		$this->data_model->is_logged_in();
		
		//Optimizacion y conexion de tags para SEO//
		$segmentoUno		= $this->uri->segment(1);
		$op['opt']    		= $this->data_model->cargarOptimizacion($segmentoUno);
		
		//Datos de usuario
		$usuario		= $this->session->userdata('usuario');
		
		 //Carga el javascript para jquery//
		$this->layouts->add_include('assets/css/jquery.tagit.css')
					  ->add_include('assets/css/tagit.ui-zendesk.css')
					  ->add_include('assets/js/tag-it.min.js')
					  ->add_include('assets/css/croppic.css')
					  ->add_include('assets/js/croppic.min.js')
					  ->add_include('assets/js/masonry.pkgd.min.js');
					  
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['tags'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		if($usuario['usuarioAlias']){
			
			$op['cat'] 				= $this->data_model-> cargarConocimientosUsuarioCatalogo($usuario['usuarioID']);
			$op['usurioTags']		= $this->usuario_model->cargarUsuarioTags($usuario['usuarioID']);
			$op['conocimientos'] 	= $this->usuario_model->cargarConocimientos($usuario['usuarioID']);
				
			$op['perfil'] = $this->usuario_model->buscaPerfilID($usuario['usuarioID']);
			
			$this->layouts->index('configuracion-vista', $op);
			
		}
		
		else
		{
			echo 'tu no existes';
		}
	}
	
	
}


