<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	
	function cargarDatosUsuario($usuarioId,$tipoUsuario){
		
		$data = array();
		if($tipoUsuario == 'usuario' || $tipoUsuario == 'mixto'){
			 
			$q = $this->db->query("SELECT * FROM solicitudReparaciones WHERE usuarioId='$usuarioId'");
			if($q->num_rows() > 0){
			
				foreach($q->result() as $row){
					
					$data[] = $row;
					
				}
				
				$q->free_result();
			  	
			}
			
		}elseif($tipoUsuario == 'reparador' || $tipoUsuario == 'mixto'){
			
			$q = $this->db->query("SELECT 
				sr.* 
				FROM solicitudReparaciones sr
				LEFT JOIN postulados p ON p.solicitudId=sr.solicitudId
				WHERE p.usuarioId='$usuarioId'");
			if($q->num_rows() > 0){
			
				foreach($q->result() as $row){
					
					$data[] = $row;
					
				}
				
				$q->free_result();
			  	
			}	
		
		}
								
		
		return $data;
		
	}
    
}