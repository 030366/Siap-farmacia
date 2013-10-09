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
	if(Objeto=='CodigoReceta' && key==13){
		document.getElementById('Buscar').focus();
	}
	

	
	/*CONTROLES PARA EL USO DE ATAJOS TERMINAR RECETAS CANCELAR O BUSCAR MEDICAMENTO DESDE CANTIDAD*/
		if(key==13 && Objeto=='Cantidad'){
			document.getElementById('NombreMedicina').focus();
		}
		
		if(key==13 && Objeto=='NombreMedicina'){
			//document.getElementById('Agregar').focus();		
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
			document.getElementById('Cambiar2').focus();
			//CorregirEspecialidad();
		}
		
		if(key==13 && Objeto=='CodigoFarmacia'){
			document.getElementById('Cambiar1').focus();
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


/*****************************************************************************************/

function Correcciones(IdOrigenCambio){
		//Datos a ingresar
		A=document.getElementById(IdOrigenCambio);
		if(IdOrigenCambio=='NombreMedico'){ObjTmp="CodigoFarmacia";}
		if(IdOrigenCambio=='NombreArea'){ObjTmp="IdArea2";}
		if(IdOrigenCambio=='Especialidad'){ObjTmp="CodigoSubEspecialidad";}
		if(IdOrigenCambio=='NombreAreaOrigen'){ObjTmp="IdAreaOrigen2";}
		
		IdReceta=document.getElementById('IdRecetaValor').value;
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/	
					A.innerHTML='Cargando ...';
					
				}
				if(ajax.readyState==4){
				   if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
					A.innerHTML=ajax.responseText;
					document.getElementById(ObjTmp).focus();
					
					
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdOrigenCambio="+IdOrigenCambio+"&Bandera=15&IdReceta="+IdReceta,true);
		ajax.send(null);
		return false;

}//Corregir Area

function PegarIdArea(IdArea){
	document.getElementById('IdArea').value=IdArea;	
}

function PegarIdAreaOrigen(IdArea){
	document.getElementById('IdAreaOrigen').value=IdArea;	
}

/*			ACTUALIZACION DE DATOS			*/
function CorregirArea(){
	var Area = document.getElementById('IdArea').value;
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
	var IdAreaOriginal = document.getElementById('IdAreaNormal').value
	var Fecha=document.getElementById('Fecha').value;
		//Datos a ingresar
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
			/*NOTHING*/	
			
			}
			if(ajax.readyState==4){
			var Respuesta=ajax.responseText;
			   if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
				if(Respuesta=='N'){
					alert('Error de conexion con el Servidor !');
				}else{
					var Respuesta=ajax.responseText.split('~');
					document.getElementById('NombreFarmacia').innerHTML=Respuesta[1];
					document.getElementById('NombreArea').innerHTML=Respuesta[0];
				}
									
			}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdArea="+Area+"&IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&Bandera=14&IdAreaOriginal="+IdAreaOriginal+"&Fecha="+Fecha,true);
		ajax.send(null);
		return false;

}//Corregir Area


function CorregirAreaOrigen(){
	var Area = document.getElementById('IdAreaOrigen').value;
	var IdReceta=document.getElementById('IdRecetaValor').value;
	var IdAreaOriginal = document.getElementById('IdAreaOrigenNormal').value
		//Datos a ingresar
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
			/*NOTHING*/	
			
			}
			if(ajax.readyState==4){
			var Respuesta=ajax.responseText;
			   if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
				if(Respuesta=='N'){
					alert('Error de conexion con el Servidor !');
				}else{
					var Respuesta=ajax.responseText;
					document.getElementById('NombreAreaOrigen').innerHTML=Respuesta;
				}
									
			}
		}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdAreaOrigen="+Area+"&IdReceta="+IdReceta+"&Bandera=14&IdAreaOriginal="+IdAreaOriginal,true);
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
				  if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

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
				  if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

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
function PegarEspecialidad(IdSubEspecialidad,CodigoFarmacia){
	document.getElementById('IdSubEspecialidad').value=IdSubEspecialidad;
	document.getElementById('CodigoSubEspecialidad').value=CodigoFarmacia;
		document.getElementById('Cambiar2').focus();


}

function PegarMedico(IdEspecialidad,NombreEspecialidad,IdMedico,NombreMedico){
	document.getElementById("IdMedico").value=IdMedico;
	document.getElementById("NombreMedico2").innerHTML=NombreMedico;
		ObtenerDatosMedicoBusqueda(IdMedico);
}//pegarMedico





function CargarSubEspecialidad(IdAreaFarmacia){
		//Datos a ingresar
		var Codigo= document.getElementById('CodigoSubEspecialidad').value;
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					/*NOTHING*/
					document.getElementById('Cambiar2').disabled=true;
				}
				if(ajax.readyState==4){
				  if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

					var Datos=ajax.responseText.split('/');
					
					document.getElementById('IdSubEspecialidad').value=Datos[0];
					
					document.getElementById('NombreSubEspecialidad').innerHTML=Datos[1];
					document.getElementById('Cambiar2').disabled=false;
					document.getElementById('Cambiar2').focus();
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Codigo="+Codigo+"&Bandera=13",true);
		ajax.send(null);
		return false;
}//Cargar SubEspecialidad


