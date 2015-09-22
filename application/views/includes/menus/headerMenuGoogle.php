<? $user =	$this->session->userdata('usuario'); ?>
<? $usuario = $this->usuario_model->buscaPerfilID($user['usuarioID'])?>
<!-- validacion php formulario -->
<? $errorEmail  = form_error('email'); ?>

<? if ($this->uri->segment(2) != 'gracias'):?>
<div <? if($this->uri->segment(1) == 'compartir'):?>id="comparteloDos"<?else:?>id="compartelo"<?endif?>>
	<? if($errorEmail):?><div class="msgError"><img src="<?=base_url()?>/assets/graphics/alert.png"><?= $errorEmail?></div><? else:?><?endif?>
	<?=$contacts;?>
</div>
<? endif?>

<header id="indexH">
	<div id="fixed">
		<div class="centerWrap">
		<a id="logo" href="<?=base_url()?>"><img src="<?=base_url()?>assets/graphics/logo-reparadores.png" alt="Reparadores Mx" /></a>
		<span>
			<img src="<?=base_url()?>assets/graphics/atencionaclientes-reparadores.png" alt="Atencion a clientes" />
			<? if(!isset($user) || $user != true):?>
				<a id="acceso" href="<?=base_url()?>registro/ingresar"><img src="<?=base_url()?>assets/graphics/acceso.png" alt="Accesar" /></a>
			<? else:?>
				<?php $no_not = $this->data_model->numero_mensajes($user['usuarioID']);?>
				<a id="notif" href="<?=base_url()?>usuarios/notificaciones"><img alt="Notificaciones" src="<?=base_url()?>assets/graphics/notificacionesIcono.png"><?php if($no_not > 0):?><i id="solicitud"><?=$no_not;?></i><?php endif;?></a>
				<? foreach($usuario as $row):?>
				<a id="dash" href="<?=base_url()?>configuracion"><img src="<?=base_url()?><?=$row->fotografiaPerfil;?>" alt="<?= $row->nombreCompleto;?>" /> <em><?= $user['nombre']?></em></a>
				<? endforeach; ?>
			<? endif;?>
		</span>
		</div>
		<nav id="mainMenu">
		<ul>
			<? $conocimientos = $this->data_model->cargarConocimientos();?>
			<? foreach($conocimientos as $rowC):?>
			<li>
				<a href="<?=base_url().$rowC->url;?>"><?= $rowC->conocimiento;?></a>
			</li>
			<? endforeach;?>
		</ul>
		<a href="#" id="actiCom"><img src="<?=base_url()?>assets/graphics/compartenos.png" alt="Compartenos" /></a>
		
		</nav>
		
		<div id="breadCrumb">
		<ul>
			<li>
				<a id="Inicio" href="<?=base_url()?>">Inicio</a>
			</li>
			<?		
			// load libary
			$this->load->library('breadcrumbs');
			
			// add breadcrumbs
			echo $this->breadcrumbs->generate_breadcrumb();
			
			?>
		</ul>
		</div>
	</div>
	
	
	<script type="text/javascript">
	$(document).ready(function() {
		$( "#actiCom" ).click(function() {
			$("#compartelo, #comparteloDos").animate({top:'250px'}, {queue:false, duration:1500, easing: 'easeInOutBack'});   	
		});
		$( "#cerComp" ).click(function() {
			$("#compartelo, #comparteloDos").animate({top:'-840px'}, {queue:false, duration:1500, easing: 'easeInOutBack'});   	
		});
	});
	</script>

</header>