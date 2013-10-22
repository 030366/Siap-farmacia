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

function popUp(URL) {
    day = new Date();
    id = day.getTime();
    //id=document.formulario.fecha.value;
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1000,height=500,left = 450,top = 450');");
}//popUp

function Imprimir(){
    var boton = document.getElementById('imprimir');
    boton.style.visibility='hidden';
    print();
    window.close();
	
}

function Valida(){
    var Ok=true;
    var Usuario = document.getElementById('Usuarios').value;
    if(Usuario == 'N'){
        alert('No ha seleccionado un usuario');
        Ok=false;
    }

    var fechaFin;
    var fechaInicio;
    fechaFin=document.getElementById('fechaFin').value;
    fechaInicio=document.getElementById('fechaInicio').value;

    if(!mayor(fechaInicio,fechaFin)){
        Ok=false;
        alert("La fecha final no puede ser menor que la inicial");
    }

    if(Ok==true){
        GeneracionReportes();	
    }
}//funcrion valida


function GeneracionReportes(){
    var Usuario = document.getElementById('Usuarios').value;
    var FechaInicio=document.getElementById('fechaInicio').value;
    var FechaFin=document.getElementById('fechaFin').value;
    /*OBTENCION DE LAYERS QUE DESPLIEGAN INFORMACION*/
    var A = document.getElementById('Layer2');

    /************************************************/
		
    var ajax = xmlhttp();
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<img src='../images/carga.gif'>";
        }
        if(ajax.readyState==4){
            var Datos = ajax.responseText;
            if(Datos=="ERROR_SESSION"){
                alert('La sesion ha caducado \n inicie sesino nuevamente!');
                window.location='../signIn.php';
            }
            A.innerHTML = Datos;

        }
    }

    ajax.open("GET","IncludeFiles/ReporteTransferenciasProceso.php?FechaInicio="+FechaInicio+"&FechaFin="+FechaFin+"&Usuario="+Usuario,true);
    ajax.send(null);
    return false;
		
}//GeneracionReporte
	