function ObtenerDatosMedicoBusqueda(IdMedico){
		//Datos a ingresar
		var IdArea=document.getElementById('IdArea').value;
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					/*NOTHING*/		
					document.getElementById('Cambiar1').disabled=true;
				}
				if(ajax.readyState==4){
				    if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
	
					document.getElementById('CodigoFarmacia').value = ajax.responseText;
					document.getElementById('Cambiar1').disabled=false;
					document.getElementById('Cambiar1').focus();

				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedico="+IdMedico+"&IdArea="+IdArea+"&Bandera=11",true);
		ajax.send(null);
		return false;
}//ObtenerDatosMedicoBusqueda


function ObtenerDatosMedico(){
		//Datos a ingresar
		var IdArea=document.getElementById('IdArea').value;
		var CodigoFarmacia= document.getElementById('CodigoFarmacia').value;
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					/*NOTHING*/	
					document.getElementById('Cambiar1').disabled=true;
				}
				if(ajax.readyState==4){
					if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

					var Datos=ajax.responseText.split('/');
					
					//document.getElementById('IdEspecialidad').value = Datos[0];
					document.getElementById('IdMedico').value=Datos[0];
					//document.getElementById('NombreEspecialidad').innerHTML=Datos[2];
					document.getElementById('NombreMedico2').innerHTML=Datos[1];
					document.getElementById('Cambiar1').disabled=false;
					document.getElementById('Cambiar1').focus();
				}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?CodigoFarmacia="+CodigoFarmacia+"&IdArea="+IdArea+"&Bandera=12",true);
		ajax.send(null);
		return false;
}//ObtenerDatosMedicoBusqueda



/*FIN FUNCIONES POPUP*/

/*CREACION DE REGISTRO DE RECETAS*/
/* Manejo de Datos del Paciente */
function valida(){
		var IdReceta = document.getElementById('CodigoReceta').value;
			var Espacios = IdReceta.trim();
		if(IdReceta == '' || Espacios==''){
			alert('Introduzca el Numero de Receta'+Espacios);
			document.getElementById('CodigoReceta').focus();
		}else{
			BuscarReceta(Espacios);
		}//else fechaInicio

}//valida


function BuscarReceta(IdReceta){
		//Datos a ingresar

	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
											
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

			var Informacion = ajax.responseText.split('~');
			if(Informacion[0]=='NO'){
				alert('No existe esta receta');

			}else{
				if(Informacion[0]=="NO2"){
					alert("El periodo "+Informacion[1]+" se encuentra finalizado y no se puede hacer ingresos extras en este periodo !");
				}else{

					document.getElementById('Fecha').value=Informacion[0];
					document.getElementById('MedicinaNueva').innerHTML=Informacion[1];
						
						
					/*	DATOS GRALES	*/
					document.getElementById('NombreArea').innerHTML=Informacion[2];
					document.getElementById('NombreMedico').innerHTML=Informacion[3];
					document.getElementById('Especialidad').innerHTML=Informacion[4];
					document.getElementById('IdHistorialClinico').value=Informacion[5];
					document.getElementById('NombreFarmacia').innerHTML=Informacion[6];
					document.getElementById('NombreAreaOrigen').innerHTML=Informacion[7];

					document.getElementById('IdRecetaValor').value = Informacion[8];
					/*************************/
				
					document.getElementById('CodigoReceta').value=IdReceta;
					document.getElementById('CodigoReceta').disabled=true;
					document.getElementById('Cantidad').disabled=false;
					document.getElementById('Agregar').disabled=false;
					document.getElementById("NombreMedicina").disabled=false;
					document.getElementById('Fecha').disabled=false;
					document.getElementById('CambiarFecha').disabled=false;
					document.getElementById('Cantidad').focus();
				}
			}
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Bandera=1",true);
		ajax.send(null);
		return false;
}//NuevaReceta

function CorregirFecha(){
			var ajax = xmlhttp();
			var IdReceta=document.getElementById('IdRecetaValor').value;
			var Fecha=document.getElementById('Fecha').value;
			var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Fecha="+Fecha+"&IdHistorialClinico="+IdHistorialClinico+"&Bandera=16",true);
		ajax.send(null);
		return false;
	
}


/*FIN CREACION DE REGISTRO DE RECETAS*/

function ObtenerExistenciaTotal(){
	var IdMedicina=document.getElementById('IdMedicina').value;
	var ExistenciaTotal=document.getElementById('ExistenciaTotal');
	var IdArea = document.getElementById('IdAreaActual').value;

var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			
		}
		if(ajax.readyState==4){
			ExistenciaTotal.value=ajax.responseText;
			validaMedicina();
		}
	}

ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=17&IdMedicina="+IdMedicina+"&IdArea="+IdArea,true);
		ajax.send(null);
		return false;

	
}


