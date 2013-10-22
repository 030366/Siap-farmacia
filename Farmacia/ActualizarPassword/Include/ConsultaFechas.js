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
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
		return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
}
/*Verificacion de campos vacios*/
function verificacion(){
	if(document.getElementById('contra').value==''){
		alert('Digite su nueva contraseña');	
	}else{
	Respuesta();
	}
}//verificacion


/*Obtencion de Recetas Futuras*/
function Respuesta(){
	   //var query = document.getElementById('q').value;
		var Contra = document.getElementById('contra').value;
		var A = document.getElementById('Respuesta');
		//var B = document.getElementById('loading');
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "Actualizando Contraseña ...";
					}
				if(ajax.readyState==4){
					    
						A.innerHTML = ajax.responseText;
						//B.innerHTML = "";
						
					}
			}
ajax.open("GET","Include/ActualizarProceso.php?Contra="+Contra,true);
		ajax.send(null);
		return false;
		
}//Respuesta

