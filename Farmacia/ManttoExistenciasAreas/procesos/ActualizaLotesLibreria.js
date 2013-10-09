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

function ActualizaDatos(IdMedicina,IdArea){
	var Lote = document.getElementById('Lote').value;
	var PrecioLote = document.getElementById('Precio').value;
	var mes = document.getElementById('mes').value;
	var ano = document.getElementById('ano').value;
	var LoteOld=document.getElementById('LoteOld').value;

if(Lote=='' && PrecioLote==0 && (mes==0 || ano==0)){
alert('Para actualizar los datos al menos debe cambiar un dato');
}else{
	
		if(ano==0 && mes==0){
			var Vencimiento="Ventto.";
		}else{
			var Vencimiento=ano+"-"+mes+"-"+"25";
		}
	var ajax = xmlhttp();
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1){
				//En Proceso
			}
			if(ajax.readyState==4){
				//B.innerHTML = "";
				window.opener.recarga(IdMedicina,IdArea,0);
				alert('Datos Actualizados');
			}
		}
			
			ajax.open("GET","procesos/ProcesoActualizaLotes.php?Lote="+Lote+"&PrecioLote="+PrecioLote+"&FechaVencimiento="+Vencimiento+"&LoteOld="+LoteOld,true);
			ajax.send(null);
			return false;
	}//fin de else
}//ActualizaDFatos
