<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reparaciones extends MX_Controller{
	
	public function __construct(){
		
		parent::__construct();
		$this->data_model->guardaUrl();
		$this->load->model('reparaciones_model');
		
	}
	
	function conocimiento($conocimientoUrl){
		
		//Datos generales de la vista
        $op['opt']	= $this->data_model->cargarOptimizacion($conocimientoUrl);
        $op['menu'] = $this->data_model->cargarMenuHeader();

		$op['conocimientoUrl'] 	= $conocimientoUrl;
		$op['subcategorias'] 	= $this->data_model->cargaSubcategorias($conocimientoUrl);
		
		if($conocimientoUrl == 'lista-de-reparadores-en-mexico'){
		
			$op['reparadores']	= $this->data_model->cargarReparadoresPorConsulta(" WHERE (u.tipoUsuario ='reparador' OR u.tipoUsuario ='mixto') AND eu.coordenadasGoogle IS NOT NULL GROUP BY u.usuarioId ORDER BY RAND()");
			
		}else{
		
			$op['reparadores']	= $this->data_model->cargarReparadoresPorConsulta(" WHERE c.url='$conocimientoUrl' AND (u.tipoUsuario ='reparador' OR u.tipoUsuario ='mixto') AND eu.coordenadasGoogle IS NOT NULL GROUP BY u.usuarioId ORDER BY RAND()");
			
		}
		//Datos para el formulario de busqueda
		$op['estados'] 			= $this->data_model->cargaEstados();
		$op['conocimientos'] 	= $this->data_model->cargarConocimientos();
		$op['subcategoriaUrl']	= null;
		
		$this->layouts->index('conocimiento-vista', $op);
		
	}
	
	function subcategoria(){
		
		$url				= $this->uri->segment(1);
		$subcategoriaUrl 	= $this->uri->segment(2);
		$estado 			= urldecode($this->uri->segment(3));

		$user 				= $this->session->userdata('usuario');

		//Datos generales de la vista
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();

		$usuarioCordenadas = $this->data_model->cargaCordenadasUsuario($user['usuarioID']);

		//Si el usuario esta logeado tomar sus cordenadas
       	if(isset($user) && $user && $usuarioCordenadas){
				
			$latDist = $usuarioCordenadas[0]->latitud;
			$logDist = $usuarioCordenadas[0]->longitud; 
         		
			$coordenadasGoo = explode(",", $usuarioCordenadas[0]->coordenadasGoogle);
				
			$op['lat']	= $coordenadasGoo[0];
			$op['long'] = $coordenadasGoo[1];
				
		//tomamos cordenadas de ip
       	}else{
        		
			$userCoords	= $this->session->userdata('usuarioCoords');
        		
			if(!$userCoords){
				
				/***Geolocalizacion***/
				$this->load->library("ipinfo","fd176ec6c1e52b8835aa0526cf2e719e77130039e3ecd23ebe992171da95763a");
				$usuarioIP 	= $this->ipinfo->getIPAddress();
				$ipDatos 	= explode(';',$this->ipinfo->getCity($usuarioIP));
	 				
				//Guardar datos en sesssion
				$data['usuarioCoords'] = array(
					'lat'	=> $ipDatos[8],
					'long'  => $ipDatos[9]
				);
				$this->session->set_userdata($data);
					
				$latDist = deg2rad($ipDatos[8]);//$latDist = deg2rad("19.40403");
				$logDist = deg2rad($ipDatos[9]);//$logDist = deg2rad("-99.24183");
				
			}else{
					
				$latDist = deg2rad($userCoords['lat']);//$latDist = deg2rad("19.40403");
				$logDist = deg2rad($userCoords['long']);//$logDist = deg2rad("-99.24183");//
					
			}
				
			$op['lat']	= $latDist;//"19.40403";
			$op['long'] = $logDist;//"-99.24183";
				
        }

			$dist = 50;
			$op['reparadores']	= $this->data_model->cargaRaparadoresPorDistacias($latDist,$logDist,$dist,$subcategoriaUrl);
		//}
		
		//if(empty($op['reparadores']))
			//$op['reparadores'] = $this->data_model->cargaRaparadoresTodos();	
		
		//Datos para el formulario de busqueda
		//$op['estados'] 			= $this->data_model->cargaEstados();
		$op['conocimientos'] 	= $this->data_model->cargarConocimientos();
		$op['subcategoriaUrl']	= $subcategoriaUrl;
		$op['conocimientoUrl'] 	= $url;
		$op['subcategorias'] 	= $this->data_model->cargaSubcategorias($url);
		
		//$op['solicitudesUsuario']	= $this->data_model->cargarSolicitudesUsuario($user['usuarioID']);
		$op['usuario']				= $user;
		
		$this->layouts->index('resultados-vista', $op);		
		
	}
	
	function agregar(){
		
		//Datos generales de la vista
		$url				= $this->uri->segment(1);
        $op['opt'] 			= $this->data_model->cargarOptimizacion($url);
        $op['menu'] 		= $this->data_model->cargarMenuHeader();
		
		$usuario			= $this->session->userdata('usuario');
		$op['usuarioId']	= $usuario['usuarioID'];
		
		$this->layouts->index('agregarReparacion-vista', $op);
		
	}
	
	function guardarreparacion(){
		
		$usuarioId		= $this->input->post('usuarioId');
		$titulo			= $this->input->post('titulo');
		$descripcion	= $this->input->post('descripcion');
		
		//Generar solicitud de reparacion
		$datosReparacion = array(
				'titulo'		=> $titulo,
				'descripcion'	=> $descripcion,
				'usuarioId'		=> $usuarioId
		);
		$this->db->insert('mensajes', $datosReparacion);
		$reparacionId = $this->db->insert_id();
		
		//Genera contenido de mensajes
		$op = array(
			'respuesta'		=> $usuarioMensaje,
			'mensajeId'    	=> $converId,
			'usuarioId'		=> $usuarioIdDos,
		);
		$this->db->insert('solicitudReparaciones', $op);
		
		//Insertar Archivos si existen
		if( isset($_FILES['archivos']) && !empty($_FILES['archivos']) ){
			
			$permitidos =  array('gif','png','jpg','pdf');
			
			foreach($_FILES['archivos']['name'] as $key => $val){
				
				$archivoNombre	= $val;
				$archivoTipo	= $_FILES['archivos']['type'][$key];
				$tamanoH		= $_FILES['archivos']['size'][$key];
				
				$ext = pathinfo($archivoNombre, PATHINFO_EXTENSION);			
	
				if(in_array($ext,$permitidos) ) {
					
	    			move_uploaded_file($_FILES['archivos']['tmp_name'][$key],DIRARCHIVOS.$archivoNombre);
	    			$data = array(			
						'nombre'	=> $archivoNombre,
						'ext'		=> $ext,
						'mensajeId' => $converId
					);
					
					$this->db->insert('archivosMensajes', $data);
					
				}	
				
			}
			
		}		
		
		//Notificar al usuario de que tiene un mesaje
		$mensje = "El usuario ".$user['nombre']." te ha contactado.";
		
		//Insertar notificacion
		$notUsario = array(
				'usuarioId' 	=> $usuarioIdUno,
				'notificacion'	=> $mensje,
				'referencia'	=> 'men-'.$converId, 
				'url'			=> base_url()."usuarios/ver_mensaje/".$converId
		);
		$this->db->insert('notificaciones', $notUsario);
		
		$this->session->set_flashdata('msg','<div class="msgFlash"><img src="http://www.apeplazas.com/obras/assets/graphics/alerta.png" alt="Alerta"><strong>Se ha enviado tu mensaje exitosamente.</strong></div><br class="clear">');
		
	}

	function asignarReparacion(){
		
		$reparadorId	= $this->input->post('reparadorId');
		$solicitudId	= $this->input->post('solicitudId');
		
		$reparadorDatos = $this->usuario_model->cargaUsuario($reparadorId);

		//Actualizar estado de solicitud
		$datosSolicitud = array(			
			'estatus'	=> "EnProceso"
		);
					
		$this->db->where('solicitudId', $solicitudId);
        $this->db->update('solicitudReparaciones', $datosSolicitud);
		
		//Insertar asignacion de rearacion
		$datos = array(
				'usuarioId' 	=> $reparadorId,
				'solicitudId'	=> $solicitudId
		);
		$this->db->insert('asignacionSolicitudes', $datos);
		
		$mensje = "Ya puedes iniciar con la reparación que te han solititado";
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($reparadorDatos[0]->email);
		$this->email->subject('Tu cotización ha sido elegida Reparadores.mx');		
		$this->email->message('
			<html>
				<head>
					<title>Contacto</title>
				</head>
				<body>
					<p>' . $mensje . ', para más detalles ingresa <a href="' . base_url()."avisosdeocacion/detalle/" . $solicitudId . '">aquí</a></p>
					<p>Saludos</p>
				</body>
			</html>
		');
		$this->email->send();
		
		$this->session->set_flashdata('msg','<div class="msgFlash"><img src="http://www.apeplazas.com/obras/assets/graphics/alerta.png" alt="Alerta"><strong>Se ha notificado al reparador exitosamente.</strong></div><br class="clear">');		
		
		redirect('dashboard');
		
	} 
	
}
