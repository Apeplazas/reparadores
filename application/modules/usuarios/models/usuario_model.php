<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Usuario_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();	
	}
	
	function buscaPerfilID($id,$buscarPor = "id"){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			u.*,eu.estadoNombre,eu.delegacionNombre,eu.codigoPostal,eu.coloniaNombre,eu.coordenadasGoogle,eu.latitud,eu.longitud
			FROM usuarios u 
			LEFT JOIN estadosUsuarios eu ON eu.usuarioId=u.usuarioId
			".(($buscarPor == 'id') ? "WHERE u.usuarioID='$id'" : null)			
			. (($buscarPor == 'email') ? "WHERE u.email='$id'" : null)
			. (($buscarPor != 'id' && $buscarPor != 'email') ? "WHERE u.urlPersonalizado='$id'" : null)
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
	}
		
	function tieneUbicacion($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			*
			FROM estadosUsuarios
			WHERE usuarioId='$usuarioId'
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	function cargaMensajes($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT m.*,u.nombreCompleto
			FROM mensajes m
			LEFT JOIN usuarios u ON u.usuarioId=m.usuarioDosId
			WHERE m.usuarioUnoId = '$usuarioId' or m.usuarioDosId = '$usuarioId' 
			AND m.estatus = 1 
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	function cargarArchivosMensaje($mensajeId){
			
		$data = array(); 
		$q = $this->db->query("SELECT
			* FROM archivosMensajes am
			WHERE am.mensajeId = '$mensajeId' 
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	
	function cargarMenLista($usuarioId){
			
		$data = array(); 
		$q = $this->db->query("SELECT * FROM mensajes m
								LEFT JOIN usuarios u ON m.usuarioDosId=u.usuarioId
								WHERE m.usuarioDosId='$usuarioId' OR m.usuarioUnoId='$usuarioId'
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}


	function cargarDetalleMensaje($mensajeId){
			
		$data = array(); 
		$q = $this->db->query("SELECT u.*,mr.*,m.asunto
			FROM mensajes m 
			LEFT JOIN mensajesRespuestas mr ON mr.mensajeId=m.mensajeId
			LEFT JOIN usuarios u ON u.usuarioId=mr.usuarioId
			WHERE m.mensajeId = '$mensajeId' 
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	/*
	 * Esta funcion regresa el otro usuario que esta participando en una conversacion
	 */
	function notificarA($usuarioId,$mensajeId){
			
		$data = array(); 
		$q = $this->db->query("SELECT *
			FROM usuarios
			WHERE usuarios.usuarioId = (SELECT
				if(m.usuarioUnoId = '$usuarioId',m.usuarioDosId,m.usuarioUnoId) as usuarioId
				FROM mensajes m
				WHERE m.mensajeId='$mensajeId')
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	function cargaNotificaciones($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT *
			FROM notificaciones n
			WHERE n.usuarioId = '$usuarioId' 
			AND n.estatus = 1
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	
	function cargarUsuarioTags($usuarioId){
				
		$data = array(); 
		$q = $this->db->query("SELECT h.*
			FROM habilidades h
			LEFT JOIN habilidadReparador hr ON hr.habilidadId = h.habilidadId
			WHERE hr.usuarioId = '$usuarioId'
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	function cargarConocimientos($usuarioId){
			
		$data = array(); 
		$q = $this->db->query("SELECT 
			uc.*,
			c.conocimiento,
			cc.categoriaNombre
			FROM usuariosConocimientosCategorias uc
			LEFT JOIN conocimientos c ON c.conocimientoId = uc.conocimientoId
			LEFT JOIN conocimientosCategorias cc ON cc.conocimientoId = c.conocimientoId
			WHERE uc.usuarioId = '$usuarioId'
			GROUP BY c.conocimientoId");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	function cargarTrabajos($usuarioId){
				
		$data = array(); 
		$q = $this->db->query("SELECT *
			FROM asignacionSolicitudes a
			WHERE a.usuarioId = '$usuarioId'
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	function cargaUsuario($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT *
			FROM usuarios u
			WHERE u.usuarioId = '$usuarioId'
		");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
}