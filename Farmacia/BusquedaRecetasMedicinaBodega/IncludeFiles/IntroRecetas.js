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


/*Filtracion de teclas*/
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
}

function Saltos(evt,Objeto){	
	var key = nav4 ? evt.which : evt.keyCode;	
	
	//alert(key);
	if(Objeto=='IdRecetaValor' && key==13){
		document.getElementById('Buscar').focus();
	}
	

	
	/*CONTROLES PARA EL USO DE ATAJOS TERMINAR RECETAS CANCELAR O BUSCAR MEDICAMENTO DESDE CANTIDAD*/
		if(key==13 && Objeto=='Cantidad'){
			document.getElementById('NombreMedicina').focus();
		}
		
		
		if((key==116 || key==84) && Objeto=='Cantidad'){
			//Valor de la tecla T para terminar o finalizar la receta
			FinalizarReceta();
		}
		if(key==105 && Objeto=='Cantidad'){
			//VALOR DE TECLA I PARA HACER AUTOMATICO EL CECKEO DE INSATISFECHA
			if(document.getElementById('Insatisfecha').checked==false){
				document.getElementById('Insatisfecha').checked=true;
			}else{
				document.getElementById('Insatisfecha').checked=false;
			}
		}
		if(key==99 && Objeto=='Cantidad'){
			//Valor de tecla C para cancelar la medicina seleccionada
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').value='';

		}
		
		
		/************	CORRECINES DE INFORMACION ****************/
		if(key==13 && Objeto=='CodigoSubEspecialidad'){
			document.getElementById('CodigoFarmacia').focus();
			//CorregirEspecialidad();
		}
		
		if(key==13 && Objeto=='CodigoFarmacia'){
			document.getElementById('fechaInicial').focus();
			//CorregirEspecialidad();
		}
		/////////////////////////////////////////////////////////////
		/*if(key==43 && Objeto=='Cantidad'){
			//Valor de la tecla T para terminar o finalizar la receta
			validaMedicina();
		}*/

	/**************************************/
	
	if(key==13 && Objeto=='Agregar'){
			document.getElementById('Cantidad').focus();
	}
	
	if(Objeto=='Expediente'){
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
	}else{
		
		return ((key < 13)||(key>=97 && key <=122) || (key>=48 && key<=57) ||(key==165 || key==164)||(key>=65 && key<=90)||key==43 || key==32);
	}
}//Saltos



