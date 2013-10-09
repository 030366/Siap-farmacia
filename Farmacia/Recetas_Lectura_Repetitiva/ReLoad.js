function xmlhttp(){
    var xmlhttp;
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e){
            try{
                xmlhttp = new XMLHttpRequest();
            }
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

var nav4 = window.Event ? true : false;
function acceptNum(evt){	
    var key = nav4 ? evt.which : evt.keyCode;	
    //alert(key);
    if(key == 47){
        //find();
    }else{
        if(key==13){
            Procesar(0);
        }
        return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
		
    }
}


function ActualizaMedicina(IdReceta,IdMedicina){
    //alert(IdMedicina+' '+IdReceta);	
    day = new Date();
    id = day.getTime();
    var URL="Modificaciones/Modificaciones.php?IdReceta="+IdReceta+"&IdMedicina="+IdMedicina;
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=900,height=480,left = 0,top = 100');");
}


function Cambiar(IdReceta,IdMedicina,Cantidad){
	
    var CantidadNueva=document.getElementById('Cantidad').value.trim();
    var IdMedicinaNueva=document.getElementById('IdMedicina').value;
		
    var ajax = xmlhttp();


    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            //B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";

        }
        if(ajax.readyState==4){
            //alert(ajax.responseText);
            window.opener.Procesar(IdReceta);
            window.close();
						
        }
    }
		
    ajax.open("GET","respuesta.php?Bandera=2&IdReceta="+IdReceta+"&IdMedicina="+IdMedicina+"&IdMedicinaNueva="+IdMedicinaNueva+"&Cantidad="+Cantidad+"&CantidadNueva="+CantidadNueva,true);
    ajax.send(null);
    return false;	
}//cambios



function Procesar(IdReceta){
    var A = document.getElementById('Layer1');
    var IdNumeroExp=document.getElementById('IdNumeroExp').value;
    var Complemento='';
    if(IdReceta!=0){
        Complemento='&Ok=1&IdReceta='+IdReceta;
    }
		
		
    var ajax = xmlhttp();


    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            //B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";

        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha caducado \n vuelva a iniciar sesion!");
                window.location="../Principal/index.php";
            }else{
                A.innerHTML = ajax.responseText;
            }					
						
        }
    }
		
    ajax.open("GET","proceso.php?IdNumeroExp="+IdNumeroExp+''+Complemento,true);
    ajax.send(null);
    return false;
	
}//Procesar


function Listo(IdReceta){
    var A = document.getElementById(IdReceta);
    var ajax = xmlhttp();


    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<div align='center'>GUARDANDO . . .</div>";

        }
        if(ajax.readyState==4){
            
            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha caducado \n vualva a iniciar sesion!");
                window.location="../Principal/index.php";
            }else{
                A.innerHTML = "";
                A.style.display="none";
            }				
						
        }
    }
		
    ajax.open("GET","proceso2.php?IdReceta="+ IdReceta,true);
    ajax.send(null);
    return false;
	
}//LISTO





function actualizaEstado(HistorialClinico,IdMedicina,Bandera){
    var Obj = 'IdReceta'+HistorialClinico;
    var IdReceta = document.getElementById(Obj).value;
    var ID = HistorialClinico+''+IdMedicina;
    var Combo = document.getElementById(ID);
    var ajax = xmlhttp();
	
    /*EN DADO CASO EL MEDICAMENTO DE LA RECETA PASE A SER SATISFECHA*/
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            //B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";
        }
        if(ajax.readyState==4){
            //A.innerHTML = ajax.responseText;
            var respuesta=ajax.responseText.split(' ');
            Procesar(IdReceta);
            if(respuesta[0]=='NO'){
                //CODIGO TEMPORALMENTE INHABILITADO: SE UTILIZA PARA EVITAR EL CAMBIO A INSATISFECHA SI EXISTEN EXISTENCIAS EN FARMACIA
			
                //alert('Este medicamento no puede ser demanda insatisfecha, \n posee: '+respuesta[2]+' existencias en lote: '+respuesta[1]);	
                //Combo.length=0;
                //var nuevaOpcion=document.createElement("option"); nuevaOpcion.value="SI"; nuevaOpcion.id=ID;nuevaOpcion.innerHTML="SI";
                //Combo.appendChild(nuevaOpcion);
            }
						
            if(respuesta[0]=='SI'){
                //sE UTILIZA PARA EVITAR Q SEA SATISFECHO SI NO HAY EXISTENCIAS
			
                //alert('Este medicamento no puede ser despachado, \n solo posee: '+respuesta[1]+' existencias\n en lote: '+respuesta[2]);	
                //Combo.length=0;
                //var nuevaOpcion=document.createElement("option"); nuevaOpcion.value="NO"; nuevaOpcion.id=ID;nuevaOpcion.innerHTML="NO";
                //Combo.appendChild(nuevaOpcion);

            }
            //LoadRecetas(IdArea);

        }
    }
		
    ajax.open("GET","ActualizaBandera.php?IdReceta="+ IdReceta +"&IdMedicina="+ IdMedicina +"&Bandera="+ Bandera,true);
    ajax.send(null);
    return false;


		
}//actulizaEstado

