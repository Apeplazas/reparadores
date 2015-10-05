<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends MX_Controller
{
	
	public function __construct(){
		
		parent::__construct();
		
	}
	
	function perfiles($usuarioAlias){
		
		//Optimizacion y conexion de tags para SEO//
		$segmentoUno		= $this->uri->segment(1);
		$op['opt']    		= $this->data_model->cargarOptimizacion($segmentoUno);
		
		//Datos de usuario
		$usuario		= $this->session->userdata('usuario');
		 //Carga el javascript para jquery//
		$this->layouts->add_include('assets/css/jquery.tagit.css')
					  ->add_include('assets/css/tagit.ui-zendesk.css')
					  ->add_include('assets/js/tag-it.min.js');
					  
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['tags'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$op['perfil']	= $this->usuario_model->buscaPerfilID($segmentoUno,'urlPersonalizado');
			
		$op['cat'] 				= $this->data_model->cargarConocimientos();
		$op['usurioTags']		= $this->usuario_model->cargarUsuarioTags($usuario['usuarioID']);
		$op['usuario']			= $usuario;
		$op['conocimientos'] 	= $this->usuario_model->cargarConocimientos($usuario['usuarioID']);
		$op['trabajos']	= $this->usuario_model->cargarTrabajos($usuario['usuarioID']);
		
		$this->layouts->index('usuario-sinedicion', $op);
					
	}
	
	function configuracion(){
		
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
					  ->add_include('assets/js/croppic.min.js');
					  
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['tags'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		
		if($usuario['usuarioAlias']){
			
			$op['perfil'] = $this->usuario_model->buscaPerfilID($usuario['usuarioID']);
			
			$this->layouts->index('configuracion-vista', $op);
			
		}
		
		else
		{
			echo 'tu no existes';
		}
	}
	
	function actualizaPerfil(){

		$this->data_model->is_logged_in();
		
		//Usuario datos
		$usuario			= $this->session->userdata('usuario');
		$usuarioId			= $usuario['usuarioID'];
		$usuarioUrl			= $this->input->post('usuarioUrl');
		
		//Datos ubucacion
		$googleMpas			= str_replace(array( '(', ')' ), '',$this->input->post('googleMaps'));
		$googleEstado		= $this->input->post('googleEstado');
		$googleDelegacion	= $this->input->post('googleDelegacion');
		$googleCp			= $this->input->post('googleCp');		
		$googleColonia		= $this->input->post('googleColonia');

		$latlongDatos 		= explode(',',$googleMpas); 

		//Verificar si ya tiene una ubicacion
		$usuarioConUbicacion = $this->usuario_model->tieneUbicacion($usuarioId);
		
		if(empty($usuarioConUbicacion)){
		
			$datosActualiza = array(
			
			 	'usuarioId' 		=> $usuarioId,
				'estadoNombre'		=> $googleEstado,
			 	'delegacionNombre'	=> $googleDelegacion,
			 	'codigoPostal' 		=> $googleCp,
			 	'coloniaNombre'		=> $googleColonia,
			 	'latitud'			=> deg2rad($latlongDatos[0]),
			 	'longitud'			=> deg2rad($latlongDatos[1]),
				'coordenadasGoogle' => $googleMpas,
				 
			
			);
			$this->db->insert('estadosUsuarios', $datosActualiza);
				
		}else{
				
			$datosActualiza = array(
			
				'estadoNombre'		=> $googleEstado,
			 	'delegacionNombre'	=> $googleDelegacion,
			 	'codigoPostal' 		=> $googleCp,
			 	'coloniaNombre'		=> $googleColonia,
			 	'latitud'			=> deg2rad($latlongDatos[0]),
			 	'longitud'			=> deg2rad($latlongDatos[1]),
				'coordenadasGoogle' => $googleMpas,
				 
			
			);
			
			$this->db->where('usuarioId', $usuarioId);
	        $this->db->update('estadosUsuarios', $datosActualiza);
			
		}
		
		//Datos generales de la vista
		redirect("configuracion");		
	}

	function actualizaDatosPerfil(){
		
		$this->data_model->is_logged_in();
		
		$usuario			= $this->session->userdata('usuario');
		$usuarioId			= $usuario['usuarioID'];
		
		$tags				= $this->input->post('tags');
		$fechaNacimiento	= $this->input->post('anio')."-".$this->input->post('mes')."-".$this->input->post('dia');
		$sexo				= $this->input->post('genero');
		$bio				= $this->input->post('bio');
		
		if($fechaNacimiento || $sexo){
			
			$datosUsuario = array(
				'fechaNacimiento'	=> $fechaNacimiento,
				'genero' 			=> $sexo,
				'bio'				=> $bio
			);
			
			$this->db->where('usuarioId', $usuarioId);
			$this->db->update('usuarios', $datosUsuario);
			
		}
	
		if(!empty($tags)){
		
			foreach($tags as $tag){
				
				$tagExiste = $this->data_model->existeTag($tag);
				if(empty($tagExiste)){
					
					$datosTag = array(
						'habilidad' => $tag,
					);
					
					$this->db->insert('habilidades', $datosTag);
					$tagId = $this->db->insert_id();
					
				}else{
					
					$tagId = $tagExiste[0]->habilidadId;
					
				}
				
				$datosTag = array(
					'usuarioId'		=> $usuarioId,
					'habilidadId' 	=> $tagId
				);
					
				$this->db->insert('habilidadReparador', $datosTag);
				
			}	
			
		}
		
		$this->session->set_flashdata('msg','<div class="msgFlash"><img src="http://www.apeplazas.com/obras/assets/graphics/alerta.png" alt="Alerta"><strong>Se han actualizado tus datos.</strong></div><br class="clear">');

		redirect('configuracion');		
 
	}

	function contactar($usuarioId){
		
		$this->data_model->guardaUrl();
		
		$this->data_model->is_logged_in();
		
		$op['usuarioId'] = $usuarioId;
		
		//Datos generales de la vista
		$url 		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$this->layouts->index('contactar-vista', $op);
		
	}

	function mensajes(){
		
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$user			= $this->session->userdata('usuario');
		$usuarioId		= $user['usuarioID'];
		
		$op['listaMen']	= $this->usuario_model->cargarMenLista($user['usuarioID']);
		
		$usuario			= $this->session->userdata('usuario');
		$op['perfil']		= $this->usuario_model->buscaPerfilID($usuario['usuarioID']);
		$op['mensajes']		= $this->usuario_model->cargaMensajes($usuario['usuarioID']);
		$op['usuarioId']	= $usuarioId;

		$this->layouts->index('mensajes-vista', $op);
		
	}

	function guardarMensaje(){
		
		$usuarioIdUno	= $this->input->post('usuarioId');
		$usuarioMensaje = $this->input->post('usuarioMensaje');
		$urlRegreso		= $this->input->post('urlRegreso');
		$asunto			= $this->input->post('asunto');

		$usuarioRecptorDatos 	= $this->usuario_model->cargaUsuario($usuarioIdUno); 		
		$user					= $this->session->userdata('usuario');
		$usuarioIdDos			= $user['usuarioID'];	
		
		//Generar mensaje
		$datosMensaje = array(
				'usuarioUnoId'	=> $usuarioIdUno,
				'usuarioDosId'	=> $usuarioIdDos,
				'asunto'		=> $asunto,
				'mensajeTipo'	=> 1
		);
		$this->db->insert('mensajes', $datosMensaje);
		$converId = $this->db->insert_id();
		
		//Genera contenido de mensajes
		$op = array(
			'respuesta'		=> $usuarioMensaje,
			'mensajeId'    	=> $converId,
			'usuarioId'		=> $usuarioIdDos,
		);
		$this->db->insert('mensajesRespuestas', $op);
		
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
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($usuarioRecptorDatos[0]->email);
		$this->email->subject('Te ha contactado de Reparadores.mx');		
		$this->email->message('
			<html>
				<head>
					<title>Contacto</title>
				</head>
				<body>
					<p>' . $mensje . ', para más detalles ingresa <a href="' . base_url()."usuarios/ver_mensaje/".$converId . '">aquí</a></p>
					<p>Saludos</p>
				</body>
			</html>
		');
		$this->email->send();
		
		redirect($urlRegreso);
		
	}

	function guardarSolicitudReparacion(){
		
		$titulo			= $this->input->post('titulo');
		$descripcion 	= $this->input->post('descripcion');
		$categoriaId 	= $this->input->post('subcategoria');
		
		$user			= $this->session->userdata('usuario');
		$usuarioId		= $user['usuarioID'];	
		
		//Generar Solicitud
		$datosMensaje = array(
				'titulo'		=> $titulo,
				'descripcion'	=> $descripcion,
				'usuarioId'		=> $usuarioId,
				'categoriaId'	=> $categoriaId
		);
		$this->db->insert('solicitudReparaciones', $datosMensaje);
		$solicitudId = $this->db->insert_id();
		
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
						'fotografiaNombre'	=> $archivoNombre,
						'ext'				=> $ext,
						'solicitudId' 		=> $solicitudId
					);
					
					$this->db->insert('fotografias', $data);
					
				}	
				
			}
			
		}		
		
		$this->session->set_flashdata('msg','<div class="msgFlash"><img src="http://www.apeplazas.com/obras/assets/graphics/alerta.png" alt="Alerta"><strong>Se ha publicado tu solicitud exitosamente.</strong></div><br class="clear">');
		
		redirect("usuarios/solicitudes");
		
	}

	function solicitudes(){
		
		//Datos generales de la vista
		$url 		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
		
		$this->layouts->index('solicitudes-vista', $op);
		
	}

	function contestarMensaje(){
		
		$mensajeId		= $this->input->post('mensajeId');
		$respuesta		= $this->input->post('respuesta');
		
		$user			= $this->session->userdata('usuario');
		
		//Insertar respuesta
		$respuestaDatos = array(
				'respuesta' => $respuesta,
				'mensajeId'	=> $mensajeId,
				'usuarioId' => $user['usuarioID']
		);
		$this->db->insert('mensajesRespuestas', $respuestaDatos);
		
		//Notificar al usuario de que tiene un mesaje
		$mensje 	= "El usuario ".$user['nombre']." ha respondido.";
		
		$notificarA = $this->usuario_model->notificarA($user['usuarioID'],$mensajeId); 
	
		//Insertar notificacion
		$notUsario = array(
				'usuarioId' 	=> $notificarA[0]->usuarioId,
				'notificacion'	=> $mensje,
				'referencia'	=> 'men-'.$mensajeId, 
				'url'			=> base_url()."usuarios/ver_mensaje/".$mensajeId
		);
		$this->db->insert('notificaciones', $notUsario);
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($notificarA[0]->email);
		$this->email->subject('Te han enviado un mensaje de Reparadores.mx');		
		$this->email->message('
			<html>
				<head>
					<title>Contacto</title>
				</head>
				<body>
					<p>' . $mensje . ', para más información haz click <a href="' . base_url()."usuarios/ver_mensaje/".$mensajeId . '">aquí</a></p>
					<p>Saludos</p>
				</body>
			</html>
		');
		$this->email->send();
		
		redirect('usuarios/ver_mensaje/'.$mensajeId);	
		
	}

	function ver_mensaje($mensajeId){
		
		$user			= $this->session->userdata('usuario');
		
		//Datos generales de la vista
		$url		= $this->uri->segment(1);
        $op['opt'] 	= $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();

		$op['mensaje']	= $this->usuario_model->cargarDetalleMensaje($mensajeId);
		$op['archivos']	= $this->usuario_model->cargarArchivosMensaje($mensajeId);
		$op['listaMen']	= $this->usuario_model->cargarMenLista($user['usuarioID']);
		
		//Limpiar notificaciones
		$marcarLeido = array(
			'leido' => 1
		);
		$actualizarEn = array(
			'usuarioId' 	=> $user['usuarioID'],
			'referencia'	=> 'men-'.$mensajeId 
		);
		
		$this->db->where($actualizarEn);
        $this->db->update('notificaciones', $marcarLeido);
		
		
		$this->layouts->index('verMensaje-vista', $op);
		
	}
	
	function donde_estas(){
		
    	//Datos de usuario
		$usuario		= $this->session->userdata('usuario');
		$op['perfil']	= $this->usuario_model->buscaPerfilID($usuario['usuarioID']);
		
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
        
        $this->layouts->index('mapa-vista', $op);
		
    }
	
	function notificaciones(){
		
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['opt']	= $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$usuario				= $this->session->userdata('usuario');
		$op['perfil']			= $this->usuario_model->buscaPerfilID($usuario['usuarioID']);
		$op['notificaciones']	= $this->usuario_model->cargaNotificaciones($usuario['usuarioID']);

		$this->layouts->index('notificaciones-vista', $op);
		
	}
	
	function solicitarreparacion(){
		
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['opt']	= $this->data_model->cargarOptimizacion($url);
		
		$op['conocimientos'] 	= $this->data_model->cargarConocimientos();
		
		$this->layouts->index('solicitarReparacion-vista', $op);
		
	}
	
	function activar_cuenta(){
		
		//Datos generales de la vista
		$url = $this->uri->segment(1);
        $op['opt']	= $this->data_model->cargarOptimizacion($url);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$this->layouts->index('gracias-vista', $op);
		
	}
	
	function salir(){
		
		$this->session->sess_destroy();
		redirect('');
		
	}
	
}

