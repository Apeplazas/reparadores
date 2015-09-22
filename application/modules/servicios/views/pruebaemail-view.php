<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Miko</title>
</head>
<body>
	<form action="servicios/enviarMensaje" method="post" accept-charset="utf-8">
	  
		   <p>
		   	<label for="nombreUsuario">Tu Nombre: </label><input type="text" name="nombreUsuario" value="" id="nombreUsuario"/>
		   	<label for="emailUsusario">Tu Email: </label><input type="text" name="emailUsuario" value="" id=""/>
			<label for="emailReparador">Email del Reparador: </label><input type="text" name="emailReparador" value="" id="emailReparador"/>
			<label for="asunto">Asunto: </label><input type="text" name="asunto" value="" id="asunto"/>
			<label for="mensaje">Mensaje: </label><input type="text" name="mensaje" value="" id="mensaje"/>
			<input type="submit" name="enviar" value="Enviar..." id="enviar"/>
			   
		   </p>
	</form>
</body>
</html>