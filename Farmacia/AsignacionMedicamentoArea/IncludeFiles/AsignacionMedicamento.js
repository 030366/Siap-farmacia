function xmlhttp(){
		var xmlhttp;
		try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
			try{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch(e){
				try{xmlhttp = new XMLHttpRequest();}
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

function Inint_AJAX() {
   try {return new ActiveXObject("Msxml2.XMLHTTP");} catch(e) {} //IE
   try {return new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {} //IE
   try {return new XMLHttpRequest();} catch(e) {} //Native Javascript
   alert("XMLHttpRequest not supported");
   return null;
};

var nav4 = window.Event ? true : false;
function acceptNum(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, 46 = '.'
var key = nav4 ? evt.which : evt.keyCode;	
return ((key < 13) || (key >= 48 && key <= 57) || key==46);
}

function acceptNum2(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
var key = nav4 ? evt.which : evt.keyCode;
return ((key < 13) || (key >= 48 && key <= 57 ||(key==46)));
}



function SeleccionaTodo(){

   var checkbox=document.getElementsByName('checkeo');
   var IdArea= document.getElementById('IdArea').value;
   var Tope=checkbox.length;
   var Salida=false;
	//alert(Tope);
   for(var i=0; i<Tope; i++){
   
   if(checkbox[i].checked==false){  
	var IdMedicina = checkbox[i].value;
     	var req = Inint_AJAX();
	//alert(IdMedicina);
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
		checkbox[i].checked=true;
        }

          if (req.readyState==4) {
		//ERROR DE SESSION
		var Err=req.responseText.split('ERROR_SESSION');
		if(Err.length != 1){window.location='../Principal/index.php?Error=1'}
		//*******************************
//                checkbox[i].checked=true;
		if(i==Tope){
		   var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
    			CargarCatFarmacia(IdGrupoTerapeutico);
		}
// 
// 		  if(Salida==true){
//     			
//   		  }

          }
     };

	req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=93&IdMedicina="+IdMedicina+"&IdArea="+IdArea);
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
	req.send(null); 
	
	
    }//Si esta no esta checkeado

  }//Ciclo for
}



function Inicio(){
	
	var ajax = xmlhttp();
	var ComboFarmacia = document.getElementById('ComboFarmacia');
	var ComboTerapeutico = document.getElementById('ComboGrupoTerapeutico');
	
	
	    ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		   //En Proceso
		}
		if(ajax.readyState==4){
		    //B.innerHTML = "";
		    var Respuesta = ajax.responseText.split('-');
		    ComboFarmacia.innerHTML=Respuesta[0];
		    ComboTerapeutico.innerHTML=Respuesta[1];
		}
	    }
			
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=1",true);
    ajax.send(null);
    return false;

}//inicio

function CargarAreas(IdFarmacia){
	
	var ajax = xmlhttp();
	var ComboArea = document.getElementById('ComboArea');	
	
	    ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
		   //En Proceso
		}
		if(ajax.readyState==4){
		    //B.innerHTML = "";
		    var Respuesta = ajax.responseText;
		    ComboArea.innerHTML=Respuesta;
			document.getElementById('IdGrupoTerapeutico').disabled=true;
			document.getElementById('IdGrupoTerapeutico')[0].selected=true;
			document.getElementById('Farmacos').innerHTML="";
		    
		}
	    }
			
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=2&IdFarmacia="+IdFarmacia,true);
    ajax.send(null);
    return false;

}//inicio

function CargarCatFarmacia(IdGrupoTerapeutico){
    var IdArea=document.getElementById('IdArea').value;
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
              // document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){window.location='../Principal/index.php?Error=1'}
			//*******************************

			
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		    // document.getElementById("Cargar").innerHTML="";
		}else{
		    if(req.responseText=='NO_FARMA'){
			document.getElementById("Farmacos").innerHTML="NO HAY MEDICAMENTO VALIDO PARA EL GRUPO SELECCIONADO!";
			//document.getElementById("Cargar").innerHTML="";
		    }else{
		        document.getElementById("Farmacos").innerHTML=req.responseText;
		       // document.getElementById("Cargar").innerHTML="";
		    }
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=92&IdGrupoTerapeutico="+IdGrupoTerapeutico+"&IdArea="+IdArea);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);

}


function HabilitarMedicamento(IdMedicina){
    var req = Inint_AJAX();
	var IdArea= document.getElementById('IdArea').value;
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
             //  document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){window.location='../Principal/index.php?Error=1'}
			//*******************************

               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		    // document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;

			//alert(req.responseText);
		    CargarCatFarmacia(IdGrupoTerapeutico);
		}
               
          }
     };



req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=93&IdMedicina="+IdMedicina+"&IdArea="+IdArea);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}

function HabilitarCombo(){
	var combo=document.getElementById('IdGrupoTerapeutico');
        combo.disabled=false;
        combo.selectedIndex=0;
        document.getElementById("Farmacos").innerHTML="";
        
}

function DeshabilitarMedicamento(IdMedicina,IdEstablecimiento){
	var IdArea= document.getElementById('IdArea').value;
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
              // document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){window.location='../Principal/index.php?Error=1'}
			//*******************************
 
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     //document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
		    CargarCatFarmacia(IdGrupoTerapeutico);
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=94&IdMedicina="+IdMedicina+"&IdArea="+IdArea);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}


function AreaDespacha(IdMedicina,Accion){
	var Despacha = "Despacha"+IdMedicina;
	var DespachaOld = "Old"+IdMedicina
	var IdArea=document.getElementById('IdArea').value;
	var IdAreaDispensada= document.getElementById(Despacha).value;
	var IdAreaOld=document.getElementById(DespachaOld).value;
    var req = Inint_AJAX();

//alert(IdAreaOld);

if(IdAreaDispensada==0 && Accion!='E'){
 alert('Seleccione una area valida!');
   document.getElementById(Despacha).focus();

}else{
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               //document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){window.location='../Principal/index.php?Error=1'}
			//*******************************
 
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		    // document.getElementById("Cargar").innerHTML="";
		}else{
			var IdTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
			//alert(req.responseText);
			CargarCatFarmacia(IdTerapeutico);
		}
               
          }
     };

}

req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=95&IdMedicina="+IdMedicina+"&IdArea="+IdArea+"&IdAreaDispensada="+IdAreaDispensada+"&IdAreaOld="+IdAreaOld+"&Accion="+Accion);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}

