<ul>
	<? foreach($listaMen as $rowM):
		$usuarioContacta = $this->usuario_model->buscaPerfilID($rowM->usuarioUnoId);
		?>
	<li>
		<a href="<?=base_url()?>usuarios/ver_mensaje/<?=$rowM->mensajeId;?>">
		  <span class="friendPic"><img width="50" height="50" src="<?=base_url()?><?if($usuarioContacta[0]->fotografiaPerfil == 'sinImagen.png'):?>assets/graphics/Chat<?=$usuarioContacta[0]->fotografiaPerfil;?><?else:?><?=$usuarioContacta[0]->fotografiaPerfil;?><?endif;?>" alt="Fotografia <?=$rowM->nombreCompleto;?>" /></span>
		  <div class="msgWro">
		    
		    <p><strong><?=$usuarioContacta[0]->nombreCompleto;?> </strong> <em>| <?=$rowM->fechaMensaje;?></em></p>
		    <p><?=$rowM->asunto;?></p>
		  </div>
		</a>
	</li>
	<? endforeach; ?>
</ul>