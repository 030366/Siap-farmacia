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
		return ((key < 13) || (key >= 48 && key <= 57) || key == 46);
}




function ActualizarPrecios(IdMedicina,PrecioNuevo,IdUsuarioReg){
		//Datos a ingresar
		var Precio=PrecioNuevo.trim();
		if(Precio==''){PrecioNuevo=0;}
		
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
									/*NOTHING*/		
					}
				if(ajax.readyState==4){
					var Respuesta=ajax.responseText;
						var Objeto = "Precio"+IdMedicina;
					document.getElementById(Objeto).value=Respuesta;
				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoActualizacion.php?IdMedicina="+IdMedicina+"&Precio="+Precio+"&IdUsuarioReg="+IdUsuarioReg+"&Bandera=1",true);
		ajax.send(null);
		return false;
}//ObtenerDatosMedicoBusqueda


