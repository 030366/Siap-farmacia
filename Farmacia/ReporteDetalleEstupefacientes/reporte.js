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


//******FILTROS DE COMBOS
function cargaContenido8(valor,ID){


switch(ID){
   case "farmacia":
	var A = document.getElementById('ComboAreas');
	var Objetivo = "mnt_areafarmacia";
   break;

   case "IdSubServicio":
	var A = document.getElementById('comboMedico');
	var Objetivo = "mnt_empleados";
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

ajax.open("GET","proceso_especialidad.php?valor="+valor+"&Combo="+Objetivo,true);
		ajax.send(null);
		return false;

}
//*******************




function GeneraReporte(){
	   //var query = document.getElementById('q').value;
		var IdFarmacia=document.getElementById('farmacia').value;
	   	var area=document.getElementById('area').value;
		var select1 = document.getElementById('IdSubServicio').value;
		var select2 = document.getElementById('IdEmpleado').value;
		var select3= document.getElementById('IdMedicina').value;
		var fechaInicio= document.getElementById('fechaInicio').value;
		var fechaFin= document.getElementById('fechaFin').value;
		var A = document.getElementById('Respuesta');



		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
			}
			if(ajax.readyState==4){
			    if(ajax.responseText=="ERROR_SESSION"){
				alert('La sesion ha caducado! \n vuelva a iniciar sesion!');
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
var URL1="Reporte_Especialidad.php?IdFarmacia="+IdFarmacia+"&area="+area+"&select1="+select1+"&select2="+select2+"&select3="+select3+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin;

ajax.open("GET",URL1,true);
		ajax.send(null);
		return false;
		
}//