/*****************************************************************************************/
function VentanaBusqueda(){//BUSQUEDA DE MEDICAMENTO
	day = new Date();
	id = day.getTime();
		var URL="BusquedaServicio/buscador_medicamento.php";
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function VentanaBusqueda2(){//Para Medicos
	day = new Date();
	id = day.getTime();
	var URL="BusquedaMedico/buscador_medico.php";
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function VentanaBusqueda3(IdReceta,IdMedicina){//Para Medicos
	day = new Date();
	id = day.getTime();
	var URL="Emergente.php?IdReceta="+IdReceta+"&IdMedicina="+IdMedicina;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=870,height=580,left = 0,top = 100');");
}

/*****************************************************************************************/

function Correcciones(IdOrigenCambio){
		//Datos a ingresar
		A=document.getElementById(IdOrigenCambio);
		if(IdOrigenCambio=='NombreMedico'){ObjTmp="CodigoFarmacia";}
		if(IdOrigenCambio=='NombreArea'){ObjTmp="IdArea2";}
		if(IdOrigenCambio=='Especialidad'){ObjTmp="CodigoSubEspecialidad";}
		
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/	
					A.innerHTML='Cargando ...';
					
				}
				if(ajax.readyState==4){
				  if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}
					A.innerHTML=ajax.responseText;
					document.getElementById(ObjTmp).focus();
					
					
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdOrigenCambio="+IdOrigenCambio+"&Bandera=15",true);
		ajax.send(null);
		return false;

}//Corregir Area

function PegarIdArea(IdArea){
	document.getElementById('IdArea').value=IdArea;	
}


/*			ACTUALIZACION DE DATOS			*/
function CorregirArea(){
	var Area = document.getElementById('IdArea').value;
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
		//Datos a ingresar
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/	
					
				}
				if(ajax.readyState==4){
				   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

					var Respuesta=ajax.responseText;
					if(Respuesta=='N'){
						alert('Error de conexion con el Servidor !');
					}else{
						document.getElementById('NombreArea').innerHTML=ajax.responseText;
					}
										
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdArea="+Area+"&IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&Bandera=14",true);
		ajax.send(null);
		return false;

}//Corregir Area



function CorregirMedico(){
	var Area = document.getElementById('IdArea').value;
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
	var IdMedico=document.getElementById('IdMedico').value;
		//Datos a ingresar
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/	
					
				}
				if(ajax.readyState==4){
				   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

					var Respuesta=ajax.responseText;
					if(Respuesta=='N'){
						alert('Error de Conexion con el Servidor !');
					}else{
						document.getElementById('NombreMedico').innerHTML=Respuesta;
					}
										
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdHistorialClinico="+IdHistorialClinico+"&IdMedico="+IdMedico+"&Bandera=14",true);
		ajax.send(null);
		return false;
}//Corregir Medico

function CorregirEspecialidad(){
	var Area = document.getElementById('IdArea').value;
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
	var IdSubEspecialidad=document.getElementById('IdSubEspecialidad').value;
		//Datos a ingresar
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/	
					
				}
				if(ajax.readyState==4){
				   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

					var Respuesta=ajax.responseText;
					if(Respuesta=='N'){
						alert('Error de Conexion con el Servidor !');
					}else{
						document.getElementById('Especialidad').innerHTML=Respuesta;
					}
										
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdSubEspecialidad="+IdSubEspecialidad+"&Bandera=14",true);
		ajax.send(null);
		return false;
}//Corregir Especialidad


/************************************************************************/



/*Verificacion de campos vacios*/
function verificacion(){
	if(document.form.expediente.value==''){
		alert('Introduzca un numero de Expediente valido');	
	}else{
	Respuesta();
	}
}//verificacion


function Habilita(){
	var check=document.getElementById('CheckRepetitiva');
	if(check.checked==true){
		document.getElementById('Repetitiva').disabled=false;	
		document.getElementById('Repetitiva').focus();	
	}else{
		document.getElementById('Repetitiva').value='';	
		document.getElementById('Repetitiva').disabled=true;	
	}
}

/*FUNCIONES UTILIZADAS POR LOS POPUPS*/
function PegarSubServicio(IdSubEspecialidad,CodigoFarmacia){
	document.getElementById('IdSubEspecialidad').value=IdSubEspecialidad;
	document.getElementById('CodigoSubEspecialidad').value=CodigoFarmacia;
		ObtenerDatosEspecialidadBusqueda(IdSubEspecialidad);

}

function PegarMedico(IdEspecialidad,NombreEspecialidad,IdMedico,NombreMedico){
	document.getElementById("IdMedico").value=IdMedico;
	document.getElementById("NombreMedico").innerHTML=NombreMedico;
		ObtenerDatosMedicoBusqueda(IdMedico);
}//pegarMedico





function CargarSubEspecialidad(IdAreaFarmacia){
	//Datos a ingresar
	var Codigo= document.getElementById('CodigoSubEspecialidad').value;
	var ajax = xmlhttp();
	var tmp=Codigo.trim();
		
	if(tmp!=''){
	    ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		/*NOTHING*/
		 document.getElementById('Cambiar2').disabled=true;
		}
		if(ajax.readyState==4){
  		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			var Datos=ajax.responseText.split('/');
			
			document.getElementById('IdSubEspecialidad').value=Datos[0];
			
			document.getElementById('Especialidad').innerHTML=Datos[1];
		}
	    }
			
	   ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Codigo="+Codigo+"&Bandera=13",true);
			ajax.send(null);
			return false;
	}else{
		document.getElementById('IdSubEspecialidad').value='';
		document.getElementById('Especialidad').innerHTML='';	
	}
}//Cargar SubEspecialidad


function ObtenerDatosMedicoBusqueda(IdMedico){
		//Datos a ingresar
				
	var IdArea=document.getElementById('IdArea').value;
	var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		/*NOTHING*/		
		}
		if(ajax.readyState==4){
		  if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

		  document.getElementById('CodigoFarmacia').value = ajax.responseText;
 		}
	}
		
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedico="+IdMedico+"&Bandera=11",true);
ajax.send(null);
return false;
}//ObtenerDatosMedicoBusqueda


function ObtenerDatosEspecialidadBusqueda(IdSubEspecialidad){
		//Datos a ingresar
	var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		/*NOTHING*/		
			
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

		   document.getElementById('Especialidad').innerHTML = ajax.responseText;
		}
	}
		
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdSubEspecialidad="+IdSubEspecialidad+"&Bandera=11",true);
ajax.send(null);
return false;
}//ObtenerDatosMedicoBusqueda


