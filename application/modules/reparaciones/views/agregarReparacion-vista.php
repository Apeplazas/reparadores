<form method="post" action="<?=base_url();?>reparaciones/guardarreparacion" enctype="multipart/form-data">
	<label>Titulo</label>
	<input type="text" name="titulo" value="" />
	<button id="agregarArchivo">Agregar archivo</button> 
	<label>Descripción:</label>
	<textarea name="descripcion"></textarea>
	<input type="hidden" name="usuarioId" value="<?=$usuarioId;?>" />
	<input type="submit" value="Publicar"/>
</form>

<script>
	$(document).ready(function(){
		
		$("#agregarArchivo").click(function(e){
			e.preventDefault();
			$(this).after("<input type='file' name='archivos[]' />");
		});
		
	});
</script>