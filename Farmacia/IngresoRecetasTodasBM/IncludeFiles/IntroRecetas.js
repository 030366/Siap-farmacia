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


/*Filtracion de teclas*/
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
    var key = nav4 ? evt.which : evt.keyCode;	
    //alert(key);
    return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
}

/*			ACTUALIZACION DE DATOS			*/
function CorregirExpediente(){
    var IdNumeroExp = document.getElementById('Expediente').value;
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    //Datos a ingresar
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('ActualizacionExp').innerHTML="REALIZANDO CAMBIO ...";
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta=ajax.responseText;
				
            if(Respuesta=='N'){
                document.getElementById('ActualizacionExp').innerHTML="ERROR AL REALIZAR CAMBIOS";
            }else{
                document.getElementById('ActualizacionExp').innerHTML="CAMBIO REALIZADO !";
            }
										
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdNumeroExp="+IdNumeroExp+"&IdHistorialClinico="+IdHistorialClinico+"&Bandera=14",true);		
    ajax.send(null);
    return false;

}//Corregir Expediente


function CorregirArea(){
    var Area = document.getElementById('IdArea').value;
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var IdFarmacia=document.getElementById('IdFarmacia').value;


    //Datos a ingresar
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('ActualizacionArea').innerHTML="REALIZANDO CAMBIO ...";
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta=ajax.responseText;
				
            if(Respuesta=='N'){
                document.getElementById('ActualizacionArea').innerHTML="ERROR AL REALIZAR CAMBIOS";
            }else{
                document.getElementById('ActualizacionArea').innerHTML="CAMBIO REALIZADO !";
            }
										
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdArea="+Area+"&IdFarmacia="+IdFarmacia+"&IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&Bandera=14",true);
    ajax.send(null);
    return false;

}//Corregir Area

function CorregirMedico(){
    var Area = document.getElementById('IdArea').value;
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var IdMedico=document.getElementById('IdMedico').value;
    //Datos a ingresar
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('ActualizacionMedico').innerHTML="REALIZANDO CAMBIO ...";
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta=ajax.responseText;
            if(Respuesta=='N'){
                document.getElementById('ActualizacionMedico').innerHTML="ERROR AL REALIZAR CAMBIOS";
            }else{
                document.getElementById('ActualizacionMedico').innerHTML="CAMBIO REALIZADO !";
            }
										
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdHistorialClinico="+IdHistorialClinico+"&IdMedico="+IdMedico+"&Bandera=14",true);
    ajax.send(null);
    return false;
}//Corregir Medico

function CorregirEspecialidad(){
    var Area = document.getElementById('IdArea').value;
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var IdSubServicio=document.getElementById('IdSubServicio').value;
    //Datos a ingresar
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('ActualizacionEspecialidad').innerHTML="REALIZANDO CAMBIO ...";
        }
        if(ajax.readyState==4){
				
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta=ajax.responseText;
            if(Respuesta=='N'){
                document.getElementById('ActualizacionEspecialidad').innerHTML="ERROR AL REALIZAR CAMBIOS";
            }else{
                document.getElementById('ActualizacionEspecialidad').innerHTML="CAMBIO REALIZADO !";
            }
										
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdSubServicio="+IdSubServicio+"&Bandera=14",true);
    ajax.send(null);
    return false;
}//Corregir Especialidad


/************************************************************************/

