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

function trim(str,Obj){
	str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
	if(str==''){document.getElementById(Obj).value=str;}
	return(str);
}//trim

function LimpiaFin(ID){
	var check = document.getElementById(ID);
   if(check.checked==true){
	document.getElementById('FechaFin').value='';
	document.getElementById('FechaFin').disabled=true;
   }else{
	document.getElementById('FechaFin').disabled=false;
   }

}


function Valida(){
	var IdMedicina = document.getElementById('IdMedicina');
	var Nombre = document.getElementById('q');
	var FechaInicio = document.getElementById('FechaInicio');
	var Ok=true;


	if(trim(Nombre.value,'q')=='' || trim(IdMedicina.value,'IdMedicina')==''){
	   alert('Seleccione un medicamento valido!');
	   Nombre.focus();
	   Ok=false;
	}
	
	if(trim(FechaInicio.value,'FechaInicio')==''){
	   alert('La Fecha de inicio del periodo es obligatoria!');
	   FechaInicio.focus();
	   Ok=false;
	}


 	if(Ok==true){
	   EstablecerPeriodo();
	}
}

function EstablecerPeriodo(){
	var Contenedor=document.getElementById('Desabastecido');
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
			
	ajax.open("GET","IncludeFiles/Desabastecido.php?Bandera=1&IdMedicina="+IdMedicina+"&FechaInicio="+FechaInicio+"&FechaFin="+FechaFin,true);
	ajax.send(null);
	return false;
	
	
}
