<nav <? if($this->uri->segment(1) != 'compartir' && $this->uri->segment(1) != ''):?> id="barra"<?endif;?>>
	<ul>
		<? foreach($menu as $rowM):?>
		<li><a id="<?= $rowM->imagen;?>" href="<?=base_url()?><?= $rowM->url;?>"><?= $rowM->nombre;?></a></li>
		<? endforeach; ?>
	</ul>
</nav>