function Saltos(evt,Objeto){	
    var key = nav4 ? evt.which : evt.keyCode;	
    var Valortmp = document.getElementById('CodigoFarmacia').value;
    var Valor=Valortmp.trim();
    //alert(key);
    if(Objeto=='Fecha'){
        document.getElementById('Expediente').focus();
    }
	
    if(key==13 && Objeto=='Expediente'){
        if(document.getElementById('IdFarmacia').value==0){ 
            document.getElementById('IdFarmacia').focus();
        }else{
            if(document.getElementById('IdArea').value==0){ 
                document.getElementById('IdArea').focus();
            }else{
                document.getElementById('CodigoFarmacia').focus();
                document.getElementById('CodigoFarmacia').select();
            }
        }
    }//Objeto == Expediente

    if(key==13 && Objeto=='CodigoFarmacia' && Valor !=''){
        document.getElementById('CodigoSubServicio').focus();
        document.getElementById('CodigoSubServicio').select();
    }
	
	
    if(key==13 && Objeto=='CodigoSubServicio' && Valor !=''){
        document.getElementById('AddReceta').focus();
    }
	
	
    /*CONTROLES PARA EL USO DE ATAJOS TERMINAR RECETAS CANCELAR O BUSCAR MEDICAMENTO DESDE CANTIDAD*/
    if(key==13 && Objeto=='Cantidad'){
        document.getElementById('NombreMedicina').focus();
    }
		
    if(key==13 && Objeto=='NombreMedicina'){
    //document.getElementById('Agregar').focus();		
    }
		
    if((key==116 || key==84) && Objeto=='Cantidad'){
        //Valor de la tecla T para terminar o finalizar la receta
        FinalizarReceta();
    }
    /*if(key==105 && Objeto=='Cantidad'){
			//VALOR DE TECLA I PARA HACER AUTOMATICO EL CECKEO DE INSATISFECHA
			if(document.getElementById('Insatisfecha').checked==false){
				document.getElementById('Insatisfecha').checked=true;
			}else{
				document.getElementById('Insatisfecha').checked=false;
			}
		}
		if(key==99 && Objeto=='Cantidad'){
			//Valor de tecla C para cancelar la medicina seleccionada
			document.getElementById('IdMedicina').value='';
			document.getElementById('NombreMedicina').value='';
			document.getElementById('Cantidad').value='';

		}
		if(key==43 && Objeto=='Cantidad'){
			//Valor de la tecla T para terminar o finalizar la receta
			validaMedicina();
		}*/

    /**************************************/
	
    if(key==13 && Objeto=='Agregar'){
        document.getElementById('Cantidad').focus();
    }
	
    if(Objeto=='Expediente'){
        return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
    }else{
		
        return ((key < 13)||(key>=97 && key <=122) || (key>=48 && key<=57) ||(key==165 || key==164)||(key>=65 && key<=90)||key==43 || key==32);
    }
}//Saltos

/*Verificacion de campos vacios*/
function verificacion(){
    if(document.form.expediente.value==''){
        alert('Introduzca un numero de Expediente valido');	
    }else{
        Respuesta();
    }
}//verificacion



