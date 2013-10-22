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

function CargarCombos(TipoFarmacia){
 var A = document.getElementById('Combos');
var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//A.innerHTML = "CARGANDO CAMBIOS...";
						//B.innerHTML = "<img src='../../../MonitoreoMedicamento/loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						//LoadMedicamento();
					}
			}
		ajax.open("GET","IncludeMonitoreo/Proceso.php?Bandera=1&TipoFarmacia="+TipoFarmacia,true);
		ajax.send(null);
		//recarga();
		return false;

}

function CargarAreas(IdFarmacia){

 var ComboAreas=document.getElementById('ComboAreas');
var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//A.innerHTML = "CARGANDO CAMBIOS...";
						//B.innerHTML = "<img src='../../../MonitoreoMedicamento/loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						ComboAreas.innerHTML = ajax.responseText;
						
					}
			}
		ajax.open("GET","IncludeMonitoreo/Proceso.php?Bandera=2&IdFarmacia="+IdFarmacia,true);
		ajax.send(null);
// 		recarga();
		return false;
}

function valida(){
	var Ok=true;
	var IdArea=document.getElementById('IdArea').value;
	var IdFarmacia=document.getElementById('IdFarmacia').value;	
	var TipoFarmacia=document.getElementById('TipoFarmacia').value;
   if(TipoFarmacia!=1){
	if(IdFarmacia==0){
	   alert('Seleccione una Farmacia');
	   document.getElementById('IdFarmacia').focus();
	   Ok=false;
	}

	if(IdArea==0 && Ok==true){
	   alert('Seleccione una Area');
	   document.getElementById('IdArea').focus();
	   Ok=false;
	}

	if(Ok==true){
	   LoadMedicamento();
	}
   }else{
	   LoadMedicamento();
   }
}

function LoadMedicamento(){
		var A = document.getElementById('Monitoreo');
		var IdArea=document.getElementById('IdArea').value;
		var IdFarmacia=document.getElementById('IdFarmacia').value;
	if(IdArea==0){
		A.innerHTML = "Seleccione una Area valida!";
	}else{
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						
						A.innerHTML = "<img src='../images/barra.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
					var Respuesta = ajax.responseText;
						if(Respuesta == "ERROR_SESSION"){alert("La sesion ha caducado \n inicie sesion nuevamente!"); window.location="../signIn.php";}
						A.innerHTML = Respuesta;
						//B.innerHTML = "";
				}
			}
		ajax.open("GET","existencia.php?area="+IdArea+"&farmacia="+IdFarmacia,true);
		ajax.send(null);
		//recarga();
		return false;
	}
}//LoadRecetas

function Imprimir(Tipo){
	if(Tipo==1){
	document.getElementById('Menu').style.display='none'
	document.getElementById('CombosPrint').style.display='none'
	window.print();
	}else{
	document.getElementById('Menu').style.display='inline'
	document.getElementById('CombosPrint').style.display='inline'
	}
	
	
}
	
function recarga(){
	setTimeout('LoadMedicamento()', 6000);
	//setTimeout('LoadMedicamento(IdMedicina,Area)', 9000);
}//recarga
