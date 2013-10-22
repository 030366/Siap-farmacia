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

function Reportes(){
	   //var query = document.getElementById('q').value;
		var select1 = document.getElementById('select1').value;
		var select2 = document.getElementById('select2').value;
		var fechaInicio= document.getElementById('fechaInicio').value;
		var fechaFin= document.getElementById('fechaFin').value;
		var A = document.getElementById('Layer2');

		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<img src='../barra.gif' alt='Loading...' />";
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
ajax.open("GET","Repote_GrupoTerapeutico.php?select1="+select1+"&select2="+select2+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,true);
		ajax.send(null);
		return false;
		
}//LoadRecetas
	
/*function ProcesarRecetas(valor){
		var IdReceta=valor;
		//var Posicion=document.getElementById('poss').value;
		var IH = document.getElementById('IH'+ IdReceta).value;
		var IR = document.getElementById('IR'+ IdReceta).value;
		var F = document.getElementById('F'+ IdReceta).value;
		var L = document.getElementById('L'+ IdReceta).value;
		var B = document.getElementById('B'+ IdReceta).value;
		var Estado= document.getElementById('RecetaEstado'+ IdReceta).value;

		//var IM= document.getElementById('IM' + IdReceta).value;
		//var A = document.getElementById('Recetas');
		//var B = document.getElementById('loading');
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						//A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
					}
			}
		
		ajax.open("GET","proceso.php?IH="+ IH +"&IR="+ IR +"&F="+ F +"&L="+ L +"&B="+ B +"&RecetaEstado="+ Estado + "&IdReceta="+ valor,true);
		ajax.send(null);
		LoadRecetas();
		return false;

}//fin de ProcesarRecetas

function recarga(){
	setTimeout('LoadRecetas()', 15000);
}//recarga
*/















/*function inicio(valor){
var Posicion=valor;
var IH = document.getElementById('IH'+ Posicion).value;
var countdownfrom=15;
var currentsecond=document.IH.conteo.value=countdownfrom+1;

function countredirect(){
		if (currentsecond!=1){
			currentsecond-=1
			document.IH.conteo.value=currentsecond
		}
		else{
			//window.location=targetURL
			return
		}
	setTimeout("countredirect()",1000)
}//funcion de conteo

}//inicio


var tiempo=15;
var now = new Date();
function recarga(tiempo){ 
var seconds = now.getSeconds();
if(seconds <= 15 || seconds <= 30 || seconds <= 45 || seconds <= 59){
	document.getElementById('conteo'+ conteo)=tiempo-1;
	recargar();
}else{
	LoadRecetas();
//var t = tiempo;
//setTimeout('LoadRecetas()', t); 
}
} //Recarga*/