function VentanaBusqueda(){//BUSQUEDA DE MEDICAMENTO
    day = new Date();
    id = day.getTime();
    var URL="BusquedaServicio/buscador_medicamento.php";
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function VentanaBusqueda2(){//Para Medicos
    day = new Date();
    id = day.getTime();
    var URL="BusquedaMedico/buscador_medico.php";
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 0,top = 100');");
}

function VentanaBusqueda4(URL){//Modifica Dosis
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=225,height=130,left = 150,top = 270');");
}

function Habilita(){
    var check=document.getElementById('CheckRepetitiva');
    if(check.checked==true){
        document.getElementById('Repetitiva').disabled=false;	
        document.getElementById('Repetitiva').focus();	
    }else{
        document.getElementById('Repetitiva').value='';	
        document.getElementById('Repetitiva').disabled=true;	
    }
}

/*FUNCIONES UTILIZADAS POR LOS POPUPS*/

function PegarSubServicio(IdSubServicio,CodigoFarmacia){
    document.getElementById('IdSubServicio').value=IdSubServicio;
    document.getElementById('CodigoSubServicio').value=CodigoFarmacia;
    document.getElementById('CodigoSubServicio').focus();
//document.getElementById('Agregar').focus();
}

function PegarMedico(IdEspecialidad,NombreEspecialidad,IdMedico,NombreMedico){
    //document.getElementById("IdEspecialidad").value=IdEspecialidad;
    //document.getElementById("NombreEspecialidad").innerHTML=NombreEspecialidad;
    document.getElementById("IdMedico").value=IdMedico;
    document.getElementById("NombreMedico").innerHTML=NombreMedico;
    ObtenerDatosMedicoBusqueda(IdMedico);
}//pegarMedico


function ObtenerDatosMedicoBusqueda(IdMedico){ 
    //Datos a ingresar
    var IdArea=document.getElementById('IdArea').value;
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        /*NOTHING*/		
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            document.getElementById('CodigoFarmacia').value = ajax.responseText;
            document.getElementById('CodigoSubServicio').focus();
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedico="+IdMedico+"&IdArea="+IdArea+"&Bandera=11",true);
    ajax.send(null);
    return false;
}//ObtenerDatosMedicoBusqueda


function ObtenerDatosMedico(){
    //Datos a ingresar
    var IdArea=document.getElementById('IdArea').value;
    var CodigoFarmacia= document.getElementById('CodigoFarmacia').value;
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        /*NOTHING*/		
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Datos=ajax.responseText.split('/');
					
            //document.getElementById('IdEspecialidad').value = Datos[0];
            document.getElementById('IdMedico').value=Datos[0];
            //document.getElementById('NombreEspecialidad').innerHTML=Datos[2];
            document.getElementById('NombreMedico').innerHTML=Datos[1];
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?CodigoFarmacia="+CodigoFarmacia+"&IdArea="+IdArea+"&Bandera=12",true);
    ajax.send(null);
    return false;
}//ObtenerDatosMedicoBusqueda


function CargarSubServicio(Codigo){
    //Datos a ingresar
    var Codigo= document.getElementById('CodigoSubServicio').value;
    var ajax = xmlhttp();

    if(Codigo==''){
        alert('Digite Codigo de Servicio/Especialidad');	
        document.getElementById('IdSubServicio').value='';
    }else{

        ajax.onreadystatechange=function(){
            if(ajax.readyState==1){
            /*NOTHING*/		
            }
            if(ajax.readyState==4){
                if(ajax.responseText=="ERROR_SESSION"){
                    alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                    window.location='../signIn.php';
                }

                var Datos=ajax.responseText.split('/');
					
                document.getElementById('IdSubServicio').value=Datos[0];
					
                document.getElementById('NombreSubServicio').innerHTML=Datos[1];
            }
        }
		
        ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Codigo="+Codigo+"&Bandera=13",true);
        ajax.send(null);
        return false;
    }
}//ObtenerDatosMedicoBusqueda



/*FIN FUNCIONES POPUP*/

/*CREACION DE REGISTRO DE RECETAS*/
/* Manejo de Datos del Paciente */
function valida(){
    var Expediente = document.getElementById('Expediente').value;
    var IdSubServicio = document.getElementById('IdSubServicio').value;
    var IdMedico = document.getElementById('IdMedico').value;
    var IdArea = document.getElementById('IdArea').value;
    var Fecha = document.getElementById('Fecha').value;

    if(Expediente == ''){
        alert('Introduzca el Numero de Expediente');
        document.getElementById('Expediente').focus();
    }else{
        if(IdMedico==0){
            alert('Introduczca el codigo de medico');
            document.getElementById('CodigoFarmacia').focus();
        }else{
            if(IdArea==0){
                alert('Seleccione el area de la farmacia');
                if(document.getElementById('IdArea').disabled==true){
                    document.getElementById('IdFarmacia').focus();
                }else{
                    document.getElementById('IdArea').focus();	
                }
            }else{
                if(Fecha==''){
                    alert('Seleccione la fecha de introduccion');
                }else{
                    if(IdSubServicio==0){
                        alert('Seleccione una Especialidad o Servicio que emite la receta');
                    }else{
                        GuardarRecetaNueva();	
                    }
                }
            }
        }//else fechaFin
    }//else fechaInicio

}//valida


function GuardarRecetaNueva(){
    //Datos a ingresar
    var A = document.getElementById('IdReceta');
    var Fecha=document.getElementById('Fecha').value;
    var Expediente = document.getElementById('Expediente').value;
    var IdSubServicio = document.getElementById('IdSubServicio').value;
    var IdMedico = document.getElementById('IdMedico').value;
    var IdArea = document.getElementById('IdArea').value;
    var IdAreaOrigen=document.getElementById('IdAreaOrigen').value;
    var IdFarmacia=document.getElementById('IdFarmacia').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdEstablecimiento=document.getElementById('IdEstablecimiento').value;

    //Si no hay cambio de Area Origen se asume que la recetas es de la area de despacho....
    if(IdAreaOrigen==0){
        IdAreaOrigen=IdArea;
    }
    //*************************************************************************************
		
    var ajax = xmlhttp();
	
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
											
        }
        if(ajax.readyState==4){
					
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Informacion = ajax.responseText.split('~');
            if(Informacion[0]=='NO'){
                alert("El periodo "+Informacion[1]+" se encuentra finalizado y no se puede hacer ingresos extras en este periodo !");
            }else{
					
                document.getElementById('IdHistorialClinico').value=Informacion[0];
                document.getElementById('IdRecetaValor').value=Informacion[1];
                document.getElementById('RecetaNumero').value=Informacion[1];
                document.getElementById('CorrelativoAnual').value=Informacion[2];
					
                //document.getElementById('Expediente').disabled=true;
                document.getElementById('AddReceta').disabled=true;
                document.getElementById('Cancelar').disabled=false;
                document.getElementById('Cantidad').disabled=false;
                //document.getElementById('Busqueda').disabled=false;
                document.getElementById('Agregar').disabled=false;
                document.getElementById('Finalizar').disabled=false;
                //document.getElementById('Dosis').disabled=false;
                document.getElementById("NombreMedicina").disabled=false;
                //document.getElementById('Insatisfecha').disabled=false;
                document.getElementById('Cantidad').focus();
            //document.getElementById("NombreEspecialidad").disabled=true;
            //document.getElementById("CheckRepetitiva").disabled=false;

            }
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Expediente="+Expediente+"&IdMedico="+IdMedico+"&Fecha="+Fecha+"&IdSubServicio="+IdSubServicio+"&IdFarmacia="+IdFarmacia+"&IdArea="+IdArea+"&IdAreaOrigen="+IdAreaOrigen+"&IdPersonal="+IdPersonal+"&IdEstablecimiento="+IdEstablecimiento+"&Bandera=1",true);
    ajax.send(null);
    return false;
}//NuevaReceta

/*FIN CREACION DE REGISTRO DE RECETAS*/
function ObtenerExistenciaTotal(){
    var IdMedicina=document.getElementById('IdMedicina').value;
    var ExistenciaTotal=document.getElementById('ExistenciaTotal');
    var IdArea = document.getElementById('IdArea').value;

    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
			
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            ExistenciaTotal.value=ajax.responseText;
            validaMedicina();
        }
    }

    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=16&IdMedicina="+IdMedicina+"&IdArea="+IdArea,true);
    ajax.send(null);
    return false;

	
}


