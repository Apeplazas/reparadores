<? if(!empty($reparadores)): 
	$googleCordenaadas = array(); 
?>
<? $this->load->view('includes/filtros');?>
<br class="clear">
<div id="busquedaMapa">
<div id="lista">
	<?= $this->session->flashdata('msg'); ?>
	<ul>
	<? $i = 1;
	$tempForm = '';
	foreach($reparadores as $reparador):
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
		$googleCordenaadas[$reparador->usuarioId]['datos'] = "<div class='foto'><img src='" . base_url() . $fotoUrl . "' alt='" . ucfirst($reparador->nombreCompleto) . "' /></div><div class='test'><h3>" . $reparador->nombreCompleto . "</h3><p>" . character_limiter($reparador->bio,200) . "</p>" . $contenerdorExtra . "<a href='" . base_url() . $reparador->urlPersonalizado . "'><img src='" . base_url() . "assets/graphics/ver-mas.png' alt='Ver mas'></a></div>";?>
		<li class="lista">
		<span>
			<img src="<?=base_url() . $fotoUrl;?>" width="36px" height="36px" alt="<?=$reparador->nombreCompleto;?>" />
		</span>
		
		<div class="repar contenedorReparador" id="<?=$reparador->usuarioId;?>" >
		<strong class="contenedorReparador"><?=$reparador->nombreCompleto;?></strong>
			<div class="repInfo">
				<p><?= character_limiter((isset($reparador->descripcionReparador)) ? $reparador->descripcionReparador : null, 50);?></p>
				<em>A <?=number_format((float)$reparador->dist, 2, '.', '');?> KM</em>
			</div>
			<i><a id="contactarUsuario" href="<?= base_url() . $reparador->urlPersonalizado; ?>"> Contactar</a></i>
			<!--i><a id="solicitaCotizacion" href="#<? if(!isset($usuario) || $usuario != true ) echo "ingresar"; else echo "mostrar-solicitudes-usuario";?>">Solicitar Cotización</a></i-->
		</div>
		</li>
		<? if($i <= 5){
			$tempForm .= "<input type='hidden' name='tempRep[]' value='$reparador->email' />";
		}
		++$i;
	endforeach; ?>
	</ul>
</div>
	<form id="tempReparadores" style="display:none;" method="post" action="#">
		<?= $tempForm;?>
	</form>
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
	
		var zoomCount		= 10;
		var currentMinDist	= 50;
		var currentMaxDist	= 50;
		var userLat			= <?= $lat;?>;
		var userLong		= <?= $long;?>;
	
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
				zoom: 12,
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
	  			//mostrarReparador(marker<?=$key;?>.customInfo);
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
		
		
		google.maps.event.addListener(map,'zoom_changed', function() {
			
			if(map.getZoom() < zoomCount &&  map.getZoom() >= 5){
				
				zoomCount = zoomCount - 1;
				
				currentMaxDist	= (currentMaxDist*2)+currentMinDist;
				
				$.post(ajax_url+"addMarkers", {
					userLat : userLat,
					userLong : userLong,
					currentMaxDist : currentMaxDist,
					currentMinDist : currentMinDist
				}, function(data) { sucess:
					
					$.each(data,function(index,val){
						
						var bio = (val.bio) ? val.bio.substring(1,200) : '';
						var fotoUrl = (val.fotografiaPerfil == "sinImagen.png") ? "sinfotografia.png" : val.fotografiaPerfil;
						var datosUser = "<div class='foto'><img src='<?php echo base_url();?>"
						+ fotoUrl + "' alt='" + val.nombreCompleto + "' /></div> \
							<div class='test'> \
								<h3>" + val.nombreCompleto + "</h3><p>" + bio + " \
								</p><a href='<?php echo base_url();?>" + val.urlPersonalizado + "'><img src='<?php echo base_url();?>assets/graphics/ver-mas.png' alt='Ver mas'></a> \
							</div>";
						var tempCoor	= val.coordenadasGoogle.split(",");
						var centerCoord = new google.maps.LatLng(tempCoor[0],tempCoor[1]);
						
						var marker999 = new google.maps.Marker({
							position: centerCoord,
						   	map: map,
						   	icon: 'http://reparadores.mx/assets/graphics/marca-mapa.png',
						   	draggable:false,
						   	test: datosUser
					  	});
					  		
					  	markers.push(marker999);
					  	
					  	google.maps.event.addListener(marker999, 'click', function(e) {
				  			//mostrarReparador(marker999.customInfo);
				  			$.each(markers,function(key,val){
								val.setIcon("http://reparadores.mx/assets/graphics/marca-mapa.png");
				  			});
				  			marker999.setIcon("http://reparadores.mx/assets/graphics/marca-mapa-activo.png");
				  			if (infoWindow) {
								infoWindow.close();
							}
							infoWindow.setContent(datosUser);
							infoWindow.open(map, marker999);
						});
					  	
					});
					
				},'json');
				
				currentMinDist = currentMaxDist;
				
			}
		});
		
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
	<a href="#" class="enviarMailReparadores">Enviar correo a los 5 reparadores más cercanos.</a><br />
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
    			
    			$('.enviarMailReparadores').click(function(){
    				$.post(ajax_url+"enviarEmailCercanos",$('#tempReparadores').serialize(),function(data){
					},"json");
					$('.modal').hide();
    			});
    		});
    	</script>
	<? endif;?>

<? else:?>

	<p>No se encontraron resultados</p>

<? endif;?>