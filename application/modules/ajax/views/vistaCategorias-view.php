<? $user = $this->session->userdata('usuario');?>

<? foreach($cat as $row):?>
	<div class="hab item">
	<h3><?= $row->conocimiento;?></h3>
	<ul id="lisHab">
	<? $cat =  $this->data_model->cargarCatID($row->conocimientoId, $user['usuarioID']);?>
	<? foreach($cat as $rowC):?>
	  <li id="<?= $user['usuarioID'];?>-<?= $rowC->categoriaId;?>-<?=$row->conocimientoId;?>"><span class="borarConocimiento"><img src="<?=base_url()?>assets/graphics/trashIcon.png" alt="Borrar" /></span><?= $rowC->categoriaNombre;?></li>
	<? endforeach; ?>
	</ul>
	</div>
<? endforeach; ?>
<br class="clear">
<script>
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
</script>
<script language="javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url()?>assets/js/masonry.pkgd.min.js" type="text/javascript"></script>
<script type="text/javascript">
$('#habWrap').masonry({
  columnWidth: 100,
  itemSelector: '.item'
});
</script>