function validaMedicina(){
    var Cantidad=document.getElementById('Cantidad').value;
    var Dosis=document.getElementById('Dosis').value;
    var NombreMedicina=document.getElementById('NombreMedicina').value;
    var IdMedicina=document.getElementById('IdMedicina').value;	
    var Insatisfecha = document.getElementById('Insatisfecha');
    var ExistenciaTotal=document.getElementById('ExistenciaTotal').value;
    var Ok=true;
	
    var Cantidad2=parseInt(Cantidad);
    var ExistenciaTotal2=parseInt(ExistenciaTotal);

    if(Cantidad==''){
        alert('Introduzca la Cantidad medicada');
        document.getElementById('Cantidad').focus();
        Ok=false;
    }
	
    if(ExistenciaTotal2 < Cantidad2){
        alert('No se puede ingresar esta receta \n La cantidad rebaza la existencia actual!');
        document.getElementById('Cantidad').focus();
        document.getElementById('Cantidad').select();
        Ok=false;
    }
	
    if((NombreMedicina=='' || IdMedicina=='') && Ok==true){
        alert('Seleccione el medicamento a introducir');
        document.getElementById('NombreMedicina').focus();
    }
    //if(Dosis==''){
    //	alert('Introduzca la dosis prescrita por el medico\n para el medicamento '+NombreMedicina);
    //	document.getElementById('Dosis').focus();
    //}else{
				
    //}
		
	

    if(Ok==true){
        GuardarMedicamentoReceta(IdMedicina);
    }

}


