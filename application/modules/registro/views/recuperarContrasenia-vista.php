<div id="contentReg">
	<div class="logPad">
	<form id="loginForm" action="<?=base_url()?>registro/recuperar_hash" method="post">
	<p>Para recuperar su contraseña por favor proporcione el correo electronico con el que dio de alta su cuenta.</p>
	<?= $this->session->flashdata('msg'); ?>
  <fieldset class="bbW">
    <label>Email</label>
	<input class="sans inBut" type="email" name="email" placeholder="email" />
  </fieldset>
  <fieldset class="mt20">
    <input id="cLog" class="sans bYel" type="submit" value="Enviar">
  </fieldset>
  </form>
  </div>
</div>