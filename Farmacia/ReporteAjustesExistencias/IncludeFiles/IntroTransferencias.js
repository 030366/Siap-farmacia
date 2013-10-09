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


function Valida(){
    var Ok=true;
    var FechaInicial_ = document.getElementById('FechaInicial').value;
    var FechaFinal_ = document.getElementById('FechaFinal').value;

      if(!mayor(FechaInicial_,FechaFinal_)){
            Ok=false;
            alert("La fecha final no puede ser menor que la inicial");
        }
	
    if(Ok==true){
        MostrarAjustes();	
    }
	
}


function MostrarAjustes(){
    var ajax = xmlhttp();
    var FechaInicial = document.getElementById('FechaInicial').value;
    var FechaFinal = document.getElementById('FechaFinal').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var A = document.getElementById('ReporteAjustes');

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //A.innerHTML="Desplegando Transferencia(s) ...";						
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion');
                window.location='../signIn.php'
            }else{
                A.innerHTML=ajax.responseText;
            }
        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=1&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal+"&IdPersonal="+IdPersonal,true);
    ajax.send(null);
    return false;
}