function GuardarMedicamentoReceta(IdMedicina){
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var Cantidad=document.getElementById('Cantidad').value;
    //var IdMedicina=document.getElementById('IdMedicina').value;
    var Dosis=document.getElementById('Dosis').value;
    if(Dosis==''){
        Dosis='-';
    }
    var Fecha=document.getElementById('Fecha').value;
    var B=document.getElementById('MedicinaNueva');
    var C=document.getElementById('MedicinaNuevaRepetitiva');
    //var check=document.getElementById('CheckRepetitiva');
    //var NumeroRepetitiva=document.getElementById('Repetitiva').value;
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    var IdMedico=document.getElementById('IdMedico').value;
    var IdArea = document.getElementById('IdArea').value;
    var Insatisfecha = document.getElementById('Insatisfecha');
		
    if(Insatisfecha.checked==true){
        Satisfecha='I';
    }else{
        Satisfecha='S';
    }
    var Bandera = 5;

    /* SE VERIFICA SI ES REPETITIVA */
    //if(check.checked==true){Bandera=3;}else{Bandera=5;}
    //if(NumeroRepetitiva==''){NumeroRepetitiva=0;}
	

    /********************************/
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
											
        }
        if(ajax.readyState==4){
	
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            /*	if(check.checked==true){
					var datos = ajax.responseText.split('<br>');
					B.innerHTML = datos[0];
					C.innerHTML = datos[1];
					check.checked=false;
					document.getElementById('Repetitiva').disabled=true;
					document.getElementById('Repetitiva').value='';
					}else{*/
            var RespuestaAjax=ajax.responseText.split('~');
            B.innerHTML= RespuestaAjax[0];
            document.getElementById('ContadorRecetas').innerHTML=RespuestaAjax[1];
            //}
            document.getElementById('IdMedicina').value='';
            document.getElementById('NombreMedicina').value='';
            document.getElementById('Cantidad').value='';
            document.getElementById('Dosis').value='';
            document.getElementById('Insatisfecha').checked=false;
            document.getElementById('Cantidad').focus();
        }
    }

    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Cantidad="+Cantidad+"&IdMedicina="+IdMedicina+"&Dosis="+Dosis+"&Bandera="+Bandera+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedico="+IdMedico+"&IdArea="+IdArea+"&Satisfecha="+Satisfecha+"&Fecha="+Fecha,true);
    ajax.send(null);
    return false;

}

/*
function Lotes(IdReceta,IdArea){
	var Fecha=document.getElementById('Fecha').value;
	var ajax = xmlhttp();
	ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						//En proceso hacer nada
					}
				if(ajax.readyState==4){
						//Cuando Termine hacer nada
					}
			}

ajax.open("GET","IncludeFiles/ColocacionLotes.php?IdReceta="+IdReceta+"&IdArea="+IdArea+"&Fecha="+Fecha,true);
		ajax.send(null);
		return false;
}//IdReceta
*/


function CancelarReceta(){
    var IdReceta = document.getElementById('IdRecetaValor').value;	
    var IdHistorialClinico = document.getElementById('IdHistorialClinico').value;
    var A = document.getElementById('MedicinaNueva');
    var B = document.getElementById('MedicinaNuevaRepetitiva');
    var IdArea=document.getElementById('IdArea').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var ajax = xmlhttp();
	
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
					
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            alert('La Receta y sus Medicinas han sido Canceladas');
            A.innerHTML="";
            B.innerHTML="";
            //document.getElementById('Expediente').value="";
            document.getElementById('AddReceta').disabled=false;
            document.getElementById('Cancelar').disabled=true;
            document.getElementById('Cantidad').disabled=true;
            //document.getElementById('Busqueda').disabled=true;
            document.getElementById('Agregar').disabled=true;
            document.getElementById('Finalizar').disabled=true;
            document.getElementById('Dosis').disabled=true;
            document.getElementById("NombreMedicina").disabled=true;
            document.getElementById('Cantidad').value='';
            document.getElementById('IdMedicina').value='';
            document.getElementById('NombreMedicina').value='';
            document.getElementById('Dosis').value='-';
            document.getElementById('RecetaNumero').value='';
            document.getElementById('CorrelativoAnual').value='';
            document.getElementById('Insatisfecha').disabled=true;
            document.getElementById('Insatisfecha').checked=false;
            document.getElementById('ActualizacionArea').innerHTML='';
            document.getElementById('ActualizacionMedico').innerHTML='';
            document.getElementById('ActualizacionEspecialidad').innerHTML='';

            document.getElementById('Expediente').value='';
            document.getElementById('Expediente').focus();

            document.getElementById('ContadorRecetas').innerHTML=ajax.responseText;
					
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdHistorialClinico="+IdHistorialClinico+"&IdReceta="+IdReceta+"&IdArea="+IdArea+"&IdPersonal="+IdPersonal+"&Bandera=4",true);
    ajax.send(null);
    return false;

}//CancelarReceta


