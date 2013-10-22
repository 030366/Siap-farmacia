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

function CambioMotivo(id){
  if(id=="Vencimiento"){
	document.getElementById("Justificacion").disabled=true;
  }
  if(id=="Averiado"){
	document.getElementById("Justificacion").disabled=false;
  }

	document.getElementById('IdMedicina').value='';
   	document.getElementById('Divisor').value='';
   	document.getElementById('UnidadesContenidas').value='';
   	document.getElementById('NombreMedicina').value='';
		document.getElementById('NombreMedicina').focus();
   	document.getElementById('Cantidad').value='';
   	document.getElementById('IdLote')[0].selected=true;
	document.getElementById('IdLote').disabled=true;
   	document.getElementById('Justificacion').value='';

}

function ValidaArea(){
   if(document.getElementById("IdAreaOrigen").value==0){
	alert("Seleccione el Area Origen!");
	document.getElementById("IdAreaOrigen").focus();
   }

}

function LimpiaDatos(){
   document.getElementById('IdMedicina').value='';
   document.getElementById('Divisor').value='';
   document.getElementById('UnidadesContenidas').value='';
   document.getElementById('NombreMedicina').value='';
	document.getElementById('NombreMedicina').focus();
   document.getElementById('Cantidad').value='';
   document.getElementById('IdLote')[0].selected=true;
	document.getElementById('IdLote').disabled=true;
   document.getElementById('Justificacion').value='';
	if(document.getElementById('Vencimiento').checked==true){
	document.getElementById('Justificacion').disabled=true;
	}

}

function Habilita(IdMedicina){
	var A = document.getElementById('ComboLotes');
	var Cantidad = document.getElementById('Cantidad').value;
	var IdAreaOrigen= document.getElementById('IdAreaOrigen').value;
	var Motivo=1;
	if(document.getElementById('Averiado').checked==true){Motivo=2;}
		var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//A.innerHTML="Agregando Lotes...";					
			}
			if(ajax.readyState==4){
				A.innerHTML=ajax.responseText;
				document.getElementById('Cantidad').focus();
			}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?IdMedicina="+IdMedicina+"&Cantidad="+Cantidad+"&IdAreaOrigen="+IdAreaOrigen+"&Bandera=4&Motivo="+Motivo,true);
		ajax.send(null);
		return false;


}

function PegarMedicina(IdMedicina,NombreMedicina){
	document.getElementById('IdMedicina').value=IdMedicina;
	document.getElementById('NombreMedicina').value=NombreMedicina;
}

/*CREACION DE REGISTRO DE RECETAS*/
/* Manejo de Datos del Paciente */
function valida(){
		var IdAreaOrigen = document.getElementById('IdAreaOrigen').value;
		
		var Cantidad = document.getElementById('Cantidad').value;
			
		var IdMedicina = document.getElementById('IdMedicina').value;
		var IdLote = document.getElementById('IdLote').value;
		var Ratio = document.getElementById('Averiado');
		var Justificacion = document.getElementById('Justificacion').value;

if(IdAreaOrigen == 0){
	alert('Seleccione el Area origen');
	document.getElementById('IdAreaOrigen').focus();
}else{
	if(IdMedicina==''){
		alert('Seleccione el medicamento a ser descargado');
		document.getElementById('NombreMedicina').focus();
	}else{
		if(Cantidad==''){
			alert('Introduzca la cantidad de medicamento vencido');
			document.getElementById('Cantidad').focus();
		}else{
			if(IdLote==0){
				alert('Seleccione un Lote valido');
				document.getElementById('IdLote').focus();
			}else{
				if(Justificacion=='' && Ratio.checked==true){
					alert('Debe introducir una justificacion \n para realizar el descargo por averias.');
						document.getElementById('Justificacion').focus();
				}else{
						RealizarDescargo();
				}//justifica
			}//else lotes
		}//else fechaFin
	}
}//else fechaInicio

}//valida


