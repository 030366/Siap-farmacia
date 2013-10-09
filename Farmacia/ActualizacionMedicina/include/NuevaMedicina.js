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
		document.formulario.cancelar1.disabled=false;
		A.innerHTML = "";
}//habilita


function Valida(){
		var Codigo = document.getElementById('Codigo').value.trim();
		var Nombre = document.getElementById('NombreMedicina').value.trim();
		var Concentracion = document.getElementById('Concentracion').value.trim();
		var Presentacion = document.getElementById('Presentacion').value.trim();
		var FormaFarmaceutica = document.getElementById('FormaFarmaceutica').value.trim();
		
	if(Codigo==''){
		alert('Introduzca un Codigo Valido');
		document.getElementById('Codigo').focus();
	}else{
		if(Nombre == ''){
			alert('Introduzca el Nombre del Medicamento');
			document.getElementById('NombreMedicina').focus();
		}else{
			if(Concentracion == ''){
				alert('Introduzca una Concentracion valida');
				document.getElementById('Concentracion').focus();	
			}else{
				if(Presentacion == ''){
					alert('Introduzca una Presentacion valida ');
					document.getElementById('CodigoMedicamento').focus();	
				}else{
					if(FormaFarmaceutica==''){
						alert('Introduzca una FormaFarmaceutica valida');
						document.getElementById('FormaFarmaceutica').focus();	
					}else{
						var Ok=confirm('Desea actualizar el registro?');
						if(Ok==1){
							ActualizarMedicamento();
						}else{
							alert('Accion Cancelada !');	
						}
					}
				}//presentacion
			}//GrupoTerapeutico
		}//Nombre
	
	}//Codigo
		

}//valida



function ActualizarMedicamento(){
		var IdMedicina=document.getElementById('IdMedicina').value;
		var Codigo = document.getElementById('Codigo').value.trim();
		var Nombre = document.getElementById('NombreMedicina').value.trim();
		var Concentracion = document.getElementById('Concentracion').value.trim();
		var Presentacion = document.getElementById('Presentacion').value.trim();
		var FormaFarmaceutica = document.getElementById('FormaFarmaceutica').value.trim();
		var IdTerapeutico=document.getElementById('IdTerapeutico').value;
		var IdUnidadMedida=document.getElementById('IdUnidadMedida').value;
		var A = document.getElementById('Progreso');
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "ACTUALIZANDO MEDICAMENTO...<br><img src='../images/cargando.gif' />";
											
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						window.location=window.location;
					}
			}
			
			
				
		ajax.open("GET","include/ProcesoNuevoMedicamento.php?IdMedicina="+IdMedicina+"&Codigo="+Codigo+"&Nombre="+Nombre+"&Grupo="+IdTerapeutico+"&Concentracion="+Concentracion+"&FormaFarmaceutica="+FormaFarmaceutica+"&Presentacion="+Presentacion+"&UnidadMedida="+IdUnidadMedida+"&Bandera=1",true);
		ajax.send(null);
		return false;
		
}//Agregar Medicamento

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



