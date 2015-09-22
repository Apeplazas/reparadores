<ul>
	
	<? foreach($solicitudesUsuario as $solicitud): ?>
		<li>
			<p><?=$solicitud->descripcion; ?></p>
			<button id="<?=$solicitud->solicitudId; ?>-<?=$this->uri->segment(3); ?>-<?=$solicitud->usuarioId; ?>" class="solicitarCotizacion" >Solicitar Cotización</button>
		</li>
	<? endforeach; ?>
	
</ul>

<script>
	$(document).ready(function(){
		
		$('.solicitarCotizacion').click(function(e){
			
			e.preventDefault();
			var datos = $(this).attr('id');
			
			 $.ajax({
	            url: ajax_url+"insertarSolicitudCotizacion",
	            data: { 'datos': datos },
	            dataType: "json",
	            type:  'post',
	            success: function(data) {
	                
	            }
	        });
			
		});
		
	});
	
</script>