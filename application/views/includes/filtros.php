<div id="buscarReparadores" >
<form action="#">
<h1>La comunidad más grande de Reparadores en México</h1>
<p>Encuentra al reparador más cercano a tu domicilio.</p>

	<span id="wrapForm">
	<? if(!empty($conocimientos)):?>
	<label>Categoría:</label>
	<select id="categoriaReparadores">
		<? if(empty($subcategorias)):?>
		<option value="">Selecciona una categoría</option>
		<? endif;?>
		<? foreach($conocimientos as $conociento): ?>
		<option value="<?=$conociento->url;?>" <? if($conociento->url == $conocimientoUrl) echo "selected";?>><?=$conociento->conocimiento;?></option>
		<? endforeach;?>
	</select>
	<? endif;?>
	
	
	<label>Subcategoría:</label>
	<select id="subcategoriaReparadoresTwo">
		<option value="all">Selecciona una subcategoría</option>
		<? if(!empty($subcategorias)):?>
			<? foreach($subcategorias as $subcatego): ?>
			<option value="<?=$subcatego->url;?>" ><?=$subcatego->categoriaNombre;?></option>
			<? endforeach;?>
		<? endif;?>
	</select>
	
	
	<?php if(!empty($estados)):?>
	<label>Estado:</label>
	<select id="estadoReparador">
		<option value="">Selecciona un estado</option>
		<? foreach($estados as $estado): ?>
		<option value="<?=$estado->estados;?>"><?=$estado->estados;?></option>
		<? endforeach;?>
	</select>
	<? endif;?>
	<input id="filtroBoton" type="submit" value="Buscar" />
	</span>
</form>
</div>

<script>
$(document).ready(function(){
	$('#categoriaReparadores').change(function(){
	var subcat = $('#subcategoriaReparadoresTwo');
			$.ajax({
	                data:  {'referencia':$(this).val(),"mostrarPor":"url" },
					dataType : 'json',
	                url:   ajax_url+'muestraSubcategorias',
	                type:  'post',
	                success:  function (response) {
	                	
	                	subcat.html('');
	                	subcat.append('<option value="all">Selecciona una subcategoría</option>');
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
			var subcat 	= $('#subcategoriaReparadoresTwo').val();
			var estado 	= $('#estadoReparador').val();
			
			var redirect = (estado) ? "<?=base_url();?>"+cat+"/"+subcat+"/"+estado : "<?=base_url();?>"+cat+"/"+subcat;

			window.location = redirect;
			
		});
		
	});
</script>