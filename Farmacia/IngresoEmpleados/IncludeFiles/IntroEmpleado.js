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


function GenerarCorrelativo(IdTipoEmpleado){
	
		var ajax = xmlhttp();
		
		if(IdTipoEmpleado!=0){
			ajax.onreadystatechange=function(){
					if(ajax.readyState==1){
												
						}
					if(ajax.readyState==4){
						var Respuesta=ajax.responseText.split('/');
						document.getElementById('IdEmpleado').value = Respuesta[0];
						document.getElementById('Apellidos').focus();
					}
				}
			
			ajax.open("GET","IncludeFiles/ProcesoIngresoEmpleados.php?IdTipoEmpleado="+IdTipoEmpleado+"&Bandera=1",true);
			ajax.send(null);
			return false;
		}else{
			document.getElementById('IdEmpleado').value="";	
		}
	
}




function valida(){
		var IdEmpleado = document.getElementById('IdEmpleado').value;
		var Apellidos = trim(document.getElementById('Apellidos').value,'Apellidos');
		//var PrimerNombre = document.getElementById('PrimerNombre').value.trim();
		//var SegundoNombre=document.getElementById('SegundoNombre').value.trim();
		var CodigoFarmacia=trim(document.getElementById('CodigoFarmacia').value,'CodigoFarmacia');
					

if(IdEmpleado == ''){
	alert('Seleccione el tipo de empleado a ingresar');
	document.getElementById('IdTipoEmpleado').focus();
}else{
	if(Apellidos==''){
		alert('Debe digitar el Nombre del empleado');
			document.getElementById('Apellidos').focus();
	}else{
		//if(PrimerNombre==''){
		//	alert('Debe digitar al menos el primer nombre del empleado');
		//	document.getElementById('PrimerNombre').focus();
		//}else{
			
			if(CodigoFarmacia==''){
				alert('Digite el Codigo que se asignara al medico');
				document.getElementById('CodigoFarmacia').focus();
			}else{
				document.getElementById('Apellidos').value=Apellidos;
				//document.getElementById('PrimerNombre').value=PrimerNombre;
				//document.getElementById('SegundoNombre').value=SegundoNombre;
				document.getElementById('CodigoFarmacia').value=CodigoFarmacia;
				GuardarEmpleado();
			}
		//}
		
	}
}

}//valida


function GuardarEmpleado(){
		//Datos a ingresar
		var Apellidos = document.getElementById('Apellidos').value;
		var PrimerNombre = document.getElementById('PrimerNombre').value;
		var SegundoNombre=document.getElementById('SegundoNombre').value;
		var CodigoFarmacia=document.getElementById('CodigoFarmacia').value;
		var IdTipoEmpleado=document.getElementById('IdTipoEmpleado').value;
			if(SegundoNombre!=''){SegundoNombre=" "+SegundoNombre;}else{SegundoNombre="";}
			var NombreEmpleado=Apellidos;//+", "+PrimerNombre+""+SegundoNombre;

		//****************************
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
											
					}
				if(ajax.readyState==4){
					var Informacion = ajax.responseText.split('~');
					if(Informacion[0]=='NO'){

							alert('El codigo de Farmacia ingresado ya existe!');
							document.getElementById('CodigoFarmacia').focus();
					
						
					}else{
						//alert(ajax.responseText);
						document.getElementById('Respuesta').innerHTML=Informacion[1]+"<strong><h4>Empleado ingresado satisfactoriamente !</h4></strong>";
					document.getElementById('Apellidos').value="";
					document.getElementById('PrimerNombre').value="";
					document.getElementById('SegundoNombre').value="";
					document.getElementById('CodigoFarmacia').value="";
					}
				}
			}
		
		ajax.open("GET","IncludeFiles/ProcesoIngresoEmpleados.php?IdTipoEmpleado="+IdTipoEmpleado+"&CodigoFarmacia="+CodigoFarmacia+"&NombreEmpleado="+NombreEmpleado+"&Bandera=2",true);
		ajax.send(null);
		return false;
}//NuevaReceta


function CargarEmpleados(){
	var NombreEmpleado = trim(document.getElementById('Apellidos').value,'Apellidos');
	var Empleados = document.getElementById('Medicos');
	var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
					
			}
			if(ajax.readyState==4){
				
				Empleados.innerHTML=ajax.responseText;
				
			}
		}
		
ajax.open("GET","IncludeFiles/ProcesoIngresoEmpleados.php?NombreEmpleado="+NombreEmpleado+"&Bandera=3",true);
ajax.send(null);
return false;

}
