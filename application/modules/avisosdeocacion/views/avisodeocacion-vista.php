<? $this->load->view('includes/filtrosBusquedaReparacion');?>
<div id="avisos">
<h2>Solicitudes de reparación de equipos</h2>
<ul >
<? foreach($solicitudes as $solicitud):?>	
	<li>
	<div class="foto">
		<img src="<?=base_url()?><?if ($solicitud->fotografiaPerfil == 'sinImagen.png'):?>sinfotografia.png<?else:?><?= $solicitud->fotografiaPerfil;?><?endif;?>" alt="<?= $solicitud->nombreCompleto;?>" />
	</div>
	<div class="infoRep">
	  <h3><?= $solicitud->titulo;?></h3>
	  <span>Fecha: <?= $solicitud->fechaSolicitud;?></span>
	  <p><?= character_limiter($solicitud->descripcion,200);?></p>
	  <a href="<?=base_url();?>avisosdeocacion/detalle/<?= $solicitud->solicitudId;?>"><img src="<?=base_url()?>assets/graphics/ver-mas.png" alt="Ver mas" /></a>
	</div>
	</li>
<? endforeach;?>
</ul>
<br class="clear">
</div>