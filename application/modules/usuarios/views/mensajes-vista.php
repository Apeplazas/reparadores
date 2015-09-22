<div class="centWrapMes">
<aside id="mesOpen">
<strong id="his">Historial de Conversaciones</strong>
<? $this->load->view('includes/messages');?>
</aside>

<section id="message">


<form action="">
<label id="newMes">New message</label>
	<fieldset>
		<input id="toMsg" type="text" name="for" placeholder="To:" />
	</fieldset>
	<div id="msgAjaSin">
		
	</div>
	<div id="write">
		<fieldset>
			<textarea placeholder="Escribe tu mensaje aquí..."></textarea>
		</fieldset>
		<fieldset>
			<input type="submit" value="Send it" id="mesFor" />
		</fieldset>
	</div>
</form>
</section>
</div>