function EliminaMedicina(IdMedicinaRecetada){
    var ajax = xmlhttp();
    var B=document.getElementById('MedicinaNueva');
    var C=document.getElementById('MedicinaNuevaRepetitiva');
    var IdArea=document.getElementById('IdArea').value;
    IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    IdReceta=document.getElementById('IdRecetaValor').value;
    var IdPersonal=document.getElementById('IdPersonal').value;

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //B.innerHTML="GUARDANDO RECETA ...";						
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var datos = ajax.responseText.split('<br>');
            B.innerHTML = datos[0];
            C.innerHTML = datos[1];
            document.getElementById('ContadorRecetas').innerHTML=datos[2];
        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&IdArea="+IdArea+"&IdPersonal="+IdPersonal+"&Bandera=7",true);
    ajax.send(null);
    return false;
}//EliminarMedicamento



function ActualizaDosis(IdMedicinaRecetada,NuevaDosis){
    var ajax = xmlhttp();
    var B=document.getElementById('MedicinaNueva');
    var C=document.getElementById('MedicinaNuevaRepetitiva');
    IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    IdReceta=document.getElementById('IdRecetaValor').value;
    var IdArea=document.getElementById('IdArea').value;

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //B.innerHTML="GUARDANDO RECETA ...";						
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var datos = ajax.responseText.split('<br>');
            B.innerHTML = datos[0];
            C.innerHTML = datos[1];
        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&NuevaDosis="+NuevaDosis+"&IdArea="+IdArea+"&Bandera=8",true);
    ajax.send(null);
    return false;
}//EliminarMedicamento


function ActualizaCantidad(IdMedicinaRecetada,Cantidad){
    var ajax = xmlhttp();
    var B=document.getElementById('MedicinaNueva');
    var C=document.getElementById('MedicinaNuevaRepetitiva');
    var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var IdArea=document.getElementById('IdArea').value;
		
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //B.innerHTML="GUARDANDO RECETA ...";						
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var datos = ajax.responseText.split('<br>');
            B.innerHTML = datos[0];
            C.innerHTML = datos[1];
        //Lotes(IdReceta,IdArea);
        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdHistorialClinico="+IdHistorialClinico+"&IdMedicinaRecetada="+IdMedicinaRecetada+"&Cantidad="+Cantidad+"&IdArea="+IdArea+"&Bandera=9",true);
    ajax.send(null);
    return false;
}//EliminarMedicamento


function FinalizarReceta(){
    var ajax = xmlhttp();
    var IdReceta=document.getElementById('IdRecetaValor').value;
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        //Nada
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta = ajax.responseText;
            //alert(Respuesta);
            if(Respuesta=='NO'){
                alert('No se ha instroducido ningun medicamento \n y no se puede finalizar el proceso !');
                document.getElementById('Cantidad').value='';
            }else{
                FinalizarReceta2();	
							
            }
						
						
        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&Bandera=15",true);
    ajax.send(null);
    return false;
}//Finalizar Receta verificacion de recetas



function FinalizarReceta2(){
    var IdReceta = document.getElementById('IdRecetaValor').value;
    var ajax = xmlhttp();
    var B = document.getElementById('MedicinaNueva');
    var IdArea=document.getElementById('IdArea').value;

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            B.innerHTML="<div align='center'><img src='../images/cargando.gif' /></div>";						
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            document.getElementById('RecetaNumero').value='';
            //Existencias(IdReceta,IdArea);
            //Temporalmente el siguiente codigo
            B.innerHTML="";
            alert('Receta introducida satisfactoriamente !');
            //B.innerHTML="IdReceta ="+IdReceta+" IdArea="+IdArea+" Resp="+ajax.responseText;
					
            //document.getElementById('Expediente').value="";
            document.getElementById('AddReceta').disabled=false;
            document.getElementById('Cancelar').disabled=true;
            document.getElementById('Cantidad').disabled=true;
            document.getElementById('Cantidad').value='';
            //document.getElementById('Busqueda').disabled=true;
            document.getElementById('Agregar').disabled=true;
            document.getElementById('Finalizar').disabled=true;
            document.getElementById('Dosis').disabled=true;
            document.getElementById("NombreMedicina").disabled=true;
            document.getElementById('Insatisfecha').disabled=false;
            document.getElementById('Expediente').value='';	
            document.getElementById('Expediente').focus();	
				
        /**************************************/


        }
    }
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdReceta="+IdReceta+"&IdArea="+IdArea+"&Bandera=6",true);
    ajax.send(null);
    return false;
}

