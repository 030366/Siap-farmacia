function Inint_AJAX() {
   try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
   try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
   try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
   alert("XMLHttpRequest not supported");
   return null;
};

//ADMINISTRACION DE FARMACIA 

function CargarGrupoTerapeuticos(){
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************

               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
		    if(req.responseText=='NO_GRUPO'){
			alert('Debe habilitar una Area valida para utilizar esta configuracion!');
			window.location="../Configuracion/HabilitaAreaLaboratorio.php";
		    }else{
		        document.getElementById("GruposTerapeuticos").innerHTML=req.responseText;
		        document.getElementById("Cargar").innerHTML="";
		    }
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=91");
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);

}

function CargarCatFarmacia(){
    var req = Inint_AJAX();
	var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
	var Nombre = "";
		document.getElementById('Nombre').value="";
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************

			
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
		    if(req.responseText=='NO_FARMA'){
			document.getElementById("Farmacos").innerHTML="NO HAY MEDICAMENTO VALIDO PARA EL GRUPO SELECCIONADO!";
			document.getElementById("Cargar").innerHTML="";
		    }else{
		        document.getElementById("Farmacos").innerHTML=req.responseText;
		        document.getElementById("Cargar").innerHTML="";
		    }
		}
               
          }
     };

req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=92&IdGrupoTerapeutico="+IdGrupoTerapeutico+"&Nombre="+Nombre);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);

}


function CargarCatFarmacia2(){
    var req = Inint_AJAX();
	var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
	var Nombre = trim(document.getElementById('Nombre').value,'Nombre');
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************

			
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
		    if(req.responseText=='NO_FARMA'){
			document.getElementById("Farmacos").innerHTML="NO HAY MEDICAMENTO VALIDO PARA EL GRUPO SELECCIONADO!";
			document.getElementById("Cargar").innerHTML="";
		    }else{
		        document.getElementById("Farmacos").innerHTML=req.responseText;
		        document.getElementById("Cargar").innerHTML="";
		    }
		}
               
          }
     };

req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=92&IdGrupoTerapeutico="+IdGrupoTerapeutico+"&Nombre="+Nombre);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);

}


function HabilitarMedicamento(IdMedicina,IdEstablecimiento){
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************

               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
		    CargarCatFarmacia(IdGrupoTerapeutico);
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=93&IdMedicina="+IdMedicina+"&IdEstablecimiento="+IdEstablecimiento);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}

function DeshabilitarMedicamento(IdMedicina,IdEstablecimiento){
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************
 
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
		    CargarCatFarmacia(IdGrupoTerapeutico);
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=94&IdMedicina="+IdMedicina+"&IdEstablecimiento="+IdEstablecimiento);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}


function Estupefaciente(IdMedicina,IdEstablecimiento,Estado){
    var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
               document.getElementById("Cargar").innerHTML="<strong>CARGANDO INFORMACION</strong><br><img src='../imagenes/barra.gif' alt='Cargando...'>";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************
 
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
		    CargarCatFarmacia(IdGrupoTerapeutico);
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=9&SubOpcion=95&IdMedicina="+IdMedicina+"&IdEstablecimiento="+IdEstablecimiento+"&Estado="+Estado);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);
}


function AsginarDivisor(id,valor){
	var tmp = id.split('Divisor');
	   var ids=tmp[1];

	
       
if(document.getElementById(ids).checked==true){

	 var req = Inint_AJAX();
    req.onreadystatechange = function () {
 	if (req.readyState==1) {
	
//                document.getElementById(id).value="";
          }

          if (req.readyState==4) {
			//ERROR DE SESSION
			var Err=req.responseText.split('ERROR_SESSION');
			if(Err.length != 1){ window.location='../autenticacion/index.php?Error=1'}
			//*******************************
 
               if(req.responseText=='NO'){
		     alert('Problemas con la conexion a la Base de Datos!');
		     document.getElementById("Cargar").innerHTML="";
		}else{
			var IdGrupoTerapeutico=document.getElementById('IdGrupoTerapeutico').value;
		    
		}
               
          }
     };
req.open("GET", "IncludeFiles/Procesos.php?Bandera=10&IdMedicina="+ids+"&Divisor="+valor);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1");
req.send(null);

}else{
   if(valor!=''){
   alert('Debe habilitar el medicamento para ingresar esta informacion!');
   document.getElementById(ids).focus();
   }
}


}


//******************************************************************