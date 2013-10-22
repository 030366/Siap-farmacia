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


function VentanaBusqueda(){
    var variable= document.getElementById('IdArea').value;
	
    var URL= 'BusquedaMedicamento/buscador_medicamento.php?IdArea='+variable;

    if(variable==0){
        alert('Seleccione una Farmacia valida \n para poder obtener el listado de medicamentos');
        document.getElementById('IdArea').focus();
    }else{
// 		if(Cantidad==''){
// 	alert('Antes de seleccionar el medicamento \n introduzca el numero de unidades a transferir');
// 		document.getElementById('Cantidad').focus();
// 	}else{
/*
	day = new Date();
	id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left =0,top = 100');");*/
// 	}
}//else
	
}//VentanaBusqueda


function Habilita(IdMedicina){
    var A = document.getElementById('ComboLotes');
    var Cantidad = document.getElementById('Cantidad').value;
    var IdArea= document.getElementById('IdArea').value;
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //A.innerHTML="Agregando Lotes...";					
        }
        if(ajax.readyState==4){
            A.innerHTML=ajax.responseText;
            document.getElementById('Cantidad').focus();
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?IdMedicina="+IdMedicina+"&Cantidad="+Cantidad+"&IdArea="+IdArea+"&Bandera=4",true);
    ajax.send(null);
    return false;


}

function GeneraFecha(Opcion){
    
    var FechaGenerada="";
    var dia=25;
    var mes=document.getElementById("mes").value;
    var anio=document.getElementById("anio").value;
    
    if(mes<10){mes="0"+mes;}
    
    FechaGenerada=anio+"-"+mes+"-"+dia;
    
    document.getElementById("FechaVencimiento").value=FechaGenerada;
}

/*CREACION DE REGISTRO DE RECETAS*/
/* Manejo de Datos del Paciente */
function valida(){
    var IdArea = document.getElementById('IdArea').value;
    var Justificacion = document.getElementById('Justificacion').value;
    var Cantidad = document.getElementById('Cantidad').value;
    var IdMedicina = document.getElementById('IdMedicina').value;
    var IdLote = document.getElementById('IdLote').value;
    var Precio = document.getElementById('Precio').value;
    var Acta = document.getElementById("Acta").value;
    var mes=document.getElementById("mes").value;
    var anio=document.getElementById("anio").value;

    if(IdArea == 0){
        alert('Seleccione una farmacia valida!');
        document.getElementById('IdArea').focus();
    }else{
        if(Acta==""){
            alert("Introduzca el numero de acta valida del ajuste!");
            document.getElementById('Acta').focus();
        }else{
            if(Cantidad==''){
                alert('Introduzca la cantidad de medicamento a ser tranferido');
                document.getElementById('Cantidad').focus();
            }else{
                if(IdMedicina==''){
                    alert('Seleccione el medicamento a ser transferido');
                    document.getElementById('NombreMedicina').focus();
                    document.getElementById('NombreMedicina').value="";
                }else{
                    if(IdLote==''){
                        alert('Introduzca codigo de lote valido!');
                        document.getElementById('IdLote').focus();
                    }else{
                        if(Precio==''){
                            alert("Introduzca un precio valido!");
                            document.getElementById('Precio').focus();
                        }else{
                            if(mes==0 || anio==0){
                                alert("Seleccione una fecha de vencimiento valida!");
                                document.getElementById("mes").focus();
                            }else{
                                var Fecha = new Date();
                                var mes1=Fecha.getMonth()+1;
                                var anio1=Fecha.getFullYear();
                                
                               // alert(parseInt(mes)+"<"+mes1 +" / "+anio+"=="+anio1);
                                
                                if(((parseInt(mes)<mes1)&&(anio<anio1))||((parseInt(mes)<mes1)&&(anio==anio1))){
                                    
                                    alert("La fecha de vencimiento no puede ser menor que la actual!");
                                    document.getElementById("mes").focus();
                                }else{
                                if(Justificacion==''){
                                    alert('Debe introducir una justificacion \n para realizar la transferencia');
                                    document.getElementById('Justificacion').focus();
                                }else{
                                
                                    GuardarAjuste();
                                }//justificacion
                                }//Si cumple con fecha mayor o igual a la actual
                            }//Si se ha seleccionado mes y anio
                        }
                    }//IdLote
                }//IdMedicina
            }//Cantidad
        
        }//Acta
    }//IdArea

}//valida


