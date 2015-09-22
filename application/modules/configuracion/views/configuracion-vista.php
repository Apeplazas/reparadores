<? foreach($perfil as $row):?>
<?= $this->session->flashdata('msg'); ?>
<section id="infoProf">
	<h1><?= $row->nombreCompleto;?></h1>
	<strong class="tag">Tu perfil personal</strong>
	
	<span id="wrapFoto">
	<fieldset class="prel">
		<div class="col-lg-6 cropHeaderWrapper">
			<div id="croppic"><? if($row->fotografiaPerfil != 'sinImagen.png'):?><img src="<?=base_url()?><?= $row->fotografiaPerfil;?>" style="max-width:180px;" alt="Fotografia <?= $row->nombreCompleto;?>" /><? endif;?></div>
			<span class="btn" id="cropContainerHeaderButton"></span>
		</div>
	</fieldset>
	</span>	
	<? if($row->tipoUsuario == 'reparador' || $row->tipoUsuario == 'mixto'):?>
	<!-- Si es mixto o reparador mustra estos segmentos -->
	<form method="post" enctype="multipart/form-data" action="<?=base_url()?>usuarios/actualizaDatosPerfil">
	<ul id="mainInfo">
	  <li class="bckWhite wiRe">
	  	<span class="sign"><img src="<?=base_url()?>assets/graphics/palomitaForm.png" alt="Correcto" /></span>
	    <strong>Tu Url:</strong>
	    <input id="alias" name="alias" type="text" value="<?=base_url()?><? if(isset($row->urlPersonalizado)):?><?= $row->urlPersonalizado;?><?endif?>"  placeholder="Escribe un alias" />
	  </li>
	  <li class="bckWhite wiRe">
	  <span class="sign"><img src="<?=base_url()?>assets/graphics/palomitaForm.png" alt="Correcto" /></span>
	    <strong>Email</strong>
		<p><?=$perfil[0]->email;?></p>
	  </li>
	  <li class="wiReCat">
	  <span id="signText" class="sign"><? if($row->bio == ''):?><img src="http://reparadores.mx/assets/graphics/tacheForm.png" alt="Error" /><?else:?><img src="<?=base_url()?>assets/graphics/palomitaForm.png" alt="Correcto" /><? endif;?></span>
	  <div id="wrapText">
		  <textarea name="bio" id="bio" placeholder="<? if($row->bio):?><?= $row->bio;?><?else:?>Escribe una pequeña biografia de tus conocimientos, recuerda esta información sera la que los clientes vea de ti, se claro, consciso y cuida las faltas de ortografia.<?endif?>"><? if(isset($row->bio)):?><?= $row->bio;?><?endif?></textarea>
	  </div>
	  </li>
	  </ul>
	  </form>
	  <? if(isset($row->estadoNombre)):?>
	  <ul class="mt20 f100">
	  <li class="bckWhiteTwo ovHid mb10">
	  	  <a class="editar" href="<?=base_url()?>usuarios/donde_estas"><em>Editar</em> <img src="<?=base_url()?>assets/graphics/editar.png" alt="Editar" /> </a>
	  	  <p class="edoIn"><strong>Estado:</strong><?= $row->estadoNombre;?></p>
	  	  <? if($row->delegacionNombre):?>
	  	  <p class="edoIn"><strong>Delegación:</strong><?= $row->delegacionNombre;?></p>
	  	  <? endif;?>
	  	  <? if($row->coloniaNombre):?>
	  	  <p class="edoIn"><strong>Colonia:</strong><?= $row->coloniaNombre;?></p>
	  </li>
	  </ul>
	  <? endif;?>
	
	<? else:?>
	<ul>
	  <li class="breakline"></li>
	  <li class="bckWhite wiRe map">
		  <span class="signUbi"><img src="<?=base_url()?>assets/graphics/ubi.png" alt="Selecciona tu ubicación aquí" /></span>
		  <a id="ubi" href="<?=base_url()?>usuarios/donde_estas">Agrega tu ubicación </a>
	  </li>
	  <?endif?>
	
	<li class="breaklSimple ind">Reparaciones</li>
	
	
	<li class="wiRe100">
	
	<div id="habWrap">
	<? foreach($conocimientos as $rowB):?>
		<div class="hab item">
			<h3><?= $rowB->conocimiento;?></h3>
			<ul id="lisHab">
				<? $categoria =  $this->data_model->cargarCatID($rowB->conocimientoId, $row->usuarioId );?>
				<? foreach($categoria as $rowC):?>
				<li id="<?= $row->usuarioId; ?>-<?= $rowB->conocimientoId; ?>-<?= $rowC->categoriaId; ?>" > <span class="borarConocimiento"><img src="<?=base_url()?>assets/graphics/trashIcon.png" alt="Borrar" /></span> <?= $rowC->categoriaNombre;?></li>
				<? endforeach; ?>
			</ul>
		</div>
	<? endforeach; ?>
	</div>
	
	<h4 class="perTit">Proporciona información de tus conocimientos. (Escoge entre las categorias proporcionadas)</h4>
		<form id="profile" method="post" action="<?=base_url()?>">
			<fieldset class="f100">
		     <select name="cat" class="val" id="categoriaReparadores">
		   		<option value="">Selecciona una categoría</option>
				<? foreach($cat as $conociento): ?>
				<option value="<?=$conociento->conocimientoId;?>"><?=$conociento->conocimiento;?></option>
				<? endforeach;?>
			  </select>
			  <ul id="subcategoriaReparadores" style="display:none;">
			  </ul>
		     </fieldset>
		     <fieldset  class="f100">
			     <input class="dnone" id="guBot" type="submit" value="Guardar" />
		     </fieldset>
		   </form>
	</li>
	</ul>
	<form id="divHab" method="post" action="<?=base_url()?>usuarios/actualizaDatosPerfil">
	<ul id="adedHab">
	<li class="breaklSimple ind">Agrega tus habilidades</li>
	  <? if(empty($usurioTags)): ?>
	  <li class="wiRe100 mt20">
	  	<span id="habilidades">Escribe tus habilidades</span>
	  	<ul id="myTags"></ul>
	  </li>
	  <? else: ?>
	  	<ul id="tagList">
	  	<? foreach($usurioTags as $tag): ?>
	  		<li id="<?= $tag->habilidadId ?>"><span class="borarTag"><img src="http://reparadores.mx/assets/graphics/trashIcon.png" alt="Borrar"></span><?= $tag->habilidad ?></li>
	  	<? endforeach; ?>
	  	</ul>
	  	<li>
	  	<span id="habilidades">Agregar más habilidades</span>
	  	<ul id="myTags"></ul>
	  </li>
	  <? endif; ?>
		</ul>
		<fieldset  class="f100">
			<input id="guBotSec" class="mb20" type="submit" value="Guardar" />
		</fieldset>
		     
		     
	</form>
	<? endif;?>

