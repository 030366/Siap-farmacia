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
//****OBTENER FECHA ACTUAL
	var mydate=new Date();
	var year=mydate.getFullYear();//año actual
		var yearA=mydate.getFullYear()-1;
	
	var month=mydate.getMonth()+1;//mes actual 
	if(month < 10){
	month='0' + month;
	}

//******
var aux1;
var aux2;
var fechaFin;
var fechaInicio;
fechaFin=form.fechaFin.value;
fechaInicio=form.fechaInicio.value;

if(fechaFin=='' || fechaInicio==''){
	alert("Seleccione un periodo valido!");
	Ok=false;
}


aux1 = fechaFin.split("-");
aux2 = fechaInicio.split("-");

//FECHA DE DB=2008-02-19
var DiaInicio=aux2[2];//OBTENCION DE 
var DiaFin=aux1[2];   //DIAS FORMATO ##
var anoInicio=aux2[0];//OBTENCION DE 
var anoFin=aux1[0];   //AÑOS
//los meses menores a 10 van con 0x
var mesInicio=aux2[1];//OBTENCION DE
var mesFin=aux1[1];	  //MESES EN FORMATO ##

anoFin=parseFloat(anoFin);
anoInicio=parseFloat(anoInicio);
mesFin=parseFloat(mesFin);
mesInicio=parseFloat(mesInicio);
DiaFin=parseFloat(DiaFin);
DiaInicio=parseFloat(DiaInicio);
month=parseFloat(month);

// if(year<anoInicio || year<anoFin){
// alert('Los años no pueden ser mayor al año actual');
// Ok=false;
// }

// if((month<mesInicio || month<mesFin)&&(yearA<anoInicio || yearA<anoFin)){
// alert('La fecha no puede ser mayor al mes actual');
// Ok=false;
// }//meses


if(anoFin<anoInicio || (mesFin<mesInicio && anoFin<=anoInicio)){
alert('La fecha de Finalizacion no puede ser menor a la de inicio');
Ok=false;
}//validacion de MESES y AÑOS para evitar que la fecha de inicio sea mayor a la de finalizacion

if(DiaFin<DiaInicio){
	if(mesFin==mesInicio){
	alert('El dia de Finalizacion no puede ser menor al de inicio');
	Ok=false;	
	}
}//DIAFIN


if(Ok==true){
    GeneracionReportes();
}

}//valida



function GeneracionReportes(){
	var IdFarmacia = document.getElementById('IdArea').value;	
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

   ajax.open("GET","ReporteVencimiento.php?Bandera=2&IdArea="+IdArea+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&fechaInicio="+FechaInicio+"&fechaFin="+FechaFin,true);
   ajax.send(null);
   return false;
		
}//GeneracionReporte
	
