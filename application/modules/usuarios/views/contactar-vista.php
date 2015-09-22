<div class="centWrapMes">
<section id="message">


<form method="post" action="<?=base_url();?>usuarios/guardarMensaje" enctype="multipart/form-data">
<label id="newMes">New message</label>
	<fieldset>
		<input id="toMsg" type="text" name="asunto" placeholder="To:" />
	</fieldset>
	<fieldset>
		<input id="toMsg" type="text" name="asunto" placeholder="Asunto:" />
	</fieldset>
	<div id="msgAjaSin">
		
	</div>
	<div id="writeTwo">
		<fieldset>
			<textarea name="usuarioMensaje" placeholder="Escribe tu mensaje aquí..."></textarea>
		</fieldset>
		<fieldset>
			<input type="hidden" name="usuarioId" value="<?=$usuarioId;?>" />
			<input type="hidden" name="urlRegreso" value="<?=$_GET['url'];?>" />
			<input type="submit" value="Send it" id="mesFor" />
		</fieldset>
	</div>
</form>
</section>
</div>
<script>
$(document).ready(function(){
	$("#agregarArchivo").click(function(e){
		e.preventDefault();
		$(this).after("<input type='file' name='archivos[]' />");
	});
});
</script>