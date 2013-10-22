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
function Teclas(evt,id){	
    var key = nav4 ? evt.which : evt.keyCode;	
    if(id=='Nombre' && key==8){
        document.getElementById('IdEstablecimiento').value="";
    }
    return (key);
}


function valida(){
    var form = document.getElementById('formulario');
    var Ok=true;

    var fechaFin;
    var fechaInicio;
    fechaFin=form.fechaFin.value;
    fechaInicio=form.fechaInicio.value;

    if(!mayor(fechaInicio,fechaFin)){
        Ok=false;
        alert("La fecha final no puede ser menor que la inicial");
    }


    if(Ok==true){
        GeneracionReportes();
    }

}//valida

function CargarMedicinas(IdTerapeutico){
    var A=document.getElementById('ComboMedicina');
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

    ajax.open("GET","ReporteTransferencia.php?Bandera=1&IdTerapeutico="+IdTerapeutico,true);
    ajax.send(null);
    return false;

}

function GeneracionReportes(){
    var IdEstablecimiento = document.getElementById('IdEstablecimiento').value;	
    var IdTerapeutico = document.getElementById('IdTerapeutico').value;
    var IdMedicina = document.getElementById('IdMedicina').value;
    var FechaInicio=document.getElementById('fechaInicio').value;
    var FechaFin=document.getElementById('fechaFin').value;
    /*OBTENCION DE LAYERS QUE DESPLIEGAN INFORMACION*/
    var A = document.getElementById('Reporte');
    /************************************************/
    if(IdEstablecimiento==""){
        IdEstablecimiento=0;
    }

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

    ajax.open("GET","ReporteTransferencia.php?Bandera=2&IdEstablecimiento="+IdEstablecimiento+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&fechaInicio="+FechaInicio+"&fechaFin="+FechaFin,true);
    ajax.send(null);
    return false;
		
}//GeneracionReporte
	
