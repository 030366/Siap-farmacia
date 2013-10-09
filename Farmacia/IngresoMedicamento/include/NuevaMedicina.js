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

function habilitar(){
		var A = document.getElementById('Resultados');
		document.formulario.CodigoMedicamento.disabled=false;
		document.formulario.NombreMedicamento.disabled=false;
		document.formulario.GrupoTerapeutico.disabled=false;
		document.formulario.concentracion.disabled=false;
		document.formulario.presentacion.disabled=false;
		document.formulario.Precio.disabled=false;
		document.formulario.guardar.disabled=false;
		document.formulario.UnidadMedida.disabled=false;
		//document.formulario.cancelar1.disabled=false;
		A.innerHTML = "";
}//habilita


function valida(){
		var Codigo = document.getElementById('CodigoMedicamento').value;
		var Nombre = document.getElementById('NombreMedicamento').value;
		var UnidadMedida = document.getElementById('UnidadMedida').value;
		var GrupoTerapeutico = document.getElementById('GrupoTerapeutico').value;
		var Concentracion = document.getElementById('concentracion').value;
		var Presentacion = document.getElementById('presentacion').value;
		var PrecioActual = document.getElementById('Precio').value;
		
	if(Codigo==''){
		alert('Introduzca un Codigo Valido');
		document.formulario.CodigoMedicamento.focus();
	}else{
		if(Nombre == ''){
			alert('Introduzca el Nombre del Medicamento');
			document.formulario.NombreMedicamento.focus();
		}else{
			if(GrupoTerapeutico == 0){
				alert('Seleccione un Grupo Terapeutico');
				document.formulario.GrupoTerapeutico.focus();	
			}else{
				if(Presentacion == ''){
					alert('Introduzca la Presentacion\n del nuevo medicamento');
					document.formulario.CodigoMedicamento.focus();	
				}else{
					if(UnidadMedida==0){
						alert('Seleccione la unidad de medida del medicamento');
						document.formulario.UnidadMedida.focus();	
					}else{
						AgregarMedicamento();
					}
				}//presentacion
			}//GrupoTerapeutico
		}//Nombre
	
	}//Codigo
		

}//valida



function AgregarMedicamento(){
	var IdMedicina = document.getElementById('IdMedicina').value;
	
	var Codigo = document.getElementById('CodigoMedicamento').value;
	var Nombre = document.getElementById('NombreMedicamento').value;
	
	var UnidadMedida = document.getElementById('UnidadMedida').value;
	var GrupoTerapeutico = document.getElementById('GrupoTerapeutico').value;
	var Concentracion = document.getElementById('concentracion').value;
	var Presentacion = document.getElementById('presentacion').value;
	var PrecioActual = document.getElementById('Precio').value;
	var IdHospital = document.getElementById('IdHospital').value;

	var A = document.getElementById('CodigoNuevaMedicina');
	var ajax = xmlhttp();

	ajax.onreadystatechange=function(){
	   if(ajax.readyState==1){
		A.innerHTML = "GUARDANDO NUEVO MEDICAMENTO...";
	   }
	   
	   if(ajax.readyState==4){
	  //  alert(ajax.responseText);
		alert('Medicamento Agregado!');
		A.innerHTML = ajax.responseText;
			
		document.formulario.CodigoMedicamento.disabled=true;
		document.formulario.NombreMedicamento.disabled=true;
		document.formulario.UnidadMedida.disabled=true;
		document.formulario.GrupoTerapeutico.disabled=true;
		document.formulario.concentracion.disabled=true;
		document.formulario.presentacion.disabled=true;
		document.formulario.Precio.disabled=true;
		document.formulario.guardar.disabled=true;
			
			//window.location=window.location;
		/*var resp=confirm('Medicamento agregado! \n desea agregar otro medicamento mas?');
		if(resp==true){
			window.location=window.location;
		}*/
	   }
	}
			
			
	var tmp = Nombre.split('+');
	var separador = '';
	var tope=tmp.length;
	var Nuevo ='';
	var i=0	
	var sep='';
		
	for(i;i<=tope-1;i++){
	   if((tmp[i]!=null && tmp[i]!='')&&(i<tope-1)){sep='/';}else{sep='';}
	    Nuevo+=tmp[i]+''+sep;
	}

	var tmp2 = Concentracion.split('+');
	var separador2 = '';
	var Nuevo2 ='';
        var tope2=tmp2.length;
            if(tope2==1){Nuevo2='~';}
	
	var i2=0	
	var sep2='';
		
	for(i2;i2<=tope2-1;i2++){
	   if((tmp2[i2]!=null && tmp2[i2]!='')&&(i2<tope2-1)){sep2='/';}else{sep2='';}
	    Nuevo2+=tmp2[i2]+''+sep2;
	}


				
ajax.open("GET","include/ProcesoNuevoMedicamento.php?IdMedicina="+IdMedicina+"&Codigo="+Codigo+"&Nombre="+Nuevo+"&Grupo="+GrupoTerapeutico+"&Concentracion="+Nuevo2+"&Presentacion="+Presentacion+"&Precio="+PrecioActual+"&UnidadMedida="+UnidadMedida+"&IdHospital="+IdHospital+"&Bandera=1",true);
ajax.send(null);
return false;
		
}//Agregar Medicamento



