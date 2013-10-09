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

function CargarCombo(IdOrigen,ValorSeleccionado){
	var Objeto="";
	if(IdOrigen=='IdFarmacia'){Objeto="ComboTerapeutico";Objeto2="ComboMedicina";Bandera=1;}
	if(IdOrigen=='IdTerapeutico'){Objeto="ComboMedicina";Bandera=2;}
	
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						document.getElementById(Objeto).innerHTML="<select Id='Temporal' disabled=disabled><option value='0'>Cargando ...</option></select>";
						if(IdOrigen=='IdFarmacia'){
						document.getElementById(Objeto2).innerHTML="<select id='IdMedicina' name='IdMedicina' disabled=disabled><option value='0'>[Seleccione ...]</option></select>";
						}
					}
				if(ajax.readyState==4){
					    
						document.getElementById(Objeto).innerHTML = ajax.responseText;

						
					}
			}

ajax.open("GET","IncludeFiles/ProcesoReporteFarmacias.php?ValorSeleccionado="+ValorSeleccionado+"&Bandera="+Bandera,true);
		ajax.send(null);
		return false;
	
}

function Imprimir(){//BUSQUEDA DE MEDICAMENTO
	day = new Date();
	id = day.getTime();
		var IdFarmacia = document.getElementById('IdFarmacia').value;
		var IdTerapeutico = document.getElementById('IdTerapeutico').value;
		var IdMedicina=document.getElementById('IdMedicina').value;
		var FechaInicial= document.getElementById('fechaInicio').value;
		var FechaFinal= document.getElementById('fechaFin').value;
	
		var URL="ReporteFarmacias.php?IdFarmacia="+IdFarmacia+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal+"&Bandera=2";
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function Imprimir2(){
	document.getElementById('Imprimir').style.visible='hidden';
		document.getElementById('Excel').style.visible='hidden';
	window.print();		
	document.getElementById('Imprimir').style.visible='inline';
		document.getElementById('Excel').style.visible='inline';
	
	
}

function Imprimir3(IdFarmacia,IdTerapeutico,IdMedicina,FechaInicial,FechaFinal){//BUSQUEDA DE MEDICAMENTO
	day = new Date();
	id = day.getTime();
	
		var URL="ReporteFarmacias.php?IdFarmacia="+IdFarmacia+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal+"&Bandera=3";
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function Valida(){
	var Ok=true;
	IdFarmacia=document.getElementById('IdFarmacia').value;
	
	FechaInicial_=document.getElementById('fechaInicio').value;
	FechaFinal_=document.getElementById('fechaFin').value;

	FechaInicial=document.getElementById('fechaInicio').value.split('-');
	FechaFinal=document.getElementById('fechaFin').value.split('-');
	
	/*if(IdFarmacia==0){
		alert('Seleccine una farmacia para generar el reporte');
		document.getElementById('IdFarmacia').focus();
		Ok=false;
	}*/
	
	/* ANO - MES - DIA*/
	if(FechaFinal_=='' || FechaInicial_==''){alert('Selecciones el rango de fechas');Ok=false;}
	
	if(FechaFinal[0]<FechaInicial[0]){
		alert('La de fin no puede ser menor que la fecha de inicio');
		Ok=false;	
	}
	
	if(FechaFinal[1]<FechaInicial[1]){
		alert('La de fin no puede ser menor que la fecha de inicio');
		Ok=false;	
	}
	
	if(FechaFinal[2]<FechaInicial[2]){
		alert('La de fin no puede ser menor que la fecha de inicio');
		Ok=false;	
	}
	/******************/
	
	if(Ok==true){
		GenerarReporte();	
	}
	
}

function GenerarReporte(){
	   //var query = document.getElementById('q').value;
		var IdFarmacia = document.getElementById('IdFarmacia').value;
		var IdTerapeutico = document.getElementById('IdTerapeutico').value;
		var IdMedicina=document.getElementById('IdMedicina').value;
		var FechaInicial= document.getElementById('fechaInicio').value;
		var FechaFinal= document.getElementById('fechaFin').value;
		var A = document.getElementById('Reporte');

		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<img src='../barra.gif' alt='Loading...' /><br>Cargando ...";
					}
				if(ajax.readyState==4){
					    
						A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
						
					}
			}
    	/*
		fechaInicio
		fechaFin
		select1
		select2		
		*/
ajax.open("GET","ReporteFarmacias.php?IdFarmacia="+IdFarmacia+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal,true);
		ajax.send(null);
		return false;
		
}//GenrarReporte
	
