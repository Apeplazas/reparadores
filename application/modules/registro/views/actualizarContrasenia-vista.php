<form id="loginForm" action="<?=base_url()?>registro/actualizarContrasenia" method="post">
	<?= $this->session->flashdata('msg'); ?>
  <fieldset class="bbW">
    <label>Contraseña</label>
	<input class="sans inBut" type="password" name="contrasenia" placeholder="Password" />
  </fieldset>
  <fieldset class="bbW">
    <label>Verificar contraseña</label>
	<input class="sans inBut" type="password" name="contraseniaVerificacion" placeholder="Password" />
  </fieldset>
  <input type="hidden" name="hash" value="<?= $this->uri->segment(3);?>" />
  <fieldset class="bbW">
	<input type="submit" value="Cambiar contraseña" />
  </fieldset>
</form>