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


function Habilitar(ID){
	var check = document.getElementById(ID);

   if(check.checked==true && ID=='Otros'){
	
	document.getElementById('MotivoOtros').disabled=false;
   }else{
	document.getElementById('MotivoOtros').disabled=true;
   }

}



var nav4 = window.Event ? true : false;
function Limpieza(evt,valor){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
	var Datos = document.getElementById(valor).value;
	
	
	var Total = Datos.length;
	if((Total==0 || key==8) && valor=='q'){
		document.getElementById('IdMedicina').value='';
	}
	
	if((Total==0 || key==8) && valor=='Area'){
		document.getElementById('IdArea').value='';
	}

	//	return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
	if(key==13 && valor=='q'){
	   document.getElementById('Cantidad').focus(); 
	}

	if(key==13 && valor=='Cantidad'){
	   document.getElementById('Area').focus(); 
	}

	if(key==13 && valor=='Area'){
		document.getElementById('Vencimiento').focus();
	}

}





function Valida(){
	var IdMedicina = document.getElementById('IdMedicina');
	var Nombre = document.getElementById('q');
	var IdArea = document.getElementById('IdArea');
	var nArea = document.getElementById('Area');
	var Cantidad = document.getElementById('Cantidad');
	var Ok=true;


	if(trim(Nombre.value,'q')=='' || trim(IdMedicina.value,'IdMedicina')==''){
	   alert('Seleccione un medicamento valido!');
	   Ok=false;
	   Nombre.focus();
	  
	}

	if(trim(Cantidad.value,'Cantidad')=='' && Ok==true){
	   alert('Introduzca una cantidad valida!');
	   Ok=false;
	   Cantidad.focus();
	   
	}
	
	if((trim(nArea.value,'Area')=='' || trim(IdArea.value,'IdArea')=='') && Ok==true){
	   alert('Seleccione una area valida!');
	   Ok=false;
	   nArea.focus();
	   
	}


 	if(Ok==true){
	alert('ok');
	  // RealizarDescarte();
	}
}


function RealizarDescarte(){
	var Contenedor=document.getElementById('Resultado');
	var IdMedicina = document.getElementById('IdMedicina').value;
	var FechaInicio = document.getElementById('FechaInicio').value;
	var FechaFin = document.getElementById('FechaFin').value;

	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		/*NOTHING*/		
		}
		if(ajax.readyState==4){
			if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado! \n vuelva a iniciar sesion!');window.location='../sigIn.php';}
		    var Respuesta = ajax.responseText;
			if(Respuesta=='NO'){
			   alert('Periodo no valido!');
			   Contenedor.innerHTML="<strong>Este periodo ya existe o sus limites se encuentran dentro de un periodo de desabastecimiento previamente definido.-</strong>";
			}else{
			   Contenedor.innerHTML=Respuesta;
			}
		}
	}
			
	ajax.open("GET","IncludeFiles/ProcesoDescartes.php?Bandera=1&IdMedicina="+IdMedicina+"&FechaInicio="+FechaInicio+"&FechaFin="+FechaFin,true);
	ajax.send(null);
	return false;
	
	
}
