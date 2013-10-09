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
	}

function MostrarDetalle(IdPersonal){
		
var A = document.getElementById('resultados');
var B = document.getElementById('loading');
var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			A.innerHTML = ajax.responseText;
			B.innerHTML = "";
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=1&IdPersonal="+IdPersonal,true);
ajax.send(null);
return false;
}


function CambioNivel(nivelActual){
   var IdPersonal = document.getElementById('IdPersonal').value;
   var TipoNivel = document.getElementById('TipoNivel');
	var B = document.getElementById('loading');
var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			TipoNivel.innerHTML = ajax.responseText;
			var opcion=nivelActual - 2;
			document.getElementById('nivel')[opcion].selected=true;
			document.getElementById('nivel').focus();;
			B.innerHTML = "";
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=2&SubOpcion=21&nivelActual="+nivelActual+"&IdPersonal="+IdPersonal,true);
ajax.send(null);
return false;
}


function CambioNivelFinal(NivelNuevo){
   var IdPersonal = document.getElementById('IdPersonal').value;
   var TipoNivel =document.getElementById('TipoNivel');
var B = document.getElementById('loading');

var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			TipoNivel.innerHTML = ajax.responseText;
			
			B.innerHTML = "";
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=2&SubOpcion=22&IdPersonal="+IdPersonal+"&NivelNuevo="+NivelNuevo,true);
ajax.send(null);
return false;

}


function CambiarPermisos(obj){
   var IdPersonal = document.getElementById('IdPersonal').value;
   var Opcion = document.getElementById(obj);
   var acceso=0;
	if(Opcion.checked==true){
	  acceso=1;
	}

var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			//B.innerHTML = "<img src='../../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			//B.innerHTML = "";
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=3&SubOpcion=31&IdPersonal="+IdPersonal+"&Id="+obj+"&acceso="+acceso,true);
ajax.send(null);
return false;

}

function CambiaUbicacion(Opcion){
   var IdPersonal = document.getElementById('IdPersonal').value;
   var Contenedor = document.getElementById('ReUbicacion');

var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			//B.innerHTML = "<img src='../../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			Contenedor.innerHTML = ajax.responseText;
		}
	}
var SubOpcion="";
if(Opcion==1){
   SubOpcion="&SubOpcion=41";
}
if(Opcion==2){
   SubOpcion="&SubOpcion=42";
}
if(Opcion==3){
   SubOpcion="&SubOpcion=44";
}


var URL="include/Procesos.php?Bandera=4"+SubOpcion+"&IdPersonal="+IdPersonal;

if(Opcion==2){
//ActualizaInformacion
   var IdFarmacia = document.getElementById('farmacia').value;
   var IdArea= document.getElementById('area').value;
   URL+="&IdFarmacia="+IdFarmacia+"&IdArea="+IdArea;
}

if(Opcion==3){
//cancelacion

}
ajax.open("GET",URL,true);
ajax.send(null);
return false;

}


function CargarAreas(IdFarmacia){
	var IdPersonal = document.getElementById('IdPersonal').value;
	var B = document.getElementById('ComboAreas');
var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			//B.innerHTML = "<img src='../../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			B.innerHTML = ajax.responseText;
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=4&SubOpcion=43&IdFarmacia="+IdFarmacia+"&IdPersonal="+IdPersonal,true);
ajax.send(null);
return false;
}


function CambiarEstado(){
   var IdPersonal = document.getElementById('IdPersonal').value;
   var Opcion = document.getElementById('EstadoCuenta');
   var NuevoEstado='';
	if(Opcion.checked==true){
	   NuevoEstado='H';
	}else{
	   NuevoEstado='I';
	}

var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			//B.innerHTML = "<img src='../../loading.gif' alg='Loading...'>";
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){
			   alert("La sesion ha caducado \n vuelva a iniciar sesion!");
			   window.location="../signIn.php";
			}
			//B.innerHTML = "";
		}
	}
ajax.open("GET","include/Procesos.php?Bandera=5&IdPersonal="+IdPersonal+"&NuevoEstado="+NuevoEstado,true);
ajax.send(null);
return false;

}


