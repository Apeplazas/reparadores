<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablero extends MX_Controller {
			
	function tablero()
	{
		parent::__construct();
		$this->load->model('tablero_model');
	}

	function index()
	{
		//Genera Consulta reparadores
        $op['cuentaRep'] = $this->tablero_model->cuentaUsuarios('reparador');
        $op['cuentaUsu'] = $this->tablero_model->cuentaUsuarios('usuario');
        
        //Calcula usuarios por estados
        $op['estados'] = $this->tablero_model->muestraEstados();
        //Calcula en una sola consulta todos los usuarios y reparadores
        $op['consulta'] = $this->tablero_model->calculaUsuariosReparadores();
        
        
		//Vista//
		$this->layouts->tablero('tablero-view', $op);
	}
	
	function porfecha($var)
	{
		//Genera Consulta reparadores
        $op['cuentaRep'] = $this->tablero_model->cuentaUsuarios('reparador');
        $op['cuentaUsu'] = $this->tablero_model->cuentaUsuarios('usuario');
        
        //Calcula usuarios por estados
        $op['estados'] = $this->tablero_model->muestraEstados();
        //Calcula en una sola consulta todos los usuarios y reparadores
        $op['consulta'] = $this->tablero_model->calculaUsuariosReparadores();
        
        
		//Vista//
		$this->layouts->tablero('tableroFecha-view', $op);
	}
	
	
}


