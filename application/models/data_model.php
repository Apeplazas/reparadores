<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_model extends CI_Model {
	
	function is_logged_in(){
		
        $user = $this->session->userdata('usuario');
        if(!isset($user) || $user != true)
        {
         	redirect('');
        }
		
    }
	
	function cargarOptimizacion($opt){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
								s.metaTitulo, s.metaDescripcion , s.microformatos
								FROM enlaces s 
								WHERE url='$opt'
								");
								
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarMenuHeader(){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM enlaces 
								WHERE tipoMenu='header'
								AND enlaceEstatus='1'
								");
								
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarConocimientos(){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM conocimientos");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarConocimientosUsuarioCatalogo($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM conocimientos 
								WHERE conocimientoId NOT IN 
								(SELECT conocimientoId 
									FROM usuariosConocimientosCategorias 
									WHERE usuarioId='$usuarioId')
							");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarConocimientosSelect($arr){
		$data = array(); 
		$q = $this->db->query("SELECT * FROM conocimientos WHERE conocimientoId NOT IN ($arr)");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarConocimientosHome(){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM conocimientos where url!='otros_electronicos' ");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargarConID($usuarioId){
		$data = array(); 
		$q = $this->db->query("SELECT 
								c.conocimiento as conocimiento,
								c.conocimientoId as conocimientoId
							  FROM usuariosConocimientosCategorias ucc
							  LEFT JOIN conocimientos c ON c.conocimientoId=ucc.conocimientoId
							  WHERE ucc.usuarioId='$usuarioId'
							  GROUP BY conocimiento
							  ");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
	}
	
	function cargarCatID($conocimientoId, $usuarioId){
		$data = array(); 
		$q = $this->db->query("SELECT
								c.categoriaNombre as categoriaNombre,
								c.categoriaId as categoriaId
								FROM usuariosConocimientosCategorias u
								LEFT JOIN conocimientosCategorias c ON c.categoriaId=u.categoriaId
								WHERE u.usuarioId='$usuarioId'
								AND c.conocimientoId='$conocimientoId'
							  ");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;
	}
	
	function numero_mensajes($user_id){
		$data = array(); 
		$q = $this->db->query("SELECT 
			count(leido) as total 
			FROM notificaciones 
			WHERE usuarioid=$user_id 
			AND leido=0");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data = $row->total;
			}
			$q->free_result();	
		}
		return $data;
	}
	
	function cargarUsuarioHabilidades($usuarioId){
				
		$data = array(); 
		$q = $this->db->query("SELECT
								h.habilidad,h.habilidadId
								FROM habilidades h
								LEFT JOIN habilidadReparador hr ON hr.habilidadId=h.habilidadId
								WHERE hr.usuarioId='$usuarioId'
							  ");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			$q->free_result();
			  	
		}
		
		return $data;	
		
	}
	
	function cargaSubcategorias($categoria,$buscarPor = "url"){
		
		$data = array(); 
		$q = $this->db->query("select cc.*
			FROM conocimientos c 
			LEFT JOIN conocimientosCategorias cc ON cc.conocimientoId=c.conocimientoId
			" . (($buscarPor == "url") ? "WHERE c.url='$categoria'" : "WHERE c.conocimientoId='$categoria'")
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	function cargaEstados(){
			
		$data = array(); 
		$q = $this->db->query("SELECT 
			distinct(eu.estadoNombre) as 'estados' 
			FROM estadosUsuarios eu
			LEFT JOIN usuarios u ON u.usuarioId=eu.usuarioId
			WHERE u.tipoUsuario = 'reparador'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargaCordenadasUsuario($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
			*
			FROM estadosUsuarios eu 
			WHERE eu.usuarioId='$usuarioId'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
			  	
		}
		
		return $data;
		
	}
	
	function cargaRaparadoresTodos(){

		$data = array(); 
		$q = $this->db->query("SELECT 
			u.*,
			eu.latitud,eu.longitud,eu.coordenadasGoogle
			FROM usuarios u
			LEFT JOIN estadosUsuarios eu ON eu.usuarioId = u.usuarioId
			LEFT JOIN usuariosConocimientosCategorias uc ON uc.usuarioId=u.usuarioId
			LEFt JOIN conocimientosCategorias cc ON cc.categoriaId=uc.categoriaId
			WHERE (u.tipoUsuario ='reparador' or u.tipoUsuario ='mixto')
			AND eu.coordenadasGoogle IS NOT NULL GROUP BY u.usuarioId"
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;	
		
	}
	
	function cargarReparadoresPorConsulta($consulta = false){
			
		//Decodificar url ya que toma los parametros de ahi
		if($consulta)
			$consulta = urldecode($consulta);

		$data = array(); 
		$q = $this->db->query("SELECT 
			u.*,
			eu.latitud,eu.longitud,eu.coordenadasGoogle,
			c.*
			FROM usuarios u
			LEFT JOIN estadosUsuarios eu ON eu.usuarioId = u.usuarioId
			LEFT JOIN usuariosConocimientosCategorias uc ON uc.usuarioId=u.usuarioId
			LEFT JOIN conocimientosCategorias cc ON cc.categoriaId=uc.categoriaId
			LEFT JOIN conocimientos c ON c.conocimientoId=cc.conocimientoId" . 
			($consulta ? $consulta : "")
		);
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();  	
		}
		
		return $data;
		
	}
	
	function cargaRaparadoresPorDistacias($latDist,$logDist,$dist = false,$subcat = false,$estado = false){

		$condicion	= ($subcat || $estado) ? true : false;
		$conjuncion = ($subcat && $estado) ? true : false;

		$data = array(); 
		$q = $this->db->query("SELECT 
			d.coordenadasGoogle,( (2*atan2(sqrt(a),sqrt(1-a)) ) * 6371 ) as dist,
			u.*
			FROM(
				SELECT eu.usuarioId,eu.coordenadasGoogle,eu.estadoNombre, ( power(sin((eu.latitud - '$latDist')/2),2) +
					cos('$latDist') * cos(eu.latitud) *
					power(sin((eu.longitud - '$logDist')/2),2) ) as a
					FROM estadosUsuarios eu
			) d
			LEFT JOIN usuarios u ON u.usuarioId=d.usuarioId
			LEFT JOIN usuariosConocimientosCategorias uc ON uc.usuarioId=u.usuarioId
			LEFt JOIN conocimientosCategorias cc ON cc.categoriaId=uc.categoriaId" . 
			($condicion 	? " WHERE (u.tipoUsuario ='reparador' or u.tipoUsuario ='mixto') AND coordenadasGoogle IS NOT NULL AND" : " WHERE (u.tipoUsuario ='reparador' or u.tipoUsuario ='mixto') AND coordenadasGoogle IS NOT NULL") .
			($subcat		? " cc.url='$subcat'" : "") .
			($conjuncion 	? "AND" : "") .
			($estado 		? " d.estadoNombre='$estado'" : "") .
			($dist			? " HAVING dist<='$dist' ORDER BY dist ASC" : ""));
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
		}
		
		return $data;	
		
	}

	function validarLogin($usuarioOEmail, $contrasenia){
			
		$data = array();
		
		//Verificar si usuario o email existen
		$verificaEmail = $this->db->query("SELECT * FROM usuarios
        							WHERE urlPersonalizado='$usuarioOEmail' 
        							OR email='$usuarioOEmail'
        							");
        
		if($verificaEmail->num_rows() > 0){
			
            $verificaEmail->free_result();
		
			//si todo va bien, creamos el md5 del pwd.
        	$contraseniaCodi = md5($contrasenia);
				
			$query = $this->db->query("SELECT * FROM usuarios
        							WHERE (urlPersonalizado='$usuarioOEmail' OR email='$usuarioOEmail') 
        							AND contrasenia='$contraseniaCodi'
        							");
									 
			if($query->num_rows() > 0) {
				
            	foreach($query->result() as $row){
                	$data[] = $row;
            	}
            	$query->free_result(); 
			
			 	return $data;	 	
				
        	}else{
        		
        		$data['error'] = "Contraseña Incorrecta.";
        		return $data;
				
        	}
				
        }else{
        	
        	$data['error'] = "Usuario o email invalido.";
        	return $data;
			
        }

	}

	function existeTag($tag){

		$data = array(); 
		$q = $this->db->query("SELECT habilidadId FROM habilidades WHERE habilidad='$tag'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
		}
		
		return $data;	
		
	}
	
	function cargarSolicitudesUsuario($usuarioId){
		
		$data = array(); 
		$q = $this->db->query("SELECT * FROM solicitudReparaciones WHERE usuarioId='$usuarioId'");
		
		if($q->num_rows() > 0) {
			
			foreach($q->result() as $row){
				
				$data[] = $row;
				
			}
			
			$q->free_result();
		}
		
		return $data;
		
	}
	
	function buscaObjeto($array, $key, $val) {
		
		foreach ($array as $genKey => $item)
			if (isset($item->$key) && $item->$key == $val)
				return $genKey;
		return false;
	}

	function guardaUrl(){
		
		$usuario = $this->session->userdata('usuario');
        if(!isset($usuario) || $usuario != true){
        	$parms = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1);
			$parms = ($parms) ? '?'.$parms : null;
        	$this->session->set_userdata( array( 'previous_page'=> uri_string().$parms ) );
		}
		
	}
			
}	