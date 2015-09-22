<? if(!empty($reparadores)): 
	$googleCordenaadas = array(); 
?>
<? $this->load->view('includes/filtros');?>
<br class="clear">
<div id="busquedaMapa">
<div id="lista">
	<?= $this->session->flashdata('msg'); ?>
	<ul>
	<? foreach($reparadores as $reparador):
		$conocimietos = $this->data_model->cargarConID($reparador->usuarioId);
		$contenerdorExtra = '';
		if(!empty($conocimietos)): 
			$contenerdorExtra .= "<strong>Reparador en:</strong><ul id='cats'>";
				foreach($conocimietos as $con):
					$contenerdorExtra .= "<li>" . $con->conocimiento . "</li>";
				endforeach;
			$contenerdorExtra .= "</ul>";
		endif;
			
		$habilidades = $this->data_model->cargarUsuarioHabilidades($reparador->usuarioId);
			if(!empty($habilidades)):
			$contenerdorExtra .= "<div>";
				foreach($habilidades as $habil):
					$contenerdorExtra .= $habil->habilidad . ' ';
				endforeach;
			$contenerdorExtra .= "</div>";
			endif;
		$fotoUrl = ($reparador->fotografiaPerfil == "sinImagen.png") ? "sinfotografia.png" : $reparador->fotografiaPerfil;
		$googleCordenaadas[$reparador->usuarioId]['cordenadas'] = $reparador->coordenadasGoogle;
		$googleCordenaadas[$reparador->usuarioId]['datos'] = "<div class='foto'><img src='" . base_url() . $fotoUrl . "' alt='" . ucfirst($reparador->nombreCompleto) . "' /></div><div class='test'><h3>" . $reparador->nombreCompleto . "</h3><p>" . character_limiter($reparador->bio,200) . "</p>" . $contenerdorExtra . "<a href='" . base_url() . $reparador->urlPersonalizado . "'><img src='" . base_url() . "assets/graphics/ver-mas.png' alt='Ver mas'></a><a class='accesoSoloUsuarios' data-fancybox-href='#ingresar' href='" . base_url() . "usuarios/contactar/" . $reparador->usuarioId . "?url=" . $this->uri->segment(1) . "'><img src='" . base_url() . "assets/graphics/contacto-reparador.png' alt='Contactar' /></a></div>";?>
		<li class="lista">
		<span>
		<? if(file_exists($_SERVER['DOCUMENT_ROOT'].'/assets/graphics/'.$reparador->fotografiaPerfil)):?>
			<img src="<?=base_url()?>assets/graphics/<?=$reparador->fotografiaPerfil;?>" alt="<?=$reparador->nombreCompleto;?>" />
		<? else:?>
			<img src="<?=base_url()?>assets/graphics/perfil-Reparador.png" alt="Imagen no disponible" />
		<? endif;?>
		</span>
		
		<div class="repar contenedorReparador" id="<?=$reparador->usuarioId;?>" >
		<strong class="contenedorReparador"><?=$reparador->nombreCompleto;?></strong>
			<div class="repInfo">
				<p><?= character_limiter((isset($reparador->descripcionReparador)) ? $reparador->descripcionReparador : null, 50);?></p>
				<em>A <?=number_format((float)$reparador->dist, 2, '.', '');?> KM</em>
			</div>
			<i><a id="contactarUsuario" class="accesoSoloUsuarios" data-fancybox-href="#ingresar" href="<?=base_url();?>usuarios/contactar/<?=$reparador->usuarioId;?>?url=<?=$this->uri->segment(1)."/".$this->uri->segment(2);?>"> Contactar</a></i>
			<!--i><a id="solicitaCotizacion" href="#<? if(!isset($usuario) || $usuario != true ) echo "ingresar"; else echo "mostrar-solicitudes-usuario";?>">Solicitar Cotización</a></i-->
		</div>
		</li>
	<? endforeach; ?>
	</ul>
</div>

	<div id="map-canvas"></div>
</div>
<input type="hidden" id="reparadorIdTemporal" value="" />
<div id="mostrar-solicitudes-usuario" style="display:none;">
	<ul>
	
	<? foreach($solicitudesUsuario as $solicitud): ?>
		<li>
			<p><?=$solicitud->descripcion; ?></p>
			<button id="<?=$solicitud->solicitudId; ?>-<?=$this->uri->segment(3); ?>-<?=$solicitud->usuarioId; ?>" class="solicitarCotizacion" ><img alt="Ver mas" src="<?=base_url();?>assets/graphics/solicitar-cotizacion.png"></button>
		</li>
	<? endforeach; ?>
	</ul>

</div>

<script>
	
	$("#solicitaCotizacion").fancybox({
		'titleShow'		: false,
		'speedOut'		: 9000
	});
	
