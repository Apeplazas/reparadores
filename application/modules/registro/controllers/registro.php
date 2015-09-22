<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registro extends MX_Controller {
	
	function registro(){
		
		parent::__construct();
		$this->load->model('registro_model');
		
		$this->load->library('form_validation');
		$this->form_validation->CI = & $this;
	}

	function index(){
		
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        
        //Carga estados de Mexico
        $op['estados']	= $this->registro_model->estados();
        //Carga menu header
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$op['conocimientos'] = $this->data_model->cargarConocimientos();
		
		$usuario	= $this->session->userdata('usuario');	
		
		if(!$usuario)
		{
			//Vista//
			$this->layouts->index('opcionRegistro-vista', $op);
		}
		else
		{
			if($usuario['tipoUsuario'] == 'usuario'){
				
				$actualizaUsuario = array(
					'tipoUsuario' => 'mixto'
				);
				
				$this->db->where('usuarioId', $usuario['usuarioID']);
        		$this->db->update('usuarios', $actualizaUsuario);
				
			}
			//Vista//
			redirect('dashboard');
		}
		
		
	}
	
	function facebooklogin(){
		
		$usuarioId;
		$usuario;
		$facebook;
		
		$tipoDeUsuario 	= isset($_GET['tipo']) ? $_GET['tipo'] : 'usuario';
		$urlGuardad		= $this->session->userdata('previous_page');
		
		if(strpos($urlGuardad, "http://") === true)
			$urlGuardad = site_url($urlGuardad);

		
		$this->userid = $this->session->userdata("userid");
		try{
			require_once 'facebook/facebook.php';
			$this->facebook  = new Facebook(array(
			  'appId'  => "1463139797288554",
			  'secret' => "4d9cc9e9b4673bf7fb7b909535a12598",
			  'cookie' => true
			));
			$this->usuario = $this->facebook->getUser();
	               
		}catch (FacebookApiException $e) {
			
			error_log($e);
			$this->user = null;
			
		}
	
		if($this->usuario){
			
			try {
		  	
				$user_profile = $this->facebook->api('/me');
			
		  	}catch (FacebookApiException $e) {
		  	
				error_log($e);
				$this->usuario = null;
			
		  	}
		}

		if($this->usuario){
			 
			$permissions = $this->facebook->api("me/permissions");

			if($permissions['data'][0]['status'] == 'granted' && $user_profile['email']){
			 
				//Verificar si exisste usuario
				$existeUsuario = $this->registro_model->existeUsuario($user_profile['email']);
	
				$genero = ($user_profile['gender'] == 'male') ? 'masculino' : 'femenino';
				
				//Obtener ip de usuario
				if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
					
				    $ip = $_SERVER['HTTP_CLIENT_IP'];
					
				}elseif( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					
				    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					
				}else{
					
				    $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
					
				}			
				
				
				if(!$existeUsuario){
			
					$urlPersonalizado = strtolower(str_replace(' ', '', $user_profile['name']));
					
					//Insertar Usuario
					$user_data['user'] = array(
						'nombreCompleto'		=> $user_profile['name'],
						'email'					=> $user_profile['email'],
						'tipoUsuario'			=> $tipoDeUsuario,
						'urlPersonalizado'		=> $urlPersonalizado,
						'tipoRegistro'			=> 'facebook',
						'genero'				=> $genero,
						'tokenActivacion' 		=> $this->facebook->getAccessToken(),
						'terminosCondiciones' 	=> 'autorizado',
						'uidFacebook'			=> $user_profile['id'],
						'estatus'				=> 'activado',
						'ip'					=> $ip
					);
					
					$this->db->insert('usuarios', $user_data['user']);
					$usuarioID = $this->db->insert_id();
					
					//Insertar conocimientos si es reparador
					if($tipoDeUsuario == "reparador"){
					
						$repCats = $_COOKIE['reparadorCon'];
						$repCats = explode(",", urldecode($repCats));
	
						$usuarioCats = array(
							'usuarioId'			=> $usuarioID,
							'categoriaId'		=> $repCats[0],
							'conocimientoId'	=> $repCats[1]
						);
						$this->db->insert('usuariosConocimientosCategorias', $usuarioCats);	
					}
				
					
					//Guardar datos en sesssion
					$data['usuario'] = array(
						'usuarioID'       => $usuarioID,
						'tipoUsuario'     => $tipoDeUsuario,
						'nombre'          => $user_profile['name'],
						'email'           => $user_profile['email'],
						'usuarioAlias'    => $urlPersonalizado
					);
					$this->session->set_userdata($data);
					
					
				}else{
					
					$urlPersonalizado = $existeUsuario[0]->urlPersonalizado;
					//Guardar datos en sesssion
					$data['usuario'] = array(
						'usuarioID'       => $existeUsuario[0]->usuarioId,
						'tipoUsuario'     => $existeUsuario[0]->tipoUsuario,
						'nombre'          => $existeUsuario[0]->nombreCompleto,
						'email'           => $existeUsuario[0]->email,
						'usuarioAlias'    => $urlPersonalizado
					);
					
					$this->session->set_userdata($data); 
					
				}
				
				if($urlGuardad)
					redirect($urlGuardad);
				else	
                	redirect('configuracion');
				exit;
				
			}else{
				
				$this->session->set_flashdata("error_message","No pdemos acceder a tu cuenta de Facebook");
				redirect("registro");
				exit;
				   
			}
		  
		}else{
			
			$callbackUrl = site_url("registro/facebooklogin");
			$loginUrl    = $this->facebook->getLoginUrl(array('scope'=>"user_about_me,publish_stream,offline_access,user_groups,email,user_birthday,user_location"));
			header("Location: $loginUrl");exit;
			
		}
		
	}
	
	function poremail(){
		
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        
        //Carga menu header
        $op['menu'] = $this->data_model->cargarMenuHeader();
        
        //Carga estados de Mexico
        $op['estados']		= $this->registro_model->estados();
		$op['datosUsuario']	= null;
		$op['activarUsuario'] = false;
		
		session_start();
		if(isset($_SESSION['DatosTemporalesUsuario'])){
			
			$datosSession		= unserialize($_SESSION['DatosTemporalesUsuario']);
			$datosUsuario 		= $this->usuario_model->buscaPerfilID($datosSession['usuarioID']);
			$op['datosUsuario']	= (isset($datosUsuario[0])) ? $datosUsuario[0] : null;		
			
		}
			
		//Activar usuario si vienen del mail
		if(isset($_GET['ha'])){
			
			$esValido = $this->registro_model->activarUsuario($_GET['ha']);
							
			if($esValido){
				
				if(!isset($_SESSION['DatosTemporalesUsuario']) || empty($_SESSION['DatosTemporalesUsuario'])){
						
					$datosUsuario 		= $this->usuario_model->buscaPerfilID($esValido[0]->usuarioId);
					$op['datosUsuario']	= (isset($datosUsuario[0])) ? $datosUsuario[0] : null;
					
					$filtros = array(
						"usuarioID"		=> $esValido[0]->usuarioId
					);
					
					$_SESSION['DatosTemporalesUsuario'] = serialize($filtros);
					
				}
				
				$op['activarUsuario'] = true;
			
			}
			
		}		
		
		//Vista//
		$this->layouts->index('registrate-vista', $op);
		
	}
	
	function usuarioEmail($usuarioEmail)
	{
		session_start();
		if(isset($_SESSION['DatosTemporalesUsuario']))
			return TRUE;
		$mail = $this->registro_model->confirmaEmail($usuarioEmail);
			
		if ($mail)
		{
			$this->form_validation->set_message('usuarioEmail', 'Este email ya se encuentra registrado');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function usuarioAlias($usuarioAlias)
	{
		$url = $this->registro_model->confirmaUrl($usuarioAlias);
			
		if ($url)
		{
			$this->form_validation->set_message('usuarioAlias', 'Este alias ya se encuentra activo, Escoge otro');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}	
	
	function guardarRegistro(){
		
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
		
		//Cargar Validacion
		$this->load->library('form_validation');
		$this->form_validation->set_rules('usuarioNombre', 'Nombre de usuario', 'required');
		$this->form_validation->set_rules('usuarioTelefono', 'Telefono', 'required');
		$this->form_validation->set_rules('usuarioEmail', 'Email', 'trim|required|valid_email|callback_usuarioEmail');
		$this->form_validation->set_rules('tipo', 'Tipo de Registro', 'required');
		$this->form_validation->set_rules('tipoUsuario', 'Tipo de Usuario', 'required');
		$this->form_validation->set_rules('usuarioContrasenia', 'Contraseña', 'required');
		$this->form_validation->set_rules('usuarioAlias', 'alias', 'trim|required|callback_usuarioAlias');
		
		//Datos de Usuario
		$usuarioNombre		= $this->input->post('usuarioNombre');
		$usuarioCelular     = $this->input->post('usuarioCelular');
		$usuarioTelefono    = $this->input->post('usuarioTelefono');
		$usuarioEmail       = $this->input->post('usuarioEmail');
		$tipoUsuario        = $this->input->post('tipoUsuario');
		$usuarioCont        = $this->input->post('usuarioContrasenia');
		$usuarioAlias       = $this->input->post('usuarioAlias');
		$usuarioTipo    	= $this->input->post('tipo');
		$activarUsuarioMail	= $this->input->post('activarUsuario');
		
		//Valida la informacion del contacto
		$cadena = $usuarioNombre." ".$usuarioCelular." ".$usuarioTelefono." ".$usuarioEmail." ".$tipoUsuario." ".$usuarioCont." ".$usuarioAlias." ".$usuarioTipo." ".$activarUsuarioMail;
				
		preg_match("/\b(href|declare|select|insert|somebody|xml|passwd|convert|set|response|OR|or|\=|sleep|http|www)\b/",$cadena,$registros);
		
		if(count($registros) == 0){
			//Cargar Datos para la vista en caso de que los datos no sean validos
	        $url = $this->uri->segment(1);
	        $op['tags'] = $this->data_model->cargarOptimizacion($url);
	        $op['menu'] = $this->data_model->cargarMenuHeader();
			
			//Verificar Validacion 
			if ($this->form_validation->run($this) == FALSE){
				$this->layouts->index('registrate-vista', $op);
				
			}else{
					
				$mail 			= $this->registro_model->confirmaEmail($usuarioEmail);
				$usuarioAliasV 	= $this->registro_model->confirmaUrl($usuarioAlias);
				
					
					$user_data = array();
					
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
			
					if(isset($_SESSION['DatosTemporalesUsuario'])){
					
						$datosUsuarioTemp = unserialize($_SESSION['DatosTemporalesUsuario']);
						$usuarioID = $datosUsuarioTemp['usuarioID'];
						unset($_SESSION['DatosTemporalesUsuario']);
						
						$usuarioActivadoMail = ($activarUsuarioMail) ? 'activado' : 'desactivado';
						
						//Insertar Usuario
						$actualizaUsuario = array(
							'nombreCompleto'		=> $usuarioNombre,
							'celular'				=> $usuarioCelular,
							'telefono'				=> $usuarioTelefono,
							'tipoUsuario'			=> $tipoUsuario,
							'urlPersonalizado'		=> $usuarioAlias,
							'contrasenia'			=> md5($usuarioCont),
							'tipoRegistro'			=> 'web',
							'estatus'				=> $usuarioActivadoMail,
							'tipo'					=> $usuarioTipo,
							'terminosCondiciones' 	=> 'autorizado',
							'ip'					=> $ip
						);
						$this->db->where('usuarioId', $usuarioID);
	        			$this->db->update('usuarios', $actualizaUsuario);
						
						if($usuarioActivadoMail == 'activado'){
							
							//Guardar datos en sesssion
							$data['usuario'] = array(
								'usuarioID'       => $usuarioID,
								'tipoUsuario'     => $tipoUsuario,
								'nombre'          => $usuarioNombre,
								'email'           => $usuarioEmail,
								'usuarioAlias'    => $usuarioAlias
							);
							
							$this->session->set_userdata($data);
							redirect('configuracion');
							
						}
						
					}else{
						
						//Insertar Usuario
						$user_data['user'] = array(
							'nombreCompleto'		=> $usuarioNombre,
							'celular'				=> $usuarioCelular,
							'telefono'				=> $usuarioTelefono,
							'email'					=> $usuarioEmail,
							'tipoUsuario'			=> $tipoUsuario,
							'urlPersonalizado'		=> $usuarioAlias,
							'contrasenia'			=> md5($usuarioCont),
							'tipoRegistro'			=> 'web',
							'tipo'					=> $usuarioTipo,
							'terminosCondiciones' 	=> 'autorizado',
							'ip'					=> $ip
						);
						$this->db->insert('usuarios', $user_data['user']);
						$usuarioID = $this->db->insert_id();
						
						//Generar hash para activacion
						$hashActivacion = sha1(mt_rand(10000,99999).time().$usuarioID);
						
						$activarDatos = array(
							'hashActivacion' => $hashActivacion
						);
						
						$this->db->where('usuarioId', $usuarioID);
		        		$this->db->update('usuarios', $activarDatos);
						
						$this->load->library('email');
						$this->email->set_newline("\r\n");
						$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
						$this->email->to($usuarioEmail);
						$this->email->subject('Bienvenido a Reparadores.mx');		
						$this->email->message('
						
						<html>
						<head>
						<meta charset="utf-8">
						<title>Gracias por Registrarse a Reparadores.mx</title>
						<style>
							h1, h2{font-size:20px; font-weight:700; margin:25px 0 3px}
							body{font-family:helvetica,arial,sans-serif; background-color:#f4f5f7; font-size:.8em; line-height:20px; color:#555}
							.wrap{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #999}
							.wrapTwo{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #999}
							a{color:#c30}
							#regEmail{display:inline; width:200px; margin:40px auto; height:28px; text-align:center; background-color: #B81C2D; color:#fff; text-transform:uppercase; font-size:16px; padding:12px 30px 9px; border-radius:6px; text-decoration:none;}
							li{list-style:none}
							ul{margin:0; padding:0}
							#foot{color:#777; font-size:10px;b order-top:1px solid #ccc; padding:10px 0}
							em{color:#c30; font-style: normal}
						</style>
						</head>
						<body style="background-color:#eee;">
						<table cellpadding="40" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px font-family:helvetica,arial,sans-serif; border:1px solid #ccc; color:#555; margin-top:30px;">
						  <tr>
						    <td>
						    <div class="wrap">
						    <a href="http://reparadores.mx"><img src="http://reparadores.mx/assets/graphics/reparadores-logoNegro.jpg" alt="Reparadores" /></a>
						    <h1>Hola '.$usuarioAlias.'!</h1>
						    <p>Bienvenido a reparadores.mx! La cuenta que acaba de crear le concede acceso a la mas grande comunidad de reparadores de tecnología en México, por favor para activar su cuenta de <a href="'.base_url().'registro/activarUsuario/'.$hashActivacion.'">click aquí</a><br><br>Gracias,<p>
						    </div>
						    </td>
						  </tr>
						</table>
						</body>
						</html>
						');
						if($this->email->send()){
						
							$this->session->set_flashdata('msg','<div class="msg mt20 mb20">¡Te has registrado con éxito!</div>');
							
						
						}else{
						
							show_error($this->email->print_debugger()); /* Muestra error de envio de email */
						
						}
						
					}
					
					//Insertar conocimientos si es reparador
					if($tipoUsuario == "reparador"){
						
						$repCats = $_COOKIE['reparadorCon'];
						$repCats = explode(",", urldecode($repCats));
		
						$usuarioCats = array(
							'usuarioId'			=> $usuarioID,
							'categoriaId'		=> $repCats[1],
							'conocimientoId'	=> $repCats[0]
						);
						$this->db->insert('usuariosConocimientosCategorias', $usuarioCats);	
					}
	
				redirect('usuarios/activar_cuenta');
				
			}
		}
		else{
			echo '.l. ---- LOOSER';
		}

	}

	function activarUsuario($hashActivacion){
		
		//verificar hash
		$esValido = $this->registro_model->activarUsuario($hashActivacion);
	
		if($esValido){
			
			$activarDatos = array(
				'estatus' => 'activado'
			);
				
			$this->db->where('usuarioId', $esValido[0]->usuarioId);
        	$this->db->update('usuarios', $activarDatos);
			
			$usuario = $this->session->userdata('usuario');
        	if(!isset($user) || $user != true){
        		
	         	//Guardar datos en sesssion
				$data['usuario'] = array(
					'usuarioID'       => $esValido[0]->usuarioId,
					'tipoUsuario'     => $esValido[0]->tipoUsuario,
					'nombre'          => $esValido[0]->nombreCompleto,
					'email'           => $esValido[0]->email,
					'usuarioAlias'    => $esValido[0]->urlPersonalizado
				);
				
				$this->session->set_userdata($data);
				redirect('configuracion');
        	}
			
		}else{
			
			redirect('');
			
		}
		
	} 

	function recuperar_contrasenia(){
		
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);

        //Carga menu header
        $op['menu'] = $this->data_model->cargarMenuHeader();
		
		$op['info']		= array();
		
		//Vista
		$this->layouts->index('recuperarContrasenia-vista' ,$op);
		
	}
	
	function recuperar_hash(){
		
		$correo_usuario = trim($this->input->post('email'));
		
		if( empty($correo_usuario) || !isset($correo_usuario) ){
			
			$this->session->set_flashdata('msg','<div class="msg mt20 mb20">Por favor introduzca un email.</div>');
			
		}
		
		$u = $this->db->query("SELECT * FROM usuarios WHERE email='$correo_usuario'")->result();

		if ($u){
			
			$hashActivacion = sha1(mt_rand(10000,99999).time().$u[0]->usuarioId.$u[0]->contrasenia);
	        
	        $cambioCont = array(
	        	'usuarioID'			=> $u[0]->usuarioId,
	        	'contrasenia_hash'	=> $hashActivacion
			);
			
	        $this->db->insert('cambiosContrasenia', $cambioCont);
	       
			$this->load->library('email');
			$this->email->set_newline("\r\n");
			$this->email->from('noresponder@reparadores.mx', 'Recupera tu Contraseña');
			$this->email->to($u[0]->email);
			$this->email->subject('Recuperacion de contraseña, reparadores.mx');
			$mensaje = "Hola " . $u[0]->nombreCompleto . " !!! Para generar su nueva contraseña de click <a href='" . base_url().'registro/cambiarContrasenia/' . $hashActivacion ."'>aquí</a>";
			$this->email->message($mensaje);
			$this->email->send();
			
			$this->session->set_flashdata('msg','<div class="msg mt20 mb20">Se ha enviado un correo electronico a su cuenta registrada para restablecer su contraseña.</div>');
			
		}else{
			
			$this->session->set_flashdata('msg','<div class="msg mt20 mb20">El email es incorrecto, intenta de nuevo.</div>');
			
		}

		redirect('registro/recuperar_contrasenia');
		
	}

	function cambiarContrasenia(){
		
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
		
		$hash	= trim($this->uri->segment(3));
		
		$query	= $this->db->query("SELECT * from cambiosContrasenia WHERE contrasenia_hash='$hash'");
		
		if( $query->num_rows()>0 ){
			
			foreach ($query->result() as $row){
				
				$usuarioID 		= $row->usuarioID;
				
			}
			
			$this->layouts->index('actualizarContrasenia-vista', $op);
			
		}else{
			
			//podemos redireccionar o escribimos algo
			redirect('');
			
		}
		
	}
	
	function actualizarContrasenia(){
		
		$contrasenia  	= trim($_POST["contrasenia"]);
		$contrasenia1 	= trim($_POST["contraseniaVerificacion"]);
		$hash 			= $_POST["hash"];
		
		if( strcmp($contrasenia,$contrasenia1) == 0 ){
			
			$c = $this->db->query("SELECT * FROM cambiosContrasenia WHERE contrasenia_hash='$hash'");
			
			foreach($c->result() as $row){
				$usuarioID = $row->usuarioID;
			}
			
			$hashDatos	= array('estatus'=>1);
			
			$this->db->where('contrasenia_hash', $hash);
        	$this->db->update('cambiosContrasenia', $hashDatos);
			
			$md5_cont 	= md5($contrasenia);
			$datos 		= array('contrasenia'=>$md5_cont);
				
			$this->db->where('usuarioID', $usuarioID);
        	$this->db->update('usuarios', $datos);
			
			$this->session->set_flashdata('msg','<em class="msg mt20 mb20">La contraseña ha sido cambiada exitosamente. Inicia Sesión.</em>');
			redirect('registro/ingresar');
			
		}
		else{
			$this->session->set_flashdata('msg','<em class="msg mt20 mb20">Las contraseñas proporcionadas no coinciden, Inténtalo nuevamente.</em>');
			redirect('registro/cambiarContrasenia/'.$hash.'');
		}
	}
	
	function usuario(){
		
		//Carga menu header
		$opt        = $this->uri->segment(1);
        $op['menu'] = $this->data_model->cargarMenuHeader();
		$op['opt']  = $this->data_model->cargarOptimizacion($opt);
		
		$this->layouts->index('opcionRegistro-vista', $op);
		
	}
	
	function ingresar(){
				
		//Optimizacion y conexion de tags para SEO//
		$opt         		= $this->uri->segment(1);
		$op['opt']    		= $this->data_model->cargarOptimizacion($opt);
		$urlGuardad 		= $this->session->userdata('previous_page');
		
		if(strpos($urlGuardad, "http://") === true)
			$urlGuardad = site_url($urlGuardad);
		
		$usuarioOEmail		= $this->input->post('usuarioOEmail');
		$contrasenia	 	= $this->input->post('contrasenia');
		
		if( (empty($usuarioOEmail) || empty($contrasenia)) && sizeof($_POST) > 0)
			$op['error'] = "Por favor ingrese su usuario y password";
		
		if($usuarioOEmail){
		
			$u = $this->data_model->validarLogin($usuarioOEmail, $contrasenia);
	
			if ($u && !isset($u['error'])){
				
				$data['usuario'] = array(
						'usuarioID'       => $u[0]->usuarioId,
						'tipoUsuario'     => $u[0]->tipoUsuario,
						'nombre'          => $u[0]->nombreCompleto,
						'email'           => $u[0]->email,
						'usuarioAlias'    => $u[0]->urlPersonalizado
				);
				
				 //guardamos los datos en la sesion
				 $this->session->set_userdata($data);
				 
				 if($urlGuardad)
					redirect($urlGuardad);
				 else
				 	redirect($u[0]->urlPersonalizado);
				
			}else{
				
				$op['error'] = $u['error'];	
				
			}
			
		}
			
		$this->layouts->index('ingresar-vista', $op);	
	}
	
	function verificaUsuario($hash, $comentarioID)
	{
		//Genera metatags
        $url = $this->uri->segment(1);
        $op['opt'] = $this->data_model->cargarOptimizacion($url);
        
        //Carga menu header
        $op['menu'] = $this->data_model->cargarMenuHeader();
        
        //Carga estados de Mexico
        $op['estados']	= $this->registro_model->estados();
		
		//Asigna segmentos de la url
		$hash         = $this->uri->segment(3);
		$comentarioID = $this->uri->segment(4);
		
        $op['usuario'] = $usuario = $this->registro_model->activarUsuario($hash);
        
        foreach($usuario as $row){
				$usuarioID	= $row->usuarioId;
				$status		= $row->estatus;
				$nombre		= $row->nombreCompleto;
				
			};
		if($status == 'desactivado')
		{
				
			//Vista registro//
			$this->layouts->index('registrate-vista', $op);
		}
		else
		{
			redirect('registro/ingresar');
		}
		
	}
	
	function salir(){
		
		$this->session->sess_destroy();
		redirect('');
		
	}
	
}


