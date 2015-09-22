<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Solicita_tu_reparacion extends MX_Controller
{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('solicita_tu_reparacion_model');
		$this->load->model('registro/registro_model');
		$this->load->model('usuarios/usuario_model');	
	}
	
	function index(){
	
		//Optimizacion y conexion de tags para SEO//
		$segmentoUno		= $this->uri->segment(1);
		$op['opt']    		= $this->data_model->cargarOptimizacion($segmentoUno);
		
		//Datos de usuario
		$usuario		= $this->session->userdata('usuario');
		
		//Carga las categorias
		$op['cat'] 		= $this->data_model->cargarConocimientos();
		
		 //Carga el javascript para jquery//
		$this->layouts->add_include('assets/css/jquery.tagit.css')
					  ->add_include('assets/css/tagit.ui-zendesk.css')
					  ->add_include('assets/js/tag-it.min.js');
					  
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['tags'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$this->load->view('solicitud-view', $op);
		
	}
	
	function guardaSolicitud(){
		
		$categoria 		= $this->input->post('catSel');
		$subcategoria   = $this->input->post('subCatSel');
		$comentario    	= $this->input->post('comentario');
		$nombre 		= $this->input->post('usuarioNombre');
		$email      	= $this->input->post('email');
		$tipoReg    	= 'usuario';

		$mail			= $this->registro_model->confirmaEmail($email);		
		
		//Obtener ip de usuario
		if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
					
			$ip = $_SERVER['HTTP_CLIENT_IP'];
					
		}elseif( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					
		}else{
					
			$ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
					
		}
				
		$ip = filter_var($ip, FILTER_VALIDATE_IP);
		$ip = ($ip === false) ? '0.0.0.0' : $ip;
		
		if(!$mail){
			
			$usuarioDatos = array(
					'nombreCompleto'		=> $nombre,
					'email'					=> $email,
					'tipoUsuario'			=> $tipoReg,
					'tipoRegistro'			=> 'web',
					'hashActivacion'		=> '',
					'ip'					=> $ip
			);
			$this->db->insert('usuarios', $usuarioDatos);
			$usuarioID = $this->db->insert_id();
			
			$this->load->library("ipinfo","fd176ec6c1e52b8835aa0526cf2e719e77130039e3ecd23ebe992171da95763a");
			$usuarioIP 	= $this->ipinfo->getIPAddress();
			$ipDatos 	= explode(';',$this->ipinfo->getCity($usuarioIP));
 
			$latDist = deg2rad($ipDatos[8]);
			$logDist = deg2rad($ipDatos[9]);
			
			$datosActualiza = array(
			
			 	'usuarioId' 		=> $usuarioID,
				'estadoNombre'		=> $ipDatos[5],
			 	'codigoPostal' 		=> $ipDatos[7],
			 	'coloniaNombre'		=> $ipDatos[6],
			 	'latitud'			=> $latDist,
			 	'longitud'			=> $logDist,
				'coordenadasGoogle' => $ipDatos[8].', '.$ipDatos[9],
			);
			$this->db->insert('estadosUsuarios', $datosActualiza);
				 
		}else{
			
			$usuarioDatos 	= $this->usuario_model->buscaPerfilID($email,"email");
			if($usuarioDatos[0]->tipoUsuario == "reparador"){
				
				$actualizaUsuario = array(
					'tipoUsuario' => 'mixto'
				);
				
				$this->db->where('usuarioId', $usuarioDatos[0]->usuarioId);
        		$this->db->update('usuarios', $actualizaUsuario);
				
			}
	
			$usuarioID 		= $usuarioDatos[0]->usuarioId;
			
		}
		
		//Generar hash para activacion
		$hashActivacion = sha1(mt_rand(10000,99999).time().$usuarioID);
		
		$activarDatos = array(
			'hashActivacion' => $hashActivacion
		);
				
		$this->db->where('usuarioId', $usuarioID);
        $this->db->update('usuarios', $activarDatos);
		
		//Generar Solicitud
		$datosMensaje = array(
				'descripcion'	=> $comentario,
				'usuarioId'		=> $usuarioID,
				'categoriaId'	=> $subcategoria
		);
		$this->db->insert('solicitudReparaciones', $datosMensaje);
		$solicitudId = $this->db->insert_id();
		
		//Insertar Archivos si existen
		if( isset($_FILES['userfile']) && !empty($_FILES['userfile']) ){
			
			$permitidos =  array('gif','png','jpg','pdf');
			
			foreach($_FILES['archivos']['name'] as $key => $val){
				
				$archivoNombre	= $val;
				$archivoTipo	= $_FILES['userfile']['type'][$key];
				$tamanoH		= $_FILES['userfile']['size'][$key];
				
				$ext = pathinfo($archivoNombre, PATHINFO_EXTENSION);			
	
				if(in_array($ext,$permitidos) ) {
					
	    			move_uploaded_file($_FILES['userfile']['tmp_name'][$key],DIRARCHIVOS.$archivoNombre);
	    			$data = array(			
						'fotografiaNombre'	=> $archivoNombre,
						'ext'				=> $ext,
						'solicitudId' 		=> $solicitudId
					);
					
					$this->db->insert('fotografias', $data);
					
				}	
				
			}
			
		}
		
		$filtros = array(
			"usuarioID"		=> $usuarioID,
			"categoria"		=> $categoria,
			"subcategoria"	=> $subcategoria,
			"solicitudId"	=> $solicitudId
		);
		
		session_start();
		$_SESSION['MostrarMen'] = true;
		$_SESSION['DatosTemporalesUsuario'] = serialize($filtros);
		
		$categoriaDatos		= $this->solicita_tu_reparacion_model->cargaCategoria($categoria);
		$subcategoriaDatos	= $this->solicita_tu_reparacion_model->cargaSubcategoria($subcategoria); 
		
		redirect($categoriaDatos[0]->url.'/'.$subcategoriaDatos[0]->url);
		
	}
		
}