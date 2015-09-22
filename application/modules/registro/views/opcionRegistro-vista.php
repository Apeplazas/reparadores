<div id="contentReg">
	<div class="regPad">
	<h1>Bienvenido Reparador</h1>
	<p>Aquí encontrarás una nueva forma de encontrar y obtener clientes que necesitan reparar algún equipo. Regístrate usando tu cuenta de Facebook.</p>
	<p><b>Indica en qué tipo de reparaciones te especializas.</b></p>
	<br class="clear">
	<? if(isset($_GET['tipo']) && $_GET['tipo'] == 'reparador'):?>
	<div id="catRep" class="show">
	<? if(!empty($conocimientos)):?>
		<fieldset class="show catField">
		<label>Categoría:</label>	
		<div class="sSelect">
		<select id="categoriaReparadores">
			<? if(empty($subcategorias)):?>
			<option value="">Selecciona una categoría</option>
			<? endif;?>
			<? foreach($conocimientos as $conociento): ?>
			<option value="<?=$conociento->conocimientoId;?>" ><?=$conociento->conocimiento;?></option>
			<? endforeach;?>
		</select>
		</div>
		</fieldset>	
	<? endif;?>
	
	<br class="clear">
	<fieldset class="show catField">
	<label>Subcategoría:</label>
	<div class="sSelect">
		<select id="subcategoriaReparadores">
			<option value="">Selecciona una subcategoría</option>
			<? if(!empty($subcategorias)):?>
				<? foreach($subcategorias as $subcatego): ?>
				<option value="<?=$subcatego->categoriaId;?>" ><?=$subcatego->categoriaNombre;?></option>
				<? endforeach;?>
			<? endif;?>
		</select>
	</div>
	</fieldset>
	</div>
	<? endif;?>
	
	<div id="verReg" class="hide">
	<span><a id="back" href="#" title="regresar">Regresar</a></span>
		<span class="mt20">
			<a id="faceConeTwo" href="<?=base_url()?>registro/facebooklogin?tipo=<? if(isset($_GET['tipo'])) echo $_GET['tipo'];?>">Conéctate con facebook</a>
		</span>
		<span>o usando tu</span>
		<span>
			<a id="emaCone" href="<?=base_url()?>registro/poremail?tipo=<? if(isset($_GET['tipo'])) echo $_GET['tipo'];?>">CUENTA DE CORREO ELECTRONICO</a>
		</span>
		<span>O si buscas reparadores da <a href="<?=base_url()?>">click aquí</a></span>
		</div>
	</div>
</div>

<? if(isset($_GET['tipo']) && $_GET['tipo'] == 'reparador'):?>
<script>
$(document).ready(function(){
	$('#categoriaReparadores').change(function(){
		var subcat = $('#subcategoriaReparadores');
		$.ajax({
	            data:  {'referencia':$(this).val(),"mostrarPor":"id" },
				dataType : 'json',
	            url:   ajax_url+'muestraSubcategorias',
	            type:  'post',
				success:  function (response) {
	              	
	                subcat.html('');
	                subcat.append('<option value="">Selecciona una subcategoría</option>');
	                $.each(response,function(key,val){       		
					if(val.categoriaId != null){
	                			
	           			subcat.append('<option value="' + val.categoriaId + '">' + val.categoriaNombre + '</option>');
	                			
					}
	                		
				});
						
			}
		});			
	});

	$('#subcategoriaReparadores').change(function(){
		$('#verReg').addClass('show').removeClass('hide');
		$('#catRep').addClass('hide').removeClass('show');		
	});
	$('#back').click(function(){
		$('#verReg').addClass('hide').removeClass('show');
		$('#catRep').addClass('show').removeClass('hide');		
	});

	$('#faceConeTwo,#emaCone').click(function(){
		if($('#categoriaReparadores').val() == ''){
			alert("Favor de elegir una categoría");
			return false;
		}
		if($('#subcategoriaReparadores').val() == ''){
			alert("Favor de elegir una subcategoría");
			return false;
		}
		var conocimientos = [$('#categoriaReparadores').val(),$('#subcategoriaReparadores').val()];
		$.cookie("reparadorCon",escape(conocimientos.join(',')));
		
	});
		
});
</script>
<? endif;?>