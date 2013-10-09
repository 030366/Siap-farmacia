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


function VentanaBusqueda(){
	var variable= document.getElementById('IdAreaOrigen').value;
	var Cantidad = document.getElementById('Cantidad').value;
	var URL= 'BusquedaMedicamento/buscador_medicamento.php?IdArea='+variable;

if(variable==0){
		alert('Seleccione el area origen para poder obtener el listado de medicamentos');
}else{
		if(Cantidad==''){
	alert('Antes de seleccionar el medicamento /n introduzca el numero de unidades a transferir');
	}else{
	day = new Date();
	id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1000,height=500,left = 450,top = 450');");
	}
}//else
	
}//VentanaBusqueda


function AumentaExistencia(){
	var IdArea = document.getElementById('IdArea').value;
	var A = document.getElementById('Datos'); /*aqui voy*/
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					//A.innerHTML="Agregando Lotes...";					
				}
				if(ajax.readyState==4){
					A.innerHTML="CARGANDO...";
					RecuperaMedicinaNoReclamada();
				}
		}
		
		ajax.open("GET","IncludeFile/ExistenciaVirtualProceso.php?IdArea="+IdArea+"&Bandera=1",true);
		ajax.send(null);
		return false;


}//AumentaExistencia

function RecuperaMedicinaNoReclamada(){
	var IdArea = document.getElementById('IdArea').value;
	var A = document.getElementById('Datos2'); /*aqui voy*/
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					//A.innerHTML="Agregando Lotes...";					
				}
				if(ajax.readyState==4){
					A.innerHTML="REDIRECCIONANDO...";
					window.location='../index2.php'
				}
		}
		
		ajax.open("GET","IncludeFile/ExistenciaVirtualProceso.php?IdArea="+IdArea+"&Bandera=2",true);
		ajax.send(null);
		return false;


}//AumentaExistencia
