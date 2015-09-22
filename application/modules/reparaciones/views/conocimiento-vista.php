<? $this->load->view('includes/filtros');?>
<div id="avisosTwo">
<? if(!empty($reparadores)):?>
	<ul>
	<? foreach($reparadores as $reparador):
		$conocimietos = $this->data_model->cargarConID($reparador->usuarioId);?>
		<li>
		<div class="foto">
		<img src="<?=base_url()?><?if ($reparador->fotografiaPerfil == 'sinImagen.png'):?>sinfotografia.png<?else:?><?= $reparador->fotografiaPerfil;?><?endif;?>" alt="<?= ucfirst($reparador->nombreCompleto);?>" />
		</div>
		<div class="infoRep">
			<h3><?= ucfirst($reparador->nombreCompleto);?></h3>
			<p><?= character_limiter($reparador->bio,200);?></p>
			<? if(!empty($conocimietos)): ?>
			<strong>Reparador en:</strong>
			<ul id="cats">
				<? foreach($conocimietos as $con): ?>
					<li><?=$con->conocimiento;?></li>
				<? endforeach;?>
			</ul>
			<? endif; ?>
			
			
			<? $habilidades = $this->data_model->cargarUsuarioHabilidades($reparador->usuarioId);
			if(!empty($habilidades)): ?>
			<div>
				<? foreach($habilidades as $habil): ?>
					<?=$habil->habilidad;?>
				<? endforeach;?>
			</div>
			<? endif; ?>
			
			<a href="<?=base_url()?><?=$reparador->urlPersonalizado;?>"><img src="<?=base_url()?>assets/graphics/ver-mas.png" alt="Ver mas"></a>
			<a class="cont accesoSoloUsuarios" data-fancybox-href="#ingresar" href="<?=base_url();?>usuarios/contactar/<?=$reparador->usuarioId;?>?url=<?=$this->uri->segment(1);?>"><img src="<?=base_url()?>assets/graphics/contacto-reparador.png" alt="Contactar" /></a>
			
		</div>
		</li>
	<? endforeach;?>
	</ul>
	<br class="clear">
<? else:?>	

<p class="upss"><img src="<?=base_url()?>assets/graphics/upsss.png" alt="Upsss" /></p>
<br class="clear">

<? endif;?>
</div>
