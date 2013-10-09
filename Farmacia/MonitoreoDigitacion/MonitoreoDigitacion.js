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

function recarga(){
	setTimeout('MonitoreoDigitacion()', 6000);
	
}//recarga
function recargaEnLinea(){
	setTimeout('MonitoreoEnLinea()', 6500);
}//recarga

function recargaChat(){
	setTimeout('MonitoreoChat()', 6500);
}//recarga


function MonitoreoDigitacion(){
		
   var A = document.getElementById('Monitoreo');
   var ajax = xmlhttp();
   ajax.onreadystatechange=function(){
	if(ajax.readyState==1){
	   document.getElementById('Progreso').innerHTML = "<img src='../images/barra.gif' lg='Loading...'>";
	}
	if(ajax.readyState==4){
	   if(ajax.responseText=="ERROR_SESSION"){
		alert("La sesion ha caducado \n inicie sesion nuevamente!");
 		window.location='../signIn.php';
	   }
	   A.innerHTML = ajax.responseText;
	   document.getElementById('Progreso').innerHTML ="";	
	   recarga();
	}
   }
ajax.open("GET","../MonitoreoDigitacion/MonitoreoProceso.php?Bandera=1",true);
ajax.send(null);
return false;
}

function MonitoreoEnLinea(){
		
   var A = document.getElementById('MonitoreoEnLinea');
   var ajax = xmlhttp();
   ajax.onreadystatechange=function(){
	if(ajax.readyState==1){
	   document.getElementById('ProgresoLinea').innerHTML = "<img src='../images/barra.gif' lg='Loading...'>";
	}
	if(ajax.readyState==4){
	   if(ajax.responseText=="ERROR_SESSION"){
		alert("La sesion ha caducado \n inicie sesion nuevamente!");
 		window.location='../signIn.php';
	   }
           
           var Respuesta=ajax.responseText.split("~");
           
	   A.innerHTML = Respuesta[0];
           
            if(Respuesta[1]=="OK"){              
               //document.getElementById("sound");
                var soundfile="../Sonidos/Sonido.ogg";
                document.getElementById("dummy").innerHTML="<embed src=\""+soundfile+"\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
            }
           
	  document.getElementById('ProgresoLinea').innerHTML ="";
	   recargaEnLinea();
	}
   }
ajax.open("GET","../MonitoreoDigitacion/MonitoreoProceso.php?Bandera=2",true);
ajax.send(null);
return false;
}

function MonitoreoChat(){
		
   var A = document.getElementById('MonitoreoEnLinea');
   var ajax = xmlhttp();
   ajax.onreadystatechange=function(){
	if(ajax.readyState==1){
	   document.getElementById('ProgresoLinea').innerHTML = "<img src='../images/barra.gif' lg='Loading...'>";
	}
	if(ajax.readyState==4){
	   if(ajax.responseText=="ERROR_SESSION"){
		alert("La sesion ha caducado \n inicie sesion nuevamente!");
 		window.location='../signIn.php';
	   }
		
	   var Respuesta=ajax.responseText.split("~");
           
	   A.innerHTML = Respuesta[0];
           
            if(Respuesta[1]=="OK"){              
               //document.getElementById("sound");
                var soundfile="../Sonidos/Sonido.ogg";
                document.getElementById("dummy").innerHTML="<embed src=\""+soundfile+"\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
            }
           
           
	  document.getElementById('ProgresoLinea').innerHTML ="";
	   recargaChat();
	}
   }
ajax.open("GET","../MonitoreoDigitacion/MonitoreoProceso.php?Bandera=3",true);
ajax.send(null);
return false;
}

function AbrirChat(IdPersonal){//BUSQUEDA DE MEDICAMENTO
	day = new Date();
	id = day.getTime();
		var URL="../Chat/ChatPrincipal.php?IdPersonalD="+IdPersonal;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=375,height=300,left = 100,top = 100');");
}

function VentanaBusqueda(){//BUSQUEDA DE MEDICAMENTO
	day = new Date();
	id = day.getTime();
		var URL="../AvisoVencimiento/AvisoPrincipal.php";
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=780,height=600,left = 100,top = 100');");
}