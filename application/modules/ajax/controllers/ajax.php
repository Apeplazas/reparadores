<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	function muestraSubcategorias(){
		
		$referencia		= $_POST['referencia'];
		$mostrarPor		= $_POST['mostrarPor'];
		$buscategorias 	= $this->data_model->cargaSubcategorias($referencia,$mostrarPor);
		echo json_encode($buscategorias);
		exit();
		
	}
	
	function agregarConocimiento(){
		
		$conocimientoId	= $_POST['conocimientoId'];
		$subcatId		= $_POST['subcatId'];
		$usuarioId		= $_POST['usuarioId'];
		
		$datos = array(
		 	'usuarioId' 		=> $usuarioId,
			'categoriaId'		=> $subcatId,
		 	'conocimientoId'	=> $conocimientoId
		);
		$this->db->insert('usuariosConocimientosCategorias', $datos);
		
		exit();
		
	}
	
	function buscaHabilidades(){
		
		$filtro = strtolower($_POST['q']);

		$destinos = $this->db->query("SELECT
										habilidad as 'habilidad'
										FROM habilidades
										WHERE lower(habilidad) like  '%$filtro%'
										");

		$data = array();
		foreach($destinos->result() as $row){
			$data[] = utf8_encode(trim($row->habilidad));
		}
		
		echo json_encode($data);
		exit();

	}
	
	function guardaUrl(){
		
		$urlAnterior = $_POST['urlAnterior'];
		
		$this->session->set_userdata(array('previous_page'=> $urlAnterior));
		
	}
	
	function actualizaImagenPerfil(){
		
		if( isset($_FILES['archivos']) && !empty($_FILES['archivos']) ){
			
			$permitidos =  array('gif','png','jpg','pdf');
			
			foreach($_FILES['archivos']['name'] as $key => $val){
				
				$archivoNombre	= $val;
				$archivoTipo	= $_FILES['archivos']['type'][$key];
				$tamanoH		= $_FILES['archivos']['size'][$key];
				
				$ext = pathinfo($archivoNombre, PATHINFO_EXTENSION);			
	
				if(in_array($ext,$permitidos) ) {
					
	    			move_uploaded_file($_FILES['archivos']['tmp_name'][$key],DIRARCHIVOS.$archivoNombre);
	    			$data = array(			
						'fotografiaPerfil'	=> $archivoNombre
					);
					
					$this->db->where('usuarioId', $key);
        			$this->db->update('usuarios', $data);
					
				}	
				
			}
		}	
		
		
	}
	
	function borrarConocimiento(){
		
		$conocimientoDatos = $_POST['conocimientoDatos'];
		$conocimientoDatos = explode('-', $conocimientoDatos);
		
		$datos = array(
			'usuarioId' 		=> $conocimientoDatos[0],
			'categoriaId'		=> $conocimientoDatos[2],
		 	'conocimientoId'	=> $conocimientoDatos[1]
		);
		
		$this->db->where($datos);
   		$this->db->delete('usuariosConocimientosCategorias'); 
		exit();
		
	}
	
	function borrarTag(){
		
		$tagId 		= $_POST['tagId'];
		$usuario	= $this->session->userdata('usuario');
		$usuarioId	= $usuario['usuarioID'];
		
		$datos = array(
			'usuarioId'		=> $usuarioId,
			'habilidadId'	=> $tagId
		);
		
		$this->db->where($datos);
   		$this->db->delete('habilidadReparador'); 
		exit();
		
	}
	
	function cargarSubcategoria()
	{
		
		$filtro = strtolower($_POST['filtro']);

		$sc = $this->data_model->cargaSubcategorias($filtro,'id');

		$lista_opciones = '<option value="" selected disabled>¿Que necesitas que se repare?</option>';
		
		foreach($sc as $row){
			$row->categoriaNombre = utf8_encode(trim($row->categoriaNombre));
			$row->categoriaId = utf8_encode(trim($row->categoriaId));
			
			$lista_opciones .= "<option value='".$row->categoriaId."'>".$row->categoriaNombre."</option>";
		}
		
		echo $lista_opciones;	
		
	}
	
	function verificaUrl()
	{
		$url       = $_POST['filtro'];
		$base = base_url();
		$fancyUrl = strtolower(str_replace(" ", "_", $url));
		
		$this->load->model('registro/registro_model');
		$verificado = $this->registro_model->confirmaUrl($url);
		
		if (!$verificado){
			echo $base,$fancyUrl;
		}
		else{
			$aviso = "<div class='msgAler'><img src='$base/assets/graphics/alert.png' />Este alias ya ha sido asignado</div>";
				 
			echo $aviso;
		}
	}
	
	function insertarSolicitudCotizacion(){
			
		$datos			= explode('-',$_POST['datos']);
		
		$solicitudId 	= $datos[0];
		$usuarioId		= $datos[1];
		$reparadorId 	= $_POST['reparadorID'];
		$reparadorDatos = $this->usuario_model->cargaUsuario($reparadorId);
		$url			= base_url() . 'avisosdeocacion/detalle/' . $solicitudId;
		
		$notificacion = "Te han solicitado una cotización " . $reparadorDatos[0]->nombreCompleto;
		$notificacionEmail = "Te han solicitado una cotización " . $reparadorDatos[0]->nombreCompleto . ", para más información ingresa <a href='" . $url . "'>aquí</a>";
		
		$datos = array(
		 	'usuarioId' 		=> $reparadorId,
			'notificacion'		=> $notificacion,
		 	'url'				=> $url
		);
/*
		$this->db->insert('notificaciones', $datos);
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('contacto@reparadores.mx', 'Reparadores.mx');
		$this->email->to($reparadorDatos[0]->email);
		$this->email->subject('Solicitud de Cotización Reparadores.mx');		
		$this->email->message('
			<html>
				<head>
					<meta charset="utf-8">
					<title>Solicitud de Cotización</title>
				<style>
					h1, h2{font-size:20px; font-weight:700; margin:25px 0 3px}
					body{font-family:helvetica,arial,sans-serif; background-color:#f4f5f7; font-size:.8em; line-height:20px; color:#555}
					.wrap{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #999}
					.wrapTwo{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #999}
					a{color:#c30}
					#regEmail{display:inline; width:200px; margin:40px auto; height:28px; text-align:center; background-color: #B81C2D; color:#fff; text-transform:uppercase; font-size:16px; padding:12px 30px 9px; border-radius:6px; text-decoration:none;}
					li{list-style:none}
					ul{margin:0; padding:0}
					#foot{color:#777; font-size:10px;b order-top:1px solid #ccc; padding:10px 0}
					em{color:#c30; font-style: normal}
				</style>
				</head>
				<body style="background-color:#eee;">
				<table cellpadding="40" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px font-family:helvetica,arial,sans-serif; border:1px solid #ccc; color:#555; margin-top:30px;">
				  <tr>
				    <td>
				    <div class="wrap">
				    <a href="http://reparadores.mx"><img src="http://reparadores.mx/assets/graphics/reparadores-logoNegro.jpg" alt="Reparadores" /></a>
				    <h1>Hola '.$reparadorDatos[0]->nombreCompleto.'!</h1>
				    <p>' . $notificacionEmail . '<p>
				    </div>
				    </td>
				  </tr>
				</table>
				</body>
				</html>
				');
		$this->email->send();		
*/		
		exit;
		
	}
	
	function imageTemp()
	{
		    $imagePath = "temp/";
		
			$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
			$temp = explode(".", $_FILES["img"]["name"]);
			$extension = end($temp);
		
			if ( in_array($extension, $allowedExts))
			  {
			  if ($_FILES["img"]["error"] > 0)
				{
					 $response = array(
						"status" => 'error',
						"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
					);
					echo "Return Code: " . $_FILES["img"]["error"] . "<br>";
				}
			  else
				{
					
				  $filename = $_FILES["img"]["tmp_name"];
				  list($width, $height) = getimagesize( $filename );
		
				  move_uploaded_file($filename,  $imagePath . $_FILES["img"]["name"]);
		
				  $response = array(
					"status" => 'success',
					"url" => $imagePath.$_FILES["img"]["name"],
					"width" => $width,
					"height" => $height
				  );
				  
				}
			  }
			else
			  {
			   $response = array(
					"status" => 'error',
					"message" => 'something went wrong',
				);
			  }
			  
			  print json_encode($response);
	}
	
	function imageCropper()
	{
		$imgUrl   = $_POST['imgUrl'];
		$imgInitW = $_POST['imgInitW'];
		$imgInitH = $_POST['imgInitH'];
		$imgW     = $_POST['imgW'];
		$imgH     = $_POST['imgH'];
		$imgY1    = $_POST['imgY1'];
		$imgX1    = $_POST['imgX1'];
		$cropW    = $_POST['cropW'];
		$cropH    = $_POST['cropH'];
		
		$jpeg_quality = 100;
		
		$output_filename = "assets/graphics/fotoPerfil/croppedImg_".rand();
		
		$what = getimagesize($imgUrl);
		switch(strtolower($what['mime']))
		{
		    case 'image/png':
		        $img_r = imagecreatefrompng($imgUrl);
				$source_image = imagecreatefrompng($imgUrl);
				$type = '.png';
		        break;
		    case 'image/jpeg':
		        $img_r = imagecreatefromjpeg($imgUrl);
				$source_image = imagecreatefromjpeg($imgUrl);
				$type = '.jpeg';
		        break;
		    case 'image/gif':
		        $img_r = imagecreatefromgif($imgUrl);
				$source_image = imagecreatefromgif($imgUrl);
				$type = '.gif';
		        break;
		    default: die('image type not supported');
		}
			
			$resizedImage = imagecreatetruecolor($imgW, $imgH);
			imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, 
						$imgH, $imgInitW, $imgInitH);	
			
			
			$dest_image = imagecreatetruecolor($cropW, $cropH);
			imagecopyresampled($dest_image, $resizedImage, 0, 0, $imgX1, $imgY1, $cropW, 
						$cropH, $cropW, $cropH);	
		
		
			imagejpeg($dest_image, $output_filename.$type, $jpeg_quality);
			
			$user	=	$this->session->userdata('usuario');
			
			if (isset($user))
			{
				$data = array(
	               'fotografiaPerfil' => $output_filename.$type
	            );
		        $this->db->where('usuarioID', $user['usuarioID']);
		        $this->db->update('usuarios', $data);
		    }
		    
			$response = array(
					"status" => 'success',
					"url" => $output_filename.$type 
				  );
			 print json_encode($response);
			}
	
	function guardarBio()
	{
		//Genera informacion perfil//
		$user     = $this->session->userdata('usuario');
		$bio     = $_POST['filtro'];
		
		if ($bio){
				
			$data = array( 'bio' => $bio );
			$this->db->where('usuarioID', $user['usuarioID']);
			$this->db->update('usuarios', $data);
			
			echo '<img src="http://reparadores.mx/assets/graphics/palomitaForm.png" alt="Saved" />';
		}
		else{
			echo '<img src="http://reparadores.mx/assets/graphics/tacheForm.png" alt="Error" />';
		}
	
	}
	
	function test()
	{
		$catID	= $_POST['filtro'];
		$cat = implode(",", $catID);
		$op['cat'] = $this->data_model->cargarConocimientosSelect($cat);
			
		$this->load->view('agregaCat-view', $op);
	}
	
	function actualizaHab()
	{
		//Genera informacion perfil//
		$user     = $this->session->userdata('usuario');
		
		$subCat       = $_POST['subCat'];
		$cat          = $_POST['cat'];
		$usuarioID    = $user['usuarioID'];
		
		foreach ($subCat as $value){
			if ($value > 0){
				$query	= $this->db->query("SELECT * FROM usuariosConocimientosCategorias WHERE usuarioId='$usuarioID' AND categoriaId ='$value'");
				
				if($query->num_rows() == '' ){
		        $data = array(
		               'usuarioId'		=> $user['usuarioID'],
		               'conocimientoId'		=> $cat,
		               'categoriaId'	=> $value 
		            );
				$this->db->insert('usuariosConocimientosCategorias', $data);
				} 
			}
		}
		$op['cat'] = $this->data_model->cargarConID($user['usuarioID']);
		
		$this->load->view('vistaCategorias-view', $op);
		
	}
	
	function actualizaDatosPerfil(){
		
		$usuario			= $this->session->userdata('usuario');
		$usuarioId			= $usuario['usuarioID'];
		
		$tags				= $_POST['tags'];
		
		foreach($tags as $tag){
			$tagExiste = $this->data_model->existeTag($tag);
			
			if(isset($tagExiste)){
				
				$datosTag = array(
					'habilidad' => $tag,
				);
					
				$this->db->insert('habilidades', $datosTag);
				$tagId = $this->db->insert_id();	
			}
			else{
					
				$tagId = $tagExiste[0]->habilidadId;
				
				}
					
			$datosTag = array(
				'usuarioId'		=> $usuarioId,
				'habilidadId' 	=> $tagId
			);
			
			$this->db->insert('habilidadReparador', $datosTag);
		}
		
		$op['usuarioTags']		= $this->usuario_model->cargarUsuarioTags($usuarioId);
		
		$this->load->view('vistaHabilidades-view', $op);
 
	}

	function addMarkers(){
		
		$latDist	= deg2rad($_POST['userLat']);
		$logDist 	= deg2rad($_POST['userLong']);
		
		$distMin	= $_POST['currentMinDist'];
		$distMax	= $_POST['currentMaxDist'];
		
		$reparadores	= $this->data_model->cargaRaparadoresPorLimiteDistacia($latDist,$logDist,$distMin,$distMax);
		
		echo json_encode($reparadores);
		exit;
		
	}
	
}