/* Datos de la Receta */

function Existencias(IdReceta,IdArea){
    var B = document.getElementById('MedicinaNueva');
    var Fecha=document.getElementById('Fecha').value;
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*En proceso hacer nada*/					
            B.innerHTML="<div align='center'><img src='../images/cargando.gif' /></div>";
        }
        if(ajax.readyState==4){
            B.innerHTML="";
            alert('Receta introducida satisfactoriamente !');
            //B.innerHTML="IdReceta ="+IdReceta+" IdArea="+IdArea+" Resp="+ajax.responseText;
					
            //document.getElementById('Expediente').value="";
            document.getElementById('AddReceta').disabled=false;
            document.getElementById('Cancelar').disabled=true;
            document.getElementById('Cantidad').disabled=true;
            //document.getElementById('Busqueda').disabled=true;
            document.getElementById('Agregar').disabled=true;
            document.getElementById('Finalizar').disabled=true;
            document.getElementById('Dosis').disabled=true;
            document.getElementById("NombreMedicina").disabled=true;
            document.getElementById('Insatisfecha').disabled=false;
            document.getElementById('CodigoFarmacia').focus();
					
        }
    }

    ajax.open("GET","IncludeFiles/MantenimientoExistencias.php?IdReceta="+IdReceta+"&IdArea="+IdArea+"&Fecha="+Fecha,true);
    ajax.send(null);
    return false;
}//Mantenimiento de Existencia


function CambioEstado(IdMedicinaRecetada,IdMedicina){
    var Nombre = 'Insa'+IdMedicinaRecetada;
    var Check = document.getElementById(Nombre);
    var IdArea=document.getElementById('IdArea').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        /*En proceso hacer nada*/					
        }
        if(ajax.readyState==4){
        //Lotes(IdReceta,IdArea);
											
        }
    }

    if(Check.checked==true){
        ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedicinaRecetada="+IdMedicinaRecetada+"&IdMedicina="+IdMedicina+"&Estado=I"+"&Bandera=10",true);
        ajax.send(null);
        return false;
    }else{
        ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdMedicinaRecetada="+IdMedicinaRecetada+"&IdMedicina="+IdMedicina+"&Estado=S"+"&Bandera=10",true);
        ajax.send(null);
        return false;
    }
	
}//CambioEstado


//******************** AREA ORIGEN **********************
function CargarAreaOrigen(IdArea){
    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
        /*NOTHING*/		
        }
        if(ajax.readyState==4){
            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }
			
            document.getElementById('ComboOrigen').innerHTML = ajax.responseText;
			
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?Bandera=17&IdArea="+IdArea,true);
    ajax.send(null);
    return false;

}

function CorregirAreaOrigen(){
    var AreaOrigen = document.getElementById('IdAreaOrigen').value;
    var IdReceta=document.getElementById('IdRecetaValor').value;
    var IdArea=document.getElementById('IdArea').value;

    if(AreaOrigen==0){
        AreaOrigen=IdArea;
    }

    //Datos a ingresar
    var ajax = xmlhttp();

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('ActualizacionAreaOrigen').innerHTML="REALIZANDO CAMBIO ...";
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../signIn.php';
            }

            var Respuesta=ajax.responseText;
            if(Respuesta=='N'){
                document.getElementById('ActualizacionAreaOrigen').innerHTML="ERROR AL REALIZAR CAMBIOS";
            }else{
                document.getElementById('ActualizacionAreaOrigen').innerHTML="CAMBIO REALIZADO !";
            }
										
        }
    }
		
    ajax.open("GET","IncludeFiles/IntroduccionRecetasProceso.php?IdAreaOrigen="+AreaOrigen+"&IdReceta="+IdReceta+"&Bandera=18",true);
    ajax.send(null);
    return false;
}

//*********************************************************

