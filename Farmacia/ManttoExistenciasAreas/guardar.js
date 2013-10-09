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

/*****/
function popUp(URL) {
day = new Date();
id = day.getTime();
//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=661,height=200,left = 450,top = 450');");
}//popUp
/******/

function validaForm(){



var IdTerapeutico=document.getElementById('Terapeutico').value;
var IdArea=document.getElementById('area').value;
var Ok=false;
var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//En Proceso
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado \n Inicie sesion nuevamente!'); window.location='../signIn.php';}
				//alert(ajax.responseText);
				var IdMedicinas=ajax.responseText.split('~');
				   var Tope = IdMedicinas.length;
				for(var i=0; i<Tope;i++){
				   var Combo=document.getElementById("Lote"+IdMedicinas[i]).value;
				   var Cantidad=document.getElementById(IdMedicinas[i]).value;

					if(Cantidad!=0 && Combo!=0){
					//validacion de q ambos datos sean seleccionados e ingresados
					   Ok=true;
					}
				   
				}

				if(Ok==true){
				   var Go=confirm('Desea guardar la existencia introducida?');
				   if(Go==true){
				  	document.formulario.submit();
				   }else{
					alert('Accion Cancelada!');
				   }
				}else{
				   alert('Debe introducir almenos una existencia nueva a ser aplicada!');
				}
			}
		}
			
			ajax.open("GET","procesos/validaLotes.php?IdTerapeutico="+IdTerapeutico+"&area="+IdArea,true);
			ajax.send(null);
	return false;


}


function MedicamentoPorGrupo(){
	var IdTerapeutico=document.getElementById('Terapeutico').value;
	var Nombre = "";
		document.getElementById('Nombre').value="";

	var A = document.getElementById('Medicamentos');
	var IdArea=document.getElementById('area').value;
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//En Proceso
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado \n Inicie sesion nuevamente!'); window.location='../signIn.php';}
				A.innerHTML=ajax.responseText;
			}
		}
			
ajax.open("GET","procesos/MedicamentosPorGrupo.php?IdTerapeutico="+IdTerapeutico+"&area="+IdArea+"&Nombre="+Nombre,true);
ajax.send(null);
return false;

}


function MedicamentoPorGrupo2(){
	var IdTerapeutico=document.getElementById('Terapeutico').value;
	var Nombre = trim(document.getElementById('Nombre').value,'Nombre');
		
	var A = document.getElementById('Medicamentos');
	var IdArea=document.getElementById('area').value;
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//En Proceso
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado \n Inicie sesion nuevamente!'); window.location='../signIn.php';}
				A.innerHTML=ajax.responseText;
			}
		}
		
ajax.open("GET","procesos/MedicamentosPorGrupo.php?IdTerapeutico="+IdTerapeutico+"&area="+IdArea+"&Nombre="+Nombre,true);
ajax.send(null);
return false;

}


function EliminarMedicamentoExistencia(IdMedicina,IdExistencia,IdLote,IdArea){
//alert(IdMedicina+"->"+IdLote+"->"+IdArea);
var Go = confirm('Desea eliminar esta existrencia de farmacia?');
if(Go==true){
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
	if(ajax.readyState==1){
		//En Proceso
	}
	if(ajax.readyState==4){
		//B.innerHTML = "";
		if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado \n Inicie sesion nuevamente!'); window.location='../signIn.php';}
		//alert(ajax.responseText);
		recarga(IdMedicina,IdArea,0);
	}
}
	
	ajax.open("GET","procesos/ProcesoActualizaLotes.php?Bandera=1&IdMedicina="+IdMedicina+"&IdLote="+IdLote+"&IdArea="+IdArea+"&IdExistencia="+IdExistencia,true);
	ajax.send(null);
return false;
}else{
    alert('Operacion Cancelada!');
}
}


function ValidaFecha(Campo){
	var Fecha = Campo.value;
	var ajax = xmlhttp();
	if(Fecha!='' && Fecha!='Fecha Ventto.'){
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				Campo.value='Comprobando...';
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				var Respuesta = ajax.responseText;
				if(Respuesta=='SI'){
					Campo.value=fecha;
				}else{
					alert('La fecha de lotes no puede ser menor al mes y a�o actual !');
					Campo.value='';
				}
			}
		}
			
			ajax.open("GET","saving.php?TestFecha="+Fecha+"&Bandera=2",true);
			ajax.send(null);
			return false;
	
	}//FECHA !=
	else{
		alert(Fecha);	
	}
	
}//ValidaFecha



