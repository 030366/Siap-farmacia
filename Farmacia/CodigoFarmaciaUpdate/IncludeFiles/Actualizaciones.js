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



/*Verificacion de campos vacios*/
function verificacion(){
	if(document.form.expediente.value==''){
		alert('Introduzca un numero de Expediente valido');	
	}else{
	Respuesta();
	}
}//verificacion



function VentanaBusqueda(URL){//Modifica Dosis
	day = new Date();
	id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=225,height=130,left = 150,top = 270');");
}

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

function PegarMedicina(IdMedicina,NombreMedicina){
	document.getElementById('IdMedicina').value=IdMedicina;
	document.getElementById('NombreMedicina').value=NombreMedicina;
	//document.getElementById('Agregar').focus();
}

function PegarMedico(IdEspecialidad,NombreEspecialidad,IdMedico,NombreMedico){
	document.getElementById("IdEspecialidad").value=IdEspecialidad;
	document.getElementById("NombreEspecialidad").innerHTML=NombreEspecialidad;
	document.getElementById("IdMedico").value=IdMedico;
	document.getElementById("NombreMedico").innerHTML=NombreMedico;
		ObtenerDatosMedicoBusqueda(IdMedico);
}//pegarMedico


function FillGrid(Pagina){
		//Datos a ingresar
		var Medicos=document.getElementById('Medicos').value;
		var Datos=document.getElementById('Medicos');
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/		
					}
				if(ajax.readyState==4){
					document.getElementById('Limpiar').disabled=true;
					document.getElementById('NombreEmpleado').value='';
					document.getElementById('CodigoFarmacia').value='';
					Datos.innerHTML = ajax.responseText;
				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoActualizaciones.php?Bandera=1&pagina="+Pagina,true);
		ajax.send(null);
		return false;
}//ObtenerDatosMedicoBusqueda


function CodigoMedico(Codigo,Bandera){
		//Datos a ingresar
		var ajax = xmlhttp();
		var Contenedor=Codigo.split('Codigo');
			var Div='Contenedor'+Contenedor[1];
		var Datos=document.getElementById(Div);
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					Datos.innerHTML='Guardando...';	
				}
				if(ajax.readyState==4){
					var Respuesta=ajax.responseText.split('~');
					if(Respuesta[0]=='N'){
						alert('El codigo:" '+Respuesta[1]+' " ya ha sido asignado a otro medico');
						CodigoMedico(Codigo,2);
					}else{
						Datos.innerHTML = Respuesta[0];
					}
					
					if(Bandera==2){
						document.getElementById(Codigo).focus();
					}//Bandera
				}
			}
		
		if(Bandera==3){
			var CodigoNuevo=document.getElementById(Codigo).value;	
			var Dato='&CodigoNuevo='+CodigoNuevo;
		}else{
			var Dato='';
		}
		ajax.open("GET","IncludeFiles/ProcesoActualizaciones.php?Bandera="+Bandera+"&IdMedico="+Codigo+"&Medico="+Contenedor[1]+Dato,true);
		
		
		ajax.send(null);
		return false;
}//CodigoMedico

function EspecialidadMedico(Combo,Bandera){
		//Datos a ingresar
		var ajax = xmlhttp();
		var Contenedor=Combo.split('Combo');
			var Div='Contenedor2'+Contenedor[1];
		var Datos=document.getElementById(Div);
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					Datos.innerHTML='Guardando...';	
				}
				if(ajax.readyState==4){
					var Respuesta=ajax.responseText.split('~');
					if(Respuesta[0]=='N' || Respuesta[0]=='C'){
						if(Respuesta[0]=='N'){
						alert('No se puede cambiar la especialidad de este medico \n no posee privilegios para realizar este cambio.-');
						}
						Datos.innerHTML=Respuesta[1];
						
					}else{
						Datos.innerHTML = Respuesta[0];
						if(Bandera==5){
							document.getElementById(Combo).focus();	
						}
						
					}//ELSE

				}
			}
		
		if(Bandera==6){
			var NuevaEspecialidad=document.getElementById(Combo).value;	
			var Dato='&NuevaEspecialidad='+NuevaEspecialidad;
		}else{
			var Dato='';
		}
		ajax.open("GET","IncludeFiles/ProcesoActualizaciones.php?Bandera="+Bandera+"&Combo="+Combo+"&Medico="+Contenedor[1]+Dato,true);
		
		
		ajax.send(null);
		return false;
}//Especialidadmedico


function EstadoMedico(Estado,Bandera){
		//Datos a ingresar
		var ajax = xmlhttp();
		var Contenedor=Estado.split('Estado');
			var Div='Contenedor3'+Contenedor[1];
		var Datos=document.getElementById(Div);
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					Datos.innerHTML='Guardando...';	
				}
				if(ajax.readyState==4){
					var Respuesta=ajax.responseText.split('~');
					if(Respuesta[0]=='N' || Respuesta[0]=='C'){
						if(Respuesta[0]=='N'){
						alert('No se puede cambiar la especialidad de este medico \n no posee privilegios para realizar este cambio.-');
						}
						Datos.innerHTML=Respuesta[1];
						
					}else{
						Datos.innerHTML = Respuesta[0];
						if(Bandera=7){
							document.getElementById(Estado).focus();	
						}
						
					}//ELSE

				}
			}
		
		if(Bandera==8){
			var NuevoEstado=document.getElementById(Estado).value;	
			var Dato='&NuevoEstado='+NuevoEstado;
		}else{
			var Dato='';
		}
		ajax.open("GET","IncludeFiles/ProcesoActualizaciones.php?Bandera="+Bandera+"&Estado="+Estado+"&Medico="+Contenedor[1]+Dato,true);
		
		
		ajax.send(null);
		return false;
}//EstadoMedico



function FillGridBusqueda(){
		//Datos a ingresar
		var Medicos=document.getElementById('Medicos').value;
		var NombreEmpleado=document.getElementById('NombreEmpleado').value;
		var CodigoFarmacia=document.getElementById('CodigoFarmacia').value;
		var Datos = document.getElementById('Medicos');
		
		if(NombreEmpleado=='' && CodigoFarmacia==''){
			alert('Almenos uno de los campos debe \n debe contener informacion a ser buscada');
		}else{
		
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/		
					}
				if(ajax.readyState==4){
					document.getElementById('Limpiar').disabled=false;

					Datos.innerHTML = ajax.responseText;
					
				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoActualizaciones.php?Bandera=4&NombreEmpleado="+NombreEmpleado+"&CodigoFarmacia="+CodigoFarmacia,true);
		ajax.send(null);
		return false;
		}
}//ObtenerDatosMedicoBusqueda



