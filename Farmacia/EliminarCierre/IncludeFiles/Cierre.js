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
		if(Tamano==4 && key!=8){
			document.getElementById(Obj).value=document.getElementById(Obj).value + "-";
		}
	}
	
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45 || key==8);
}






//*******************INICIALIZACION DE PANTALLA***************************
function CargarCierres(){
	var Cierres=document.getElementById('Periodos');
	var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		/*NOTHING*/		
		}
		if(ajax.readyState==4){
			var Respuesta = ajax.responseText;
			Cierres.innerHTML=Respuesta;
		}
	}
			
	ajax.open("GET","IncludeFiles/CierreProceso.php?Bandera=1",true);
	ajax.send(null);
	return false;
	
	
}



//************************************************************************


/*Verificacion de campos vacios*/
function valida(){
	var Todos = document.getElementsByName('Cierres');
	var Tope=Todos.length;
	var Ok=false;
	var IDs=new Array();	
	   var J=0;
	for(i=0; i<Tope;i++){
	   if(Todos[i].checked==true){
		   IDs[J]=Todos[i].value;
		     J++;//Aumento de posicion de Vector IDs para conteo exacto de seleccionados
	   	Ok=true;
	   }
	   
	}
	
	if(Ok==false){
	   alert("Debe Seleccionar almenos un periodo a ser eliminar...");
	   
	}else{
		Cierre(IDs);
	}
	
}//verificacion






function Cierre(IDs){
		//Datos a ingresar
		var Tope=IDs.length;
		

		var pro = document.getElementById('Operaciones');
		var Periodos=document.getElementById('Periodos');
		var Confirmacion=confirm("Desea eliminar los cierres seleccionados?");
		
		if(Confirmacion==false){
			alert('Operacion Cancelada !');
			CargarCierres();
		}else{
			
			var ajax = xmlhttp();
	
			ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					//*NOTHING		
				    pro.innerHTML="<h2> Eliminando...</h2>";
				}
				if(ajax.readyState==4){
					pro.innerHTML="";
				    var Respuesta = ajax.responseText;
				   if(Respuesta=='SI'){
					alert('Periodos Seleccionados fueron abiertos satisfactoriamente!');
				    	CargarCierres();
				   }else{
					alert('Surgio un problema con la conexion de servidor...');
				   }
					
				}
			}
			
		    ajax.open("GET","IncludeFiles/CierreProceso.php?Bandera=2&IDs="+IDs,true);
		    ajax.send(null);
		    return false;
		}
}//Cierre
