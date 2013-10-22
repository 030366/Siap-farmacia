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
function ActualizarLista(IdArea){
  if(IdArea!=0){
      var IdTerapeutico=document.getElementById('Terapeutico').value;
	if(IdTerapeutico!=0){
	    MedicamentoPorGrupo(IdTerapeutico);
        }else{
	   MedicamentoPorGrupo(0);
	}
  }else{
	MedicamentoPorGrupo(0);
   }

}
function MedicamentoPorGrupo(IdTerapeutico){

	var A = document.getElementById('Medicamentos');
	var IdArea=document.getElementById('IdArea').value;
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//En Proceso
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				if(ajax.responseText=="ERROR_SESSION"){alert('La sesion ha caducado \n Inicie sesion nuevamente!'); window.location='../signIn.php';}
				
				A.innerHTML=ajax.responseText;
			}
		}
			
			ajax.open("GET","procesos/MedicamentosPorGrupo.php?IdTerapeutico="+IdTerapeutico+"&IdArea="+IdArea,true);
			ajax.send(null);
			return false;

}

function MostrarOpcionLote(valor,id){
	
	var Id=id.split('Lote');

	var Precio="Precio"+Id[1];
	var mes="mes"+Id[1];
	var ano="ano"+Id[1];
		
	var inputCombo="<input id=\""+id+"\" name=\""+id+"\" size=\"8\" value=\"Lote.\" onFocus=\"if(this.value=='Lote.'){this.value='';}\" onBlur=\"if(this.value==''){this.value='Lote.';}\">";

	if(valor=='N'){
		var ok=confirm('Seguro de ingresar un nuevo lote?');
	
		if(ok==true){
		
		   document.getElementById(Precio).disabled=false;
		   document.getElementById(mes).disabled=false;
		   document.getElementById(ano).disabled=false;
			//alert(inputCombo);
			document.getElementById('ComboLotesMedicina'+Id[1]).innerHTML=inputCombo;
		}

	}

}

function ValidaFecha(Campo){
	var Fecha = Campo.value;
	var ajax = xmlhttp();
	if(Fecha!='' && Fecha!='Fecha Ventto.'){
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				Campo.value='Comprobando...';
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				var Respuesta = ajax.responseText;
				if(Respuesta=='SI'){
					Campo.value=fecha;
				}else{
					alert('La fecha de lotes no puede ser menor al mes y año actual !');
					Campo.value='';
				}
			}
		}
			
			ajax.open("GET","saving.php?TestFecha="+Fecha+"&Bandera=2",true);
			ajax.send(null);
			return false;
	
	}//FECHA !=
	else{
		alert(Fecha);	
	}
	
}//ValidaFecha



function Alerta(IdMedicina){
	var CantidadMedicamento= document.getElementById(IdMedicina).value;
	var LoteMedicina="Lote"+IdMedicina;
	var Lote = document.getElementById(LoteMedicina).value;
	
	if(CantidadMedicamento==0){
		alert('Introduzca la Cantidad de Medicamento a Ingresar');
	}else{
		if(Lote=="Lote."){
			alert('Introduzca el lote del medicamento');
		}else{
			var confirmacion=confirm('Son los datos correctos?');
			if(confirmacion==1){
				save(IdMedicina);	
			}
		}//Else Cantidad
	}//Else Lote
}


function save(IdMedicina){
		//var query = document.getElementById('q').value;
		var ajax = xmlhttp();
		/*Fecha Vencimiento*/
		var mes=document.getElementById("mes"+IdMedicina).value;	//Fecha de Vencimineto
		var ano=document.getElementById("ano"+IdMedicina).value;
		FechaVencimiento=ano+"-"+mes+"-"+"25";
		/*******************/
		var Precio="Precio"+IdMedicina;		//Precio Unitario del Medicamento del Lote
		var Lotes="Lote"+IdMedicina;			//Lote del medicamento entrante
		
		var nombrediv='saving'+IdMedicina;
		var boton='guardar'+IdMedicina;
		
		var divExistencia='existenciaActual'+IdMedicina;
		//alert(FechaVencimiento);
		/* Valor a introducir */
		var Existencia = document.getElementById(IdMedicina).value;
		var Lotes=document.getElementById(Lotes).value;
		//var FechaVencimiento=document.getElementById(Ventto).value;
		var PrecioUnitarioLote=document.getElementById(Precio).value;
		var IdArea=document.getElementById("IdArea").value;
		
		var A = document.getElementById(divExistencia);
		/* FIN VALORES */
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "GUARDANDO...";
						
					}
				if(ajax.readyState==4){
						//B.innerHTML = "";
// 					alert(ajax.responseText);
						recarga(IdMedicina,0);

					}
			}
		
		ajax.open("GET","saving.php?IdMedicina="+IdMedicina+"&Existencia="+Existencia+"&Lote="+Lotes+"&FechaVentto="+FechaVencimiento+"&PrecioLote="+PrecioUnitarioLote+"&IdArea="+IdArea,true);
		ajax.send(null);
		return false;
		
}//LoadRecetas
	
/*****/
function popUp(URL) {
day = new Date();
id = day.getTime();
//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=661,height=220,left = 450,top = 450');");
}//popUp
/******/
	
	function recarga(IdMedicina,Bandera){
		var ajax = xmlhttp();
		var divExistencia='existenciaActual'+IdMedicina;
		var Precio="Precio"+IdMedicina;		//Precio Unitario del Medicamento del Lote
		var Lotes="Lote"+IdMedicina;			//Lote del medicamento entrante
		var CombosDiv="Combos"+IdMedicina;
		

		var A = document.getElementById(divExistencia);
		var B = document.getElementById(IdMedicina);
		var Combos = document.getElementById(CombosDiv);

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.value= "CARGANDO...";
						B.style.display="none";
					}
				if(ajax.readyState==4){
						var Respuesta = ajax.responseText.split('~');
						A.innerHTML = Respuesta[0];
						B.style.display="inline";
						B.value='0';
						document.getElementById(Lotes).value='Lote.';
						document.getElementById(Precio).value='0';
						Combos.innerHTML=Respuesta[1];
						
					}
			}
												/*** BANDERA = 0 ***/
		ajax.open("GET","saving.php?IdMedicina="+ IdMedicina +"&Bandera="+ Bandera,true);
		ajax.send(null);
		return false;

}//Obtener Nueva Existencia
