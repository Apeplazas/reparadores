<? foreach($perfil as $row):?>
<?
$fechaNac = $row->fechaNacimiento;
//Separa la fecha en segmentos
$segmento = explode("-", $fechaNac);
$anio     = $segmento[0]; 
$mes      = $segmento[1]; 
$dia      = $segmento[2];
?>
<?= $this->session->flashdata('msg'); ?>
<form id="profile" method="post" enctype="multipart/form-data" action="<?=base_url()?>usuarios/actualizaDatosPerfil">
<aside id="pictWrap">
	<span class="frame">
	<fieldset>
		<a id="iconoFoto" >
		<input id="userfile" class="subirFoto required" value="" type="file" size="35" name="userfile" />
		</a>
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
	    aqui va el dia
	      </select>
	    </fieldset>
	    <fieldset>
		    aqui va la mes
	    </fieldset>
	    <fieldset>
		   aqui el año
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
	  <li class="bckWhite wiRe">
	    <strong>Tu Url:</strong>
	    <input id="alias" name="alias" type="text" value="<?=base_url()?><? if(isset($row->urlPersonalizado)):?><?= $row->urlPersonalizado;?><?endif?>"  placeholder="Escribe un alias" />
	  </li>
	  <li class="bckWhite wiRe">
	    <strong>Email</strong>
		<p><?=$perfil[0]->email;?></p>
	  </li>
	  <li class="wiRe">
		  <textarea name="bio" id="bio" placeholder="<? if(isset($row->bio)):?><?= $row->bio;?><?else:?>Escribe una pequeña biografia de tus conocimientos, recuerda esta información sera la que los clientes vea de ti, se claro, consciso y cuida las faltas de ortografia.<?endif?>"><? if(isset($row->bio)):?><?= $row->bio;?><?endif?></textarea>
	  </li>
	</ul>
	<input type="submit" class="mt20 botonNegroSmaR borGri" value="Guardar cambios" />
</section>
<aside id="buttons">
	<h3 class="boLin">Promociona tu perfil con amigos.</h3>
	<div id="fb-root"></div>
	<a onclick="FacebookInviteFriends();" id="invSprite" title="Invite a tus amigos" href="#"><img src="<?=base_url()?>assets/graphics/amigosFacebook.png" alt="Invita a tus amigos a ver tu perfil" /></a>
</aside>
</form>

<? if(isset($row->estadoNombre)):?>
	  <li class="bckWhiteTwo ovHid wiRe">
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
	  </li>
	  <? else:?>
	  <li class="bckWhiteTwo wiRe">
		  <a id="ubi" href="<?=base_url()?>usuarios/donde_estas">Agrega tu ubicación <span><img src="<?=base_url()?>assets/graphics/clickaqui.png" alt="Selecciona tu ubicación aquí" /></span></a>
	  </li>
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

// Tagit
    $("#myTags").tagit({

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
</script>
