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
    
    var ok = true;
    
    if(document.getElementById('fechaInicial').value==""){
        ok=false;
        alert("seleccione una fecha inicial valida!");
    }
    
    if(ok==true){
        ObtenerInformacion();
    }
}


function ObtenerInformacion(){
    
    var fechaInicial= document.getElementById('fechaInicial').value;
    var IdFarmacia = document.getElementById("IdFarmacia").value;
    var A = document.getElementById('ErroresDespacho');
    var B = document.getElementById('Progreso');
		
    var ajax = xmlhttp();
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            B.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n por favor inicie sesion nuevamente!');
                window.location='../signIn.php'
            }
            A.innerHTML = ajax.responseText;
            B.innerHTML = "";
						
        }
    }

    ajax.open("GET","IncludeFiles/Proceso.php?Bandera=1&IdFarmacia="+IdFarmacia+"&fechaInicial="+fechaInicial,true);
    ajax.send(null);
    return false;
		
}


function MostrarDetalle(IdMedicina,IdArea,IdMedicinaRecetada){
    
    var A = document.getElementById('detalles'+IdMedicinaRecetada);
            A.style.display='inline';
		
    var ajax = xmlhttp();
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n por favor inicie sesion nuevamente!');
                window.location='../signIn.php'
            }
            A.innerHTML = ajax.responseText;
        
						
        }
    }

    ajax.open("GET","IncludeFiles/Proceso.php?Bandera=2&IdMedicina="+IdMedicina+"&IdArea="+IdArea+"&IdMedicinaRecetada="+IdMedicinaRecetada,true);
    ajax.send(null);
    return false;
		
}

function Cerrar(IdMedicinaRecetada){
    document.getElementById("detalles"+IdMedicinaRecetada).style.display='none';
}

function AplicarCambios(IdMedicinaDespachada,IdMedicinaRecetada){
    var CantidadDespachada = document.getElementById("nueva"+IdMedicinaRecetada).value;
    var LoteDespacho = document.getElementById("nuevo"+IdMedicinaRecetada).value;
    var A =document.getElementById("detalles"+IdMedicinaRecetada);
        document.getElementById("aplicar"+IdMedicinaRecetada).disabled=true;
    var B = document.getElementById("fix"+IdMedicinaRecetada);
    
    var ajax = xmlhttp();
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = A.innerHTML+"<br/>Aplicando Cambios!<br/><br/>";
        }
        if(ajax.readyState==4){
            A.innerHTML = "<strong>Registro actualizado!</strong>";
            B.innerHTML="FIXED!"
        }
    }

    ajax.open("GET","IncludeFiles/Proceso.php?Bandera=3&IdMedicinaDespachada="+IdMedicinaDespachada+"&CantidadDespachada="+CantidadDespachada+
                    "&LoteDespacho="+LoteDespacho,true);
    ajax.send(null);
    return false;
}