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


//CARGA DE AREAS DE FARMACIA SELECCIONADA
function CargarAreas(IdFarmacia){

    if(IdFarmacia==0){
	document.getElementById('acciones').innerHTML="";
    }else{

var A = document.getElementById('acciones');
var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			var Informacion = ajax.responseText;
			//alert(ajax.responseText);
			if(Informacion=='NO'){
			   A.innerHTML="";
					
			}else{
			   A.innerHTML=ajax.responseText;
			}
		}
	}
		
ajax.open("GET","IncludeFiles/Procesos.php?IdFarmacia="+IdFarmacia+"&Bandera=1",true);
ajax.send(null);
return false;
   }
}

//**********************************************

//Cambio de estado

function CambiaEstado(IdArea,TipoCambio,IdFarmacia){

   var ajax = xmlhttp();
	var Estado='S';
	if(TipoCambio==2){Estado='N';}
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			//alert(ajax.responseText);
			CargarAreas(IdFarmacia);
			
		}
	}

ajax.open("GET","IncludeFiles/Procesos.php?IdArea="+IdArea+"&Estado="+Estado+"&Bandera=2",true);
ajax.send(null);
return false;

}

//******************CAMBIO DE NOMBRE D FARMACIAS********************

function CambioNombre(IdFarmacia){
	var Obj=document.getElementById(IdFarmacia);
	var span="span"+IdFarmacia;
	var spanExt="spanExt"+IdFarmacia;
	    var Cambios=document.getElementById(span);
	    var Visualizar=document.getElementById(spanExt);
	var Ok=true;

  if(Obj.disabled==true && IdFarmacia==4){
	alert('No se puede cambiar el nombre de esta area!');	
	Ok=false;
  }

  if(Obj.checked==false){
	alert('Antes de cambiar el nombre de las farmacias debe se ser seleccionada!');
	Ok=false;
  }


    if(Ok==true){
	
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			
			Visualizar.innerHTML=ajax.responseText;
			var futuro="Nombre"+IdFarmacia;
			document.getElementById(futuro).focus();
		}
	}


ajax.open("GET","IncludeFiles/Procesos.php?IdFarmacia="+IdFarmacia+"&Bandera=5",true);
ajax.send(null);
return false;

  }

}


function CambiarNombreFinal(Id,valor,IdFarmacia){

	var Ok=true;
	var spanExt="spanExt"+IdFarmacia;
	    var Visualizar=document.getElementById(spanExt);


if(trim(valor,Id)==''){
	alert('El nombre de la Farmacia no puede ser vacio!');
	Ok=false;
}

if(Ok==true){
	
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			
			Visualizar.innerHTML=ajax.responseText;
			Habilitar(IdFarmacia,0); //segundo parametro no se utiliza
			
		}
	}


ajax.open("GET","IncludeFiles/Procesos.php?IdFarmacia="+IdFarmacia+"&NombreNuevo="+valor+"&Bandera=6",true);
ajax.send(null);
return false;

  }	

}
///***************************************************************************

//*****************CAMBIO DE NOMBRE DE AREAS DE FARMACIA*************************

function CambioNombreArea(IdArea){
	var Obj=document.getElementById(IdArea);
	var span="spanArea"+IdArea;
	var spanExt="spanAreaExt"+IdArea;
	    var Cambios=document.getElementById(span);
	    var Visualizar=document.getElementById(spanExt);
	var Ok=true;


    if(Ok==true){
	
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			
			Visualizar.innerHTML=ajax.responseText;
			var futuro="Nombre"+IdArea;
			document.getElementById(futuro).focus();
		}
	}


ajax.open("GET","IncludeFiles/Procesos.php?IdArea="+IdArea+"&Bandera=7",true);
ajax.send(null);
return false;

  }

}

//CAMBIO FINAL DE NOMBRE DE AREA DE FARMACIA

