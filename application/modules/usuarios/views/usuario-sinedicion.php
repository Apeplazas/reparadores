<? foreach($perfil as $row):?>
<div id="profile">
<aside id="pictWrap">
	<span class="frame">
		<span id="iconoFoto" >
		<img src="<?=base_url()?><? if ($row->fotografiaPerfil == 'sinImagen.png'):?>sinfotografia.png<? else:?><?= $row->fotografiaPerfil;?><? endif?>" alt="Foto Perfil" />
		</span>
	</span>
</aside>
<section id="infoProfTwo">
	<h1><img id="proIcon" src="<?=base_url()?>assets/graphics/profile.png" alt="Perfil" /><?= $row->nombreCompleto;?></h1>
	
	<ul id="mainPerfil">
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
	<?php session_start();
	if(!isset($_SESSION['DatosTemporalesUsuario']) && !$usuario):?> 
		<a class="contTw accesoUsuarioTemp" data-fancybox-href="#usuarioTemp" href="#"><img src="<?=base_url()?>assets/graphics/contactar-reparador.png" alt="Contactar"></a>
	<?php else:?>
		<p>Email: <?=$row->email;?></p>
		<?php if(!empty($row->telefono)):?>
			<p>Teléfono: <?=$row->telefono;?></p>
		<?php endif;?>
		<?php if(!empty($perfil[0]->celular)):?>
			<p>Celular: <?=$row->celular;?></p>
		<?php endif;?>
	<?php endif;?>
</aside>
</div>
<? if($perfil[0]->estatus == 'noAutorizado'):?>
	<p>Tu cuenta aún no ha sido activada!</p>
<? endif;?>
<? endforeach; ?>
<div id="usuarioTemp" style="display:none;">
	<form id="loginForm" action="<?=base_url()?>registro/ingresar" method="post">
		<? if(isset($error)) echo $error;?>
	  	<span><div class="msgBlack"></div></span>
	  	<fieldset class="bbW">
	    	<label>Nombre Completo</label>
			<input class="sans inBut" type="text" name="usuarioNombre" placeholder="Nombre Completo" required />
	  	</fieldset>
	  	<fieldset class="bbW">
	    	<label>Email</label>
			<input class="sans inBut" type="email" name="usuarioEmail" placeholder="Email" required />
	  	</fieldset>
	  	<fieldset class="bbW">
	    	<label>Teléfono</label>
			<input class="sans inBut" type="text" name="usuarioTel" placeholder="Teléfono" required />
	  	</fieldset>
	  	<fieldset class="mt20">
	  		<input type="hidden" name="reparadorId" value="<?= $row->usuarioId;?>" />
			<input id="cLog" class="sans bYel" type="submit" value="Enviar" />
	  	</fieldset>
	  	<fieldset class="mt10">
		  	<i class="sans fOne">Al dar click en el boton entrar confirmas que aceptas nuestros </i><a class="sans fOne" href="<?=base_url()?>">Terminos  de Servicio</a>
	  	</fieldset>
	</form>
</div>
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
    
    $(".accesoUsuarioTemp").fancybox({
		'scrolling'		: 'no',
		'titleShow'		: false
	});
    
    $(document).ready(function(){
    	$('#loginForm').submit(function(e){
    		e.preventDefault();
    		$('#loginForm .msgBlack').html('');
    		$.post(ajax_url+"gusardaUsuarioTemp",$(this).serialize(),function(data){
				sucess:				
					if(data){
						var telefono = (data.telefono && data.telefono != 0) ? '<p>Teléfono ' + data.telefono + '</p>' : '';
						var celular = (data.celular && data.celular != 0) ? '<p>Celular ' + data.celular + '</p>' : '';
						$('.accesoUsuarioTemp').remove();
						$('#buttons #mainInPre').after('<p>Email: ' + data.email + '</p>'+telefono+celular);
						$.fancybox.close();
					}else{
						$('#loginForm .msgBlack').html('Favor de ingresar todos los datos');
					}
			},"json");
    	});
    });
</script>