function RealizarDescargo(){
		//Detalles de Transferencia
		var A = document.getElementById('NuevaTransferencia');
		var B = document.getElementById('ComboLotes');
		var C = document.getElementById('restante');
		var Cantidad= document.getElementById('Cantidad').value;
			
		var IdAreaOrigen = document.getElementById('IdAreaOrigen').value;
		
		var Justificacion = document.getElementById('Justificacion').value;
		var IdMedicina = document.getElementById('IdMedicina').value;
		var Fecha = document.getElementById('Fecha').value;
		var Lote = document.getElementById('IdLote').value;
		var Divisor=document.getElementById('Divisor').value;
		var UnidadesContenidas=document.getElementById('UnidadesContenidas').value;
		var ajax = xmlhttp();


		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					//A.innerHTML="Agregando Transferencia...";					
				}
				if(ajax.readyState==4){
				    if(ajax.responseText=="ERROR_SESSION"){
					alert('La sesion ha caducado \n vuelva a iniciar sesion');
					window.location='../signIn.php'
				    }else{
					var Respuesta =ajax.responseText.split('~');
					B.innerHTML=Respuesta[0];
					C.innerHTML="<h2>Unidades faltantes para descargo: "+Respuesta[1];
					document.getElementById('Justificacion').value='';
					document.getElementById('NombreMedicina').value='';
					document.getElementById('IdMedicina').value='';
					document.getElementById('Cantidad').value='';
					document.getElementById('Divisor').value='';
					document.getElementById('NombreMedicina').focus();
					//document.getElementById('IdAreaOrigen').options[0].selected=true;
					
					MostrarDescargos();
				    }
				}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=1&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&IdAreaOrigen="+IdAreaOrigen+"&Justificacion="+Justificacion+"&Fecha="+Fecha+"&Lote="+Lote+"&Divisor="+Divisor+"&UnidadesContenidas="+UnidadesContenidas,true);
		ajax.send(null);
		return false;
    
}//NuevaReceta



function MostrarDescargos(){
	var ajax = xmlhttp();
	var Fecha = document.getElementById('Fecha').value;
	var UnidadesContenidas=document.getElementById('UnidadesContenidas').value;
	var IdAreaOrigen=document.getElementById('IdAreaOrigen').value;
	var A = document.getElementById('NuevaTransferencia');

	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//A.innerHTML="Desplegando Transferencia(s) ...";						
					}
				if(ajax.readyState==4){
				    if(ajax.responseText=="ERROR_SESSION"){
					alert('La sesion ha caducado \n vuelva a iniciar sesion');
					window.location='../signIn.php'
				    }else{
					//alert(ajax.responseText);
					A.innerHTML=ajax.responseText;
				    }
				}
			}
ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=2&Fecha="+Fecha+"&IdAreaOrigen="+IdAreaOrigen+"&UnidadesContenidas="+UnidadesContenidas,true);
		ajax.send(null);
		return false;
}


/*MUESTRA TODAS LAS TRANSFERENCIAS EN ESPERA DE SER FINALIZADAS*/
function FinalizarTransferencia(){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					
					}
				if(ajax.readyState==4){
					alert('Transferencia(s) Guardada(s) existosamente');
					window.location.href=window.location.href;
					}
			}
ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?&Bandera=6",true);
		ajax.send(null);
		return false;
}


/*ELIMINACION PUNTAL DE CADA TRANSFERENCIA DIGITADA*/
function BorrarDescarga(IdEntrega){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
											
					}
				if(ajax.readyState==4){
					//alert(ajax.responseText);
					var Respuesta = ajax.responseText.split('~');
					alert('El descargo fue eliminada \n '+ Respuesta[0]+' Unidades fueron retornadas a la area de origen de vencimiento!');
					MostrarDescargos();
					}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=3&IdEntrega="+IdEntrega,true);
		ajax.send(null);
		return false;

}//CancelarReceta


