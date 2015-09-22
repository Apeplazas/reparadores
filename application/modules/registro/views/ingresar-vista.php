<div id="contentReg">
	<div class="logPad">
	<form id="loginFormTh" action="<?=base_url()?>registro/ingresar" method="post">
		<?= $this->session->flashdata('msg'); ?>
		<? if(isset($error)) echo $error;?>
	  <span><div class="msgBlack"></div></span>
	  <fieldset class="bbW">
	    <label>User or Email</label>
		<input class="sans inBut" type="text" name="usuarioOEmail" placeholder="Username or email" />
	  </fieldset>
	  <fieldset class="bbW">
	    <label>Contraseña</label>
		<input class="sans inBut" type="password" name="contrasenia" placeholder="Password" />
	  </fieldset>
	  <fieldset>
		  <a id="forgot" href="<?=base_url()?>registro/recuperar_contrasenia">¿Olvidaste tu contraseña?</a>
	  </fieldset>
	  <fieldset class="mt20">
		  <input id="cLog" class="sans bYel" type="submit" value="Entrar" />
	  </fieldset>
	  <fieldset>
		  <em id="or">o</em>
	  </fieldset class="fleft mt20">
	  <fieldset>
		  <a id="faceCone" href="<?=base_url()?>registro/facebooklogin">Ingresar con facebook</a>
	  </fieldset>
	  <fieldset class="mt10">
		  <i class="sans fOne">Al dar click en el boton entrar confirmas que aceptas nuestros <a class="sans fOne" href="<?=base_url()?>">Terminos  de Servicio</a></i>
	  </fieldset>
	  <br class="clear">
	</form>
	</div>
</div>