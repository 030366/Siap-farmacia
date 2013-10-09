function cancelar(){
var A = document.getElementById('Layer2');	
//var B = document.getElementById('Layer3');
A.innerHTML = "";	
A.innerHTML = "<h2>CANCELADO</h2>"
}


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

function desplegar(IdPersonal){
		//var query = document.getElementById('q').value;
		var A = document.getElementById('Layer2');
		var B = document.getElementById('DetalleRecetas');
		var ubicacionNombre = 'Nombre'+IdPersonal;
		var NombrePersonal = document.getElementById(ubicacionNombre).value;
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "BUSCANDO RECETAS DE USUARIO SELECCIONADO...";
						B.innerHTML = "";
						
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
					}
			}
		
		ajax.open("GET","buscador_receta.php?IdPersonal="+IdPersonal+"&NombrePersonal="+NombrePersonal,true);
		ajax.send(null);
		return false;
		
}//desplegar
	
	
	
function DetalleReceta(IdReceta){
		var A = document.getElementById('DetalleRecetas');
		var ubicacionIMG = 'IMG'+IdReceta;
		var IMG = document.getElementById(ubicacionIMG);
		var NombrePaciente = document.getElementById('NombrePaciente'+IdReceta).value;
		var ajax = xmlhttp();
		var Nombre = NombrePaciente;
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "OBTENIENDO DETALLES DE RECETA...";
						//IMG.innerHTML = "Cargando Informaci&oacute;n...";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						IMG.innerHTML = "<img src='Iconos/ready.JPG'/>";	
					}
			}
		
		ajax.open("GET","DetalleReceta.php?IdReceta="+IdReceta+"&NombrePaciente="+encodeURIComponent(NombrePaciente),true);
		ajax.send(null);
		return false;
		
}//DetalleReceta


function paginacion(pagina,IdPersonal){
		var Detalles = document.getElementById('DetalleRecetas');
		var Resulta2 = document.getElementById('resultados2');
		var ubicacionNombre = 'Nombre'+IdPersonal;
		var NombrePersonal = document.getElementById(ubicacionNombre).value;

		var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						
						Resulta2.innerHTML = "OBTENIENDO RECETAS...";
						Detalles.innerHTML = "";
					
					}
				if(ajax.readyState==4){
						Resulta2.innerHTML = ajax.responseText;
					}
			}
		
		ajax.open("GET","buscador_receta.php?page2="+pagina+"&IdPersonal="+IdPersonal+"&NombrePersonal="+NombrePersonal,true);
		ajax.send(null);
		return false;
	
	
	
	
	
}

