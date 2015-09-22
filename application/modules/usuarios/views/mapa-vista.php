<? if(empty($perfil[0]->coordenadasGoogle)):?>

<? endif;?>
	
<div id="map">
	<input id="pac-input" class="controls" type="text" placeholder="Escribe la dirección y arrastra el icono hasta seleccionar la ubicacion exacta.">
	<div id="mapCanvasTwo"></div>
	
	<form method="post" action="<?=base_url()?>usuarios/actualizaPerfil" id="actualizarGoogleMpas">
		
		<input type="hidden" name="googleMaps" id="googleMaps" value="<?=$perfil[0]->coordenadasGoogle;?>" />
		<input type="hidden" name="usuarioUrl" id="usuarioUrl" value="<?=$perfil[0]->urlPersonalizado;?>" />
		<input type="hidden" name="googleEstado" id="googleEstado" value="<?=$perfil[0]->estadoNombre;?>" />
		<input type="hidden" name="googleDelegacion" id="googleDelegacion" value="<?=$perfil[0]->delegacionNombre;?>" />
		<input type="hidden" name="googleCp" id="googleCp" value="<?=$perfil[0]->codigoPostal;?>" />
		<input type="hidden" name="googleColonia" id="googleColonia" value="<?=$perfil[0]->coloniaNombre;?>" />
    	<input class="mt20 botonMap borGri" type="submit" value="Guardar ubicación">
    	
	</form>
</div>
	
<script>
		
		
	$(document).ready(function(jQuery) {	
	
		var geocoder;
		geocoder = new google.maps.Geocoder();
	<? if(empty($perfil[0]->coordenadasGoogle)):?>
		var mapOptions = {
	    	center: new google.maps.LatLng(21.0000, -102.3667),
	    	zoom: 5
	  	};
	<?else:?>
		var mapOptions = {
	    	center: new google.maps.LatLng(<?=$perfil[0]->coordenadasGoogle;?>),
	    	zoom: 16
		};
	<? endif;?>
		var map = new google.maps.Map(document.getElementById('mapCanvasTwo'),
	    mapOptions);

	  	var input = /** @type {HTMLInputElement} */(
			document.getElementById('pac-input'));
	
	  	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	
	  	var autocomplete = new google.maps.places.Autocomplete(input);
	  	autocomplete.bindTo('bounds', map);
	
	  	var infowindow = new google.maps.InfoWindow();
	  	var marker = new google.maps.Marker({
	    	map: map,
	    	draggable:true,
	    	anchorPoint: new google.maps.Point(0, -29)
	  	});
	
	<? if(!empty($perfil[0]->coordenadasGoogle)):?>  
		var centerCoord = new google.maps.LatLng(<?=$perfil[0]->coordenadasGoogle;?>);
		marker.setPosition(centerCoord);
		marker.setVisible(true);
	<? endif;?>
	
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
	    	infowindow.close();
	    	marker.setVisible(false);
	    	var place = autocomplete.getPlace();
	    	if (!place.geometry) {
	      		return;
	    	}
	
	    	//Si el lugar cuenta con datos presentarlo en el mapa
	    	if (place.geometry.viewport) {
	    		
	      		map.fitBounds(place.geometry.viewport);
	      		
	    	}else{
	    		
	      		map.setCenter(place.geometry.location);
	      		map.setZoom(17);  // Why 17? Because it looks good.
	    	}
	    	
	    	marker.setIcon(/** @type {google.maps.Icon} */({
	      		url: place.icon,
	      		size: new google.maps.Size(71, 71),
	      		origin: new google.maps.Point(0, 0),
	      		anchor: new google.maps.Point(17, 34),
	      		scaledSize: new google.maps.Size(35, 35)
	    	}));
	    
	    	marker.setPosition(place.geometry.location);
	    	marker.setVisible(true);
	    
	    	//Inserta datos de Google
	    	$('#googleMaps').val(place.geometry.location);
			actualizaDatosGoogle(place.address_components);
	
	    	var address = '';
	    	if (place.address_components) {
	    		
	      		address = [
	        		(place.address_components[0] && place.address_components[0].short_name || ''),
	        		(place.address_components[1] && place.address_components[1].short_name || ''),
	        		(place.address_components[2] && place.address_components[2].short_name || '')
	      		].join(' ');
	    	}
	
	    	infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
	    	infowindow.open(map, marker);
	  	});
	  
		function actualizaDatosGoogle(datos){

			$.each(datos,function(key,val){
			
				//Inserta Estado
				if(val.types[0] == 'administrative_area_level_1')
					$('#googleEstado').val(val.long_name);
					
				//Inserta Delegacion
				if(val.types[0] == 'sublocality_level_1')
					$('#googleDelegacion').val(val.long_name);
				
				//Inserta CP
				if(val.types[0] == 'postal_code')
					$('#googleCp').val(val.long_name);
					
				//Inserta Colonia
				if(val.types[0] == 'neighborhood')
					$('#googleColonia').val(val.long_name);
				
			});	
			
		}
	  
		function geocodePosition(pos) {
			geocoder.geocode({
				latLng: pos
		  	}, function(responses) {
		    	if (responses && responses.length > 0) {
		    		actualizaDatosGoogle(responses[0].address_components);
		    		infowindow.setContent('<div>'+responses[0].formatted_address+'</div>');
		    		$('#googleMaps').val(responses[0].geometry.location);
		    	} else {
		      		infowindow.setContent('No se puede cargar este punto');
		    	}
		  	});
		}
	  
		google.maps.event.addListener(marker, 'dragend', function(){
			geocodePosition(marker.getPosition());
			infowindow.open(map, marker);
		});
	
	
	});	
	
/*	
	$('#actualizarGoogleMpas').submit(function(e){
		
		e.preventDefault();
		
		if($('#googleMaps').val() == ''){
			alert("Eliga su ubicación");
			return false;
		}
		
		return true;
		
	});
*/		
	</script>