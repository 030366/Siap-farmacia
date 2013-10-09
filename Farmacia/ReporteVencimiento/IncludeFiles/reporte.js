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


function CargarMedicinas(IdTerapeutico){
var A=document.getElementById('ComboMedicina');
var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "<img src='../images/carga.gif'>";
		}
		if(ajax.readyState==4){
			var Datos = ajax.responseText;
		 	if(Datos=="ERROR_SESSION"){alert('La sesion ha caducado \n inicie sesino nuevamente!'); window.location='../signIn.php';}
			A.innerHTML = Datos;
		}
	}

   ajax.open("GET","ReporteVencimiento.php?Bandera=1&IdTerapeutico="+IdTerapeutico,true);
   ajax.send(null);
   return false;

}


function valida(){
var form = document.getElementById('formulario');
var Ok=true;

var fechaFin;
var fechaInicio;
fechaFin=form.fechaFin.value;
fechaInicio=form.fechaInicio.value;

  if(!mayor(fechaInicio,fechaFin)){
            Ok=false;
            alert("La fecha final no puede ser menor que la inicial");
        }

if(Ok==true){
    GeneracionReportes();
}

}//valida



function GeneracionReportes(){
// 	var IdFarmacia = document.getElementById('IdFarmacia').value;	
	var IdTerapeutico = document.getElementById('IdTerapeutico').value;
	var IdMedicina = document.getElementById('IdMedicina').value;
	var FechaInicio=document.getElementById('fechaInicio').value;
	var FechaFin=document.getElementById('fechaFin').value;
	/*OBTENCION DE LAYERS QUE DESPLIEGAN INFORMACION*/
	var A = document.getElementById('Reporte');
	/************************************************/

	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "<img src='../images/carga.gif'>";
		}
		if(ajax.readyState==4){
			var Datos = ajax.responseText;
		 	if(Datos=="ERROR_SESSION"){alert('La sesion ha caducado \n inicie sesino nuevamente!'); window.location='../signIn.php';}
			A.innerHTML = Datos;
		}
	}

   ajax.open("GET","ReporteVencimiento.php?Bandera=2&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&fechaInicio="+FechaInicio+"&fechaFin="+FechaFin,true);
   ajax.send(null);
   return false;
		
}//GeneracionReporte
	
