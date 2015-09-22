<a class="closeCat" href="#"><img src="<?=base_url()?>assets/graphics/closeCat.png" alt="Cerrar Categoria" /></a>
<select class="val cateRepSec" id="cateRepSec">
	<option value="">Selecciona una categoría</option>
	<? foreach($cat as $conociento): ?>
	<option value="<?=$conociento->conocimientoId;?>"><?=$conociento->conocimiento;?></option>
	<? endforeach;?>
</select>
<select id="subcRepSec" style="display:none;"></select>
<a class="plus" href="<?=base_url()?>ajax/test">Agregar Categoria</a>

<script type="text/javascript" charset="utf-8">
$(function(){
	$('.plus').click(function(event){
		var filtro  = [];
		$.each($('.val'), function(){
		filtro.push($(this).val());
	});
	console.log(filtro);
	event.preventDefault();
	$(this).parent().after('<fieldset class="f100 mt8 catSEC"></fieldset>');
	$(this).parent().next().html('<img src="<?= base_url(); ?>assets/graphics/loading.gif" />').load($(this).attr('href'),{filtro:filtro});
	$('.plus').hide();
	})
})
</script>
<script>
$('.cateRepSec').change(function(){
var subcat = $('#subcRepSec');
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
	    $('#subcRepSec').show();			
		}
	});			
});

$(function(){
	$('.closeCat').click(function(event){
		event.preventDefault();
		$('#catSEC').remove();
		$('#plus').show();		
	});
});
</script>