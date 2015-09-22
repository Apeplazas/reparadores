<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Avisosdeocacion_model extends CI_Model {
	
	function cargaSolicitudesReparacion(){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM 
								solicitudReparaciones s
								LEFT JOIN usuarios u ON u.usuarioid=s.usuarioid
								WHERE s.estatus = 'Abierta'
								ORDER BY fechaSolicitud DESC");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
}