<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class Servicios extends CI_Controller {
	
	function Servicios(){
		parent:: __construct();
		$this->load->model('servicios_model');
		
	}
	
	function index(){
		$this->load->view('servicios-views');
	}
	
	function registro() {
		
		$nombre = $this->input->post('nombre');
		$contrasena = md5($this->input->post('contrasena'));
		$email = $this->input->post('email');
		$apellidos = $this->input->post('apellidos');		
		
		$tipoUsuario = 'usuario';
		$tipoRegistro = 'movil';
		
		$data = array('nombreCompleto' 	=> $nombre . " " . $apellidos,
					  'contrasenia' 	=> $contrasena,
					  'email' 			=> $email,
					  'tipoUsuario' 	=> $tipoUsuario,
					  'tipoRegistro'	=> $tipoRegistro,
					  'estatus'			=> 'activado');
					  
		//Validar  email
		$result = $this->servicios_model->validarEmail($email);

		if($result == 'FALSE') {
			
			//Registrar usuario nuevo
				
			$this->db->insert('usuarios', $data);
			$usuarioID = $this->db->insert_id();
				
			$hashActivacion = sha1(mt_rand(10000,99999).time().$usuarioID);
				
			$activarDatos = array('hashActivacion' => $hashActivacion);
								    
			$this->db->where('usuarioId', $usuarioID);
			$this->db->update('usuarios', $activarDatos);
			
			echo 'FALSE';
			
		} else if($result == $email) {
			
			echo $result;
			
		}else {
			
			echo 'error';
			
		}
					  
	}
	
	function entrar() {
		$usuario = $this->input->post('usuario');
		$contrasena = md5($this->input->post('contrasena'));
		
		$result = $this->servicios_model->entrar($usuario);
		
		if($result == 'FALSE') {
			echo 'FALSE';
		}
		else if($result == $contrasena) {
			echo 'TRUE';
		}
		else {
			echo 'NO';
		}
	}
	
	function extraerDatos ($usuario) {
		//$usuario = $this->input->post('usuario');
		
		$result = $this->servicios_model->extraerDatos($usuario);
		
		echo json_encode($result);
	}
	
	function buscar($nombre) {
		
		//$nombre = $this->input->post('nombre');
		
		$result = $this->servicios_model->buscar($nombre);
		
		print_r($result);
	}
	
	//Funcion para cargar estados de mexico.
	function Estados() {
		
		$q = $this->db->query("SELECT nombreEstado FROM estadosMexico 
								WHERE nombreEstado != ''
								GROUP BY nombreEstado");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		
		echo json_encode($data);
	}
	
	
	function Municipios($colonia) {
		
		//$estado = $this->input->post("estado");
		
		$colonia = urldecode($colonia);
	
		
		$q = $this->db->query("SELECT nombreMunicipio FROM estadosMexico 
								WHERE nombreMunicipio != ''
								AND nombreEstado = '$colonia'
								GROUP BY nombreMunicipio");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		
		echo json_encode($data);
		
	}
	
	
	
	function Colonias($colonia) {
		
		//$estado = $this->input->post("estado");
		
		$colonia = urldecode($colonia);
	
		
		$q = $this->db->query("SELECT nombreColonia FROM estadosMexico 
								WHERE nombreColonia != ''
								AND nombreMunicipio = '$colonia'
								GROUP BY nombreColonia");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		
		echo json_encode($data);
		
	}
	
	
	function buscarReparadoresApp($lat,$long){
		
		$latitud 	= deg2rad($lat);
		$long		= deg2rad($long);
		
		$q = $this->db->query("SELECT 
									d.coordenadasGoogle,( (2*atan2(sqrt(a),sqrt(1-a)) ) * 6371 ) as dist,d.coloniaNombre,
									u.usuarioId,u.nombreCompleto,
									if(u.celular is null OR u.celular = '0',u.telefono,u.celular) as telefono,
									u.email,u.fotografiaPerfil,u.bio
									FROM(
										SELECT eu.usuarioId,eu.coordenadasGoogle,eu.estadoNombre,eu.coloniaNombre, ( power(sin((eu.latitud - '$latitud')/2),2) +
										cos('$latitud') * cos(eu.latitud) *
										power(sin((eu.longitud - '$long')/2),2) ) as a
										FROM estadosUsuarios eu
									) d
									LEFT JOIN usuarios u ON u.usuarioId=d.usuarioId
									LEFT JOIN usuariosConocimientosCategorias uc ON uc.usuarioId=u.usuarioId
									LEFt JOIN conocimientosCategorias cc ON cc.categoriaId=uc.categoriaId
									WHERE (u.tipoUsuario ='reparador' or u.tipoUsuario ='mixto') AND coordenadasGoogle IS NOT NULL
									GROUP BY u.usuarioId
									HAVING dist<='30' ORDER BY dist ASC
									LIMIT 10");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}	
		echo json_encode($data);
		
	}


	function buscarReparadoresNombre ($estado, $colonia) {
		
		$estado = urldecode($estado);
		$colonia = urldecode($colonia);
		
		$data = array();
		
		$q = $this->db->query("SELECT us.usuarioId,us.nombreCompleto,
									if(us.celular is null OR us.celular = '0',us.telefono,us.celular) as telefono,
									us.email,us.fotografiaPerfil,us.bio
										FROM usuarios us LEFT JOIN estadosUsuarios es ON es.usuarioId=us.usuarioId
                               			WHERE us.tipoUsuario='reparador' 
                               			AND es.estadoNombre='$estado' 
                               			AND es.coloniaNombre='$colonia'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}	
		echo json_encode($data);
	}
	
	function datosReparador($id) {
		
		$result = $this->servicios_model->datosReparador($id);
		
		echo json_encode($result);
		
	}
	
	function datosHabilidadReparador($id) {
		
		$result = $this->servicios_model->datosHabilidadReparador($id);
	
		echo json_encode($result);
		
	}
	
	function guardarMensaje() {
		
		$idUsuario = $this->input->post("idUsuario");
	  $idReparador = $this->input->post("idReparador");
	//	$mensaje = $this->input->post("mensaje");
		
		$mensajeId = $this->servicios_model->obtenerIdMensaje($idUsuario, $idReparador);
		
		echo $mensajeId;
	}
	
	function pruebaemail() {
		
		$this->load->View('pruebaemail-view');
	}
	
	
	function enviarMensaje() {
		
		$nombreReparador = $this->input->post('nombreReparador');
		$nombreUsuario = $this->input->post('nombre');
		$Mensaje = $this->input->post('mensaje');
		$emailReparador = $this->input->post('emailReparador');
		$emailUsuario = $this->input->post('email');
		$telefonoUsuario = $this->input->post('telefono'); 
		$idUsuario = $this->input->post('idUsuario');
		$idReparador = $this->input->post('idReparador');
		
		$mensajeId = $this->servicios_model->obtenerIdMensaje($idUsuario, $idReparador, $Mensaje);
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($emailReparador);
		$this->email->subject('Solicitud de reparacion');
		$this->email->message('
								<html lang="en">
								<head>
									<meta charset="UTF-8" />
									
									<title>Mensaje Reparadores.mx</title>
									<style type="text/css" media="screen">
										#mensaje {width: auto;
												   height: auto;
												   background: #313131;
												   color: #fff;
												   padding: 20px;}
												   
										#cabecera { border: #000 solid 1px;
													padding: 20px;
													margin: 0 auto;
													height: auto;
													width: 50%;}
									</style>
								</head>
								<body>
									
									<div id="cabecera">
										
										<h3>Hola '.$nombreReparador.':</h3>
										<p>Se te a solicitado una cotizacion, para un servicio de reparador.</p>
										<p>Este es el mensaje: </p>
										<div id="mensaje">
											<p>'.$Mensaje.'</p>
										</div>
										<div id="datos">
										  <h4>Datos del solicitante</h4>
										  <p>Nombre: '.$nombreUsuario.' </p>
										  <p>Telefono: '.$telefonoUsuario.'</p>
										  <p>Email: '.$emailUsuario.'</p>
										</div>
										<p>Saludos...</p>
										
									</div>
									
								</body>
								</html>');
		
		if($this->email->send()){
			echo 'TRUE';
		}else {
			show_error($this->email->print_debugger());
			echo 'FALSE';
		}
	}
	
	
	
	
//Metodos para prueba de de insertar, eliminar, borrar, ver, datos mediante objeto JSON en android.


	function insertar() {
		
		$nombre = $this->input->post("nombre");
		$telefono = $this->input->post("telefono");
	
		$otro['miko'] = array('nombre' => $nombre,
					  'telefono' => $telefono);
	
		$this->db->insert('prueba', $otro);
		
		$this->load->view('insertar-view', $otro);
		
		
	}

	function cargarEstados() {
		
		$q = $this->db->query("SELECT estadoNombre FROM estadosUsuarios es 
								LEFT JOIN usuarios us ON es.usuarioId=us.usuarioId
								WHERE us.tipoUsuario='reparador'
								AND es.estadoNombre != ''
								GROUP BY estadoNombre");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		
		echo json_encode($data);
	
	}
	
	function cargarColonias($colonia) {
		
		//$estado = $this->input->post("estado");
		
		$colonia = urldecode($colonia);
	
		
		$q = $this->db->query("SELECT coloniaNombre FROM estadosUsuarios es
								LEFT JOIN usuarios us ON es.usuarioId=us.usuarioId
								WHERE us.tipoUsuario='reparador'
								AND es.estadoNombre != ''
								AND es.estadoNombre = '$colonia'
								GROUP BY coloniaNombre");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		
		echo json_encode($data);
		
	}
	
	function borrar() {
		
		$data = array('nombre' => 'Miko', 'blog' => 'Blog Hola Miko',
					  'nombre' => 'Hola', 'blog' => 'Blog nuevo');
		
		return print(json_encode($data));
	}
	
	function ver() {
		
		$data = array();
		
		$q = $this->db->query("SELECT * FROM prueba");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		 echo json_encode($data);
		
		
	}
	
	}
 

 
 
 
 
 
 
 
 
 
 
 
 
 