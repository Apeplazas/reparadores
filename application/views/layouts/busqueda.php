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
<script src="https://maps.googleapis.com/maps/api/js?v=3.15&sensor=false&libraries=places"></script>
<script>
	var ajax_url = "<?=base_url();?>ajax/"
</script>
<script language="javascript" src="<?=base_url()?>assets/js/functions.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<?= $this->layouts->print_includes(); ?>
<link rel="icon" type="image/png" href="<?=base_url()?>assets/graphics/test.ico" />
</head>
<body id="default">
<header id="wrapHead">
	<div id="gen">
	<a id="logo" href="<?=base_url()?>"><img src="<?=base_url()?>assets/graphics/logo-reparadores.png" alt="Reparadores Mx" /></a>
	<span>
		<a id="acceso" href="<?=base_url()?>" rel="nofollow">Acceso</a>
		<a id="registrate" href="<?=base_url()?>" rel="nofollow">Registrate</a>
	</span>
	</div>
	
</header>
<br class="clear">
<?= $content; ?>

<? if(!isset($usuario) || $usuario != true ): ?>
<div id="ingresar" style="display:none;">
	<form id="loginForm" action="<?=base_url()?>registro/ingresar" method="post">
		<? if(isset($error)) echo $error;?>
	  	<span><div class="msgBlack"></div></span>
	  	<fieldset class="bbW">
	    	<label>User or Email</label>
			<input class="sans inBut" type="text" name="usuarioOEmail" placeholder="Username or email" />
	  	</fieldset>
	  	<fieldset class="bbW">
	    	<label>Contraseña</label>
			<input class="sans inBut" type="password" name="contrasenia" placeholder="Password" />
			<a id="forgot" href="<?=base_url()?>registro/recuperarContrasenia">¿Olvidaste tu contraseña?</a>
	  	</fieldset>
	  	<fieldset class="mt20">
			<input id="cLog" class="sans bYel" type="submit" value="Entrar" />
	  	</fieldset>
	  	<fieldset>
			<em id="or">o</em>
	  	</fieldset>
	  	<fieldset>
		  	<a id="faceCone" href="<?=base_url()?>registro/facebooklogin">Ingresar con facebook</a>
	  	</fieldset>
	  	<fieldset class="mt10">
		  	<i class="sans fOne">Al dar click en el boton entrar confirmas que aceptas nuestros</i><a class="sans fOne" href="<?=base_url()?>">Terminos  de Servicio</a>
	  	</fieldset>
	</form>
</div>
<? endif; ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55162334-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>

