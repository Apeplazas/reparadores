<? foreach($perfil as $row):?>
<div id="profile">
<aside id="pictWrap">
	<span class="frame">
		<span id="iconoFoto" >
		<img src="<?=base_url()?><? if ($row->fotografiaPerfil == 'sinImagen.png'):?>sinfotografia.png<? else:?><?= $row->fotografiaPerfil;?><? endif?>" alt="Foto Perfil" />
		</span>
	</span>
	<a id="reviewProf" href="<?=base_url()?>" title="<?= $row->nombreCompleto;?> reputation">
		<span class="markColor">1,200</span>
		<em class="alegreya">Mis Reseñas</em>
	</a>
</aside>
<section id="infoProfTwo">
	<h1><img id="proIcon" src="<?=base_url()?>assets/graphics/profile.png" alt="Perfil" /><?= $row->nombreCompleto;?></h1>
	
	<ul id="mainPerfil">
	  <li>
	    <strong>Email</strong>
		<p><?=$perfil[0]->email;?></p>
	  </li>
	  <? if(isset($row->estadoNombre)):?>
	  <li class="ovHid">
	  	  <h4>Ubicación de <?= $row->nombreCompleto;?></h4>
	  	  <strong>Estado:</strong>
	  	  <p><?= $row->estadoNombre;?></p>
	  	  <? if($row->delegacionNombre):?>
	  	  <strong>Delegación:</strong>
	  	  <p><?= $row->delegacionNombre;?></p>
	  	  <? endif;?>
	  	  <? if($row->coloniaNombre):?>
	  	  <strong>Colonia:</strong>
	  	  <p><?= $row->coloniaNombre;?></p>
	  	  <? endif;?>
	  </li>
	  <?endif?>
	  <? if(isset($row->bio)):?>
	  <li>
		  <p id="descri"><?= $row->bio;?></p>
	  </li>
	  <?endif?>
	</ul>
	<!--h3 id="historial">Historial de trabajos realizados</h3>
	<ul>
		<li>
		  <strong>Juanito Perez</strong>
		  <p>Rotura de display de ipad por caida</p>
		  <em>Status trabajo: Sin finalizar</em>
		 </li>
		 <li>
		  <strong>Pedro Marmol</strong>
		  <p>Componer motherboard para LG 325 por caida en agua</p>
		  <em>Status trabajo: Finalizado</em>
		 </li>
	</ul-->
	<h3 id="conocim">Conocimientos y habilidades</h3>
	<ul>
		<li>
		  <strong>Tablets</strong>
		  <p>Ipad, Android Tablets, Chinas</p>
		</li>
		<li>
		<strong>Celulares</strong>
		<p>Iphone, Display, Baterias, Motherboards</p>
		</li>
	</ul>
</section>
<aside id="buttons">
	<h3 class="boLin"><img src="<?=base_url()?>assets/graphics/informacion.png" alt="" />Información de perfil</h3>
	<ul id="mainInPre">
		<li>
			<strong>Trabajos</strong> <p><?=sizeof($trabajos);?></p> <em>Total</em>
		</li>
		<li>
			<strong>Opiniones</strong> <p>0</p> <em>Total</em>
		</li>
		<li>
			<strong>Clientes</strong> <p>0</p> <em>Total</em>
		</li><li>
			<strong>Califcación</strong> <p><img src="<?=base_url()?>assets/graphics/5estrellas.png" alt="Estrellas" /></p>
		</li>
	</ul>
	<a class="contTw accesoSoloUsuarios" data-fancybox-href="#ingresar" href="http://reparadores.mx/usuarios/contactar/107?url=reparacion-de-celulares"><img src="<?=base_url()?>assets/graphics/contactar-reparador.png" alt="Contactar"></a>
</aside>
</div>
<? if($perfil[0]->estatus == 'noAutorizado'):?>
	<p>Tu cuenta aún no ha sido activada!</p>
<? endif;?>
<? endforeach; ?>
<script>
// Tagit
    $("#myTags").tagit({

		tagSource: function(search, showChoices) {
	        $.ajax({
	            url: "/ajax/buscaHabilidades",
	            data: { 'q': search.term },
	            dataType: "json",
	            type:  'post',
	            success: function(data) {
	                showChoices(data);
	            }
	        });
    	}
         
    });
</script>