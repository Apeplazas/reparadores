<? foreach($perfil as $row):?>
<?
$fechaNac = $row->fechaNacimiento;
//Separa la fecha en segmentos
$segmento = explode("-", $fechaNac);
$anio     = $segmento[0]; 
$mes      = $segmento[1]; 
$dia      = $segmento[2];
$imagen	= ($row->fotografiaPerfil != 'sinImagen.png') ? URLARCHIVOS.$row->fotografiaPerfil : null;
?>
<?= $this->session->flashdata('msg'); ?>
<form id="profile" method="post" enctype="multipart/form-data" action="<?=base_url()?>usuarios/actualizaDatosPerfil">
<aside id="pictWrap">
	<span class="frame">
	<fieldset>
		
		<img id="iconoFoto" src="<?=$imagen;?>" />
		<input id="userfile" class="subirFoto required" value="" type="file" size="35" name="userfile" onchange="readURL(this);" />
	</fieldset>
	</span>
	<a id="reviewProf" href="<?=base_url()?>" title="<?= $row->nombreCompleto;?> reputation">
		<span class="markColor">1,200</span>
		<em class="alegreya">my reviews</em>
	</a>
</aside>
<section id="infoProf">
	<div id="aju" class="fthin mb20"><img src="<?=base_url()?>assets/graphics/ajustes.png" alt="Ajustes" /><h3>Ajustes Personales</h3></div>
	<h1><?= $row->nombreCompleto;?></h1>
	<strong class="tag">Tu perfil personal</strong>
	
	<ul id="mainInfo">
	  <li class="wiRe">
	    <strong id="fechaNac">Fecha de Nacimiento:</strong>
	    <fieldset>
	      <select name="dia" id="dia">
	        <? if($dia == '00'):?> <option value="00" selected="selected"><? if ($dia == '00'):?>DIA<?else:?><?=$dia?><?endif;?></option><?endif?>
	      <?php for ($i=1;$i<=31;$i++){?>
	        <option value="<?=$i?>" <? if ($i==$dia){?> selected="selected" <? } ?> ><?=$i?></option>
	      <?php } ?>
	      </select>
	    </fieldset>
	    <fieldset>
		    <select name="mes" id="mes">
		    	<? if($mes == '00'):?> <option value="00" selected="selected"><? if ($mes == '00'):?>MES<?else:?><?=$mes?><?endif;?></option><?endif?>
			    <option value="01" <? if($mes=='01'):?>selected="selected"<?endif?>>ENERO</option>
			    <option value="02" <? if($mes=='02'):?>selected="selected"<?endif?>>FEBRERO</option>
			    <option value="03" <? if($mes=='03'):?>selected="selected"<?endif?>>MARZO</option>
			    <option value="04" <? if($mes=='04'):?>selected="selected"<?endif?>>ABRIL</option>
			    <option value="05" <? if($mes=='05'):?>selected="selected"<?endif?>>MAYO</option>
			    <option value="06" <? if($mes=='06'):?>selected="selected"<?endif?>>JUNIO</option>
			    <option value="07" <? if($mes=='07'):?>selected="selected"<?endif?>>JULIO</option>
			    <option value="08" <? if($mes=='08'):?>selected="selected"<?endif?>>AGOSTO</option>
			    <option value="09" <? if($mes=='09'):?>selected="selected"<?endif?>>SEPTIEMBRE</option>
			    <option value="10" <? if($mes=='10'):?>selected="selected"<?endif?>>OCTUBRE</option>
			    <option value="11" <? if($mes=='11'):?>selected="selected"<?endif?>>NOVIEMBRE</option>
			    <option value="12" <? if($mes=='12'):?>selected="selected"<?endif?>>DICIEMBRE</option>
		    </select>
	    </fieldset>
	    <fieldset>
		    <select name="anio" id="anio">
		    <? if($anio == '0000'):?> <option value="0000" selected="selected">AÑO</option><?endif?>
		    <?php for ($i=1905;$i<=2014;$i++){?>
		      <option value="<?=$i?>" <? if ($i==$anio){?> selected="selected" <? } ?> ><?=$i?></option>
		    <?php } ?>
		    </select>
	    </fieldset>
	  </li>
	  <li class="wiRe">
	    <strong id="sexo">Sexo:</strong>
	    <select name="genero" id="genero">
	    	<? if($row->genero == ''):?> <option value="" selected="selected">Genero</option><?endif?>
		    <option value="masculino" <? if($row->genero=='masculino'):?>selected="selected"<?endif?>>Hombre</option>
		    <option value="femenino" <? if($row->genero=='femenino'):?>selected="selected"<?endif?>>Mujer</option>
	    </select>
	  </li>
	   <li class="wiRe">
		  <textarea name="bio" id="bio" placeholder="<? if(isset($row->bio)):?><?= $row->bio;?><?else:?>Escribe una pequeña biografia de tus conocimientos, recuerda esta información sera la que los clientes vea de ti, se claro, consciso y cuida las faltas de ortografia.<?endif?>"><? if(isset($row->bio)):?><?= $row->bio;?><?endif?></textarea>
	  </li>
	  <li class="bckWhite wiRe">
	    <strong>Tu Url:</strong>
	    <p><?=base_url()?><? if(isset($row->urlPersonalizado)):?><?= $row->urlPersonalizado;?><?endif?></p>
	  </li>
	  <li class="bckWhite wiRe">
	    <strong>Email</strong>
		<p><?=$perfil[0]->email;?></p>
	  </li>
	  
	  <ul id="listaConocimientos">
	  <? if(!empty($conocimientos)): ?>
	  	<? foreach($conocimientos as $con): ?>
	  		<li id="<?= $con->usuarioId; ?>-<?= $con->conocimientoId; ?>-<?= $con->categoriaId; ?>"><?= $con->conocimiento; ?> - <?= $con->categoriaNombre; ?><span class="borarConocimiento">x</span></li>
	  	<? endforeach; ?>
	  <? endif; ?>
	  </ul>
	  
	<li class="wiRe">
	   <fieldset>
	   <select id="categoriaReparadores">
	   		<option value="">Selecciona una categoría</option>
			<? foreach($cat as $conociento): ?>
			<option value="<?=$conociento->conocimientoId;?>"><?=$conociento->conocimiento;?></option>
			<? endforeach;?>
		</select>
		<select id="subcategoriaReparadores" style="display:none;">
		</select>
		<button id="agregarConocimiento" style="display:none;">Agregar a mi perfil</button>
	   </fieldset>
		
	</li>
	  
	  <? if(empty($usurioTags)): ?>
	  <li class="wiRe">
	  	<span id="habilidades">Escribe tus habilidades</span>
	  	<ul id="myTags"></ul>
	  </li>
	  <? else: ?>
	  	<ul>
	  	<? foreach($usurioTags as $tag): ?>
	  		<li id="<?= $tag->habilidadId ?>"><?= $tag->habilidad ?><span class="borarTag">x</span></li>
	  	<? endforeach; ?>
	  	</ul>
	  	<li class="wiRe">
	  	<span id="habilidades">Agrgar más habilidades</span>
	  	<ul id="myTags"></ul>
	  </li>
	  <? endif; ?>
	  
	  <li class="wiRe">
		  <textarea name="bio" id="bio"  placeholder="<? if(isset($row->bio)):?><?= $row->bio;?><?else:?>Escribe una pequeña biografia de tus conocimientos, recuerda esta información sera la que los clientes vea de ti, se claro, consciso y cuida las faltas de ortografia.<?endif?>"><? if(isset($row->bio)):?><?= $row->bio;?><?endif?></textarea>
	  </li>
	</ul>
	<input type="submit" class="mt20 botonNegroSmaR borGri" value="Guardar cambios" />
