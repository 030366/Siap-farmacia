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


// function trim(str,Obj){
// 	//str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
// 	str = str.replace(/^\s+|\s+$/g, "");
// 
// 	if(str==''){document.getElementById(Obj).value=str;}
// 	return(str);
// }//trim

function trim(str,Obj){
    str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
    if(str==''){
        document.getElementById(Obj).value=str;
    }
    return(str);
}//trim



function PegarPermisos(nivel){

    document.getElementById('administracion').disabled=false;
    document.getElementById('reportes').disabled=false;
    document.getElementById('datos').disabled=false;

    switch(nivel){
        case '2':
            document.getElementById('administracion').checked=true;
            document.getElementById('reportes').checked=true;
            document.getElementById('datos').checked=true;
            document.getElementById('farmacia')[0].selected=true;
            document.getElementById('farmacia').disabled=true;
            document.getElementById('area')[0].selected=true;
            document.getElementById('area').disabled=true;
            break
        case '3':
            document.getElementById('administracion').checked=false;
            document.getElementById('reportes').checked=false;
            document.getElementById('datos').checked=false;
            document.getElementById('administracion').disabled=true;
            document.getElementById('reportes').disabled=true;
            document.getElementById('datos').disabled=true;
		
            document.getElementById('farmacia').disabled=false;
            document.getElementById('area').disabled=false;
            break
        case '4':
            document.getElementById('administracion').checked=false;
            document.getElementById('reportes').checked=true;
            document.getElementById('datos').checked=true;
            document.getElementById('farmacia')[1].selected=true;
            document.getElementById('farmacia').disabled=true;
            CargarAreas(1);
		
		
            break
        case '5':
            document.getElementById('administracion').checked=true;
            document.getElementById('reportes').checked=true;
            document.getElementById('datos').checked=true;
            document.getElementById('farmacia').disabled=true;
           
            var tope = document.getElementById("farmacia").length;
            for($i=0;$i < tope; $i++){
                if(document.getElementById('farmacia')[$i].value==4){
                    document.getElementById('farmacia')[$i].selected=true;
                    break;
                }
               
            }
            
            CargarAreas(4);
            break
        default:
            document.getElementById('administracion').checked=false;
            document.getElementById('reportes').checked=false;
            document.getElementById('datos').checked=false;
            document.getElementById('farmacia').disabled=false;
            document.getElementById('area').disabled=false;
            break
    }
}




function VerificaUsuarios(){

    var usuario=document.getElementById('usuario').value;
    var A = document.getElementById('Progreso');


    Texto=usuario.toLowerCase();
    var texto_=Texto.split(' ');
    var Tope = texto_.length;
    var Tope2=Texto.length;
    var i = 0;
    var texto2="";
    for(i; i<Tope; i++){
        texto2+=texto_[i];
    }

    document.getElementById("usuario").value=texto2;


    if(texto2==''){
        A.innerHTML="";
    }else{

        var ajax = xmlhttp();
        ajax.onreadystatechange=function(){
            if(ajax.readyState==1){
                A.innerHTML = "<img src='../imagenes/loading.gif'>";
            }
            if(ajax.readyState==4){
			
                if(ajax.responseText=="ERROR_SESSION"){
                    alert("La sesion ha cadudo \n vuelva a iniciar sesion!");
                    window.location="../signIn.php";
                    return false;
                }
			
                if(ajax.responseText=="SI"){
                    document.getElementById('add').disabled=true;
                    if(Tope2 <= 4){
                        A.innerHTML = "<img src='../imagenes/NO.png'> Debe ser mayor de 4 caracteres";
                    }else{
                        A.innerHTML = "<img src='../imagenes/NO.png'>";
                    }
                }else{
                    document.getElementById('add').disabled=false;
                    if(Tope2 <= 4){
                        A.innerHTML = "<img src='../imagenes/SI.png'> Debe ser mayor de 4 caracteres";
                    }else{
                        A.innerHTML = "<img src='../imagenes/SI.png'>";
                    }
                }
			
			
            }
        }
		
        ajax.open("GET","envioUsr.php?Bandera=1&usuario="+usuario,true);
        ajax.send(null);
        return false;
    }
}//Verifuca Usuarios

