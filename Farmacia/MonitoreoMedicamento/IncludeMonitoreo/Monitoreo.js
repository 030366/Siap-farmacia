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

function CargarCombos(){
 var A = document.getElementById('Combos');
var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//A.innerHTML = "CARGANDO CAMBIOS...";
						//B.innerHTML = "<img src='../../../MonitoreoMedicamento/loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						LoadMedicamento();
					}
			}
		ajax.open("GET","IncludeMonitoreo/Proceso.php?Bandera=1",true);
		ajax.send(null);
		recarga();
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
		recarga();
		return false;
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
						//A.innerHTML = "CARGANDO CAMBIOS...";
						//B.innerHTML = "<img src='../../../MonitoreoMedicamento/loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
					}
			}
		ajax.open("GET","existencia.php?area="+IdArea+"&farmacia="+IdFarmacia,true);
		ajax.send(null);
		recarga();
		return false;
	}
}//LoadRecetas

	
function recarga(){
	setTimeout('LoadMedicamento()', 6000);
	//setTimeout('LoadMedicamento(IdMedicina,Area)', 9000);
}//recarga
