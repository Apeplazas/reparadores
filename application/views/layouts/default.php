<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
<? foreach($opt as $rowOpt):?>
<title><?= $rowOpt->metaTitulo;?></title>
<meta name="description" content="<?=$rowOpt->metaDescripcion;?>" />
<? endforeach; ?>
<meta name="robots" content="All,index, follow" />
<link type="text/css" href="<?=base_url()?>assets/css/style.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/css/jquery.fancybox.css" rel="stylesheet"/>
<script language="javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/jquery.cookie.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/jquery.formatCurrency-1.4.0.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/jquery.fancybox.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.15&sensor=false&libraries=places"></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
	FB.init({
		appId:'1463139797288554',
		cookie:true,
		status:true,
		xfbml:true
	});
	
	function FacebookInviteFriends(){
		FB.ui({
			method: 'apprequests',
			message: 'Invita a tus amigos'
		});
	}
</script>
<script>
	var ajax_url = "<?=base_url();?>ajax/"
</script>
<script language="javascript" src="<?=base_url()?>assets/js/functions.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
<?= $this->layouts->print_includes(); ?>
<link rel="icon" type="image/png" href="<?=base_url()?>assets/graphics/test.ico" />
<? if ($this->uri->segment(2) == 'activar_cuenta'):?>
<!-- Facebook Conversion Code for Reparadores - Registros -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6028725757820', {'value':'0.01','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6028725757820&amp;cd[value]=0.01&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
<? endif;?>
</head>
<body id="default">
	
<?= $this->load->view('includes/header');?>
<br class="clear">
<? $usuario		= $this->session->userdata('usuario');?>
<? if ($usuario):?>
<aside>
	<ul id="bar">
		<li class="prel inactive pict"><a href="<?=base_url()?>dashboard"><img src="<?=base_url()?>assets/graphics/perfilIcon.png" alt="" /><i>Dashborad</i></a></li>
		<li class="prel inactive"><a href="<?=base_url()?>configuracion"><span><img src="<?=base_url()?>assets/graphics/configuracionIcon.png" alt="Test" /></span><em>Configuración</em></a></li>
		<? if ($usuario['tipoUsuario'] == "reparador" || $usuario['tipoUsuario'] == "mixto"):?>
		<li class="prel inactive"><a href="<?=base_url()?>avisosdeocacion"><span><img src="<?=base_url()?>assets/graphics/dashboard.png" alt="Test" /></span><em>Solicitudes</em></a></li>
		<? endif;?>
		<li class="prel inactive"><a href="<?=base_url()?>usuarios/mensajes"><span><img src="<?=base_url()?>assets/graphics/inboxIcon.png" alt="Test" /></span><em>Mensajes</em></a></li>
		<li class="prel inactive"><a onclick="FacebookInviteFriends();" id="invSpriteTwo" title="Invita a tus amigos" href="#"><img src="<?=base_url()?>assets/graphics/encuentraAmigos.png" alt="Invita a tus amigos a ver tu perfil"><em>Amigos</em></a></li>
	</ul>
</aside>

<?endif;?>
<?= $content; ?>
<footer id="foo">
<? $this->load->view('includes/footer');?>
</footer>
<?= $this->load->view('includes/loginForm');?>
</body>
</html>

