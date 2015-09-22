<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class Avisosdeocacion extends CI_Controller {
	
	function Avisosdeocacion(){
		parent:: __construct();
		$this->load->model('avisosdeocacion_model');
		$this->load->model('reparaciones/reparaciones_model');
	}
	
	function index(){
		
		//Genera metatags
        $url 		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
		
		$op['conocimientos'] 	= $this->data_model->cargarConocimientos();
		$op['solicitudes'] = $this->avisosdeocacion_model->cargaSolicitudesReparacion();
		
		//Vista//
		$this->layouts->index('avisodeocacion-vista', $op);	
		
	}
	
	function detalle($solicitudId){
		
		$usuario			= $this->session->userdata('usuario');
		$usuarioId			= (isset($usuario['usuarioID'])) ? $usuario['usuarioID'] : null;
	
		//Genera metatags
        $url 		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
        
        //Carga el javascript y CSS //
		$this->layouts->add_include('assets/js/jquery.fancybox.js')
					  ->add_include('assets/css/jquery.fancybox.css');
		
		$detalle			= $this->reparaciones_model->detalleReparacion($solicitudId);
		$urlNotificacion	= base_url() . 'avisosdeocacion/detalle/' . $solicitudId;

		//Quitar Notificaciones
		$datosActualiza = array(
			'leido' => 1,		 
		);
			
		$this->db->where('url', $urlNotificacion);
	    $this->db->update('notificaciones', $datosActualiza);

		$op['postulados']	= $this->reparaciones_model->postuladosReparacion($solicitudId);
		$op['detalle'] 		= $detalle[0];
		$op['archivos'] 	= $this->reparaciones_model->archivosSolicitud($solicitudId);
		$op['haCotizado'] 	= $this->data_model->buscaObjeto($op['postulados'],'usuarioId',$usuarioId);
		if($detalle[0]->usuarioId == $usuarioId)
			$op['propietarioSolicitud'] = true;
	
		//Vista//
		$this->layouts->index('detalle-vista', $op);	
		
	}

	function presupuestar(){
		
		$usuario			= $this->session->userdata('usuario');
		
		$titulo				= $this->input->post('titulo');
		$solicitudId		= $this->input->post('solicitudId');
		$costo				= $this->input->post('costo');
		$mensaje			= $this->input->post('mensaje');
		$usuarioSolicita 	= $this->input->post('usuarioSolicita');
		
		$usuarioRecptorDatos = $this->usuario_model->cargaUsuario($usuarioSolicita);
		
		if($usuarioRecptorDatos[0]->estatus == 'desactivado'){
			$mensajeNotCorreo	= 'El usuario ' . $usuario['nombre'] . ' ha hecho una cotozación para una de tus reparaciones, registrate <a href="' . base_url() . 'registro/poremail?ha=' . $usuarioRecptorDatos[0]->hashActivacion . '">aquí</a> para poder ver los detalles.';
		}else{
			$mensajeNotCorreo	= 'El usuario ' . $usuario['nombre'] . ' ha hecho una cotozación para una de tus reparaciones, para más información haz click <a href="' . base_url() . 'avisosdeocacion/detalle/' . $solicitudId . '">aquí</a>';
		}

		//Genera Mensaje para la conversacion de la reparacion
		$datosMensaje = array(	
			'usuarioUnoId' 	=> $usuario['usuarioID'],
			'usuarioDosId'	=> $usuarioSolicita,
			'asunto'		=> $titulo,
			'mensajeTipo'	=> 2	 
		);
		$this->db->insert('mensajes', $datosMensaje);
		$mensajeId = $this->db->insert_id();
		
		//inserta contenido del mensaje
		$datosMensajeContenido = array(	
			'respuesta' 	=> $mensaje,
			'mensajeId'		=> $mensajeId,
			'usuarioId'		=> $usuario['usuarioID']
		);
		$this->db->insert('mensajesRespuestas', $datosMensajeContenido);
		
		//Inserta Peticion de reparacion
		$datosPostulacion = array(
			'usuarioId' 	=> $usuario['usuarioID'],
			'solicitudId'	=> $solicitudId,
			'mensajeId'		=> $mensajeId,
			'costo'			=> $costo	 
		);
		$this->db->insert('postulados', $datosPostulacion);
		
		//Insertar notificacion
		$notUsario = array(
				'usuarioId' 	=> $usuarioRecptorDatos[0]->usuarioId,
				'notificacion'	=> 'El usuario ' . $usuario['nombre'] . ' ha hecho una cotozación para una de tus reparaciones',
				'referencia'	=> 'cotz', 
				'url'			=> base_url() . 'avisosdeocacion/detalle/' . $solicitudId
		);
		$this->db->insert('notificaciones', $notUsario);
		
		$this->session->set_flashdata('msg','<div class="msgFlash"><img src="http://www.apeplazas.com/obras/assets/graphics/alerta.png" alt="Alerta"><strong>Se ha enviado tu cotización.</strong></div><br class="clear">');
			
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($usuarioRecptorDatos[0]->email);
		$this->email->subject('Te han contactado de Reparadores.mx');		
		$this->email->message('
			<html>
				<head>
					<title>Contacto</title>
				</head>
				<body>
					<p>' . $mensajeNotCorreo . '</p>
					<p>Saludos</p>
				</body>
			</html>
		');
		$this->email->send();
		
		redirect("dashboard");
			
	}
	
}