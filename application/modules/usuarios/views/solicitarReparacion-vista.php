<div id="avisos">
<h1 id="imag">¿Se te descompuso un equipo? Aquí está la solución.</h1>
<p id="imagP">Proporciona los datos y las características del equipo descompuesto.</p>
<form id="pregunta" method="post" action="<?=base_url();?>usuarios/guardarSolicitudReparacion" enctype="multipart/form-data"/>
	<fieldset>
		<label>Título</label>
		<input class="inpQue" type="text" name="titulo" value="" placeholder="Resume lo que necesitas"/>
	</fieldset>
	<? if(!empty($conocimientos)):?>
	<fieldset>
	<label>Categoría:</label>
	<select id="categoriaSolicitud" name="categoria">
		<option value="" >Selecciona una Categoría</option>
		<? foreach($conocimientos as $conociento): ?>
		<option value="<?=$conociento->conocimientoId;?>" ><?=$conociento->conocimiento;?></option>
		<? endforeach;?>
	</select>
	</fieldset>
	<? endif;?>
	<fieldset>
	<label>Subcategoría:</label>
	<select id="subcategoriaSolicitud" name="subcategoria">
		<option value="" >Elige una suncategoría</option>
	</select>
	</fieldset>
	
	<span class="wrapFrame">
	<fieldset>
		<a id="pic">
		<input id="agregarArchivo" class="subirFoto required" value="" type="file" name="userfile">
		</a>
	</fieldset>
	</span>
	

	<fieldset> 
	<label>Descripción:</label>
	<textarea name="descripcion" placeholder="Descripcion completa de lo ocurrido con tu equipo"></textarea>
	</fieldset>
	<fieldset>
	<input id="env" type="submit" value="Enviar"/>
	</fieldset>
</form>
<br class="clear">
</div>
<style>
	#pic{float:left; width:275px; height:85px; background: url(<?=base_url()?>assets/graphics/agregarFotografia.png) center no-repeat; cursor:pointer;}
</style>


<script>
	$(document).ready(function(){
		
		$("#agregarArchivo").click(function(e){
			e.preventDefault();
			$(this).after("<input type='file' name='archivos[]' />");
		});
		
		$('#categoriaSolicitud').change(function(){
			
			var subcat = $('#subcategoriaSolicitud');
			
			$.ajax({
	                data:  {'referencia':$(this).val(),"mostrarPor":"id" },
					dataType : 'json',
	                url:   ajax_url+'muestraSubcategorias',
	                type:  'post',
	                success:  function (response) {
	                	
	                	subcat.html('');
	                	$.each(response,function(key,val){
	                		
	                		if(val.categoriaId != null){
	                			
	                			subcat.append('<option value="' + val.categoriaId + '">' + val.categoriaNombre + '</option>');
	                			
	                		}
	                		
	                	});
						
	                }
	        });
	        
			
		});
		
	});
</script>