//actualizacion de informacion de medicamentos

function CambiarInfo(opcion,IdMedicina){
	var divT=opcion+"1";
	var Obj=opcion+"Nuevo";
var A = document.getElementById(divT);

var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "CARGANDO...";
			
		}
		if(ajax.readyState==4){
			A.innerHTML = ajax.responseText;
				document.getElementById(Obj).focus();
				
		}
	}
	
	
ajax.open("GET","include/ProcesoNuevoMedicamento.php?Bandera=6&SubBandera="+opcion+"&IdMedicina="+IdMedicina,true);
ajax.send(null);
return false;

}

function MakeChange(NuevoDato,IdMedicina,opcion){
//  alert(NuevoDato+" -> "+IdMedicina+" -> "+opcion);

	var divT=opcion+"1";
var A = document.getElementById(divT);

	   var NuevoDato=opcion+"Nuevo"
	var NuevaInfo=document.getElementById(NuevoDato).value;

var ajax = xmlhttp();
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "GUARDANDO...";
			
		}
		if(ajax.readyState==4){
			A.innerHTML = ajax.responseText;
			
		}
	}
		

if(opcion=='Nombre' || opcion=='Concentracion'){
	var tmp = NuevaInfo.split('+');
	var separador = '';
	var tope=tmp.length;
	var Nuevo ='';
	var i=0	
	var sep='';
		if(tope==1 && opcion=='Concentracion'){Nuevo='~';}
	for(i;i<=tope-1;i++){
	   if((tmp[i]!=null && tmp[i]!='')&&(i<tope-1)){sep='/';}else{sep='';}
	    Nuevo+=tmp[i]+''+sep;
	}

NuevaInfo=Nuevo;

}

ajax.open("GET","include/ProcesoNuevoMedicamento.php?Bandera=6&SubBandera="+divT+"&campo="+opcion+"&IdMedicina="+IdMedicina+"&NuevaInfo="+NuevaInfo,true);
ajax.send(null);
return false;



}


//************************************************




function ComboTerapeutico(IdTerapeutico){

   var ComboTerapeutico = document.getElementById('GrupoTerapeutico');
   var Opciones = ComboTerapeutico.length;

	for(var i=0; i < Opciones;i++){
	  if(ComboTerapeutico[i].value==IdTerapeutico){
		
		ComboTerapeutico[i].selected=true;
		return false;
	  }
	  
	}

}


function PegaCombo(IdMedicina,IdUnidadMedida,Descripcion){

	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		    //A.innerHTML = "ASIGNANDO AREA...";
									
		}
		if(ajax.readyState==4){
		   document.getElementById('ComboUnidadMedida').innerHTML = ajax.responseText;
		}
	}
	

ajax.open("GET","include/ProcesoNuevoMedicamento.php?IdMedicina="+IdMedicina+"&IdUnidadMedida="+IdUnidadMedida+"&Descripcion="+Descripcion+"&Bandera=5",true);
ajax.send(null);
return false;
}


function AsignarArea(){
		var A = document.getElementById('Resultados');
		var IdArea = document.getElementById('IdArea').value;
		if (IdArea=='N'){
			alert('Seleccione una Area');
		}else{
		var IdMedicina=document.getElementById('IdMedicina2').value;
		
		var A = document.getElementById('Resultados');
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "ASIGNANDO AREA...";
											
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
					}
			}
		
		ajax.open("GET","include/ProcesoNuevoMedicamento.php?IdArea="+IdArea+"&IdMedicina="+IdMedicina+"&Bandera=3",true);
		ajax.send(null);
		return false;
		}//ELSE Especialidad
}//Asignar Medicamento

function AsignarDespacho(){
		var A = document.getElementById('Resultados');
		var IdArea = document.getElementById('IdAreaDespacho').value;
		if (IdArea=='N'){
			alert('Seleccione una Area');
		}else{
		var IdAreaMedicina=document.getElementById('IdAreaMedicina').value;
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//A.innerHTML = "ASIGNANDO DESPACHO...";
											
					}
				if(ajax.readyState==4){
						alert("Medicamento asigando a area de despacho");
					}
			}
		
		ajax.open("GET","include/ProcesoNuevoMedicamento.php?IdArea="+IdArea+"&IdAreaMedicina="+IdAreaMedicina+"&Bandera=4",true);
		ajax.send(null);
		return false;
		}//ELSE Especialidad
}//Asignar Medicamento

function AsignarMedicamento(){
		var A = document.getElementById('Resultados');
		var Especialidad = document.getElementById('Especialidad').value;
		if (Especialidad=='N'){
			alert('Seleccione una Especialidad');
			document.formulario.Especialidad.focus();
		}else{
		var IdMedicina=document.getElementById('IdMedicina2').value;
		var A = document.getElementById('Resultados');
		var ajax = xmlhttp();
	
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "GUARDANDO NUEVO MEDICAMENTO...";
											
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
					}
			}
		
		ajax.open("GET","include/ProcesoNuevoMedicamento.php?Especialidad="+Especialidad+"&IdMedicina="+IdMedicina+"&Bandera=2",true);
		ajax.send(null);
		return false;
		}//ELSE Especialidad
}//Asignar Medicamento

///*************POPUP