function ObtenerDatosMedico(){
		//Datos a ingresar

	var IdArea=document.getElementById('IdArea').value;
	var CodigoFarmacia= document.getElementById('CodigoFarmacia').value;
		
	var tmp=CodigoFarmacia.trim();

	if(tmp!=''){
		
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
			/*NOTHING*/	
			document.getElementById('Cambiar1').disabled=true;
			}
			if(ajax.readyState==4){
			    if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			  var Datos=ajax.responseText.split('/');
			
			  //document.getElementById('IdEspecialidad').value = Datos[0];
			  document.getElementById('IdMedico').value=Datos[0];
			  //document.getElementById('NombreEspecialidad').innerHTML=Datos[2];
			  document.getElementById('NombreMedico').innerHTML=Datos[1];

			}
		}
			
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?CodigoFarmacia="+CodigoFarmacia+"&IdArea="+IdArea+"&Bandera=12",true);
ajax.send(null);
return false;
		}else{
			document.getElementById('IdMedico').value='';
			document.getElementById('NombreMedico').innerHTML='';
		}
		
}//ObtenerDatosMedicoBusqueda



/*FIN FUNCIONES POPUP*/

function ComboArea(IdFarmacia){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
											
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}
			document.getElementById('ComboArea').innerHTML=ajax.responseText;
				
			}
		}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=3&IdFarmacia="+IdFarmacia,true);
ajax.send(null);
return false;
}




/*VALIDACION DE FILTRACION DE REPORTE*/

function valida(){
		
		var FechaInicio=document.getElementById('fechaInicial').value;
		var FechaFin=document.getElementById('fechaFinal').value;
		
		if(FechaInicio=='' || FechaFin==''){
			alert('Seleccione un periodo valido para la generacion de datos.-');
			document.getElementById('fechaInicial').focus();
		}else{

				BuscarReceta();
		}//Fechas
}//valida


function ObtenerExistenciaTotal(){
	
	var IdMedicina=document.getElementById('IdMedicina').value;
	var ExistenciaTotal=document.getElementById('ExistenciaTotal');
	var IdArea = document.getElementById('IdReceta').value;

var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			
		}
		if(ajax.readyState==4){
			ExistenciaTotal.value=ajax.responseText;
			valida2();
		}
	}

ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=17&IdMedicina="+IdMedicina+"&IdArea="+IdArea,true);
		ajax.send(null);
		return false;

	
}


function valida2(){
	var Cantidad=document.getElementById('Cantidad').value;
	var IdMedicina = document.getElementById('IdMedicina').value;
	var NombreMedicina=document.getElementById('NombreMedicina').value;	
		var ExistenciaTotal=document.getElementById('ExistenciaTotal').value;
	var Ok=true;
	
	var Cantidad2=parseInt(Cantidad);
	var ExistenciaTotal2=parseInt(ExistenciaTotal);

	if(Cantidad==''){
		alert('Introduzca la Cantidad medicada');
		document.getElementById('Cantidad').focus();
		Ok=false;
	}
	
	if(ExistenciaTotal2 < Cantidad2){
		alert('No se puede ingresar esta receta \n La cantidad rebaza la existencia actual!');
		document.getElementById('Cantidad').focus();
		document.getElementById('Cantidad').select();
		Ok=false;
	}
	
	if((NombreMedicina=='' || IdMedicina=='') && Ok==true){
		alert('Seleccione el medicamento a introducir');
		document.getElementById('NombreMedicina').focus();
	}
		
	

	if(Ok==true){
	 IntroducirNuevaMedicina();
	}

}


