<?
	$primera = '2014-09-26 11:44:26'; 	// Fecha de primer registro
	$pres = '30000'; 					// Presupuesto por dia
	$dias = '22';						// Dias de gasto de presupuesto
	
	// Forma de inversion de medios digitales (5 usuarios contra 1 para inversion 50%)
	$usuariosBet = '5';				// Cantidad de usuarios por reparadores
	$reparadoresBet = '1'; 			// Cantidad reparadores contra usuarios
	$estadosParticipantes = '5'; 	// Estados considerados para el gasto del presupuesto
	$invDia = floor((($pres / $dias)/$estadosParticipantes)/2); /// Inversion por dia por estado, usuario y reparador
	$porUsu = $usuariosBet / $reparadoresBet; 					/// Porcentage entre usuario Reparador
	$porRep = $reparadoresBet / $usuariosBet; 					/// Porcentage entre Reparador usuario
	
	////////-- Variables fechas ---/////////
	$today         = date("Y-m-d");
	$varToday      = $today.' 23:59:59';
	$varYesterday  = $today.' 00:00:00';
	$yesterday     = strtotime ( '-1 day' , strtotime ( $today ));
	$yesterday     = date ( 'Y-m-d' , $yesterday );
	$varYesCero    = $yesterday.' 00:00:00';
?>
<table id="info">
	<thead>
	  <tr>
	    <th></th>
	    <th colspan="5" class="center main">Información General</th>
	    <th colspan="5" class="center last">Hoy <?= $invDia?></th>
	</tr>
	</thead>
	
	<thead id="gen">
	<tr>
	  <th></th>
	  <th>Cantidad</th>
	  <th>Usuarios</th>
	  <th>Reparadores</th>
	  <th>Mixtos</th>
	  <th>Preguntas Reparación</th>
	  <th>Preguntas hoy</th>
	  <th>Usuarios hoy</th>
	  <th>Reparadores hoy</th>
	  <th>Mixtos hoy</th>
	  <th>Total hoy</th>
	</tr>
	</thead>
	<tbody>
	
	
	<? foreach($estados as $row):?>
	<? $reparadores = $this->tablero_model->calculaUsuariosEstados($row->estadoNombre,'reparador');?>
	<? $usuarios = $this->tablero_model->calculaUsuariosEstados($row->estadoNombre,'usuario');?>
	<? $mixto = $this->tablero_model->calculaUsuariosEstados($row->estadoNombre,'mixto');?>
	<? $estado = $this->tablero_model->calculaComentariosEstado($primera, $varYesCero, $row->estadoNombre);?>
	
	
	<? $hoy = $this->tablero_model->calculaComentariosFecha($varYesterday, $varToday, $row->estadoNombre);?>
	<? $reparadoresHoy = $this->tablero_model->calculaUsuariosEstadosHoy($varYesterday, $varToday, $row->estadoNombre, 'reparador');?>
	<? $usuariosHoy = $this->tablero_model->calculaUsuariosEstadosHoy($varYesterday, $varToday, $row->estadoNombre, 'usuario');?>
	<? $mixtoHoy = $this->tablero_model->calculaUsuariosEstadosHoy($varYesterday, $varToday, $row->estadoNombre, 'mixto');?>
	<? $totalesHoy = $this->tablero_model->calculaUsuariosTotalesHoy($varYesterday, $varToday, $row->estadoNombre);?>
	
	<tr>
		<td class="estado"><?= $row->estadoNombre;?></td>
		<td class="center"><?= $row->calculo;?></td>
		<? foreach($usuarios as $u):?>
		
		<td class="center">
			<?= $u->usuarios;?>
			<? $perCal = $this->tablero_model->calculaPorcentages($row->estadoNombre);?>
			 <? foreach($perCal as $pc):?><br><br>
			 
			
			<i><? 
				 if($pc->usuarios != '0'){
					 $varR = $pc->rep / $pc->usuarios; 
					 $varFinalR = ($varR * 100)/$porRep;
					 $varFR = ($varFinalR * $invDia)/100;
					 
					 if($varFR > $invDia){
						 $toTU = floor($invDia * 2);
						 echo 'usuarios'.$toTU;
					 }
					 else{
						if($toTU){
						 	echo '0 pesotes';
					 	}
					 	else{echo 'usuarios'.$varFR;}
						 
					 }
					 
				 }
				 ?>
			</i>
			<em><?
				 if($pc->rep != '0'){
				 	$varU = $pc->usuarios / $pc->rep;
				 	$varFinal = ($varU * 100)/$porUsu;
				 	$varF = ($varFinal * $invDia)/100;
				 	if($varF > $invDia){
					 	
					 	echo 'reparadores'.floor($invDia * 2);
				 	}
				 	else{
					 	if($toTU){
						 	echo '0 pesotes';
					 	}
					 	else{
						 	echo 'reparadores'.$varF;
					 	}
				 	}
				 	
				 }
				 ?>
			</em>
			 <? endforeach; ?>
			
		
		</td>
		
		<? endforeach; ?>
		<? foreach($reparadores as $r):?>
		<td class="center"><?= $r->usuarios;?></td>
		<? endforeach; ?>
		<? foreach($mixto as $m):?>
		<td class="center"><?= $m->usuarios;?></td>
		<? endforeach; ?>
		<? foreach($estado as $e):?>
		<td class="center division <? if ($e->cuenta < ($r->usuarios + $m->usuarios)):?>mark<?endif?>"><?= $e->cuenta;?></td>
		<? endforeach; ?>
		<? foreach($hoy as $h):?>
		<td class="center"><?= $h->cuenta;?></td>
		<? endforeach; ?>
		<? foreach($usuariosHoy as $uh):?>
		<td class="center"><?= $uh->usuarios;?></td>
		<? endforeach; ?>
		<? foreach($reparadoresHoy as $rh):?>
		<td class="center"><?= $rh->usuarios;?></td>
		<? endforeach; ?>
		<? foreach($mixtoHoy as $mx):?>
		<td class="center"><?= $mx->usuarios;?></td>
		<? endforeach; ?>
		<? foreach($totalesHoy as $t):?>
		<td class="center"><?= $t->usuarios;?></td>
		<? endforeach; ?>
	</tr>
	<? endforeach; ?>
	</tbody>
</table>

<table id="infoPrin">
  <tr>
	  <th>Usuarios</th>
	  <th>Cantidad</th>
  </tr>
  <tr>
    <td class="estado">Reparadores</td>
    <td class="center"><?= $cuentaRep[0]->cuenta?></td> 
   
  </tr>
  <tr>
    <td class="estado">Usuarios</td> 
    <td class="center"><?= $cuentaUsu[0]->cuenta?></td>
  </tr>
</table>


<script type="text/javascript">
$(document).ready(function(){
    $("#info > tbody > tr:odd").addClass("odd");
});
</script>

<!-- Tu comentario
<br><br><br><br><br><br><br><br>
<table>
	<tr>
		<th>Estados</th>
		<th>Cantidad</th>
		<th>Usuarios</th>
		<th>Reparadores</th>
	</tr>
	<? foreach($consulta as $c):?>
	<tr>
		<td><?= $c->estadoNombre;?></td>
		<td><?= $c->usuarios + $c->reparadores;?></td>
		<td><?= $c->usuarios;?></td>
		<td><?= $c->reparadores;?></td>
	</tr>
	<? endforeach; ?>

</table>
 -->