function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false;
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}


function cargaContenido8(valor){
//Filtracion de areas de Farmacia
	var ajax = nuevoAjax();
	var A = document.getElementById('ComboDestino');
		ajax.onreadystatechange=function(){
		   if(ajax.readyState==1){
			A.innerHTML="<select id='IdAreaDestino' disabled='disabled'><option value='0'>[Cargando ...]</option></select>";
		   }
		   if(ajax.readyState==4){
			A.innerHTML=ajax.responseText;
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
		   }
		}
		
ajax.open("GET","IncludeFiles/proceso_especialidad.php?Bandera=1&ValorOrigen="+valor,true);
ajax.send(null);
return false;
}