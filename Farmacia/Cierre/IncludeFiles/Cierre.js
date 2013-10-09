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
function acceptNum(evt,Obj){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
	if(Obj=='Periodo'){
		var Texto = document.getElementById(Obj).value;
		var Tamano=Texto.length;
		//alert(Tamano);
		if(Tamano==2 && key!=8){
			document.getElementById(Obj).value=document.getElementById(Obj).value + "-";
		}
	}
	
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45 || key==8);
}



/*Verificacion de campos vacios*/
function valida(){

	if(document.getElementById('ano').value=='' || document.getElementById('ano').value.length < 4){
		alert('Introduzca el a�o a finalizar!');	
		document.getElementById('ano').focus();
		document.getElementById('ano').select();
	}else{
		Cierre();
	}
}//verificacion

/*Verificacion de campos vacios*/
function valida2(){
	var Tamano = document.getElementById('Periodo').value.length;
	if(document.getElementById('Periodo').value==''){
		alert('Introduzca el año a finalizar!');	
		document.getElementById('Periodo').focus();
	}else{
		if(Tamano < 7){
			alert('El periodo a cerrar no se encuentra bien escrito \n revise la escritura con respecto al ejemplo brindado !');
			document.getElementById('Periodo').focus();
		}else{
			CierreMes();
		}
	}
}//verificacion

function Cierre(){
		//Datos a ingresar
		
		var Confirmacion=confirm("Desea Cerrar operaciones del a�o: "+document.getElementById('ano').value);
		
		if(Confirmacion==false){
			alert('Operacion Cancelada !');
		}else{
			
			var Ano=document.getElementById('ano').value;
			var IdPersonal=document.getElementById('IdPersonal').value;
			var ajax = xmlhttp();
	
			ajax.onreadystatechange=function(){
					if(ajax.readyState==1){
										/*NOTHING*/		
						}
					if(ajax.readyState==4){
						var Respuesta = ajax.responseText;
						alert(Respuesta);
						
					}
				}
			
			ajax.open("GET","IncludeFiles/CierreProceso.php?Bandera=1&Ano="+Ano+"&IdPersonal="+IdPersonal,true);
			ajax.send(null);
			return false;
		}
}//ObtenerDatosMedicoBusqueda

function CierreMes(){
		//Datos a ingresar
		
		var Confirmacion=confirm("Desea Cerrar operaciones del periodo: "+document.getElementById('Periodo').value);
		
		if(Confirmacion==false){
			alert('Operacion Cancelada !');
		}else{
			
			var Periodo=document.getElementById('Periodo').value;
			
			var PeriodoTemp=Periodo.split('-');
			  Periodo = PeriodoTemp[1]+"-"+PeriodoTemp[0];
			
			var IdPersonal=document.getElementById('IdPersonal').value;
			var ajax = xmlhttp();
	
			ajax.onreadystatechange=function(){
					if(ajax.readyState==1){
										/*NOTHING*/		
						}
					if(ajax.readyState==4){
						var Respuesta = ajax.responseText;
						alert(Respuesta);
						
					}
				}
			
			ajax.open("GET","IncludeFiles/CierreProceso.php?Bandera=2&Periodo="+Periodo+"&IdPersonal="+IdPersonal,true);
			ajax.send(null);
			return false;
		}
}//ObtenerDatosMedicoBusqueda