</script>

<script>
$(document).ready(function(jQuery) {	
	
		var geocoder, map, markers = [];
		
		var infoWindow = new google.maps.InfoWindow({
			maxWidth: 400
		});
	  	
		function initMap(centerCoord) {
			if (!centerCoord) {
				centerCoord = new google.maps.LatLng(21.0000, -102.3667);
		  	}
		  	
		  	var styles = 
										  	[
					    {
					        "stylers": [
					            {
					                "saturation": -45
					            },
					            {
					                "lightness": 13
					            }
					        ]
					    },
					    {
					        "featureType": "road.highway",
					        "elementType": "geometry.fill",
					        "stylers": [
					            {
					                "color": "#eeeeee"
					            }
					        ]
					    },
					    {
					        "featureType": "road.highway",
					        "elementType": "geometry.stroke",
					        "stylers": [
					            {
					                "color": "#FF8000"
					            }
					        ]
					    },
					    {
					        "featureType": "road.highway",
					        "elementType": "labels.text.fill",
					        "stylers": [
					            {
					                "color": "#333333"
					            }
					        ]
					    },
					    {
					        "featureType": "road.highway",
					        "elementType": "labels.text.stroke",
					        "stylers": [
					            {
					                "color": "#cccccc"
					            },
					            {
					                "gamma": 2
					            }
					        ]
					    },
					    {
					        "featureType": "road.arterial",
					        "elementType": "geometry.fill",
					        "stylers": [
					            {
					                "color": "#cccccc"
					            }
					        ]
					    },
					    {
					        "featureType": "road.arterial",
					        "elementType": "geometry.stroke",
					        "stylers": [
					            {
					                "color": "#cccccc"
					            }
					        ]
					    },
					    {
					        "featureType": "road.arterial",
					        "elementType": "labels.text.fill",
					        "stylers": [
					            {
					                "color": "#999999"
					            }
					        ]
					    },
					    {
					        "featureType": "road.local",
					        "elementType": "geometry.fill",
					        "stylers": [
					            {
					                "color": "#cccccc"
					            }
					        ]
					    },
					    {
					        "featureType": "road.local",
					        "elementType": "geometry.stroke",
					        "stylers": [
					            {
					                "color": "#eeeeee"
					            }
					        ]
					    },
					    {
					        "featureType": "road.local",
					        "elementType": "labels.text.fill",
					        "stylers": [
					            {
					                "color": "#555555"
					            }
					        ]
					    },
					    {
					        "featureType": "water",
					        "elementType": "geometry.fill",
					        "stylers": [
					            {
					                "color": "#bbd9e9"
					            }
					        ]
					    },
					    {
					        "featureType": "administrative",
					        "elementType": "labels.text.fill",
					        "stylers": [
					            {
					                "color": "#525f66"
					            }
					        ]
					    },
					    {
					        "featureType": "transit",
					        "elementType": "labels.text.stroke",
					        "stylers": [
					            {
					                "color": "#bbd9e9"
					            },
					            {
					                "gamma": 2
					            }
					        ]
					    },
					    {
					        "featureType": "transit.line",
					        "elementType": "geometry.fill",
					        "stylers": [
					            {
					                "color": "#a3aeb5"
					            }
					        ]
					    }
					];
		  	
		  	
		  	var mapOptions = {
				center: centerCoord,
				zoom: 11,
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles: styles,
				mapTypeControl: true,
			    mapTypeControlOptions: {
			        position: google.maps.ControlPosition.TOP_RIGHT
			    },
			    panControl: true,
			    panControlOptions: {
			        position: google.maps.ControlPosition.TOP_RIGHT
			    },
			    zoomControl: true,
			    zoomControlOptions: {
			        position: google.maps.ControlPosition.TOP_RIGHT
			    },
			    scaleControl: true,
			    streetViewControl: true,
			    streetViewControlOptions: {
			        position: google.maps.ControlPosition.TOP_RIGHT
			    }
		  	};
		  	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		}
		
		var bounds = new google.maps.LatLngBounds();
		geocoder = new google.maps.Geocoder();
		
		if (!map) {
			initMap(bounds.getCenter());
		}
		
		//map.ControlPosition.RIGHT_TOP;
		
		<? foreach($googleCordenaadas as $key => $cordenadas): ?>

			centerCoord = new google.maps.LatLng(<?=$cordenadas['cordenadas'];?>);
			bounds.extend(centerCoord);
			var marker<?=$key;?> = new google.maps.Marker({
				position: centerCoord,
		    	map: map,
		    	icon: 'http://reparadores.mx/assets/graphics/marca-mapa.png',
		    	draggable:false,
		    	customInfo: <?=$key;?>,
		    	test: "<?= preg_replace("/\r?\n/", "\\n", addslashes($cordenadas['datos']));?>"
	  		});
	  		
	  		markers.push(marker<?=$key;?>);
	  		
	  		google.maps.event.addListener(marker<?=$key;?>, 'click', function(e) {
	  			mostrarReparador(marker<?=$key;?>.customInfo);
	  			$.each(markers,function(key,val){
					val.setIcon("http://reparadores.mx/assets/graphics/marca-mapa.png");
	  			});
	  			marker<?=$key;?>.setIcon("http://reparadores.mx/assets/graphics/marca-mapa-activo.png");
	  			if (infoWindow) {
					infoWindow.close();
				}
				infoWindow.setContent("<?=preg_replace('/\"/',"'",preg_replace("/\r?\n/", "\\", addslashes($cordenadas['datos'])));?>");
				infoWindow.open(map, marker<?=$key;?>);
			});
			
			/*
			google.maps.event.addListener(marker, 'mouseout', function() {
				if (infoWindow) {
					infoWindow.close();
				}
			});
			*/
		<?php endforeach;?>
		
		map.setCenter(bounds.getCenter());
		//map.fitBounds(bounds);
		
		$('.contenedorReparador').click(function(){

			var markerId = $(this).attr('id');
			
			$.each(markers,function(key,val){
				if(val.customInfo == markerId){
					val.setIcon("http://reparadores.mx/assets/graphics/marca-mapa-activo.png");
					map.setCenter(val.getPosition());
					if (infoWindow) {
						infoWindow.close();
					}
					infoWindow.setContent(val.test);
					infoWindow.open(map, val);
				}else
					val.setIcon("http://reparadores.mx/assets/graphics/marca-mapa.png");
	  		});
			
		});
		
		$('.solicitarCotizacion').click(function(e){
			
			e.preventDefault();
			var datos		= $(this).attr('id');
			var reparadorID = $('#reparadorIdTemporal').val();
	
			 $.ajax({
	            url: ajax_url+"insertarSolicitudCotizacion",
	            data: { 'datos': datos, 'reparadorID': reparadorID },
	            dataType: "json",
	            type:  'post'
	        });
			$('#mostrar-solicitudes-usuario').html("<p>Su solicitud de cotización ha sido enviada.</p>");
			
			parent.$.fancybox.close();
			
		});
		
		$('#solicitaCotizacion').click(function(){
			
			$('#reparadorIdTemporal').val($(this).parent().parent().attr('id'));
			
		});
	
	});
	
	function mostrarReparador(id) {
		
		if($("#" + id).css('display') == 'none')
			$("#" + id).show();
		$('#lista ul').animate({scrollTop: $("#" + id).offset().top});
    	$('.contenedorReparador').not('#' + id).hide();
    	
	}
	
</script>





<script>
$(document).ready(function(jQuery) {
/********************************************************************************************************************
Cambia la clase en la busqueda para pasar de box a horizontal
********************************************************************************************************************/
	$('.solNom').click(function(){
		$('.cambia').removeClass('listaGra');
		$('.cambia').addClass('listaChi');
		$('.infoComp').removeClass('selFil');
		$('.infoComp').addClass('noFil');
		$('.solNom').addClass('selFil');
		$('.solNom').removeClass('noFil');
	});
	$('.infoComp').click(function(){
		$('.cambia').addClass('listaGra');
		$('.cambia').removeClass('listaChi');
		$('.solNom').removeClass('selFil');
		$('.solNom').addClass('noFil');
		$('.infoComp').addClass('selFil');
		$('.infoComp').removeClass('noFil');
	});
	
});
</script>

<style>
.modal{
  background-color: white;
  border-radius: 5px;
  box-shadow: 2px 2px 2px rgba(0,0,0,0.2);
  height:200px;
  width:300px;
 
  position:fixed;
  font-size: 24px;
  padding: 20px;
  top:50%;
  left:50%;
  margin-left: -150px;
  margin-top:-100px;
}
.modal::backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5);
  z-index:99999999;
}
</style>
<div class="modal" style="display:none;">
	<p>Se ha recibido su solicitud</p>
	<a href="<?=base_url()?>">Finalizar</a><br />
	<a href="#" class="mostrarReparadores">Mostrar Reparadores</a>
</div>

<? session_start(); 
	if(isset($_SESSION['MostrarMen'])):
		unset($_SESSION['MostrarMen']);?>
    	<script>
    		$(document).ready(function(){
    			$('.modal').show();
    			$('.mostrarReparadores').click(function(){
    				$('.modal').hide();
    			});
    		});
    	</script>
	<? endif;?>

<? else:?>

	<p>No se encontraron resultados</p>

<? endif;?>