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

function valida(){
		var Grupo = document.getElementById('select1').value;
		var Medicina = document.getElementById('select2').value;
		var fechaInicio= document.getElementById('fechaInicio').value;
		var fechaFin= document.getElementById('fechaFin').value;
		var barra = document.getElementById('barras');
		var pastel = document.getElementById('pastel');
		var lineas = document.getElementById('lineas');

if(Grupo == 0){
	alert('Seleccione un Grupo Terapeutico');
	document.getElementById('select1').focus();
}else{
	if(fechaInicio==""){
		alert('Seleccione una Fecha de Inicio');
		document.getElementById('fechaInicio').click();
	}else{
		if(fechaFin==""){
			alert('Seleccione una Fecha de Finalización');
			document.getElementById('fechaFin').click();
		}//if interno
		else{
			if(barra.checked==false && pastel.checked==false && lineas.checked==false){
				alert('seleccione un tipo de grafico');	
			}else{
				Graficos();
			}//else barra
		}//else fechaFin
	}//else fechaInicio
	
}//grupo ==0

}//valida


function refreshImages(){
var search="?"+(new Date()).getTime();
for(var i=0;i<document.images.length;document.images[i++].src+=search);
}//refreshImages


function Graficos(){
		//var query = document.getElementById('q').value;
		var A = document.getElementById('respuesta');
		var select1 = document.getElementById('select1').value;
		var select2 = document.getElementById('select2').value;
		var barra = document.getElementById('barras');
		var pastel = document.getElementById('pastel');
		var lineas = document.getElementById('lineas');
		var fechaInicio= document.getElementById('fechaInicio').value;
		var fechaFin= document.getElementById('fechaFin').value;
		var botonVista = document.getElementById('imprimir');
		var TipoInfo=document.getElementById('TipoInfo').value;
		var Gbarra=0;
		var Gpastel=0;
		var Glineas=0;
		if(barra.checked==true){
			Gbarra=1;
		}
		if(pastel.checked==true){
			Gpastel=1;
		}		
		if(lineas.checked==true){
			Glineas=1;
		}		
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "GENERANDO GRAFICA...";
											
					}
				if(ajax.readyState==4){
						refreshImages();
						A.innerHTML = ajax.responseText;
						refreshImages();
						botonVista.disabled= false;
					}
			}
		
		ajax.open("GET","GraficoPorGrupo.php?select1="+select1+"&select2="+select2+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&TipoInfo="+TipoInfo+"&Pastel="+Gpastel+"&Barras="+Gbarra+"&Lineas="+Glineas,true);
		ajax.send(null);
		refreshImages();
		return false;
		
}//graficos
///*************POPUP
function popUp() {
day = new Date();
id = day.getTime();

URL='GraficoPorGrupo.php?Print=1&select1='+ document.getElementById('select1').value +'&select2='+ document.getElementById('select2').value +'&fechaInicio='+ document.getElementById('fechaInicio').value +'&fechaFin='+ document.getElementById('fechaFin').value+'&Pastel='+document.getElementById('Gpastel').value+'&Barras='+document.getElementById('Gbarras').value+'&Lineas='+document.getElementById('Glineas').value+'&TipoInfo='+document.getElementById('TipoInfo').value;

//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=850,height=500,left = 450,top = 450');");
}//popUp



