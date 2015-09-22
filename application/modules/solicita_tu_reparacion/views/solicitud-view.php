<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
<? foreach($opt as $rowOpt):?>
<title><?= $rowOpt->metaTitle;?></title>
<meta name="description" content="<?=$rowOpt->metaDescripcion;?>" />
<? endforeach; ?>
<meta name="robots" content="All,index, follow" />
<link type="text/css" href="<?=base_url()?>assets/css/style.css" rel="stylesheet"/>
<script language="javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/functions.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<?= $this->layouts->print_includes(); ?>
<link rel="icon" type="image/png" href="<?=base_url()?>assets/graphics/test.ico" />
</head>
<body>
<? $user =	$this->session->userdata('usuario'); ?>
<? $usuario = $this->usuario_model->buscaPerfilID($user['usuarioID'])?>
<header id="indexH">
	<div class="centerWrap">
	<a id="logo" href="<?=base_url()?>"><img src="<?=base_url()?>assets/graphics/logo-reparadores.png" alt="Reparadores Mx" /></a>
	<span>
		<img src="<?=base_url()?>assets/graphics/atencionaclientes-reparadores.png" alt="Atencion a clientes" />
		<? if(!isset($user) || $user != true):?>
			<a id="acceso" href="<?=base_url()?>registro/ingresar"><img src="<?=base_url()?>assets/graphics/acceso.png" alt="Accesar" /></a>
		<? else:?>
			<a id="notif" href="<?=base_url()?>usuarios/notificaciones"><img alt="Notificaciones" src="<?=base_url()?>assets/graphics/notificacionesIcono.png"></a>
			<? foreach($usuario as $row):?>
			<a id="dash" href="<?=base_url()?>dashboard"><img src="<?=base_url()?>assets/graphics/small<?= $row->fotografiaPerfil;?>" alt="<?= $row->nombreCompleto;?>" /> <em><?= $user['nombre']?></em></a>
			<? endforeach; ?>
		<? endif;?>
	</span>
	</div>
</header>
<br class="clear">
<div id="preguntas">
<a id="cerrarG" href="<?=base_url()?>"><img src="<?=base_url()?>assets/graphics/cerrarG.png" alt="Regresar" /></a>

<form id="catForm" action="<?=base_url()?>solicita_tu_reparacion/guardaSolicitud" method="post">
<ul id="firsForm" class="show">
<li>
<h1>Solicita tu reparación de equipo aquí</h1>
<p>Proporciona la informacion que se solicita en el formulario</p>
</li>
	<li id="catS" class="show">
	   <fieldset id="catSelfield" class="styled-select">
	   <select id="catSel" name="catSel">
	      <option disabled selected>¿Que tipo de articulo deseas reparar?</option>
	      <? foreach($cat as $row):?>
	        <option value="<?= $row->conocimientoId;?>"><?= ucfirst($row->conocimiento);?></option>
	      <? endforeach; ?>
	   </select>
	   </fieldset>
		
	</li>
	<li id="subC" class="hide">
	   <fieldset id="subCatSelfield" class="styled-select">
	   <select id="subCatSel" name="subCatSel">
	      <option disabled selected>¿Que necesitas que se repare?</option>
	      <? foreach($cat as $row):?>
	        <option value="<?= $row->conocimiento;?>"><?= ucfirst($row->conocimiento);?></option>
	      <? endforeach; ?>
	        <option value="Otro">Otro</option>
	   </select>
	   </fieldset>
	</li>
	<li id="fotC" class="hide">
		<span class="wrapFrameTwo">
		<fieldset class="styled-selectTwo">
			<a id="pic">
			<input id="agregarArchivoTwo" class="subirFoto required" value="" type="file" name="userfile">
			</a>
		</fieldset>
		</span>
		<span id="notengo"><img src="<?=base_url()?>assets/graphics/notengoimagenes.png" alt="No tengo imagenes" /></span>
	</li>
	<li id="hFot" class="hide">
		<fieldset>
			<textarea id="test" name="comentario" placeholder="Este es un comentario"></textarea>
		</fieldset>
	</li>
	<li id="bout" class="hide">
		<fieldset>
			<span id="fina"><img src="<?=base_url()?>assets/graphics/finalizar.png" alt="" /></span>
		</fieldset>
	</li>
</ul>
<ul id="secSeg" class="hide">
<li>
<h1>Ingresa tus datos completos</h1>
<p>En cuanto finalizes empezaras a recibir cotizaciones</p>
</li>
	<li>
		<fieldset class="segFi">
			<input class="in" type="text" placeholder="Nombre Completo" name="usuarioNombre" id="usuarioNombre" />
		</fieldset>
		<fieldset class="segFi">
			<input class="in" type="email" placeholder="ejemplo@gmail.com" name="email" id="emailUsuario" />
		</fieldset>
	</li>
	<li>
		<input id="finForm" type="image" src="<?=base_url()?>assets/graphics/finalizar.png" />
	</li>
</ul>
<br class="clear">
</form>
</div>


<style>
	#pic{float:left; width:375px; height:85px; background: url(http://reparadores.mx/assets/graphics/agregarFotografia.png) -8px -9px no-repeat; cursor:pointer;}
</style>
<script>
$('#catSel').change(function(){
	$('#subC').addClass('show').removeClass('hide');
});
$('#subC').change(function(){
	$('#fotC').addClass('show').removeClass('hide');
});
$('#agregarArchivoTwo').change(function(){
	$('#hFot').addClass('show').removeClass('hide');
});
$('#test').focus(function(){
	$('#bout').addClass('show').removeClass('hide');
});
$('#notengo').click(function(){
	$('#hFot').addClass('show').removeClass('hide');
});
$('#fina').click(function(){
	if( $('#catSel').val() == '' || $('#subCatSel').val() == '' || $('#test').val() == '' ){
		alert("Favor de ingresar todos los campos");
		return;
	}
	$('#firsForm').addClass('hide').removeClass('show');
	$('#secSeg').addClass('show').removeClass('hide');
});


</script>
<script type="text/javascript">
// Busca subcategorias 
$(document).ready(function(){
	//Para cargar los modelos
	$("#catSel").change(function(){
		var filtro = $(this).val();
		$("#tipoArticulo").removeAttr("disabled");
		$.post("<?=base_url()?>ajax/cargarSubcategoria",{filtro:filtro},function(data){
			sucess:				
				$("#subCatSel").empty().append(data);
				$("#subCatSel").removeAttr("disabled");
		});
	});
	
	$("#catForm").submit(function(){
		
		if( $("#usuarioNombre").val() == '' || $("#emailUsuario").val() == '' ){
			alert("favor de ingresar todos los campos");
			return false;
		}
		
		if(!validateEmail($("#emailUsuario").val())){
			alert("favor de ingresar un email valido");
			return false;
		}
		
	});
	
	function validateEmail($email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	  	if( !emailReg.test( $email ) ) {
	    	return false;
	  	}else {
	    	return true;
	  }
	}
	
});
</script>

</body>
</html>