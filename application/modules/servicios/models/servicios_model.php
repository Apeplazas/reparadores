<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicios_model extends CI_Model {
	
	function entrar($usuarioAlias) {
		
		$query = $this->db->query("SELECT contrasenia FROM usuarios WHERE email='$usuarioAlias'");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $riw){
				$pass = $riw->contrasenia;
			}
           	return $pass;
		}
        else
        	return 'FALSE';   
	}
	
	//------------------------------------------------------------------------
	
	function extraerDatos($usuarioAlias) {
		
		$data = array();
		
		$query = $this->db->query("SELECT contrasenia, usuarioId, telefono, email, fotografiaPerfil, nombreCompleto FROM usuarios WHERE urlPersonalizado='$usuarioAlias'");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $riw){
				$data[] = $riw;
			}
           	$query->free_result();  	
		}
		return $data;  
	}
	
	function obtenerIdMensaje($idUsuario, $idReparador, $mensaje) {
		
		$datos = array(
							"usuarioUnoId" => $idUsuario,
							"usuarioDosId" => $idReparador,
							"asunto" => "Mensaje desde Android",
							"mensajeTipo" => "1");
							
		$this->db->insert('mensajesSoria', $datos);
		$mensajeId = $this->db->insert_id();
		//echo $mensajeId;
							
		try {
			
			
			$otrosDatos = array( 
									"respuesta" => $mensaje,
									"mensajeId" => $mensajeId,
									"usuarioId" => $idUsuario);
			$this->db->insert('mensajesRespuestasSoria', $otrosDatos);
			
			
		}catch(Exception $e){
			echo $e;
		}
		
	}
	
	function buscar($nombre) {
		
		$data[] = array();
		
		$q = $this->db->query("SELECT * FROM usuarios WHERE tipoUsuario='$nombre'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		return $data;
		
	}
	
	//Funcion para validar campos del registro. 
	function validarEmail($email) {
							   
		$query = $this->db->query("SELECT email
							   FROM usuarios WHERE email='$email'");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $riw){
				$pass = $riw->email;
			}
           	return $pass;
		}
        else
        	return 'FALSE';   
	}
	
	function validarUsuario($usuario) {
							   
		$query = $this->db->query("SELECT urlPersonalizado
							   FROM usuarios WHERE urlPersonalizado='$usuario'");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $riw){
				$pass = $riw->urlPersonalizado;
			}
           	return $pass;
		}
        else
        	return 'FALSE';   
	}
	
	
//Funciones para mostrar la lista y los datos de los reparadores.
	function buscaReparadoresNombre($estado, $colonia) {
		$data = array();
		
		$q = $this->db->query("SELECT urlPersonalizado, coloniaNombre 
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
		return $data;
	}

	function datosReparador($id) {
		//$data = array();
		
		$q = $this->db->query("SELECT u.nombreCompleto,
			if(u.celular is null OR u.celular = '0',u.telefono,u.celular) as telefono,
			u.email,u.fotografiaPerfil,u.bio
			FROM usuarios u WHERE u.usuarioId='$id'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		return $data;
	}
	
	function datosHabilidadReparador($id) {
		$data = array();
		$q = $this->db->query("SELECT cc.categoriaNombre
			FROM usuariosConocimientosCategorias acc 
			LEFT JOIN conocimientosCategorias cc ON cc.categoriaId=acc.categoriaId
			WHERE acc.usuarioId='$id'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();  	
		}
		return $data;
	}

//-----------------------------------------------------------------------------


	
	//]Funciones para probar coneccion de a DB mediante JSON
	function insertar($nombre, $telefono) {
		$datos[] = array(
							"nombre" => $nombre,
							"telefono" => $telefono);
							
		$this->db->insert('prueba', $datos);
	}
	
}