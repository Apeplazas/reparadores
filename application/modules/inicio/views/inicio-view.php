<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
<? foreach($op as $rowOpt):?>
<title><?= $rowOpt->metaTitulo;?></title>
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
	<h1>Comunidad de reparadores en línea</h1>
	<p>BUSCA Y ENCUENTRA LOS MEJORES TÉCNICOS DE REPARACIÓN EN MÉXICO</p>
		<div id="acciones">
		<span class="fright sombraVerdeG">
			<a class="mt20 botonNegroG borVer" href="<?=base_url()?>solicita_tu_reparacion">¿Problemas con algún equipo?</a>
		</span>
		<span class="fright sombraVerde mr10">
			<a class="mt20 botonRojoG borVer" href="<?=base_url()?>registro?tipo=reparador">¿Eres Reparador?</a>
		</span>
		</div>
</section>
<section id="contentIndex">
<h2>Profesionales en reparación, ajustes y chequeos.</h2>
<ul>
	<? foreach($conocimientos as $rowC):?>
	<li>
		<a href="<?=base_url().$rowC->url;?>"><img src="<?=base_url()?>assets/graphics/<?= $rowC->imagenIcono;?>.png" alt="<?= $rowC->anchorTitulo;?>" /></a>
		<strong><?= $rowC->conocimiento;?></strong>
		<p><?= $rowC->anchorTitulo;?></p>
	</li>
	<? endforeach; ?>
</ul>
<br class="clear">
</section>
<footer id="foo">
<? $this->load->view('includes/footer');?>
</footer>
</body>
</html>