function validaMedicina(){
	var Cantidad=document.getElementById('Cantidad').value;
	var Dosis=document.getElementById('Dosis').value;
	var NombreMedicina=document.getElementById('NombreMedicina').value;	
	var Insatisfecha = document.getElementById('Insatisfecha');
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
	
	if(NombreMedicina=='' && Ok==true){
		alert('Seleccione el medicamento a introducir');
		document.getElementById('NombreMedicina').focus();
	}
			//if(Dosis==''){
			//	alert('Introduzca la dosis prescrita por el medico\n para el medicamento '+NombreMedicina);
			//	document.getElementById('Dosis').focus();
			//}else{
				
			//}
		
	

	if(Ok==true){
	  GuardarMedicamentoReceta();
	}

}


function GuardarMedicamentoReceta(){
		/*	VALORES DE BUSQUEDA	*/
		var IdReceta=document.getElementById('IdRecetaValor').value;

		
		/*	VALORES */
		var IdArea = document.getElementById('IdAreaActual').value;
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

	
	
		/********************************/
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
		
			B.innerHTML= ajax.responseText;
				
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').value='';
			document.getElementById('Dosis').value='';
			document.getElementById('Insatisfecha').checked=false;
			document.getElementById('Cantidad').focus();
		}
	}

ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&Dosis="+Dosis+"&IdArea="+IdArea+"&Bandera="+Bandera+"&Satisfecha="+Satisfecha+"&Fecha="+Fecha,true);
		ajax.send(null);
		return false;
}



function CancelarReceta(){
	var IdReceta = document.getElementById('IdRecetaValor').value;	
	var IdHistorialClinico = document.getElementById('IdHistorialClinico').value;
	var A = document.getElementById('MedicinaNueva');
	var B = document.getElementById('MedicinaNuevaRepetitiva');
	var IdArea=document.getElementById('IdArea').value;
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}

			alert('La Receta y sus Medicinas han sido Canceladas');
			A.innerHTML="";
			B.innerHTML="";
			//document.getElementById('Expediente').disabled=false;
			document.getElementById('AddReceta').disabled=false;
			document.getElementById('Cancelar').disabled=true;
			document.getElementById('Cantidad').disabled=true;
			//document.getElementById('Busqueda').disabled=true;
			document.getElementById('Agregar').disabled=true;
			document.getElementById('Finalizar').disabled=true;
			document.getElementById('Dosis').disabled=true;
			document.getElementById("NombreMedicina").disabled=true;
				document.getElementById('Cantidad').value='';
				document.getElementById('IdMedicina').value='';
				document.getElementById('NombreMedicina').value='';
				document.getElementById('Dosis').value='-';
				//document.getElementById('RecetaNumero').value='';
			document.getElementById('Insatisfecha').disabled=true;
			document.getElementById('Insatisfecha').checked=false;
			document.getElementById('ActualizacionArea').innerHTML='';
			document.getElementById('ActualizacionMedico').innerHTML='';
			document.getElementById('ActualizacionEspecialidad').innerHTML='';
			
		}
	}
		
	ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdHistorialClinico="+IdHistorialClinico+"&IdReceta="+IdReceta+"&IdArea="+IdArea+"&Bandera=4",true);
		ajax.send(null);
		return false;

}//CancelarReceta


function EliminaMedicina(IdMedicinaRecetada){
		var ajax = xmlhttp();
		var B=document.getElementById('MedicinaNueva');
		var C=document.getElementById('MedicinaNuevaRepetitiva');
		var IdArea=document.getElementById('IdAreaActual').value;

		IdReceta=document.getElementById('IdRecetaValor').value;

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		//B.innerHTML="GUARDANDO RECETA ...";						
		}
		if(ajax.readyState==4){
		   if(ajax.responseText=='ERROR_SESSION'){alert('Sesion caducada! \n Inicie sesion nuevamente');window.location='../signIn.php';}
			var datos = ajax.responseText.split('<br>');
			B.innerHTML = datos[0];
			C.innerHTML = datos[1];
		}
	}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&IdArea="+IdArea+"&Bandera=7",true);
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
					var datos = ajax.responseText.split('<br>');
					B.innerHTML = datos[0];
					C.innerHTML = datos[1];
					//Lotes(IdReceta,IdArea);
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
						//Existencias(IdReceta,IdArea);
						window.location='BusquedaRecetas.php';
					}
			}
ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Bandera=6",true);
		ajax.send(null);
		return false;
}

/* Datos de la Receta */

function Existencias(IdReceta,IdArea){
	var B = document.getElementById('MedicinaNueva');
	var Fecha=document.getElementById('Fecha').value;
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			/*En proceso hacer nada*/					
			B.innerHTML="<div align='center'><img src='../images/cargando.gif' /></div>";
		}
		if(ajax.readyState==4){
			B.innerHTML="";
			alert('Receta introducida satisfactoriamente !');
			window.location=window.location;		
		
		}
	}

ajax.open("GET","IncludeFiles/MantenimientoExistencias.php?IdReceta="+IdReceta+"&IdArea="+IdArea+"&Fecha="+Fecha,true);
		ajax.send(null);
		return false;
}//Mantenimiento de Existencia


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


