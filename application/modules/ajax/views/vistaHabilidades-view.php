<? foreach($usuarioTags as $tag): ?>
  <li id="<?= $tag->habilidadId ?>"><span class="borarTag"><img src="http://reparadores.mx/assets/graphics/trashIcon.png" alt="Borrar"></span><?= $tag->habilidad ?></li>
<? endforeach; ?>
<script type="text/javascript">
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
</script>