</section>


<style>
	#iconoFoto{float:left; width:164px; height:200px; background: url(<?=base_url()?>assets/graphics/<?= $row->fotografiaPerfil;?>) center no-repeat; cursor: pointer;}
	#iconoFoto:hover{background: url(<?=base_url()?>assets/graphics/agregarImagen.png) center no-repeat;}
</style>
<? endforeach; ?>




<? if($perfil[0]->estatus == 'noAutorizado'):?>
	<p>Tu cuenta aún no ha sido activada!</p>
<? endif;?>

<script type="text/javascript" charset="utf-8">
$(function(){
	$('#guBot').click(function(event){
		event.preventDefault();
		$('#habWrap').html('<img src="<?=base_url()?>assets/graphics/loadingBar.gif">  loading...');
		
		$('#lisHab').empty();
		var str = $('#profile').serialize();
		console.log(str);
		if(str.search("subCat") == -1){
			alert("Escoga alguna de nuestras categorias");
			return false;
		}
			
		$(this).before('<li class="f100 mt8"></li>');
		
		$.ajax({
		data: $('#profile').serialize(),
			url:   ajax_url+'actualizaHab',
			type:  'post',
			success:  function (response) {
				$( "#habWrap" ).empty();
				$( "#habWrap" ).append(response);
				var $select = $('#categoriaReparadores');
				$('option:selected',$select).remove();
				$('#subcategoriaReparadores').empty();
				$('#guBot').hide();
			}
		});	
	});
		
})
</script>
<script type="text/javascript" charset="utf-8">
$(function(){
	$('#guBotSec').click(function(event){
		event.preventDefault();
		$('#tagList').empty();
		$('#tagList').html('<img src="<?=base_url()?>assets/graphics/loadingBar.gif">  loading...');
		var str = $('#divHab').serialize();
		console.log(str);
		if(str.search("tags") == -1){
			alert("Escriba que habilidades tiene.");
			return false;
		}
		$(this).before('<li class="f100 mt8"></li>');
		
		$.ajax({
		data: $('#divHab').serialize(),
			url:   ajax_url+'actualizaDatosPerfil',
			type:  'post',
			success:  function (response) {
				$( "#tagList" ).empty();
				$(".tagit-choice").remove();
				$( "#tagList" ).append(response);
			}
		});	
	});
		
})
</script>
<script>
var croppicHeaderOptions = {
	uploadUrl:'ajax/imageTemp',
		cropData:{
			"dummyData":1,
			"dummyData2":"asdas"
		},
		cropUrl:'ajax/imageCropper',
		customUploadButtonId:'cropContainerHeaderButton',
		modal:false,
		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
		onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
		onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
		onImgDrag: function(){ console.log('onImgDrag') },
		onImgZoom: function(){ console.log('onImgZoom') },
		onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
		onAfterImgCrop:function(){ console.log('onAfterImgCrop') }
	}	
