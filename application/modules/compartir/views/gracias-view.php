<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!--meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
<? foreach($op as $rowOpt):?>
<title><?= $rowOpt->metaTitle;?></title>
<meta name="description" content="<?=$rowOpt->metaDescripcion;?>" />
<? endforeach; ?>
<meta name="robots" content="All,index, follow" /-->
<link type="text/css" href="<?=base_url()?>assets/css/style.css" rel="stylesheet"/>
<script language="javascript" src="<?=base_url()?>assets/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<!-- <meta name="viewport" content="width=device-width, initial-scale=0, user-scalable=no, minimum-scale=0, maximum-scale=0" />-->
<script language="javascript" src="<?=base_url()?>assets/js/functions.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/jquery.easing.1.3.js" type="text/javascript"></script>
<?= $this->layouts->print_includes(); ?>
<link rel="icon" type="image/png" href="<?=base_url()?>assets/graphics/test.ico" />
</head>
<body id="bckVerde">
	



<?= $this->load->view('includes/header');?>
<br class="clear">
<section id="texto">
<? $this->load->view('includes/menus/headerMenu');?>
	<span id="tec"><img src="<?=base_url()?>assets/graphics/experto-reparador.png" alt="Experto en reparación" /></span>
	<h1>Gracias por compartirnos, Saludos</h1>
	<p>Comunidad de reparadores en línea</p>
</section>

<footer id="foo">
<? $this->load->view('includes/footer');?>
</footer>
</body>
</html>


