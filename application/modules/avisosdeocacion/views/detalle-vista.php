<div id="avisos">
	<div id="headAvi">
	  <h3><?= $detalle->titulo;?></h3>
	  <span>Fecha: <?= $detalle->fechaSolicitud;?></span>
	  <p><?= $detalle->descripcion;?></p>
	
	<? if(!empty($archivos)):?>
		<strong>Fotos de equipo:</strong>
		<? foreach($archivos as $archivo):?>
		<a class="action" href="#<?=$archivo->solicitudId;?>">
			<img class="fot" src="<?=URLARCHIVOS.$archivo->fotografiaNombre;?>" width="200px"/>
		</a>
		<? endforeach;?>
	<? endif;?>
	
	<? if(isset($propietarioSolicitud) && $propietarioSolicitud):?>
		
		<ul>
			<li><em>Cotizaciones: </em> <b><?=sizeof($postulados);?></b></li>
			<? foreach($postulados as $postulado):
				$reparadorDatos = $this->usuario_model->cargaUsuario($postulado->usuarioId);?>
			
			<li id="<?=$postulado->usuarioId;?>">
				<em><?=$reparadorDatos[0]->nombreCompleto . ' - ' . $postulado->fechPostulacion;?></em>
				<p>Costo: <?=$postulado->costo;?></p>
				<a href="<?=base_url();?>usuarios/ver_mensaje/<?=$postulado->mensajeId;?>">Ver Mensajes</a>
				
				<? if($detalle->estatus == "Abierta"):?>
				<form action="<?=base_url();?>reparaciones/asignarReparacion" method="post">
					<input type="hidden" name="reparadorId" value="<?=$postulado->usuarioId;?>">
					<input type="hidden" name="solicitudId" value="<?=$postulado->solicitudId;?>">
					<input type="submit" value="Asignar Reparación">
				</form>
				<? else:?>
					<? $detalleAsignacionSolicitud = $this->reparaciones_model->cargaAsignacionSolicitud($detalle->solicitudId);
					if($detalleAsignacionSolicitud[0]->usuarioId == $postulado->usuarioId):?>
						<?=$detalle->estatus;?>
					<? endif;?>
				<? endif;?>
				
			</li>
			
			<? endforeach;?>
		</ul>
	</div>	
	<? else:?>
	</div>
	
	<aside id="barCot">
		<div id="cuadroInf">
		<ul>
			<li><em>Cotizaciones: </em> <b><?=sizeof($postulados);?></b></li>
		</ul>
		<? if($haCotizado !== false): ?>
			<p>Tu cotización</p>
			<ul>
				<li><em>Costo: </em> <b>$<?=$postulados[$haCotizado]->costo;?></b></li>
			</ul>
		<?	else: ?>
			<a class="action" href="#presupuestar-solicitud" ><img src="<?=base_url()?>assets/graphics/cotizar.png" alt="Cotizar reparación" /></a>
		<?	endif; ?>	
		</div>
	</aside>
	<? endif;?>
	<br class="clear">
	
	<div id="presupuestar-solicitud" style="display:none;">
	<h4>Formulario de cotización de reparaciones</h4>
		<form id="sendQuote" action="<?=base_url();?>avisosdeocacion/presupuestar" method="post" />
		  <fieldset>
			<label>Costo:</label>
			<input class="stIN currency" placeholder="Costo por reparación" type="textbox" step="any" name="costo" id="costo" />
		  </fieldset>
		  <fieldset>
			<label>Mensaje:</label>
			<textarea class="stIN" id="mensaje" name="mensaje" placeholder="Anexa cualquier condición o información importante que pudiera necesitarse en un futuro "></textarea>
		  </fieldset>
		  <fieldset>
			<input type="hidden" name="solicitudId" value="<?=$detalle->solicitudId;?>"/>
			<input type="hidden" name="titulo" value="Reparación - <?=$detalle->titulo;?>"/>
			<input type="hidden" name="usuarioSolicita" value="<?=$detalle->usuarioId;?>"/>
			<input id="enviarCot" src="<?=base_url()?>assets/graphics/enviar-cotizacion.png" type="image" value="Enviar" />
		  </fieldset>
		</form>
	</div>
	
	<? foreach($archivos as $rowA):?>
		<div id="<?=$rowA->solicitudId;?>" style="display:none">
			<img class="imaFot" src="<?=URLARCHIVOS.$rowA->fotografiaNombre;?>"/>
		</div>
		<? endforeach;?>
</div>
<script>
$(document).ready(function() {
	$(".action").fancybox();
	$('#costo').blur(function(){
    	$('#costo').formatCurrency();
    });
	$("#sendQuote").submit(function(){
		
		if($("#costo").val() == '' || $("#costo").val() == 0 || $("#mensaje").val() == ''){
			
			alert("favor de llenar todos los campos");
			return false;
		}
		
	});
	
});
</script>
	