function CargarAreas(IdFarmacia){
    var ajax = xmlhttp();

    var Nivel = document.getElementById('nivel').value;

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
		   
        }
        if(ajax.readyState==4){

            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha cadudo \n vuelva a iniciar sesion!");
                window.location="../signIn.php";
                return false;
            }

            document.getElementById('ComboAreas').innerHTML=ajax.responseText;

            if(Nivel==4 || Nivel==5){
                var TopeCombo=document.getElementById('area').length;
                for(var i=0; i<TopeCombo;i++){
                    if(document.getElementById('area')[i].value==7 && IdFarmacia==1){
                        document.getElementById('area')[i].selected=true;
                    }
                    if(document.getElementById('area')[i].value==12 && IdFarmacia==4){
                        document.getElementById('area')[i].selected=true;
                    }
			
                }
                document.getElementById('area').disabled=true;
            }
		   
        }
    }
		
    ajax.open("GET","envioUsr.php?Bandera=2&IdFarmacia="+IdFarmacia+"&Nivel="+Nivel,true);
    ajax.send(null);
    return false;

}

function Validar(){ 
    var Ok=true;
    var nombre = trim(document.getElementById("NombreEmpleado").value,"NombreEmpleado");
    var usuario = document.getElementById("usuario").value;
    var nivel = document.getElementById("nivel").value;
    var IdFarmacia = document.getElementById("farmacia").value;
    var IdArea=document.getElementById("area").value;

    var Tamano = usuario.length;

    if(Tamano < 5){
        alert('El usuario debe ser mayor de 4 caracteres!');
        document.getElementById("usuario").focus();
        document.getElementById("usuario").select();
        Ok=false;
    }

    if(usuario=='' && Ok==true){
        alert('El usuario no puede ser vacio!');
        document.getElementById('usuario').focus();
        Ok=false;
    }

    if(nombre=='' && Ok==true){
        alert('El nombre del usuario no puede ser vacio!');
        document.getElementById('NombreEmpleado').focus();
        Ok=false;
	
    }

    if(nivel=='0' && Ok==true){
        alert('Seleccione el nivel del usuario!');
        document.getElementById('nivel').focus();
        Ok=false;
	
    }
    if(nivel=='3' && IdFarmacia=='0' && Ok==true){
        alert('Seleccione la farmacia donde estara este recurso!');
        document.getElementById('farmacia').focus();
        Ok=false;
	
    }

    if(nivel=='3' && IdFarmacia!='0' && IdArea=='0' && Ok==true){
        alert('Seleccione el area de farmacia donde estara este recurso!');
        document.getElementById('area').focus();
        Ok=false;
    }


    if(Ok==true){
        GuardarUsuario();
    }


}


function GuardarUsuario(){
    var nombre = document.getElementById("NombreEmpleado").value;
    var usuario = document.getElementById("usuario").value;
    var nivel = document.getElementById("nivel").value;
    var IdFarmacia = document.getElementById("farmacia").value;
    var IdArea=document.getElementById("area").value;
    var administracion = 0;
    var reportes = 0;
    var datos = 0;
    var pass = document.getElementById('pass').value;

    if(document.getElementById('administracion').checked==true){
        administracion = 1;
    }

    if(document.getElementById('reportes').checked==true){
        reportes = 1;
    }


    if(document.getElementById('datos').checked==true){
        datos = 1;
    }


    var ajax = xmlhttp();
    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            document.getElementById('Progreso2').innerHTML="<img src='../imagenes/barra.gif'>";
        }
        if(ajax.readyState==4){
			
            if(ajax.responseText=="ERROR_SESSION"){
                alert("La sesion ha cadudo \n vuelva a iniciar sesion!");
                window.location="../signIn.php";
                return false;
            }

            document.getElementById('Progreso2').innerHTML="Usuario Guardado! <img src='../imagenes/SI.png'>";
			
            document.getElementById("NombreEmpleado").value="";
            document.getElementById("usuario").value="";
            document.getElementById("nivel")[0].selected=true;
            document.getElementById("farmacia")[0].selected=true;
            document.getElementById("area")[0].selected=true;
            document.getElementById('Progreso').innerHTML="";
            document.getElementById('administracion').checked=false;
            document.getElementById('reportes').checked=false;
            document.getElementById('datos').checked=false;
        }
    }
		
    ajax.open("GET","envioUsr.php?Bandera=3&usuario="+usuario+"&pass="+pass+"&nombre="+nombre+"&nivel="+nivel+"&IdFarmacia="+IdFarmacia+"&IdArea="+IdArea+"&administracion="+administracion+"&reportes="+reportes+"&datos="+datos,true);
    ajax.send(null);
    return false;

}



