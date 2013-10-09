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
function NumeroExpediente(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
}

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
	
	
	FechaInicial_=document.getElementById('fechaInicio').value;
	FechaFinal_=document.getElementById('fechaFin').value;
	
	  if(!mayor(FechaInicial_,FechaFinal_)){
            Ok=false;
            alert("La fecha final no puede ser menor que la inicial");
        }
	
	if(Ok==true){
		GenerarReporte();	
	}
	
}

function GenerarReporte(){
	   //var query = document.getElementById('q').value;
		var IdNumeroExp= document.getElementById('Expediente').value;
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
						A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
					}
				if(ajax.readyState==4){
					    if(ajax.responseText=="ERROR_SESSION"){
						alert('La sesion ha caducado \n por favor inicie sesion nuevamente!');
						window.location='../signIn.php'
					    }
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
	ajax.open("GET","ReporteFarmacias.php?IdNumeroExp="+IdNumeroExp+"&IdFarmacia="+IdFarmacia+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal,true);
	ajax.send(null);
	return false;
		
}//GenrarReporte
	
