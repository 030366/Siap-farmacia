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


function CargarUltimo(){
	var A = document.getElementById('Ultimo');
	var ajax = xmlhttp();	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					A.innerHTML="Cargando...";
					}
				if(ajax.readyState==4){
					A.innerHTML = ajax.responseText;

				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoIngresoServicios.php?Bandera=2",true);
		ajax.send(null);
		return false;
}

function CargarCombo(){
	var A = document.getElementById('ComboServicio');
	var ajax = xmlhttp();	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					A.innerHTML="Cargando...";
					}
				if(ajax.readyState==4){
					A.innerHTML = ajax.responseText;

				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoIngresoServicios.php?Bandera=3",true);
		ajax.send(null);
		return false;
}


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
		var IdServicio=document.getElementById('IdServicio').value;
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
		
		ajax.open("GET","IncludeFiles/ProcesoIngresoServicios.php?CodigoServicio="+CodigoServicio+"&NombreServicio="+NombreServicio+"&IdServicio="+IdServicio+"&Bandera=1",true);
		ajax.send(null);
		return false;
}//NuevaReceta