function BuscarReceta(){
		//Datos a ingresar

	var ajax = xmlhttp();
	//********************** DATOS GENERALES DE BUSQUEDA ****************************
	var IdFarmacia=document.getElementById('IdFarmacia').value;
	var IdArea=document.getElementById('IdArea').value;
	var IdSubEspecialidad=document.getElementById('IdSubEspecialidad').value;
	var IdEmpleado=document.getElementById('IdMedico').value;
	var IdMedicina=document.getElementById('IdMedicina').value;
	
	var FechaInicial=document.getElementById('fechaInicial').value;
	var FechaFinal=document.getElementById('fechaFinal').value;
	
	//*******************************************************************************
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			document.getElementById('Progreso').innerHTML='<strong>BUSCANDO INFORMACION !</strong>';					
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			document.getElementById('Progreso').innerHTML='&nbsp;';
			var Informacion = ajax.responseText.split('~');

			if(Informacion[0]=='NO'){
				alert("El periodo "+Informacion[1]+" se encuentra finalizado y no se puede hacer ingresos extras en este periodo !");
			}else{
				//*************** SALIDA DE INFORMACION ****************
				var Respuesta=ajax.responseText.split('~');
				document.getElementById('RespuestaExcel').innerHTML=Respuesta[0];
				document.getElementById('Respuesta').innerHTML=Respuesta[1];
					
				
				//******************************************************
					
			}
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=1&IdMedicina="+IdMedicina+"&IdFarmacia="+IdFarmacia+"&IdArea="+IdArea+"&IdSubEspecialidad="+IdSubEspecialidad+"&IdEmpleado="+IdEmpleado+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal,true);
ajax.send(null);
return false;
}//NuevaReceta


function IntroducirNuevaMedicina(){
		//Datos a ingresar

		var ajax = xmlhttp();
		//********************** DATOS GENERALES DE BUSQUEDA ****************************
		var IdReceta=document.getElementById('IdReceta').value;

		var IdMedicina=document.getElementById('IdMedicina').value;
		
		var Cantidad=document.getElementById('Cantidad').value.trim();
		
		var IdMedicinaOrigen=document.getElementById('IdMedicinaOrigen').value;
		//*******************************************************************************
		
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
	           document.getElementById('Respuesta').innerHTML='Guardando ...'				
		}
		if(ajax.readyState==4){
		    if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			document.getElementById('Respuesta').innerHTML='Proceso Finalizado !';
			document.getElementById('Cantidad').value='';
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').focus();
			CargarDetalle(IdReceta,IdMedicinaOrigen);
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=8&IdMedicina="+IdMedicina+"&IdReceta="+IdReceta+"&Cantidad="+Cantidad,true);
		ajax.send(null);
		return false;
}//NuevaReceta





function CargarDetalle(IdReceta,IdMedicina){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
											
		}
		if(ajax.readyState==4){
		  if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

		  var Respuesta = ajax.responseText;
		  document.getElementById(IdReceta).innerHTML=Respuesta;
		}
	}

ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdMedicina="+IdMedicina+"&Bandera=2",true);
ajax.send(null);
return false;	
}//Mostrar Detalle


//**********Cambio de estado de Medicina

function CambioEstadoDetalle(IdMedicinaRecetada,Estado,IdReceta,IdMedicina){
	var A = document.getElementById(IdMedicinaRecetada);
	
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML='Realizando Cambio...';	
		}
		if(ajax.readyState==4){
		  if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			A.innerHTML='';
			CargarDetalle(IdReceta,IdMedicina);
			
		}
	}
	ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=16&IdMedicinaRecetada="+IdMedicinaRecetada+"&Estado="+Estado,true);
	ajax.send(null);
	return false;
	
}



/*FIN CREACION DE REGISTRO DE RECETAS*/

function validaMedicina(){
	var Cantidad=document.getElementById('Cantidad').value;
	var Dosis=document.getElementById('Dosis').value;
	var NombreMedicina=document.getElementById('NombreMedicina').value;	
	var Insatisfecha = document.getElementById('Insatisfecha');
	
	
	
	if(Cantidad==''){
		alert('Introduzca la Cantidad medicada');
		document.getElementById('Cantidad').focus();
	}else{
		if(NombreMedicina==''){
			alert('Seleccione el medicamento a introducir');
			document.getElementById('NombreMedicina').focus();
		}else{
			//if(Dosis==''){
			//	alert('Introduzca la dosis prescrita por el medico\n para el medicamento '+NombreMedicina);
			//	document.getElementById('Dosis').focus();
			//}else{
				GuardarMedicamentoReceta();
			//}
		}
	}
}


