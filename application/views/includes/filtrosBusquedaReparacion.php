<div id="buscarReparadores" >
<form action="#">
<h1>La comunidad mas grande de reparadores en México</h1>
<p>Busca y contacta a personas dependiendo necesidades </p>

	<span id="wrapForm">
	<label>Categoria:</label>
	<select id="categoriaReparadores">
		<? foreach($conocimientos as $conociento): ?>
		<option value="<?=$conociento->url;?>"><?=$conociento->conocimiento;?></option>
		<? endforeach;?>
	</select>
	
	<label>Subcategoria:</label>
	<select id="subcategoriaReparadores">
		<? foreach($subcategorias as $subcatego): ?>
		<option value="<?=$subcatego->url;?>" ><?=$subcatego->categoriaNombre;?></option>
		<? endforeach;?>
	</select>
	
	<label>Estado:</label>
	<select>
		<? foreach($estados as $estado): ?>
		<option value="<?=$estado->estados;?>"><?=$estado->estados;?></option>
		<? endforeach;?>
	</select>
	<input id="filtroBoton" type="submit" value="Buscar" />
	</span>
</form>
</div>

<script>
$(document).ready(function(){
	$('#categoriaReparadores').change(function(){
	var subcat = $('#subcategoriaReparadores');
			$.ajax({
	                data:  {'referencia':$(this).val(),"mostrarPor":"url" },
					dataType : 'json',
	                url:   ajax_url+'muestraSubcategorias',
	                type:  'post',
	                success:  function (response) {
	                	
	                	subcat.html('');
	                	$.each(response,function(key,val){
	                		
	                		if(val.categoriaId != null){
	                			
	                			subcat.append('<option value="' + val.url + '">' + val.categoriaNombre + '</option>');
	                			
	                		}
	                		
	                	});
						
	                }
	        });
	        
			
		});
		
		$('#buscarReparadores').submit(function(e){
			
			e.preventDefault();
			var cat 	= $('#categoriaReparadores').val();
			var subcat 	= $('#subcategoriaReparadores').val();
			window.location = cat+"/"+subcat;
			
		});
		
	});
</script>