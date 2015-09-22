<? if($notificaciones):?>
	<ul>
	<? foreach($notificaciones as $not):?>
		<li>
			<p class="notGen"><em>Dia y Hora: <?=$not->fecha;?></em></p>
			<span>
			   <a href="<?=base_url()?>"><img src="<?=base_url()?>assets/graphics/deleteRow.png" alt="Borrar" /></a>
			</span>
			<a href="<?=$not->url;?>">
				<p class="notMain"><?=$not->notificacion;?></p>
			</a>
		</li>	
	<? endforeach;?>
	</ul>
<? endif;?>