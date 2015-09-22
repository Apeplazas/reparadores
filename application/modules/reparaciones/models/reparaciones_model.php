<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reparaciones_model extends CI_Model{
	
	public function __construct(){
		
		parent::__construct();	
		
	}
	
	function detalleReparacion($solicitudId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			* FROM solicitudReparaciones sr 
			LEFT JOIN conocimientosCategorias cc ON cc.categoriaId=sr.categoriaId
			WHERE sr.solicitudId='$solicitudId'");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function postuladosReparacion($solicitudId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			* FROM postulados p 
			WHERE p.solicitudId='$solicitudId'");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function archivosSolicitud($solicitudId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			* FROM fotografias
			WHERE solicitudId='$solicitudId'");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargaAsignacionSolicitud($solicitudId){
			
		$data = array(); 
		$q = $this->db->query("SELECT 
			* FROM asignacionSolicitudes
			WHERE solicitudId='$solicitudId'");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;	
		
	}
	
}