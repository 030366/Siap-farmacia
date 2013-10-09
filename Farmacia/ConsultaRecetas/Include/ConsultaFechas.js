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


/*Obtencion de Recetas Futuras*/
function Respuesta(){
	   //var query = document.getElementById('q').value;
		var Expediente = document.getElementById('expediente').value;
		var A = document.getElementById('Respuesta');
		var B = document.getElementById('Cambios');
		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "Listando Recetas Repetitivas . . .";
						B.innerHTML = "";
					}
				if(ajax.readyState==4){
					    
						A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
						
					}
			}
ajax.open("GET","Include/ObtencionConsulta.php?Expediente="+Expediente,true);
		ajax.send(null);
		return false;
		
}//Respuesta

/*Actualizacion de Fechas*/
function mostrar(IdReceta){
	   //var query = document.getElementById('q').value;
	   var Fecha = document.getElementById('fecha'+IdReceta).value;
		var A = document.getElementById('Cambios');
		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "Listando Opciones . . .";
					}
				if(ajax.readyState==4){
					    
						A.innerHTML = ajax.responseText;
					
					}
			}
ajax.open("GET","Include/ObtencionFechas.php?IdReceta="+IdReceta+"&Fecha="+Fecha,true);
		ajax.send(null);
		return false;
		
}//Actualizacin de fechas

/* Focus */
function inicio(){
	document.getElementById('expediente').focus();	
}//inicio