var croppic = new Croppic('croppic', croppicHeaderOptions);
</script>

<script>
$('#categoriaReparadores').change(function(){
	$('#loading').html('<img src="<?=base_url()?>assets/graphics/loading.gif"> loading...');
	var subcat = $('#subcategoriaReparadores');
	$.ajax({
		data:  {'referencia':$(this).val(),"mostrarPor":"id" },
			dataType : 'json',
			url:   ajax_url+'muestraSubcategorias',
			type:  'post',
			success:  function (response) {
				subcat.html('');
				subcat.append('<legend>* Selecciona los conocimientos que tengas en esta subcategoria</legend>');
				$.each(response,function(key,val){
				if(val.categoriaId != null){
					subcat.append('<span class="subCaRe"><input name="subCat[]" type="checkbox" value="'+ val.categoriaId +'" /><label>' + val.categoriaNombre + '</label></span>');
				}
			});
		$('#subcategoriaReparadores').show();
		$('#plus, #guBot').show();
		}
	});	
});

$('#categoriaReparadores').change(function(){
	$('#agregarConocimiento').show();
});

// Tagit
$("#myTags").tagit({
	fieldName: "tags[]",
	tagSource: function(search, showChoices) {
		$.ajax({
			url: "/ajax/buscaHabilidades",
			data: { 'q': search.term },
			dataType: "json",
			type:  'post',
			success: function(data) {
				showChoices(data);
			}
		});
    }
});
    
$("#agregarConocimiento").click(function(e){
	e.preventDefault();
	
	var conocimientoId 	= $('#categoriaReparadores').val();
	var subcatId 		= $('#subcategoriaReparadores').val();
    var usuarioId 		= $('#usuarioId').val();
    var conocimientoNombre 	= $('#categoriaReparadores option[value="'+conocimientoId+'"]').text();
    var subCatNombre 		= $('#subcategoriaReparadores option[value="'+subcatId+'"]').text();
    
    if(conocimientoId == '' || subcatId == ''){
	    alert("seleccione una categoría y una subcategoría");
	    return false;
    }
    
    $.ajax({
	    url: "/ajax/agregarConocimiento",
	    data: { 'conocimientoId': conocimientoId,'subcatId':subcatId,'usuarioId':usuarioId },
	    dataType: "json",
	    type:  'post'
	});
	$('#listaConocimientos').append('<li id="' + usuarioId + '-'+conocimientoId+'-'+subcatId+'">'+conocimientoNombre +' - '+ subCatNombre +'<span class="borarConocimiento">x</span></li>');
});
    
$(".borarConocimiento").click(function(){
	var conocimientoDatos	= $(this).closest('li').attr('id');
	$.ajax({
		url: "/ajax/borrarConocimiento",
		data: { 'conocimientoDatos': conocimientoDatos },
		dataType: "json",
		type:  'post'
	});
$('#'+conocimientoDatos).remove(); 	
});
    
$(".borarTag").click(function(){ 
	var tagId = $(this).closest('li').attr('id');
    $.ajax({
	    url: "/ajax/borrarTag",
	    data: { 'tagId': tagId },
	    dataType: "json",
	    type:  'post'
	});
$('#'+tagId).remove();
});
    
function readURL(input) {
		var imageUrl = input.name+'img';
        if (input.files && input.files[0]) {
        	
        	formdata = new FormData(); 
        	
        	var reader = new FileReader();
            reader.onload = function(e) {
            	if (/^image/.test(input.files[0].type)){
            		console.log(input.files[0]);
            		formdata.append("archivos[<?=$perfil[0]->usuarioId;?>]", input.files[0]);
            		$('#iconoFoto').attr('src', e.target.result);
            		$.ajax({
		                data:  formdata,
						dataType : 'json',
						processData: false,
    					contentType: false,
		                url:   ajax_url+'actualizaImagenPerfil',
		                type:  'post',
		                success:  function (response) {
							
		                }
		        	});
            	}else{
            		alert("No se acepta este tipo de archivo");
            		return false;
            	}            	
            }
            
            reader.readAsDataURL(input.files[0]);
 
       }
	}
</script>
<script type="text/javascript">
// with jQuery
$('#habWrap').masonry({
  columnWidth: 100,
  itemSelector: '.item'
});</script>