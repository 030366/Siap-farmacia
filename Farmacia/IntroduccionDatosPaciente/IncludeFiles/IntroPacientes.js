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


/* DATOS GENERALES PACIENTE */
function valida(){
		var PrimerNombre = document.getElementById('PrimerNombre').value;
		var SegundoNombre = document.getElementById('SegundoNombre').value;
		var PrimerApellido = document.getElementById('PrimerApellido').value;
		var SegundoApellido= document.getElementById('SegundoApellido').value;
		var FechaNacimiento = document.getElementById('FechaNacimiento').value;
		var Sexo = document.getElementById('Sexo').value
		var NombreMadre = document.getElementById('NombreDeMadre').value;

if(PrimerApellido == '' || SegundoNombre=='' || PrimerNombre==''){
	alert('Introduzca el nombre completo del paciente');
	document.getElementById('PrimerApellido').focus();
}else{
	if(Sexo==0){
		alert('Seleccione el genero del paciente');
			document.getElementById('Sexo').focus();
	}else{
		if(FechaNacimiento==''){
			alert('Seleccione la fecha de nacimiento del paciente');
		}else{
			if(NombreMadre==''){
				alert('Para fines de distincion\n introduzca el nombre de la madre del paciente');
				document.getElementById('NombreDeMadre').focus();
			}else{
				if(confirm('Son los datos del paciente correctos?')==1){
				GuardarDatos();
				}
			}
				}//else barra
			}//else fechaFin
		}//else fechaInicio

}//valida


function GuardarDatos(){
		//Datos a ingresar
		var A = document.getElementById('IntroduccionExpediente');
		var PrimerNombre = document.getElementById('PrimerNombre').value;
		var SegundoNombre = document.getElementById('SegundoNombre').value;
		var TercerNombre = document.getElementById('TercerNombre').value;
		var PrimerApellido = document.getElementById('PrimerApellido').value;
		var SegundoApellido= document.getElementById('SegundoApellido').value;
		var FechaNacimiento = document.getElementById('FechaNacimiento').value;
		var Sexo = document.getElementById('Sexo').value
		var NombreMadre = document.getElementById('NombreDeMadre').value;

		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
											
					}
				if(ajax.readyState==4){
					var Informacion = ajax.responseText.split('-');
					if(Informacion[0]=='SI'){alert('Registro de paciente creado !');}
					document.getElementById('IdPaciente').value=Informacion[1];//introduccion del valor de idpaciente
					A.innerHTML = Informacion[2];//impresion de la tabla
					document.getElementById('PrimerNombre').disabled=true;
					document.getElementById('SegundoNombre').disabled=true;
					document.getElementById('PrimerApellido').disabled=true;
					document.getElementById('SegundoApellido').disabled=true;
					document.getElementById('FechaNacimiento').disabled=true;
					document.getElementById('Sexo').disabled=true
					document.getElementById('NombreDeMadre').disabled=true;
					document.getElementById('BotonExpediente').disabled=true;
					document.getElementById('Cancelar').disabled=true;
					}
			}
		
		ajax.open("GET","IncludeFiles/IntroduccionPacientesProceso.php?Apellido1="+PrimerApellido+"&Apellido2="+SegundoApellido+"&Nombre1="+PrimerNombre+"&Nombre2="+SegundoNombre+"&Nombre3="+TercerNombre+"&FechaNacimiento="+FechaNacimiento+"&Sexo="+Sexo+"&NombreMadre="+NombreMadre+"&Bandera=1",true);
		ajax.send(null);
		return false;
}//NuevaReceta


function valida2(){
	var NumeroExpediente=document.getElementById('NumeroExpediente').value;
	if(NumeroExpediente==''){
		alert('Introduzca el numero de expediente del paciente');
		document.getElementById('NumeroExpediente').focus();
	}else{
		AsignarNumeroExpediente(NumeroExpediente);
	}
}



function AsignarNumeroExpediente(NumeroExpediente){
	var IdPaciente = document.getElementById('IdPaciente').value;
	var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//B.innerHTML="GUARDANDO RECETA ...";						
					}
					if(ajax.readyState==4){
						var Respuesta = ajax.responseText;
						if(Respuesta=='OK1'){	
							alert('Registro creado !');
							window.location='../IntroduccionRecetas/IntroduccionRecetasPrincipal.php';
						}else{
							alert('Este Numero de expediente ya existe');
						}//eslse
					}
			}//function
ajax.open("GET","IncludeFiles/IntroduccionPacientesProceso.php?NumeroExpediente="+NumeroExpediente+"&IdPaciente="+IdPaciente+"&Bandera=2",true);
		ajax.send(null);
		return false;
}//funcnio

/* Datos de la Receta */







