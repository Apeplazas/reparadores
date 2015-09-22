<? $usuario		= $this->session->userdata('usuario');?>
<div class="centWrapMes">
<aside id="mesOpen">
<strong id="his">Historial de Conversaciones</strong>
<? $this->load->view('includes/messages');?>
</aside>
<? $user = $this->session->userdata('usuario');?>
<section id="message">
<form action="<?= base_url();?>usuarios/contestarMensaje" method="post" id="contestarMensaje">
<div class="tools">
<strong>Conversación</strong>
<a class="newMes" href="<?=base_url()?>usuarios/mensajes"><img src="<?=base_url()?>assets/graphics/plus.png" alt="" />Nuevo Mensaje</a>
</div>
	<p id="titChat">Asunto: <?= $mensaje[0]->asunto;?></p>
	<? if($archivos):?>
		<h3>Imagenes</h3>
		<? foreach($archivos as $archivo):?>
			<img width="60px" src="<?=URLARCHIVOS.$archivo->nombre;?>"/>
		<? endforeach;?>
	<? endif;?>
	<ul>
		<? if($mensaje): ?>
		<? foreach($mensaje as $men):?>
		<li>
			<div class="<? if($usuario['usuarioID'] == $men->usuarioId):?>msgLeft<?else:?>msgRight<?endif?>">
			<span class="friendPicSma"><img width="35" height="35" src="<?=base_url()?><?if($men->fotografiaPerfil == 'sinImagen.png'):?>assets/graphics/Chat<?=$men->fotografiaPerfil?><?else:?><?=$men->fotografiaPerfil?><?endif;?>" alt="Fotografia <?=$men->nombreCompleto;?>" /></span>
				<div class="msgWroCom">
					<em><?=$men->fechaRespuesta;?></em>
					<p><?=$men->respuesta;?> </p>
				</div>
			</div>
		</li>
		<? endforeach;?>
		<? endif;?>
	</ul>
	<div id="write">
		<fieldset>
			<textarea name="respuesta" id="respuesta" placeholder="Ecribe tu mensaje..."></textarea>
		</fieldset>
		<fieldset>
			<input type="hidden" name="mensajeId" value="<?= $mensaje[0]->mensajeId;?>" />
			<input type="submit" value="Enviar" id="mesFor" />
		</fieldset>
	</div>
</form>
</section>
</div>

<script>
	$(document).ready(function(){
		
		$('#contestarMensaje').submit(function(){
			
			if($('#respuesta').val() == ''){
				
				alert("Favor de ingresar un mensaje");
				return false;
			}
			
		
		});
		
	});
	
</script>