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



function valida(){

    var Ok=true;

   
    fechaFin=document.getElementById('fechaFin').value;
    
    fechaInicio=document.getElementById('fechaInicio').value;
    

      if(!mayor(fechaInicio,fechaFin)){
            Ok=false;
            alert("La fecha final no puede ser menor que la inicial");
        }

    if(Ok==true){
        Reportes();
    }

}



function Reportes(){
    var IdTerapeutico=document.getElementById('IdTerapeutico').value;
    var fechaInicio= document.getElementById('fechaInicio').value;
    var fechaFin= document.getElementById('fechaFin').value;
    var A = document.getElementById('Respuesta');

    var ajax = xmlhttp();
	
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n inicie sesion nuevamente !');
                window.location='../signIn.php';
            }
            A.innerHTML = ajax.responseText;
        //B.innerHTML = "";
			
        }
    }

    ajax.open("GET","ConsultaBitacora.php?Bandera=1&IdTerapeutico="+IdTerapeutico+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,true);
    ajax.send(null);
    return false;
		
}//