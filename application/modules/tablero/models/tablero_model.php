<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablero_model extends CI_Model {
	

	
	function cuentaUsuarios($tipo){
		
		$data = array(); 
		$q = $this->db->query("SELECT count(*) as cuenta from usuarios where tipoUsuario='$tipo'");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}
	
	function calculaUsuariosEstados($estado,$tipo){
		
		$data = array(); 
		$q = $this->db->query("SELECT count(e.estadoNombre) usuarios
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estado'
								and u.tipoUsuario='$tipo'
								and e.coordenadasGoogle !=''
								and u.email !=''
								and u.email !='0'
								and u.nombreCompleto !=''
								
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}
	
	function calculaPorcentages($estadoNombre)
	{
		$data = array(); 
		$q = $this->db->query("SELECT count(e.estadoNombre) as 'usuarios',
								(
								SELECT count(e.estadoNombre) as 'reparadores'
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estadoNombre'
								and u.tipoUsuario='reparador'
								and e.coordenadasGoogle !=''
								and u.email !=''
								and u.email !='0'
								and u.nombreCompleto !='') as rep
								
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estadoNombre'
								and u.tipoUsuario='usuario'
								and e.coordenadasGoogle !=''
								and u.email !=''
								and u.email !='0'
								and u.nombreCompleto !=''
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;
	}
	
	function calculaUsuariosEstadosHoy($del, $al, $estado,$tipo){
		
		$data = array(); 
		$q = $this->db->query("SELECT count(e.estadoNombre) usuarios
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estado'
								and u.tipoUsuario='$tipo'
								and e.coordenadasGoogle !=''
								and u.email !=''
								and u.email !='0'
								and u.nombreCompleto !=''
								AND u.fechaRegistro BETWEEN '$del' AND '$al'
								
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}
	
	function calculaUsuariosTotalesHoy($del, $al, $estado){
		
		$data = array(); 
		$q = $this->db->query("SELECT count(e.estadoNombre) usuarios
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estado'
								and e.coordenadasGoogle !=''
								and u.email !=''
								and u.email !='0'
								and u.nombreCompleto !=''
								AND u.fechaRegistro BETWEEN '$del' AND '$al'
								
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}
	
	
	
	function calculaComentariosEstado($del, $al, $estado)
	{
		$data = array(); 
		$q = $this->db->query("SELECT count(*) as cuenta FROM solicitudReparaciones s
								LEFT JOIN usuarios u ON u.usuarioId=s.usuarioId
								LEFT JOIN estadosUsuarios e ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estado'
								AND s.fechaSolicitud BETWEEN '$del' AND '$al'
								
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
		
	}
	
	function calculaComentariosFecha($del, $al, $estado)
	{
		$data = array(); 
		$q = $this->db->query("SELECT count(*) as cuenta FROM solicitudReparaciones s
								LEFT JOIN usuarios u ON u.usuarioId=s.usuarioId
								LEFT JOIN estadosUsuarios e ON e.usuarioId=u.usuarioId
								WHERE e.estadoNombre='$estado'
								AND s.fechaSolicitud BETWEEN '$del' AND '$al'
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
		
	}
	
	
	function muestraEstados(){
		
		$data = array(); 
		$q = $this->db->query("SELECT 
								e.estadoNombre as estadoNombre,
								count(e.estadoNombre) calculo
								from estadosUsuarios e
								left join usuarios u ON e.usuarioId=u.usuarioId
								where estadoNombre != ''
								and e.estadoNombre != '0'
								and e.coordenadasGoogle !=''
								and e.estadoNombre != 'Arizona'
								and e.estadoNombre != 'New York'
								and e.estadoNombre != 'ILLINOIS'
								and e.estadoNombre != 'MICHIGAN'
								and e.estadoNombre != 'CALIFORNIA'
								and e.estadoNombre != 'District Of Columbia'
								and e.estadoNombre != 'Georgia'
								and e.estadoNombre != 'Kansas'
								and e.estadoNombre != 'MICHIGAN'
								and e.estadoNombre != 'Texas'
								and e.estadoNombre != 'Virginia'
								and e.estadoNombre != 'RIO DE JANEIRO'
								and u.nombreCompleto !=''
								and u.email !=''
								and u.email !='0'
								group by e.estadoNombre 
								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}
	
	function calculaUsuariosReparadores(){
		
		$data = array(); 
		$q = $this->db->query("select distinct(final.estadoNombre),IfNull(table1.usuarios, 0) as usuarios, IfNull(table2.reparadores, 0) as reparadores FROM
( SELECT * FROM
 (SELECT 
        UPPER(e.estadoNombre) estadoNombre
        from estadosUsuarios e
        left join usuarios u ON e.usuarioId=u.usuarioId
        where estadoNombre != ''
        and e.estadoNombre != '0'
        and e.coordenadasGoogle !=''
        and e.estadoNombre != 'Arizona'
        and e.estadoNombre != 'New York'
        and e.estadoNombre != 'ILLINOIS'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'CALIFORNIA'
        and e.estadoNombre != 'District Of Columbia'
        and e.estadoNombre != 'Georgia'
        and e.estadoNombre != 'Kansas'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'Texas'
        and e.estadoNombre != 'Virginia'
        and e.estadoNombre != 'RIO DE JANEIRO'
        and u.tipoUsuario ='reparador'
        group by e.estadoNombre ) a 
    UNION ALL select * from  
    (SELECT 
        UPPER(e.estadoNombre) estadoNombre
        from estadosUsuarios e
        left join usuarios u ON e.usuarioId=u.usuarioId
        where estadoNombre != ''
        and e.estadoNombre != '0'
        and e.coordenadasGoogle !=''
        and e.estadoNombre != 'Arizona'
        and e.estadoNombre != 'New York'
        and e.estadoNombre != 'ILLINOIS'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'CALIFORNIA'
        and e.estadoNombre != 'District Of Columbia'
        and e.estadoNombre != 'Georgia'
        and e.estadoNombre != 'Kansas'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'Texas'
        and e.estadoNombre != 'Virginia'
        and e.estadoNombre != 'RIO DE JANEIRO'
        and u.tipoUsuario ='usuario'
        group by e.estadoNombre ) b) as final
LEFT JOIN
(SELECT 
        UPPER(e.estadoNombre) estadoNombre,
        count(e.estadoNombre) usuarios
        from estadosUsuarios e
        left join usuarios u ON e.usuarioId=u.usuarioId
        where estadoNombre != ''
        and e.estadoNombre != '0'
        and e.coordenadasGoogle !=''
        and e.estadoNombre != 'Arizona'
        and e.estadoNombre != 'New York'
        and e.estadoNombre != 'ILLINOIS'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'CALIFORNIA'
        and e.estadoNombre != 'District Of Columbia'
        and e.estadoNombre != 'Georgia'
        and e.estadoNombre != 'Kansas'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'Texas'
        and e.estadoNombre != 'Virginia'
        and e.estadoNombre != 'RIO DE JANEIRO'
        and u.tipoUsuario ='usuario'
        group by e.estadoNombre ) table1 on final.estadoNombre = table1.estadoNombre
LEFT JOIN
(SELECT 
        UPPER(e.estadoNombre) estadoNombre,
        count(e.estadoNombre) reparadores
        from estadosUsuarios e
        left join usuarios u ON e.usuarioId=u.usuarioId
        where estadoNombre != ''
        and e.estadoNombre != '0'
        and e.coordenadasGoogle !=''
        and e.estadoNombre != 'Arizona'
        and e.estadoNombre != 'New York'
        and e.estadoNombre != 'ILLINOIS'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'CALIFORNIA'
        and e.estadoNombre != 'District Of Columbia'
        and e.estadoNombre != 'Georgia'
        and e.estadoNombre != 'Kansas'
        and e.estadoNombre != 'MICHIGAN'
        and e.estadoNombre != 'Texas'
        and e.estadoNombre != 'Virginia'
        and e.estadoNombre != 'RIO DE JANEIRO'
        and u.tipoUsuario ='reparador'
        group by e.estadoNombre ) table2 on final.estadoNombre = table2.estadoNombre;								");
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;	
			}
			$q->free_result();	  	
		}
		return $data;	
	}	
			
}	