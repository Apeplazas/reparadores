jQuery(function($) {
/********************************************************************************************************************
Ajusta barra ancho
********************************************************************************************************************/
	$(function() {
		$('#bar').hover(function() {
		    $(this).stop().animate({ width: '170px' }, 250);
		}, function() {
		    $(this).stop().animate({ width: '66px' }, 250);
		});

	});
/********************************************************************************************************************
Busca y verifica si esta url disponible
********************************************************************************************************************/
	$("#fancy").keyup(function(){
		var filtro    = $("#fancy").val();
		$("#fancy").removeAttr("disabled");
		$.post("http://reparadores.mx/ajax/verificaUrl",{filtro:filtro},function(data){
			sucess:				
				$("#url").empty().append(data);
				$("#url").removeAttr("disabled");
		});
		
	})
/********************************************************************************************************************
Busca y verifica si existe email disponible
********************************************************************************************************************/	
	$("#emaCheck").keyup(function(){
		var filtro    = $("#emaCheck").val();
		$("#ajaxEma").removeAttr("disabled");
		$.post("http://localhost:8888/mandela/ajax/verificaEmail",{filtro:filtro},function(data){
			sucess:				
				$("#ajaxEma").empty().append(data);
				$("#ajaxEma").removeAttr("disabled");
		});
		
	})
/********************************************************************************************************************
Muestra y esconde barra de quote
********************************************************************************************************************/		
	var resultsSelected = false;
	$("#barInput").hover(
	    function () { resultsSelected = true; },
	    function () { resultsSelected = false; }
	);
	$("#quoteit").blur(function () {
	    if (!resultsSelected) {  //if you click on anything other than the results
	        $("#barInput").hide();  //hide the results
	    }
	});
	$("#quoteit").focus(function () {
	        $("#barInput").show();
	});
/********************************************************************************************************************
Guarda en bio en textarea de reparadores
********************************************************************************************************************/
	$("#bio").blur(function(){
		var filtro    = $("#bio").val();
		$("#bio").removeAttr("disabled");
		$.post("http://reparadores.mx/ajax/guardarBio",{filtro:filtro},function(data){
			sucess:				
				$("#signText").empty().append(data);
				$("#signText").removeAttr("disabled");
		});
		
	})

});