</section>
<aside id="buttons">
	<h3 class="boLin">Promociona tu perfil con amigos.</h3>
	<div id="fb-root"></div>
	<a onclick="FacebookInviteFriends();" id="invSprite" title="Invite a tus amigos" href="#"><img src="<?=base_url()?>assets/graphics/amigosFacebook.png" alt="Invita a tus amigos a ver tu perfil" /></a>
</aside>
<input type="hidden" id="usuarioId" name="usuarioId" value="<?=$row->usuarioId;?>">
</form>

<? if(isset($row->estadoNombre)):?>
	  <div class="bckWhiteTwo ovHid wiRe">
	  	  <a class="editar" href="<?=base_url()?>usuarios/donde_estas"><em>Editar</em> <img src="<?=base_url()?>assets/graphics/editar.png" alt="Editar" /> </a>
	  	  <strong>Estado:</strong>
	  	  <p class="edoIn"><?= $row->estadoNombre;?></p>
	  	  <? if($row->delegacionNombre):?>
	  	  <strong>Delegación:</strong>
	  	  <p class="edoIn"><?= $row->delegacionNombre;?></p>
	  	  <? endif;?>
	  	  <? if($row->coloniaNombre):?>
	  	  <strong>Colonia:</strong>
	  	  <p class="edoIn"><?= $row->coloniaNombre;?></p>
	  	  <? endif;?>
	  </div>
	  <? else:?>
	  <div class="bckWhiteTwo wiRe">
		  <a id="ubi" href="<?=base_url()?>usuarios/donde_estas">Agrega tu ubicación <span><img src="<?=base_url()?>assets/graphics/clickaqui.png" alt="Selecciona tu ubicación aquí" /></span></a>
	  </div>
<?endif?>

<style>
	#iconoFoto{float:left; width:164px; height:200px; background: url(<?=base_url()?>assets/graphics/<?= $row->fotografiaPerfil;?>) center no-repeat; cursor: pointer;}
	#iconoFoto:hover{background: url(<?=base_url()?>assets/graphics/agregarImagen.png) center no-repeat;}
</style>
<? endforeach; ?>




<? if($perfil[0]->estatus == 'noAutorizado'):?>
	<p>Tu cuenta aún no ha sido activada!</p>
<? endif;?>


        
<script>

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
	                	
	                	$('#subcategoriaReparadores').show();
						
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
    	
    	var tagId 		= $(this).closest('li').attr('id');
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
