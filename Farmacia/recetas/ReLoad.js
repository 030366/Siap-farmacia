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
 

var nav4 = window.Event ? true : false;
function acceptNum(evt){	
    var key = nav4 ? evt.which : evt.keyCode;	
    //alert(key);
    if(key == 47){
        document.getElementById("buscar").focus();
    }else{
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
    var A = document.getElementById(IdReceta);
    var ajax = xmlhttp();


    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            //B.innerHTML = "<img src='../loading.gif' alg='Loading...'>";

        }
        if(ajax.readyState==4){
            A.innerHTML = ajax.responseText;
						
						
        }
    }
		
    ajax.open("GET","proceso.php?IdReceta="+ IdReceta,true);
    ajax.send(null);
    return false;
	
}//Procesar


function Listo(IdReceta,NumeroReceta){
    var A = document.getElementById(IdReceta);
    var ajax = xmlhttp();
		

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<div align='center'><h2>GUARDANDO RECETA NUMERO: <strong>"+NumeroReceta+"</strong>. . .</h2></div>";

        }
        if(ajax.readyState==4){
            
            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha caducado \n vuelva a iniciar sesion!");
                window.location="../Principal/index.php";
            }else{
            var ALL = document.getElementById("TODO"); 
            var ALL_nested = document.getElementById(IdReceta); 
            var throwawayNode = ALL.removeChild(ALL_nested);
            }
						
        }
    }
		
    ajax.open("GET","proceso2.php?IdReceta="+ IdReceta,true);
    ajax.send(null);
    return false;
	
}//LISTO



function Carga(){
    var A = document.getElementById('TODO');
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            //A.innerHTML = "<div align='center'>GUARDANDO . . .</div>";
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                  alert("La sesion ha caducado \n vuelva a iniciar sesion!");
                  window.location="../Principal/index.php";
            }else{
                A.innerHTML = ajax.responseText;
                recarga();
            }
        }
    }
		
    ajax.open("GET","proceso3.php",true);
    ajax.send(null);
    return false;
}


function recarga(){
    setTimeout('Carga()', 30000);
}//recarga


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
						
        }
    }
		
    ajax.open("GET","ActualizaBandera.php?IdReceta="+ IdReceta +"&IdMedicina="+ IdMedicina +"&Bandera="+ Bandera,true);
    ajax.send(null);
    return false;
}//actulizaEstado

