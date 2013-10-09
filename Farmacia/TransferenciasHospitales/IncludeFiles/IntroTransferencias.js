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
var nav4 = window.Event ? true : false;
function Limpiar(evt,Id){	
	var key = nav4 ? evt.which : evt.keyCode;	
// 		alert(key);
	   if(key==8 && Id=='NombreEstablecimiento'){document.getElementById('IdEstablecimiento').value="";}
	   if(key==8 && Id=='NombreMedicina'){document.getElementById('IdMedicina').value="";}
		return (key);
}


function Habilita(IdMedicina){
	var A = document.getElementById('ComboLotes');
	var Cantidad = document.getElementById('Cantidad').value;
	var IdAreaOrigen= document.getElementById('IdAreaOrigen').value;
		var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//A.innerHTML="Agregando Lotes...";					
			}
			if(ajax.readyState==4){
				A.innerHTML=ajax.responseText;
				document.getElementById('IdLote').focus();
			}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?IdMedicina="+IdMedicina+"&Cantidad="+Cantidad+"&IdAreaOrigen="+IdAreaOrigen+"&Bandera=4",true);
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
		
		var IdEstablecimientoDestino = document.getElementById('IdEstablecimiento').value;
		var Justificacion = document.getElementById('Justificacion').value;
		var Cantidad = document.getElementById('Cantidad').value;
			
		var IdMedicina = document.getElementById('IdMedicina').value;
		var IdLote = document.getElementById('IdLote').value;


   if(IdEstablecimientoDestino==''){
	alert('Seleccione el establecimiento destino de transferencia');
	document.getElementById('NombreEstablecimiento').focus();
   }else{
	if(Cantidad==''){
		alert('Introduzca la cantidad de medicamento a ser tranferido');
		document.getElementById('Cantidad').focus();
	}else{
		if(IdMedicina==''){
			alert('Seleccione el medicamento a ser transferido');
			document.getElementById('NombreMedicina').focus();
		}else{
			if(IdLote==0){
				alert('Seleccione un Lote valido');
			}else{
				if(Justificacion==''){
					alert('Debe introducir una justificacion \n para realizar la transferencia');
						document.getElementById('Justificacion').focus();
				}else{
						GuardarTransferencia();
				}//justificacion
			}//IdLote
		}//IdMedicina
	}//Cantidad
   }//IdAreaDestino


}//valida


function GuardarTransferencia(){
		//Detalles de Transferencia
		var A = document.getElementById('NuevaTransferencia');
		var B = document.getElementById('ComboLotes');
		var C = document.getElementById('restante');
		var Cantidad= document.getElementById('Cantidad').value;
			var ExistenciaTotal=document.getElementById('ExistenciaTotal').value;
		var IdEstablecimientoOrigen = document.getElementById('IdAreaOrigen').value;
		var IdEstablecimientoDestino=document.getElementById('IdEstablecimiento').value;
		var Justificacion = document.getElementById('Justificacion').value;
		var IdMedicina = document.getElementById('IdMedicina').value;
		var Fecha = document.getElementById('Fecha').value;
		var Lote = document.getElementById('IdLote').value;
		var Unidades=document.getElementById('Unidades').value;
		var ajax = xmlhttp();

	var Cantidad2=parseInt(Cantidad);
	var ExistenciaTotal2=parseInt(ExistenciaTotal);

    if(ExistenciaTotal2 < Cantidad2){
	alert('No se puede realizar esta transferencia \n La cantidad a transferir rebaza la existencia total!');
    }else{
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
					C.innerHTML="<h2>Unidades faltantes para suplir la transferencia: "+Respuesta[1];
					document.getElementById('Justificacion').value='';
					document.getElementById('NombreMedicina').value='';
					   document.getElementById('IdMedicina').value='';
					document.getElementById('Cantidad').value='';
					document.getElementById('IdEstablecimiento').value="";
					   document.getElementById('NombreEstablecimiento').value="";
					MostrarTransferencia();
				    }
				}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=1&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&IdEstablecimientoOrigen="+IdEstablecimientoOrigen+"&IdEstablecimientoDestino="+IdEstablecimientoDestino+"&Justificacion="+Justificacion+"&Fecha="+Fecha+"&Lote="+Lote+"&Unidades="+Unidades,true);
		ajax.send(null);
		return false;
    }
}//NuevaReceta



function MostrarTransferencia(){
	var ajax = xmlhttp();
	var Fecha = document.getElementById('Fecha').value;
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
					A.innerHTML=ajax.responseText;
				    }
				}
			}
ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Fecha="+Fecha+"&IdAreaOrigen="+IdAreaOrigen+"&Bandera=2",true);
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
function BorrarTransferencia(IdTransferencia){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
											
					}
				if(ajax.readyState==4){
					//alert(ajax.responseText);
					var Respuesta = ajax.responseText.split('~');
					alert('La Transferencias fue eliminada \n '+ Respuesta[0]+' Unidades fueron retornadas a la area de origen!');
					MostrarTransferencia();
					}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?IdTransferencia="+IdTransferencia+"&Bandera=3",true);
		ajax.send(null);
		return false;

}//CancelarReceta