function Alerta(IdMedicina,Area){
	var CantidadMedicamento= document.getElementById(IdMedicina).value;
	var LoteMedicina="Lote"+IdMedicina;
	var Lote = document.getElementById(LoteMedicina).value;
	
	if(CantidadMedicamento==0){
		alert('Introduzca la Cantidad de Medicamento a Ingresar');
	}else{
		if(Lote=="0"){
			alert('Seleccione el lote del medicamento');
		}else{
			var confirmacion=confirm('Son los datos correctos?');
			if(confirmacion==1){
				save(IdMedicina,Area);	
			}
		}//Else Cantidad
	}//Else Lote
}


function save(IdMedicina,Area){
		//var query = document.getElementById('q').value;
		var ajax = xmlhttp();
		/*Fecha Vencimiento*/
		var mes=document.getElementById("mes"+IdMedicina).value;	//Fecha de Vencimineto
		var ano=document.getElementById("ano"+IdMedicina).value;
		FechaVencimiento=ano+"-"+mes+"-"+"25";
		/*******************/
		var Precio="Precio"+IdMedicina;		//Precio Unitario del Medicamento del Lote
		var Lotes="Lote"+IdMedicina;			//Lote del medicamento entrante
		
		var nombrediv='saving'+IdMedicina;
		var boton='guardar'+IdMedicina;
		
		var divExistencia='existenciaActual'+IdMedicina;
		//alert(FechaVencimiento);
		/* Valor a introducir */
		//Cantidad a introducir....
		var Existencia = document.getElementById(IdMedicina).value;

		var Lotes=document.getElementById(Lotes).value;
		//var FechaVencimiento=document.getElementById(Ventto).value;
		var PrecioUnitarioLote=document.getElementById(Precio).value;
		
		var A = document.getElementById(divExistencia);
		/* FIN VALORES */
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "GUARDANDO...";
						
					}
				if(ajax.readyState==4){
						//B.innerHTML = "";
						recarga(IdMedicina,Area,0);

					}
			}
		
		ajax.open("GET","saving.php?IdMedicina="+IdMedicina+"&area="+Area+"&Existencia="+Existencia+"&Lote="+Lotes+"&FechaVentto="+FechaVencimiento+"&PrecioLote="+PrecioUnitarioLote,true);
		ajax.send(null);
		return false;
		
}//LoadRecetas
	

	
	function recarga(IdMedicina,Area,Bandera){
		var ajax = xmlhttp();
		var divExistencia='existenciaActual'+IdMedicina;
		var divComboLotes='ComboLotes'+IdMedicina;
		
		var Precio="Precio"+IdMedicina;		//Precio Unitario del Medicamento del Lote
		var Lotes="Lote"+IdMedicina;			//Lote del medicamento entrante

		var A = document.getElementById(divExistencia);
		var B = document.getElementById(IdMedicina);
		var C = document.getElementById(divComboLotes);

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.value= "CARGANDO...";
						B.style.display="none";
					}
				if(ajax.readyState==4){
						var Respuesta=ajax.responseText.split('~');
						A.innerHTML = Respuesta[0];
						C.innerHTML = Respuesta[1];						
						B.style.display="inline";
						B.value='0';
						document.getElementById(Lotes).value='Lote.';
						document.getElementById(Precio).value='0';
						
					}
			}
		
		ajax.open("GET","saving.php?IdMedicina="+ IdMedicina +"&area="+ Area+"&Bandera="+ Bandera,true);
		ajax.send(null);
		return false;

}//Obtener Nueva Existencia

/*********	EXISTENCIAS		*******************/
	function Existencias(IdLote,IdMedicina){
		var ajax = xmlhttp();
		var divExistencia='LoteExistenciaActual'+IdMedicina;
		
		var A = document.getElementById(divExistencia);

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML= "CARGANDO...";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						
					}
			}
		
		ajax.open("GET","saving.php?IdMedicina="+IdMedicina+"&IdLote="+IdLote+"&Bandera=3",true);
		ajax.send(null);
		return false;

}//Obtener Nueva Existencia




