<?php
//Cargar Datos si ya fueeron ingresados 
$usuarioNombre		= set_value('usuarioNombre');
$usuarioTelefono    = set_value('usuarioTelefono');
$usuarioEmail  		= set_value('usuarioEmail');
$tipoUsuario    	= set_value('tipoUsuario');
$usuarioContrasenia = set_value('usuarioContrasenia');
$usuarioAlias 		= set_value('usuarioAlias');

$errorNombre        = form_error('usuarioNombre');
$errorTelefono      = form_error('usuarioTelefono');
$errorEmail         = form_error('usuarioEmail');
$errorUsuario       = form_error('tipo');
$errorContrasenia   = form_error('usuarioContrasenia');
$errorAlias         = form_error('usuarioAlias');
$errortipoUsuario   = form_error('tipoUsuario');

?>
<div id="contentEma">
	<div class="emaPad">
	<? if($this->uri->segment(2) == 'verificaUsuario'):?>
	<? foreach($usuario as $rowU):?>
	<?
	 $nombreCompleto = $rowU->nombreCompleto;
	 $email  = $rowU->email;
	?>
	<h1>Hola <?= $nombreCompleto;?> </h1>
	<p>Para poder ver y contestar tu comentario, Proporcionan la siguiente información.</p>
	<? endforeach; ?>
	<? else:?>
	<h1>Selecciona la información solicitada a continuación</h1>
	<? endif;?>
	<form method="post" action="<?=base_url()?>registro/guardarRegistro">
		<fieldset class="bbW">
			<label><? if ($errorNombre != ''):?><?= $errorNombre;?><?else:?>Escriba su nombre<? endif;?></label>
			<div><? if ($errorNombre != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
			<?
			$nombreUsuario = null; 
			if(!empty($datosUsuario)):
				$nombreUsuario = $datosUsuario->nombreCompleto;
			else:
				if($this->uri->segment(2) == 'verificaUsuario'):
					$nombreUsuario = $nombreCompleto;
				else:
					$nombreUsuario = $usuarioNombre;
				endif;
			endif;
			?>
			<input class="inBut" type="text" name="usuarioNombre" placeholder="Nombre Completo" value="<?=$nombreUsuario;?>"/>
		</fieldset>
		
		
		<fieldset class="bbW">
			<label><? if ($errorAlias != ''):?><?= $errorAlias;?><?else:?>Escoge un usuario<? endif;?></label>
			<div><? if ($errorAlias != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
			<i id="url"><?=base_url()?>usuario</i>
			<input id="fancy" onkeydown='onlytext(this);' type="text" name="usuarioAlias" placeholder="Alias" value="<?=$usuarioAlias;?>"/>
		</fieldset>
		
		
		
		<fieldset class="bbW">
			<label><? if ($errorEmail != ''):?><?= $errorEmail;?><?else:?>Cuenta de correo electrónico<? endif;?></label>
			<div><? if ($errorEmail != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
			<?
			$datosUsuarioEmail = null; 
			if(!empty($datosUsuario)):
				$datosUsuarioEmail = $datosUsuario->email;
			else:
				if($this->uri->segment(2) == 'verificaUsuario'):
					$datosUsuarioEmail = $email;
				else:
					$datosUsuarioEmail = $usuarioEmail;
				endif;
			endif;
			?>
			<input class="inBut" type="text" name="usuarioEmail" placeholder="ejemplo@gmail.com" value="<?= $datosUsuarioEmail; ?>"/>
		</fieldset>
		
		
		<fieldset class="bbW">
			<label><? if ($errorContrasenia != ''):?><?= $errorContrasenia;?><?else:?>Escoge una Contraseña<? endif;?></label>
			<div><? if ($errorNombre != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
			<input class="inBut" type="password" name="usuarioContrasenia" placeholder="Contraseña" value="<?= $usuarioContrasenia;?>"/>
		</fieldset>
		
		<fieldset class="bbW">
			<label><? if ($errorTelefono != ''):?><?= $errorTelefono;?><?else:?>Telefono<? endif;?></label>
			<div><? if ($errorNombre != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
			<input class="inBut" type="text" name="usuarioTelefono" placeholder="Celular o numero de contacto" value="<?=$usuarioTelefono;?>"/>
		</fieldset>
		
		<? if (!isset($_GET['tipo'])):?>
		<fieldset class="bbW">
		<label><? if ($errortipoUsuario != ''):?><?= $errortipoUsuario?><?else:?>Ingresar como:<? endif;?></label>
		<div><? if ($errortipoUsuario != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
		<select class="sel" name="tipoUsuario">
			<option value="">Tipo de registro...</option>
			<option value="reparador">Reparador</option>
			<option value="usuario" <? if(!empty($datosUsuario)) echo "selected";?>>Usuario</option>
		</select>
		</fieldset>
		
		<? else:?>
		<input type="hidden" name="tipoUsuario" value="<?=$_GET['tipo']?>" />
		<? endif;?>
		
		<fieldset class="bbW">
		<label><? if ($errorUsuario != ''):?><?= $errorUsuario;?><?else:?>Tipo registro<? endif;?></label>
		<div><? if ($errorUsuario != ''):?><span class="tache"><img src="<?=base_url()?>assets/graphics/tache.png" alt="" /></span>  <? endif;?></div>
		<select class="sel" name="tipo">
			<option value="" selected="">Escoge tu tipo de registro</option>
			<option value="empresa">Empresa</option>
			<option value="usuario">Usuario</option>
		</select>
		</fieldset>
		<fieldset>
		<span class="fleft sombraGris mt20">
		<? if( isset($activarUsuario) && $activarUsuario ):?>
			<input type="hidden" name="activarUsuario" value="true" />
		<? endif;?>	
		<input class="mt20 botonNegroGF borGri" type="submit" value="Registrarse" />
		</span>
		</fieldset>
	</form>
	<script>
		function onlytext(box){
		regexp = /\W/g;
		 if(box.value.search(regexp) >= 0){
		 box.value = box.value.replace(regexp, '');
		 }
		}
	</script>
	</div>
<br class="clear">
</div>