function GuardarMedicamentoReceta(){
		/*	VALORES DE BUSQUEDA	*/
		var IdReceta=document.getElementById('IdRecetaValor').value;

		
		/*	VALORES */
		var Cantidad=document.getElementById('Cantidad').value;
		var IdMedicina=document.getElementById('IdMedicina').value;
		var Dosis=document.getElementById('Dosis').value;
			if(Dosis==''){Dosis='-';}
		var Fecha=document.getElementById('Fecha').value;
		var B=document.getElementById('MedicinaNueva');
		var C=document.getElementById('MedicinaNuevaRepetitiva');

		var Insatisfecha = document.getElementById('Insatisfecha');
		
			if(Insatisfecha.checked==true){Satisfecha='I';}else{Satisfecha='S';}
		var Bandera = 5;
		
		/* SE VERIFICA SI ES REPETITIVA */
	//if(check.checked==true){Bandera=3;}else{Bandera=5;}
	//if(NumeroRepetitiva==''){NumeroRepetitiva=0;}
	
	
		/********************************/
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			B.innerHTML= ajax.responseText;
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').value='';
			document.getElementById('Dosis').value='';
			document.getElementById('Insatisfecha').checked=false;
			document.getElementById('Cantidad').focus();
		}
	}

ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&Dosis="+Dosis+"&Bandera="+Bandera+"&Satisfecha="+Satisfecha+"&Fecha="+Fecha,true);
ajax.send(null);
return false;
}


function EliminarMedicina(IdMedicinaRecetada,IdReceta,IdMedicinaOrigen){
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			document.getElementById('Respuesta').innerHTML="Eliminando ...";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}
		//alert(ajax.responseText);
			var datos = ajax.responseText;
			document.getElementById('Respuesta').innerHTML="... Proceso Finalizado ...";
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').value='';
			document.getElementById('Cantidad').focus();
			CargarDetalle(IdReceta,IdMedicinaOrigen);
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedicinaRecetada="+IdMedicinaRecetada+"&Bandera=7",true);
ajax.send(null);
return false;
}//EliminarMedicamento

function ActualizaDosis(IdMedicinaRecetada,NuevaDosis){
	var ajax = xmlhttp();
	var B=document.getElementById('MedicinaNueva');
	var C=document.getElementById('MedicinaNuevaRepetitiva');
	IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	IdReceta=document.getElementById('IdRecetaValor').value;
	var IdArea=document.getElementById('IdArea').value;

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			//B.innerHTML="GUARDANDO RECETA ...";						
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

		   var datos = ajax.responseText.split('<br>');
		   B.innerHTML = datos[0];
		   C.innerHTML = datos[1];
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&NuevaDosis="+NuevaDosis+"&IdArea="+IdArea+"&Bandera=8",true);
ajax.send(null);
return false;
}//EliminarMedicamento


function ActualizaCantidad(IdMedicinaRecetada,Cantidad){
		var ajax = xmlhttp();
		var B=document.getElementById('MedicinaNueva');
		var C=document.getElementById('MedicinaNuevaRepetitiva');
		var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
		var IdReceta=document.getElementById('IdRecetaValor').value;
		var IdArea=document.getElementById('IdArea').value;
		
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		//B.innerHTML="GUARDANDO RECETA ...";						
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}

			var datos = ajax.responseText.split('<br>');
			B.innerHTML = datos[0];
			C.innerHTML = datos[1];
			
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&Cantidad="+Cantidad+"&IdArea="+IdArea+"&Bandera=9",true);
ajax.send(null);
return false;
}//EliminarMedicamento



function FinalizarReceta(){
	var IdReceta = document.getElementById('IdRecetaValor').value;
	var ajax = xmlhttp();
	var B = document.getElementById('MedicinaNueva');
	var IdArea=document.getElementById('IdArea');

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			B.innerHTML="<div align='center'><img src='../images/cargando.gif' /></div>";						
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('La sesion ha caducdo \n Vuelva a iniciar sesion!');window.location='../signIn.php';}
		   window.location='BusquedaRecetas.php';
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Bandera=6",true);
ajax.send(null);
return false;
}

/* Datos de la Receta */


function CambioEstado(IdMedicinaRecetada,IdMedicina){
	var Nombre = 'Insa'+IdMedicinaRecetada;
	var Check = document.getElementById(Nombre);
	var IdArea=document.getElementById('IdArea').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
	var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						/*En proceso hacer nada*/					
					}
				if(ajax.readyState==4){
						//Lotes(IdReceta,IdArea);
											
					}
			}

	if(Check.checked==true){
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedicinaRecetada="+IdMedicinaRecetada+"&IdMedicina="+IdMedicina+"&Estado=I"+"&Bandera=10",true);
		ajax.send(null);
		return false;
	}else{
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedicinaRecetada="+IdMedicinaRecetada+"&IdMedicina="+IdMedicina+"&Estado=S"+"&Bandera=10",true);
		ajax.send(null);
		return false;
	}
	
}//CambioEstado


