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


function VentanaBusqueda(Tipo){
	
	
	var URL= 'BusquedaMedicamento/buscador_medicamento.php?IdArea='+variable;




	day = new Date();
	id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1000,height=500,left = 450,top = 450');");


	
}//VentanaBusqueda


function Valida(){
	var Generar=true;
	var Error=0;
	var ErrAnos=false;
	var ErrAnos2=false;
	var ErrAnos3=false;
	
	var ErrMeses=false;
	var ErrMeses2=false;
	var ErrMeses3=false;
	
	
	var FechaActual=document.getElementById('FechaActual').value.split('-');
	var FechaInicio=document.getElementById('fechaInicio').value.split('-');
	var FechaFin=document.getElementById('fechaFin').value.split('-');
	
	/*Validacion de Fecha*/
	
	//Fechas no pueden ser vacias
	if(FechaInicio[0]=='' || FechaInicio[0]==null){Generar=false;Error=1;}
	if(FechaFin[0]=='' || FechaFin[0]==null){Generar=false;Error=1}

	//Fecha de inicio no puede ser mayor a la fecha de finalizacion	
			if((FechaFin[0]<FechaInicio[0])){
				//Comparacion de Anos 
				ErrAnos=true;Generar=false;
				Error=2;
			}else{
				if(FechaFin[0]==FechaInicio[0]){
					ErrAnos=false;
				}
			}
				//Comparacion de meses si los anos estan bn ubicados
			if(FechaFin[1]<FechaInicio[1]&&(ErrAnos==false)){
				
				ErrMeses=true;Generar=false;
				Error=2;alert('JO');
				
			}
				//Comparacion de dias si los meses estan bn ubicados
			if((FechaFin[2]<FechaInicio[2])&&(ErrMeses==true)){
				Generar=false;
				Error=2;
			}
	
	


	if(Error != 0){
		switch(Error){
			case 1:
				alert('Los campos de fechas no pueden estar vacios');
			break;
			case 2:
				alert('La fecha de finalizacion no puede ser menor que la fecha de inicio');
			break;
			case 3:
				alert('La fecha de inicio no puede ser mayor al mes actual o año actual');
			break;
			case 4:
				alert('La fecha de finalizacion no puede ser mayor al mes actual o año actual');
			break;
		}
	}
	
	if(Generar==true){GenerarReporte();}
	
}

function GenerarReporte(){
	var IdArea = document.getElementById('IdArea').value;
	var A = document.getElementById('Datos'); /*aqui voy*/
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					//A.innerHTML="Agregando Lotes...";					
				}
				if(ajax.readyState==4){
					A.innerHTML="CARGANDO...";
					RecuperaMedicinaNoReclamada();
				}
		}
		
		ajax.open("GET","IncludeFile/ExistenciaVirtualProceso.php?IdArea="+IdArea+"&Bandera=1",true);
		ajax.send(null);
		return false;


}//AumentaExistencia