function CambiarNombreFinalArea(Id,valor,IdArea){

	var Ok=true;
	var spanExt="spanAreaExt"+IdArea;
	    var Visualizar=document.getElementById(spanExt);


if(trim(valor,Id)==''){
	alert('El nombre de la Farmacia no puede ser vacio!');
	Ok=false;
}

if(Ok==true){
	
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			
			Visualizar.innerHTML=ajax.responseText;
// 			HabilitarArea(IdArea,0); //segundo parametro no se utiliza
			
		}
	}


ajax.open("GET","IncludeFiles/Procesos.php?IdArea="+IdArea+"&NombreNuevo="+valor+"&Bandera=8",true);
ajax.send(null);
return false;

  }	

}

//***********************************************************************************//


function Habilitar(IdFarmacia,TipoCambio){

   var ajax = xmlhttp();
	var Estado='S';
	if(document.getElementById(IdFarmacia).checked==true){Estado='S';}else{Estado='N';}
// 	if(TipoCambio==2){}
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			
			document.getElementById('ComboFarmacia').innerHTML=ajax.responseText;
			if(Estado=='N'){document.getElementById('acciones').innerHTML="";}
		}
	}


ajax.open("GET","IncludeFiles/Procesos.php?IdFarmacia="+IdFarmacia+"&Estado="+Estado+"&Bandera=4",true);
ajax.send(null);
return false;

}

//*********************************************

//Ingreso de Area Nueva
function Agregar(){

   var NuevaArea = document.getElementById('NuevaArea').value;
   var IdFarmacia=document.getElementById('IdFarmacia').value;
   if(trim(NuevaArea,'NuevaArea')==''){
	alert('Digite el nombre de la area a crear \n El nombre no puede ser vacio');
	document.getElementById('NuevaArea').focus();
   }else{
   var ajax = xmlhttp();
	
	
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
				
		}
		if(ajax.readyState==4){
			if(ajax.responseText=='N'){
			   alert('La area a introducir ya existe!');
			}else{
			CargarAreas(IdFarmacia);
			}
		}
	}

ajax.open("GET","IncludeFiles/Procesos.php?NombreArea="+NuevaArea+"&IdFarmacia="+IdFarmacia+"&Bandera=3",true);
ajax.send(null);
return false;
}
}
//**********************************************


function valida(){
		var CodigoServicio = document.getElementById('CodigoServicio').value.trim();
		var NombreServicio = document.getElementById('NombreServicio').value.trim();
					

if(CodigoServicio == ''){
	alert('Ingrese el codigo del servicio');
	document.getElementById('CodigoServicio').focus();
}else{
	if(NombreServicio==''){
		alert('Debe digitar el nombre del servicio a ingresar');
			document.getElementById('NombreServicio').focus();
	}else{

				document.getElementById('CodigoServicio').value=CodigoServicio;
				document.getElementById('NombreServicio').value=NombreServicio;
				GuardarServicio();

	}
}

}//valida


function GuardarServicio(){
		//Datos a ingresar
		var CodigoServicio = document.getElementById('CodigoServicio').value;
		var NombreServicio = document.getElementById('NombreServicio').value;

		//****************************
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
											
					}
				if(ajax.readyState==4){
					var Informacion = ajax.responseText;
					//alert(ajax.responseText);
					if(Informacion=='NO'){

							alert('El codigo de Farmacia ingresado ya existe!');
							document.getElementById('CodigoServicio').focus();
							document.getElementById('Respuesta').innerHTML="<strong><h2>ERROR DE INGRESO DE SERVICIO!</h2></strong>";
						
					}else{
						document.getElementById('Respuesta').innerHTML="<strong><h2>Servicio ingresado satisfactoriamente !</h2></strong>";
					}
				}
			}
		
		ajax.open("GET","IncludeFiles/Procesos.php?CodigoServicio="+CodigoServicio+"&NombreServicio="+NombreServicio+"&Bandera=1",true);
		ajax.send(null);
		return false;
}//NuevaReceta
