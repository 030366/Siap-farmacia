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


function Imprimir(){//BUSQUEDA DE MEDICAMENTO
    day = new Date();
    id = day.getTime();
    var IdFarmacia = document.getElementById('IdFarmacia').value;
    var IdTerapeutico = document.getElementById('IdTerapeutico').value;
    var IdMedicina=document.getElementById('IdMedicina').value;
    var FechaInicial= document.getElementById('fechaInicio').value;
    var FechaFinal= document.getElementById('fechaFin').value;
	
    var URL="ReporteFarmacias.php?IdFarmacia="+IdFarmacia+"&IdTerapeutico="+IdTerapeutico+"&IdMedicina="+IdMedicina+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal+"&Bandera=2";
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function Imprimir2(){
    document.getElementById('Imprimir').style.visible='hidden';
    document.getElementById('Excel').style.visible='hidden';
    window.print();		
    document.getElementById('Imprimir').style.visible='inline';
    document.getElementById('Excel').style.visible='inline';
	
	
}

function Emergente(URL){//BUSQUEDA DE MEDICAMENTO
    day = new Date();
    id = day.getTime();
	
		
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=670,height=400,left = 0,top = 100');");
}

function Desplegar(IdSubEspecialidad,FechaInicial,FechaFinal){
    var URL="IncludeFiles/Emergente.php?IdSubEspecialidad="+IdSubEspecialidad+"&FechaInicial="+FechaInicial+"&FechaFinal="+FechaFinal;
    Emergente(URL);
}


function Valida(){
    var Ok=true;
	
    var Mes = document.getElementById('Mes').value; 
    var Anio= document.getElementById('Anio').value;
	
    if(Ok==true){
        ObtenerRecetasAbiertas();	
    }
	
}

function ObtenerRecetasAbiertas(){
    //var query = document.getElementById('q').value;
    var Mes = document.getElementById('Mes').value; 
    var Anio= document.getElementById('Anio').value;
    var A = document.getElementById('Reporte');

    //var B = document.getElementById('loading');
    var ajax = xmlhttp();
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "<img src='../imagenes/barra.gif' alt='Loading...' />";
        }
        if(ajax.readyState==4){
            var Respuesta = ajax.responseText;
            if(Respuesta=="ERROR_SESSION"){
                alert("La sesion ha caduda \n Inicie sesion nuevamente!");
                window.location="../signIn.php";
            }else{
                A.innerHTML = Respuesta;
            //B.innerHTML = "";
            }
        }
    }

    ajax.open("GET","ReporteRecetasAbiertas.php?Bandera=1&Mes="+Mes+"&Anio="+Anio,true);
    ajax.send(null);
    return false;
		
}//Recetas Abiertas


function Finalizar(IdReceta,CorrelativoAnual){
    
    var Objeto="Fin"+IdReceta;
    var A = document.getElementById(IdReceta);
    var ajax = xmlhttp();
    
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            A.innerHTML = "Finalizando Receta..."+CorrelativoAnual;
            document.getElementById(Objeto).style.display="none";
        }
        if(ajax.readyState==4){
            var Respuesta = ajax.responseText;
            if(Respuesta=="ERROR_SESSION"){
                alert("La sesion ha caduda \n Inicie sesion nuevamente!");
                window.location="../signIn.php";
            }else{
                A.innerHTML = "Receta Finalizada!";
                
            }
        }
    }

    ajax.open("GET","ReporteRecetasAbiertas.php?Bandera=2&IdReceta="+IdReceta,true);
    ajax.send(null);
    return false;
    
    
}// Finalizar
	
