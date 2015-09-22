<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registro_model extends CI_Model {
	
	function confirmaEmail($admEmail){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM usuarios WHERE email='$admEmail'
								");
		if($q->num_rows() > 0){
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function confirmaUrl($usuarioAlias){
		
	    $q = $this->db->query("SELECT * FROM usuarios WHERE urlPersonalizado='$usuarioAlias'");
			
		if($q->num_rows() > 0)
           	return TRUE;
        else
        	return FALSE;    
        
	}
	
	function activarUsuario($hashActivacion){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			* 
			FROM usuarios 
			WHERE hashActivacion='$hashActivacion'
		");
								
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result(); 	
		}
		
		return $data;
	}
    	
    function validarNombre($var, $password)
    {
        $data = array();
        //si no existen los datos regresamos false
        if(empty($var) || empty($password) || !isset($var) || !isset($password) )
            return false;
        
        //si todo va bien, creamos el md5 del pwd.
        $passwordShai = md5($password);
        
        //ejecutamos la consulta
        $query = $this->db->query("SELECT * FROM usuarios
        							WHERE fancyUrl='$var' 
        							AND hash='$passwordShai'
        							");
        
		if($query->num_rows() > 0) 
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            $query->free_result(); 
			
			 return $data;	 	
        }else{
        	return FALSE;
        }
       	
    }
    
        
    function validarEmail($var, $password)
    {
        $data = array();
        //si no existen los datos regresamos false
        if(empty($var) || empty($password) || !isset($var) || !isset($password) )
            return false;
        
        //si todo va bien, creamos el md5 del pwd.
        $passwordShai = md5($password);
        
        //ejecutamos la consulta
        $query = $this->db->query("SELECT * FROM proveedores
        							WHERE emailAdmin='$var' 
        							AND contrasenia='$passwordShai'
        							");
        
		if($query->num_rows() > 0) 
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            $query->free_result(); 
			
			 return $data;	 	
        }else{
        	return FALSE;
        }
       	
    }
	
	function existeUsuario($facebookemail){
		
		$data = array();
        
        //ejecutamos la consulta
        $query = $this->db->query("SELECT 
        	* 
        	FROM usuarios
			WHERE email='$facebookemail' 
		");
        
		if($query->num_rows() > 0) {
			
            foreach($query->result() as $row){
            	
                $data[] = $row;
				
            }
			
            $query->free_result(); 
			
			return $data;
				 	
        }else{
        	
        	return FALSE;
			
        }
		
	}
    
    function estados(){
		$data = array(); 
        $q = $this->db->query("SELECT 
        						claveEstado AS idEstado,
        						nombreEstado AS nombreEstado 
        					   FROM estadosMexico 
        					   GROUP BY claveEstado 
        					   ORDER BY claveEstado
        					   ");
        if($q->num_rows() > 0) 
        {
        	foreach($q->result() as $row)
        	{
        		$data[] = $row;
        	}
            $q->free_result();  	
        }
        return $data;
	}
    
}