function GuardarAjuste(){
    //Detalles de Transferencia
    var A = document.getElementById('NuevaTransferencia');

    var Cantidad= document.getElementById('Cantidad').value;
    var IdArea = document.getElementById('IdArea').value;
    var Justificacion = document.getElementById('Justificacion').value;
    var IdMedicina = document.getElementById('IdMedicina').value;
    var Fecha = document.getElementById('Fecha').value;
    var Lote = document.getElementById('IdLote').value;
    var Precio = document.getElementById('Precio').value;
    var Acta = document.getElementById("Acta").value;
    var FechaVencimiento=document.getElementById("FechaVencimiento").value;

    var Divisor=document.getElementById('Divisor').value;
    var UnidadesContenidas=document.getElementById('UnidadesContenidas').value;


    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //A.innerHTML="Agregando Transferencia...";					
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion');
                window.location='../signIn.php'
            }else{
                var Respuesta =ajax.responseText;
                    
                document.getElementById('Justificacion').value='';
                document.getElementById('NombreMedicina').value='';
                document.getElementById('IdMedicina').value='';
                document.getElementById('Cantidad').value='';
                //document.getElementById('IdArea').options[0].selected=true;
                document.getElementById('mes').options[0].selected=true;
                document.getElementById('anio').options[0].selected=true;
                document.getElementById('Precio').value="";
                //document.getElementById("Acta").value="";
                document.getElementById("Acta").focus();
                    document.getElementById("Acta").select();
                document.getElementById("IdLote").value="";
                document.getElementById("FechaVencimiento").value="";
               
                MostrarAjustes();
            }
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=1&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&IdArea="+IdArea+"&Acta="+Acta+"&Justificacion="+Justificacion+"&Fecha="+Fecha+"&Lote="+Lote+"&Precio="+Precio+"&Divisor="+Divisor+"&UnidadesContenidas="+UnidadesContenidas+"&FechaVencimiento="+FechaVencimiento,true);
    ajax.send(null);
    return false;
    
}//NuevaReceta



function MostrarAjustes(){
    var ajax = xmlhttp();
    var Fecha = document.getElementById('Fecha').value;
    var IdArea=document.getElementById('IdArea').value;
    var A = document.getElementById('NuevaTransferencia');

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
    ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=2&Fecha="+Fecha+"&IdArea="+IdArea+"",true);
    ajax.send(null);
    return false;
}


/*MUESTRA TODAS LAS TRANSFERENCIAS EN ESPERA DE SER FINALIZADAS*/
function FinalizarAjustes(){

    var Ok=confirm('Desea Finalizar las transferencias?');
    if(Ok==true){
        var ajax = xmlhttp();
        ajax.onreadystatechange=function(){
            if(ajax.readyState==1){
					
            }
            if(ajax.readyState==4){
                alert('Ajuste(s) Guardado(s) existosamente');
                window.location.href=window.location.href;
            }
        }
        ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?&Bandera=6",true);
        ajax.send(null);
        return false;
    }
}


/*ELIMINACION PUNTAL DE CADA TRANSFERENCIA DIGITADA*/
function BorrarAjustes(IdAjuste){
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
											
        }
        if(ajax.readyState==4){
            //alert(ajax.responseText);
            var Respuesta = ajax.responseText;
            MostrarAjustes();
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionTransferenciasProceso.php?Bandera=3&IdAjuste="+IdAjuste,true);
    ajax.send(null);
    return false;

}//CancelarReceta


