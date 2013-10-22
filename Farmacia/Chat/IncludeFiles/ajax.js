function ajaxFunction(){
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

//Manejo de Teclado
var nav4 = window.Event ? true : false;
function acceptNum(evt,Obj){	
    var key = nav4 ? evt.which : evt.keyCode;	
    //alert(key);
    if(document.getElementById(Obj).value==""){
        Leidos();
    }
    if(Obj=='comentario' && key==13){
        fajax();
    }else{
        return(key);
    }
//return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
}

//******************

function fajax()
{
    var comentario; 
    comentario = document.getElementById('comentario').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    var ajax;
    ajax = ajaxFunction();
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState==4)
        {
            //alert(ajax.responseText);
	
            document.getElementById('comentario').value="";
            document.getElementById('comentario').focus();
            fajax3();
        }
    }
    ajax.open("GET","ProcesoChat.php?Enviar=si&comentario="+comentario+"&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);


}

function fajaxClear()
{
    var comentario; 
    comentario = document.getElementById('comentario').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    var ajax;
    ajax = ajaxFunction();
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState==4)
        {
            //alert(ajax.responseText);
            document.getElementById('chat').innerHTML="";
            document.getElementById('comentario').value="";
            document.getElementById('comentario').focus();
            fajax2();
        }
    }
    ajax.open("GET","ProcesoChat.php?Borrar=si&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);


}

function Leidos()
{
    var comentario; 
    comentario = document.getElementById('comentario').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    var ajax;
    ajax = ajaxFunction();
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState==4)
        {
            // fajax2();
            Nuevos();
        }
    }
    ajax.open("GET","ProcesoChat.php?Leido=si&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);


}

function fajax2()
{
    var ajax;
    ajax = ajaxFunction();
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState==4)
        {
            //alert(ajax.responseText);
            document.getElementById('chat').innerHTML=ajax.responseText;
		
            scrolli();
            Recargar();
		
        }
    }
    ajax.open("GET","ProcesoChat.php?Leer=si&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);
} 

function fajax3()
{
    var ajax;
    ajax = ajaxFunction();
    var hashviejo;
    hashviejo=document.getElementById('id_hash').value;
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState==4){
            if(hashviejo!=ajax.responseText && ajax.responseText!='vacio')
            {
                document.getElementById('id_hash').value=ajax.responseText;
                document.title="Mensaje Nuevo!";
                fajax2();		   
            }else{
             
                document.title='MiniChat :)';
            }        
        }
    }
    ajax.open("GET","ProcesoChat.php?Hash=si&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);
}

function scrolli() {
    //var i = 0
    //var speed = 1
    //i = i + speed
    var div = document.getElementById("chat");
    // div.scrollTop=div.scrollTop+30;
    div.scrollTop = div.scrollHeight;
//if (i < div.scrollHeight - 160) {i = 0}
//  t1=setTimeout("scrolli()",5000)
}

function Recargar(){
    setInterval("fajax3()",5000);
}

function RecargarNuevos(){
    setInterval("Nuevos()",5000);
}


function Nuevos(){
    var ajax;
    ajax = ajaxFunction();
    var IdPersonal=document.getElementById('IdPersonal').value;
    var IdPersonalD=document.getElementById('IdPersonalD').value;
    ajax.onreadystatechange=function(){
        if(ajax.readyState==4){
            //alert(ajax.responseText);
            var Respuesta=ajax.responseText;
            if(Respuesta=='S'){
                document.title="Mensaje Nuevo!";
            }else{
                document.title='MiniChat :)';
            //RecargarNuevos();
            }	
        }
    }
    ajax.open("GET","ProcesoChat.php?Nuevos=si&IdPersonal="+IdPersonal+"&IdPersonalD="+IdPersonalD,true);
    ajax.send(null);
}


