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
	}

function buscar(){
		var query = document.getElementById('q').value;
		var A = document.getElementById('resultados');
		var B = document.getElementById('loading');
		var C = document.getElementById('Layer2');
		var D = document.getElementById('DetalleRecetas');
		var IdFarmacia=document.getElementById('IDFarmacia').value;
		var IdArea=document.getElementById('IDArea').value;
		var ajax = xmlhttp();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						B.innerHTML = "<img src='../../loading.gif' alg='Loading...'>";
					}
				if(ajax.readyState==4){
						A.innerHTML = ajax.responseText;
						B.innerHTML = "";
						C.innerHTML = "";
						D.innerHTML = "";
					}
			}
		ajax.open("GET","busqueda.php?q="+encodeURIComponent(query)+"&farmacia="+IdFarmacia+"&area="+IdArea,true);
		ajax.send(null);
		return false;
	}