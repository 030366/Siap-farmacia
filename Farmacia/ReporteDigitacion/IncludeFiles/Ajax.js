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
}



function Valida(){
    var Ok=true;
	
    FechaInicial_=document.getElementById('fechaInicio').value;
    FechaFinal_=document.getElementById('fechaFin').value;


    if(!mayor(FechaInicial_,FechaFinal_)){
        Ok=false;
        alert("La fecha final no puede ser menor que la inicial");
    }
	
    if(Ok==true){
        GenerarReporte();	
    }
	
}


function GenerarReporte(){
    var IdPersonal= document.getElementById("IdPersonal").value;
    var FechaInicial=document.getElementById('fechaInicio').value;
    var FechaFinal=document.getElementById('fechaFin').value;
       
    var A = document.getElementById('Reporte');
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<img src='../images/barra.gif' lg='Loading...'>";
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha caducado \n inicie sesion nuevamente!");
                window.location='../signIn.php';
            }
            A.innerHTML = ajax.responseText;
        }
    }
    ajax.open("GET","Proceso.php?Bandera=1&IdPersonal="+IdPersonal+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal,true);
    ajax.send(null);
    return false;
}

function detalleDigitacion(IdPersonal){
    posicion_x=(screen.width/2)-(780/2); 
    posicion_y=(screen.height/2)-(600/2);  
    
    var FechaInicial=document.getElementById('fechaInicio').value;
    var FechaFinal=document.getElementById('fechaFin').value;
    
    window.open("Proceso.php?Bandera=2&IdPersonal="+IdPersonal+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal,"Detalle de Digitacion","height=600, width=780, menubar=no, \n\
            scrollbars=yes,top="+posicion_y+", left="+posicion_x+"");

    
}