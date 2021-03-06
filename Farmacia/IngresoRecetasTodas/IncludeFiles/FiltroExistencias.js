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

// Declaro los selects que componen el documento HTML. Su atributo ID debe figurar aqui.
var listadoSelects8=new Array();
listadoSelects8[0]="select1";
listadoSelects8[1]="select2";
listadoSelects8[2]="select3";
listadoSelects8[3]="select4";

function buscarEnArray(array, dato)
{
	// Retorna el indice de la posicion donde se encuentra el elemento en el array o null si no se encuentra
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}

function cargaContenido8(idSelectOrigen8)
{
	// Obtengo la posicion que ocupa el select que debe ser cargado en el array declarado mas arriba
	var posicionSelectDestino=buscarEnArray(listadoSelects8, idSelectOrigen8)+1;
	// Obtengo el select que el usuario modifico
	var selectOrigen=document.getElementById(idSelectOrigen8);
	// Obtengo la opcion que el usuario selecciono
	var opcionSeleccionada=selectOrigen.options[selectOrigen.selectedIndex].value;
	// Si el usuario eligio la opcion "Elige", no voy al servidor y pongo los selects siguientes en estado "Selecciona opcion..."
	if(opcionSeleccionada==0)
	{
		var x=posicionSelectDestino, selectActual=null;
		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito
		while(listadoSelects8[x])
		{
			selectActual=document.getElementById(listadoSelects8[x]);
			selectActual.length=0;
			var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; 
					/* GENERA EL NOMBRE CORRECTO POR CADA SELECT CUANDO LA SELECCION SEA = 0 */
					if(selectActual.id=="select2"){
						nuevaOpcion.innerHTML="SELECCIONE UNA AREA";
					}else{
						if(selectActual.id=="select3"){
							nuevaOpcion.innerHTML="TODOS LOS GRUPOS TERAPEUTICOS";
						}else{
							nuevaOpcion.innerHTML="TODAS LAS MEDICINAS";	
						}
					}
			selectActual.appendChild(nuevaOpcion);	selectActual.disabled=true;
			x++;
		}
	}
	// Compruebo que el select modificado no sea el ultimo de la cadena
	else if(idSelectOrigen8!=listadoSelects8[listadoSelects8.length-1])
	{
		// Obtengo el elemento del select que debo cargar
		var idSelectDestino=listadoSelects8[posicionSelectDestino];
		var selectDestino=document.getElementById(idSelectDestino);
		if(idSelectDestino=="select4"){
		var Area = document.getElementById("select2").value;
		}else{ var Area=0;}
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=nuevoAjax();
ajax.open("GET", "proceso_existencias.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&area="+Area, true);
		ajax.onreadystatechange=function() 
		{ 
			if (ajax.readyState==1)
			{
				// Mientras carga elimino la opcion "Selecciona Opcion..." y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion); selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)
			{
				if(ajax.responseText=="ERROR_SESSION"){window.location='../signIn.php';}
				selectDestino.parentNode.innerHTML=ajax.responseText;
			} 
		}
		ajax.send(null);
	}
}