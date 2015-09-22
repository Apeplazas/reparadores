<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Solicita_tu_reparacion_model extends CI_Model
{
	
	public function __construct(){
		
		parent::__construct();
			
	}
	
	function cargaCategoria($categoriaId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			*
			FROM conocimientos 
			WHERE conocimientoId='$categoriaId'"
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	function cargaSubcategoria($subcategoriaId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			*
			FROM conocimientosCategorias 
			WHERE categoriaId='$subcategoriaId'"
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
}