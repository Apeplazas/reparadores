<div id="dashboard">
<section id="infoDash">
<h2>Solicitudes de reparación de equipos</h2>
<?= $this->session->flashdata('msg'); ?>
<? if($usuarioDatos): ?>
	
	<ul class="repara"> 
	<? foreach($usuarioDatos as $datos): ?>
		<li>
			<strong><?=$datos->titulo;?></strong>
			<p><?= character_limiter($datos->descripcion, '200');?></p><span><?=$datos->estatus;?></span>
			<a href="<?=base_url();?>avisosdeocacion/detalle/<?=$datos->solicitudId;?>"><img src="<?=base_url()?>assets/graphics/ver-mas.png" alt="Ver mas"></a>	
		</li>
	<? endforeach; ?>
	</ul> 
	
<? endif; ?>
</section>

<br class="clear">
</div>