<?
$usuario = $this->session->userdata('usuario'); 
if(!isset($usuario) || $usuario != true ): ?>

<div id="ingresar" style="display:none;">
	<form id="loginForm" action="<?=base_url()?>registro/ingresar" method="post">
		<? if(isset($error)) echo $error;?>
	  	<span><div class="msgBlack"></div></span>
	  	<fieldset class="bbW">
	    	<label>User or Email</label>
			<input class="sans inBut" type="text" name="usuarioOEmail" placeholder="Username or email" />
	  	</fieldset>
	  	<fieldset class="bbW">
	    	<label>Contraseña</label>
			<input class="sans inBut" type="password" name="contrasenia" placeholder="Password" />
			<a id="forgot" href="<?=base_url()?>registro/recuperarContrasenia">¿Olvidaste tu contraseña?</a>
	  	</fieldset>
	  	<fieldset class="mt20">
			<input id="cLog" class="sans bYel" type="submit" value="Entrar" />
	  	</fieldset>
	  	<fieldset>
			<em id="or">o</em>
	  	</fieldset>
	  	<fieldset>
		  	<a class="regis" href="<?=base_url()?>registro/poremail"><img src="<?=base_url()?>assets/graphics/registrateLogin.png" alt="Registrate" /></a>
	  	</fieldset>
	  	<fieldset class="mt10">
		  	<i class="sans fOne">Al dar click en el boton entrar confirmas que aceptas nuestros</i><a class="sans fOne" href="<?=base_url()?>">Terminos  de Servicio</a>
	  	</fieldset>
	</form>
</div>

<script>
	$('.accesoSoloUsuarios').click(function(e){
		<? 
		if(!isset($usuario) || $usuario != true ):?>
		
				var url = e.currentTarget.href;
		
			$.ajax({
				data		: {"urlAnterior":url},
				dataType 	: 'json',
				url 		: ajax_url+'guardaUrl',
				type 		: 'post'
			});
		<? endif;?>
	});
	
	$(".accesoSoloUsuarios").fancybox({
		'scrolling'		: 'no',
		'titleShow'		: false
	});
	
</script>

<? endif; ?>