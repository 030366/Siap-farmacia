function xmlhttp(){
		var xmlhttp;
		try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
			try{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch(e){
				try{xmlhttp = new XMLHttpRequest();}
				catch(e){
					xmlhttp = false;
				}
			}
		}
		if (!xmlhttp) 
				return null;
			else
				return xmlhttp;
}//xmlhttp


function cargaContenido(valor,ID){


switch(ID){
   case "IdTerapeutico":
	var A = document.getElementById('ComboMedicinas');
	var Objetivo = "farm_catalogoproductos";
   break;

   case "IdTerapeutico":
	var A = document.getElementById('ComboMedicinas');
	var Objetivo = "farm_catalogoproductos";
   break;

}

var ajax = xmlhttp();
		
	ajax.onreadystatechange=function(){
	    if(ajax.readyState==1){
		A.innerHTML = '<select name="none" id="none" disabled="disabled"><option value="0">[CARGANDO ...]</option></select>';
	    }

	    if(ajax.readyState==4){
		A.innerHTML = ajax.responseText;
	    }
	}

ajax.open("GET","proceso_farmacias.php?valor="+valor+"&Combo="+Objetivo,true);
		ajax.send(null);
		return false;

}
//*******************



function Reportes(){
	   //var query = document.getElementById('q').value;
                var IdFarmacia = document.getElementById('IdFarmacia').value;
		
		var IdTerapeutico = document.getElementById('IdTerapeutico').value;
		var IdMedicina = document.getElementById('IdMedicina').value;
		var fechaInicio= document.getElementById('fechaInicio').value;
		var fechaFin= document.getElementById('fechaFin').value;
		var A = document.getElementById('Layer2');
		
		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
					}
				if(ajax.readyState==4){
					    if(ajax.responseText=="ERROR_SESSION"){
						alert('La sesion ha caducado \n inicie sesion nuevamente !');
						window.location='../signIn.php';
					    }
						A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
						
					}
			}
    	/*
		fechaInicio
		fechaFin
		select1
		select2		
		*/
ajax.open("GET","Reporte_Estupefacientes.php?IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&IdFarmacia="+IdFarmacia,true);
		ajax.send(null);
		return false;
		
}//