<?php
class encabezado{
		function top($NombreDeFarmacia,$tipoUsuario,$nick,$nombre){
		if($NombreDeFarmacia==1){
			$NombreDeFarmacia="Central";
		}elseif($NombreDeFarmacia==2){
			$NombreDeFarmacia="Consulta Externa";
		}elseif($NombreDeFarmacia==3){$NombreDeFarmacia="Emergencia"; }
			else{
				$NombreDeFarmacia="Control Global";
		}
		echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
		<strong>Farmacia:</strong>&nbsp;&nbsp; $NombreDeFarmacia";
		}//funcion top
}//fin de clase
 
/*CONEXION A LA DB*/
class conexion{
function conectar()
{
	mysql_connect("localhost","farma","farm4");
	mysql_select_db("siap");
}

function desconectar()
{
	mysql_close();
}
}//fin de la clase conexion
/********************/

class queries{
//FECHAS ATRAS (3 DIAS HABILES)

function ComboGrupoTerapeutico(){
	$query="select IdTerapeutico, GrupoTerapeutico from mnt_grupoterapeutico where GrupoTerapeutico <>'--'";
	$resp=mysql_query($query);
	return($resp);
}



function Atras(){
$selectNombreFecha="select dayname(curdate()) as NombreFechaActual";
$NombreDiaActual = mysql_query($selectNombreFecha);
$rowNombre=mysql_fetch_array($NombreDiaActual);
$NombreFecha=$rowNombre["NombreFechaActual"];
switch($NombreFecha){
		case "Monday":
		$querySelect="select adddate(curdate(), interval -5 day) as FechaAtras";//Dia Lunes
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAtras=$rowFechaA["FechaAtras"];
		break;
		
		case "Tuesday":
		$querySelect="select adddate(curdate(), interval -4 day) as FechaAtras";//Dia martes
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAtras=$rowFechaA["FechaAtras"];
		break;
		
		default:
		$querySelect="select adddate(curdate(), interval -3 day) as FechaAtras";//los demas dias de la semana
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAtras=$rowFechaA["FechaAtras"];
		break;
}//fin switch
return($FechaAtras);
}//atras

function Adelante(){
$selectNombreFecha="select dayname(curdate()) as NombreFechaActual";
$NombreDiaActual = mysql_query($selectNombreFecha);
$rowNombre=mysql_fetch_array($NombreDiaActual);
$NombreFecha=$rowNombre["NombreFechaActual"];
switch($NombreFecha){
		case "Friday":
		$querySelect="select adddate(curdate(), interval 4 day) as FechaAdelante";//Dia Lunes
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAdelante=$rowFechaA["FechaAdelante"];
		break;
		
		case "Thursday":
		$querySelect="select adddate(curdate(), interval 4 day) as FechaAdelante";//Dia martes
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAdelante=$rowFechaA["FechaAdelante"];
		break;
		
		default:
		$querySelect="select adddate(curdate(), interval 2 day) as FechaAdelante";//los demas dias de la semana
		$dates = mysql_query($querySelect);
		$rowFechaA=mysql_fetch_array($dates);
		$FechaAdelante=$rowFechaA["FechaAdelante"];
		break;
}//fin switch
return($FechaAdelante);
}//Adelante



function vacaciones($FechaAtras,$FechaAdelante){
    $query="SELECT min( FechaIni ) AS inicio, max( FechaFin ) AS fin
            FROM cit_eventos
            WHERE IdEmpleado = 'Todos'
            AND (FechaIni BETWEEN '$FechaAtras' AND '$FechaAdelante' OR FechaFin BETWEEN '$FechaAtras' AND '$FechaAdelante')";
    
    $resp=mysql_fetch_array(mysql_query($query));
    return $resp;
}



/**Establece nuevo precio para Medicina en catalogoproductos**/
function EstableceNuevoPrecio($NuevoPrecio, $IdMedicina){
$queryUpdate="update farm_catalogoproductos set PrecioActual='$NuevoPrecio' where IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);
}//fin de NuevoPrecio

//*********************Obtencion de Datos de Receta
function NombreEmpleado($IdEmpleado){
$querySelect="select mnt_empleados.NombreEmpleado
			from mnt_empleados
			where mnt_empleados.IdEmpleado='$IdEmpleado'";
$resp=mysql_fetch_array(mysql_query($querySelect));
return($resp[0]);
}


function ObtenerDatosPacienteReceta($bandera,$IdReceta,$IdArea){
$FechaAtras=queries::Atras();//Obtiene 3 fechas atras del dia de accion (vida util de recetas en dado caso no son entregadas)
$FechaAdelante = queries::Adelante();

$vacaciones=queries::vacaciones($FechaAtras, $FechaAdelante);
$complemento="";
if($vacaciones[0] != null and $vacaciones[0]!=""){
    $complemento=" OR farm_recetas.Fecha between '".$vacaciones["inicio"]."' and '".$vacaciones["fin"]."'";
}


switch($bandera){
case 1:
/*TODAS LAS RECETAS A MOSTRAR*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%i:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%m-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
where (farm_recetas.IdEstado='R' or farm_recetas.IdEstado='P') 
and farm_recetas.Fecha = curdate()
and year(farm_recetas.Fecha)=year(curdate())
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";

break;
case 15:
    $IdRecetaQ="";
    if($IdReceta!=0){
        $IdRecetaQ=" and farm_recetas.IdReceta='$IdReceta' ";
        
    }
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%i:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%m-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
where (farm_recetas.IdEstado='L' or farm_recetas.IdEstado='RL') 
and (farm_recetas.Fecha between '".$FechaAtras."' and '".$FechaAdelante."' ".$complemento.")
and year(farm_recetas.Fecha)=year(curdate())
".$IdRecetaQ."
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.IdEstado, farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";

break; 
default:
/*PARA LA IMPRESION DE VINETAS*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%m:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%i-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio

where (farm_recetas.IdEstado='R' or farm_recetas.IdEstado='P') 
and farm_recetas.Fecha = curdate()
and farm_recetas.IdReceta='$IdReceta'
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";
break;
}//fin de switch
//Para consulta del mes en where month(FechaConsulta)=month(curdate())...Para despues
$resp=mysql_query($querySelect);
return($resp);
}//ObtenerDatosPacienteReceta

function datosReceta($IdReceta,$IdArea){ 

$querySelect="select farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico, farm_catalogoproductos.Nombre as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica, farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis,farm_medicinarecetada.IdEstado,
farm_medicinarecetada.IdEstado as EstadoMedicina
from  farm_recetas
inner join farm_medicinarecetada
on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
inner join farm_catalogoproductos
on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
where farm_recetas.IdReceta='$IdReceta'";
$respuesta=mysql_query($querySelect);
return($respuesta);
}//fin de datosReceta

//Informacion de Recetas Listas
function VerificaEstadoReceta($IdReceta){
$querySelect="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
$resp=mysql_query($querySelect);
return($resp);
}//estados

function datosRecetaListas($IdReceta,$Ancla,$IdArea){
$FechaAtras=queries::Atras();
$FechaAdelante=queries::Adelante();
/* Determina que tipo de query se llevara a cabo, dependiendo del Ancla enviado
	construccion de query: */
	
	switch($Ancla){
	case 'L':
		$ancla="(farm_recetas.IdEstado='L' or farm_recetas.IdEstado='O') ";
	break;
	case 'RL':
		$ancla="(farm_recetas.IdEstado='RL' or farm_recetas.IdEstado='RO') ";
	break;
	default:
		$ancla="farm_recetas.IdEstado='$Ancla' ";
	break;
	}//fin switch
/*Fin Construccion de query*/

$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
sec_historial_clinico.FechaConsulta,farm_recetas.*, farm_catalogoproductos.NOMBRE as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica,year(mnt_datospaciente.FechaNacimiento)as year,
year(curdate()) as year2,farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_subespecialidad
on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
inner join farm_medicinarecetada
on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
inner join farm_catalogoproductos
on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
where $ancla 
and farm_recetas.IdReceta='$IdReceta' 
and (farm_recetas.Fecha between '$FechaAtras' and curdate() OR farm_recetas.Fecha between curdate() and '$FechaAdelante')
and year(farm_recetas.Fecha)=year(curdate())
and farm_recetas.IdArea='$IdArea'";
$respuesta=mysql_query($querySelect);
return($respuesta);
}//fin de datosRecetaListas


function datosRecetaListasTotal($IdReceta,$Ancla,$IdArea){
$FechaAtras=queries::Atras();
$FechaAdelante=queries::Adelante();
/* Determina que tipo de query se llevara a cabo, dependiendo del Ancla enviado
	construccion de query: */
	
	switch($Ancla){
	case 'L':
		$ancla="(farm_recetas.IdEstado='L' or farm_recetas.IdEstado='O') ";
	break;
	case 'RL':
		$ancla="(farm_recetas.IdEstado='RL' or farm_recetas.IdEstado='RO') ";
	break;
	default:
		$ancla="farm_recetas.IdEstado='$Ancla' ";
	break;
	}//fin switch
/*Fin Construccion de query*/

$querySelect="select distinct mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
sec_historial_clinico.FechaConsulta,farm_recetas.*, farm_catalogoproductos.NOMBRE as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica,farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis
from sec_historial_clinico
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_subespecialidad
on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
inner join farm_medicinarecetada
on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
inner join farm_catalogoproductos
on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
where $ancla 
and farm_recetas.IdReceta='$IdReceta' 
and (farm_recetas.Fecha between '$FechaAtras' and curdate() OR farm_recetas.Fecha between curdate() and '$FechaAdelante')
and year(farm_recetas.Fecha)=year(curdate())
and farm_recetas.IdArea='$IdArea'";
$respuesta=mysql_query($querySelect);
return($respuesta);
}//fin de datosRecetaListasTotal




//*******************Verifica que recetas con medicamneto en estado satisfecha
function verificaSatisfecha($IdMedicina,$IdReceta){
if ($IdReceta==0){
$querySelect="select * from farm_medicinarecetada where IdMedicina='$IdMedicina' and IdEstado='S'";
}else{
$querySelect="select * from farm_medicinarecetada where IdReceta='$IdReceta' and IdMedicina='$IdMedicina' and IdEstado='S'";
}
$resp=mysql_query($querySelect);
return($resp);
}//verificaSatisfechos

//*****************Verifica que recetas son insatisfechas
function verificaInsatisfecha($IdMedicina,$IdReceta){
$querySelect="select * from farm_medicinarecetada where IdReceta='$IdReceta' and IdMedicina='$IdMedicina' and IdEstado='I'";
$resp=mysql_query($querySelect);
return($resp);
}//verificaSatisfechos

/*************Verifica si existen datos introducidos de recetas satisfechas o no
 *************y si no existen las crea en las tablas farm_medicinarecetada actualizando el IdEstado*/
function InsertarDatosReceta($Medicina,$Receta,$IdHistorialClinico){
$IdMedicina=$Medicina;//IdMedicina
$IdReceta=$Receta;//IdReceta 
$respuesta=queries::verificaSatisfecha($IdMedicina,$IdReceta);
$respuesta2=queries::verificaInsatisfecha($IdMedicina,$IdReceta);
	if($row=mysql_fetch_array($respuesta)){ 
    $queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE() where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
    }
	elseif($row=mysql_fetch_array($respuesta2)){queries::ActualizaInfo($Medicina,$Receta,'S');/*actualiza el estado del medicamento*/}
	else{
	$queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE(), IdEstado='S' where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
    }//fin de ELSE
    mysql_query($queryUpdate);
	
}//fin de Insertar

//***********Verifica si existen datos introducidos en de recetas satisfechas o no
//***********y si no existen las crea en las tablas farm_insatisfechas
function InsertarDatosReceta2($Medicina,$Receta,$IdHistorialClinico){
$IdMedicina=$Medicina;
$IdReceta=$Receta;
$IdHistorialClinico=$IdHistorialClinico;
$respuesta=queries::verificaInsatisfecha($IdMedicina,$IdReceta);
$respuesta2=queries::verificaSatisfecha($IdMedicina,$IdReceta);

if($row=mysql_fetch_array($respuesta)){queries::ActualizaInfo($Medicina,$Receta,'I');}
	elseif($row=mysql_fetch_array($respuesta2)){
		$queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE() where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
		mysql_query($queryUpdate);
	}//elseIF
	else{
		$queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE(), IdEstado='I' where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
		mysql_query($queryUpdate);
	}//fin de ELSE
	

}//fin de Insertar2


/*Actualiza el estado de la bandera de Isatisfecha a Satisfecha 
  en dado caso el tecnico decida cambiar el estado.
*/
function ActualizaInfo($IdMedicina,$IdReceta,$Estado){
switch($Estado){
	case 'S':
			$queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE(), IdEstado='S' where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
	break;
	case 'I':
			$queryUpdate="update farm_medicinarecetada set FechaEntrega=CURDATE() where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'";
	break;
}//FinSwitch
mysql_query($queryUpdate);
}//ActualizaInfo



/*
Actualiza el estado de las recetas del Paciente dependiendo del tratamiento de la receta, puede ser:
R= Recetada (Estado por Default)..estado de Recetada viene del consultorio
P= En Proceso, Se esta buscando la medicina a ser entregada
L= Lista, es decir que la medicina ha sido buscada y puesta en cola de entrega
E= Entregada a Paciente
N= No Entregada
T= Medicamento entregado pero en dias posteriores (No mas de 3 dias habiles)
*/
function ActualizarEstadoRecetas($IdReceta, $Bandera,$IdArea){
$queryUpdate2='';
	switch($Bandera){
    case 1:
		$queryUpdate="update farm_recetas set IdEstado='P' where IdReceta='$IdReceta'";
    break;
	case 2:
		$querySelect="select * from farm_recetas
					  inner join farm_medicinarecetada
					  on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					  where farm_medicinarecetada.IdEstado='S' and farm_recetas.IdReceta='$IdReceta'";
		$querySelectTipo="select * from farm_recetas
					  inner join farm_medicinarecetada
					  on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					  where farm_medicinarecetada.IdEstado='S' and farm_recetas.IdReceta='$IdReceta'
					  and farm_recetas.IdEstado='RP'";
						$resp=mysql_query($querySelect);//verificacion de datos
						$resp2=mysql_query($querySelectTipo);
if($row=mysql_fetch_array($resp)){
		if($row2=mysql_fetch_array($resp2)){
		$queryUpdate="update farm_recetas set IdEstado='RL' where IdReceta='$IdReceta'";
		}else{
		$queryUpdate="update farm_recetas set IdEstado='L' where IdReceta='$IdReceta'";
		}
}else{//Bandera Temporal para aviso de inexistencias de todos los medicamentos de la receta " O "
	$queryTipo="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
	$row2=mysql_fetch_array(mysql_query($queryTipo));
		if($row2[0]=='RP'){
			$queryUpdate="update farm_recetas set IdEstado='RL' where IdReceta='$IdReceta'";
		}else{
			$queryUpdate="update farm_recetas set IdEstado='L' where IdReceta='$IdReceta'";}
		}//RL
	break;
    case 3:
    //LISTA A ENTREGAR
			$queryTipo="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
		$row2=mysql_fetch_array(mysql_query($queryTipo));
		if($row2[0]=='RL'){
			if($IdArea==1){
		$queryUpdate="update farm_recetas set IdEstado='ER' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";	
			}else{
		$queryUpdate="update farm_recetas set IdEstado='ER' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}
		}else{
			if($IdArea==1){			
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}else{
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}
		}
	break;
    case 5://ENTREGA TARDE 
		$queryTipo="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
		$row2=mysql_fetch_array(mysql_query($queryTipo));
		if($row2[0]=='RN'){
			if($IdArea==1){
		$queryUpdate="update farm_recetas set IdEstado='ER' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}else{
		$queryUpdate="update farm_recetas set IdEstado='ER' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}
		}else{
			if($IdArea==1){
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}else{
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		$queryUpdate2="update farm_medicinarecetada set FechaEntrega=curdate() where IdReceta='$IdReceta'";
			}
		}
	break;
    case 6://receta en la cual todos los medicamentos fueron insatisfechos " X "
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
    break;
	case 7:
		$queryUpdate="update farm_recetas set IdEstado='RP' where IdReceta='$IdReceta'";
    break;      
	default://NO ENTREGADA
		$queryTipo="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
		$row2=mysql_fetch_array(mysql_query($queryTipo));
		if($row2[0]=='RL'){
			$queryUpdate="update farm_recetas set IdEstado='RN' where IdReceta='$IdReceta'";
		}else{
		$queryUpdate="update farm_recetas set IdEstado='N' where IdReceta='$IdReceta'";
		}
    break;
	case 9:
		$queryTipo="select IdEstado from farm_recetas where IdReceta='$IdReceta'";
		$row2=mysql_fetch_array(mysql_query($queryTipo));
		if($row2[0]=='RL'){
			$queryUpdate="update farm_recetas set IdEstado='RP' where IdReceta='$IdReceta'";
		}else{
		$queryUpdate="update farm_recetas set IdEstado='P' where IdReceta='$IdReceta'";
		}
	
	break;
	}//fin swtich
mysql_query($queryUpdate);
			if($queryUpdate2!=''){
				mysql_query($queryUpdate2);//actualiza la fecha de dispensada
			}

}//ActualizaEstadoRecetas

/*UTILIZADA EN INTERFAZ DE DIGITADORES PARA EL ACOPLAMIENTO DE LA FECHA DE INTRODUCCION TARDIA*/
function ActualizaFechaEntregaMedicina($IdReceta,$Fecha){
	$queryUpdate="update farm_medicinarecetada set FechaEntrega='$Fecha' where IdReceta='$IdReceta'";
	$resp=mysql_query($queryUpdate);
}//ActualizaFechaEntregaMedicina


/******funcion que inserta el IdPersonal de tabla farm_usuarios a tabla farm_recetas
 llevando un control de quien hace valida la preparacion de una receta en especifico
*******/
function PersonalEncargado($IdPersonal,$IdReceta){
$queryUpdate="update farm_recetas set IdPersonal='$IdPersonal' where IdReceta='$IdReceta'";
mysql_query($queryUpdate);
}//PersonalEncargado

//*******************CONTROL DE EXISTENCIAS
function MedicinaExistencias($IdMedicina,$Cantidad,$bandera,$IdArea,$Lote, $IdEstablecimiento, $IdModalidad){
/* AQUI LA CANTIDAD ES OBTENIDA POR LA DISTINCION DE LOTES Y OBTENCION DE SUS EXISTENCIAS PARA EQUILIBRIOS 
	LOTE = IDLOTE
*/
	if($bandera=="SI"){ 
        switch($IdArea){
case 2:
$selectQuery="select farm_medicinaexistenciaxarea.Existencia
			from farm_medicinaexistenciaxarea 
			inner join mnt_areamedicina
			on mnt_areamedicina.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
			inner join mnt_areafarmacia
			on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
			where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina' 
			and mnt_areafarmacia.IdArea='$IdArea'
			and farm_lotes.IdLote='$Lote'
                        and farm_medicinaexistenciaxarea.IdEstablecimiento=$IdEstablecimiento
                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad";
break;
default:
$selectQuery="select farm_medicinaexistenciaxarea.Existencia
                        from farm_medicinaexistenciaxarea 
                        inner join mnt_areamedicina
                        on mnt_areamedicina.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
                        inner join mnt_areafarmacia
                        on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
                        where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina' 
			and mnt_areamedicina.Dispensada='$IdArea'
			and farm_lotes.IdLote='$Lote'
                        and farm_medicinaexistenciaxarea.IdEstablecimiento=$IdEstablecimiento
                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad";
break;
            }  
            
$resp=mysql_query($selectQuery);
$row=mysql_fetch_array($resp);
$existencia_old=$row["Existencia"];
			if($existencia_old<=0){/* DO NOTHING */}else{
$existencia_new=$existencia_old-$Cantidad;
			if($existencia_new <=0){$existencia_new=0;}
queries::UpdateExistenciaArea($IdMedicina,$existencia_new,$Cantidad,$IdArea,$Lote,$IdEstablecimiento,$IdModalidad);//actualizacion de Existencia farm_existenciasxarea
			}//existencia <=0
		}//fin de bandera == "SI"
}//fin MedicinaExistencias

//**********************actualizacion de existencias en catalogo
function UpdateExistenciaArea($IdMedicina,$existencia_new,$cantidad,$IdArea,$Lote,$IdEstablecimiento,$IdModalidad){
    if($IdArea==1){$IdArea=2;}
$queryUpdateMedicinaxarea="update farm_medicinaexistenciaxarea set Existencia='$existencia_new' 
                           where IdMedicina='$IdMedicina' and IdArea='$IdArea' 
                           and IdLote='$Lote' and IdEstablecimiento=$IdEstablecimiento
                           and IdModalidad=$IdModalidad";//IdArea es de medicinaxexistencia no modificar
mysql_query($queryUpdateMedicinaxarea);
}//UpdateExistenciaCatalogo

function ObtenerLotes($IdMedicina,$IdReceta,$IdArea,$Bandera,$IdSubEspecialidad,$IdEmpleado,$FechaInicio,$FechaFin){
/* UTILIZADO EN REPORTE POR ESPECIALIDADES */
$Estado='';$Estado2='';$Estado3='';$Estado4='';$Estado5='';$Estado6='';$Estado7='';$Estado8='';

if($FechaInicio!=''){
	  $Estado8="and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'";
}
    switch($Bandera){
        case 1:
			  $Estado3="farm_recetas.IdReceta='$IdReceta' and";
              $Estado="'L'";
        break;
        case 2:
			  $Estado3="farm_recetas.IdReceta='$IdReceta' and";
              $Estado="'RL'";
        break;
		case 3:
			  $Estado3="farm_recetas.IdReceta='$IdReceta' and";
			  $Estado="'N'";
		break;
		case 4:
			  $Estado3="farm_recetas.IdReceta='$IdReceta' and";
			  $Estado="'RN'";
		break;
		case 5:
    		  $Estado="'E' OR farm_recetas.IdEstado='ER'"; 
			  $Estado2="and farm_medicinarecetada.IdEstado='S'";
			  $Estado7="order by Lote2 desc";
		break;
		case 6:
			  $Estado="'ER'";
		break;
		case 7:
		/********* REPORTE POR SUBESPECIALIDADES **********/
			  if($IdEmpleado!='0'){
			  $Estado6="and mnt_empleados.IdEmpleado='$IdEmpleado'";
			  }
		      $Estado="'E' OR farm_recetas.IdEstado='ER'"; 
			  $Estado2="and farm_medicinarecetada.IdEstado='S'";

			  $Estado4="inner join sec_historial_clinico
						on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
						inner join mnt_empleados
						on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
						inner join mnt_subespecialidad
						on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad";
			  $Estado5="and mnt_subespecialidad.IdSubEspecialidad='$IdSubEspecialidad'";
		/**************************************************/
		break;
		case 8:
			  $Estado3="farm_recetas.IdReceta='$IdReceta' and";
              $Estado="'TT'";
		break;
    }
	$querySelect="select farm_medicinarecetada.Lote1,farm_medicinarecetada.Lote2,
				farm_medicinarecetada.CantidadLote1,farm_medicinarecetada.CantidadLote2
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
                $Estado4
				where $Estado3
				farm_recetas.IdArea='$IdArea'
                and (farm_recetas.IdEstado=$Estado)
				$Estado2
                $Estado5
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				$Estado6
				$Estado8
								
				and farm_medicinarecetada.CantidadLote1 is not NULL
				and farm_medicinarecetada.Lote1 is not NULL
				
				$Estado7";
	$resp=mysql_query($querySelect);
	return($resp);
}//Obtener Lotes Utilizados

	function ObtenerConsumosMedicamentoLote($IdMedicina,$IdArea,$FechaInicio,$FechaFin){
	/* FUNCION UTILIZADA EN REPORTE DE CONSUMO DE MEDCAMENTOS */
	if($IdArea!='0'){
		$Area="and farm_recetas.IdArea='$IdArea'";
	}else{
		$Area="";
	}
	
		$querySelect="select farm_catalogoproductos.IdMedicina,
					sum(farm_medicinarecetada.CantidadLote1) as TotalLote1,farm_medicinarecetada.Lote1,
					sum(farm_medicinarecetada.CantidadLote2)as TotalLote2, farm_medicinarecetada.Lote2
					from farm_medicinarecetada
					inner join farm_recetas
					on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
				
				where farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER' or farm_recetas.IdEstado='O' or farm_recetas.IdEstado='RO')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
					$Area
				and farm_medicinarecetada.CantidadLote1 is not NULL
				and farm_medicinarecetada.Lote1 is not null
				
				group by farm_medicinarecetada.IdMedicina, farm_medicinarecetada.Lote1,farm_medicinarecetada.Lote2";	
		$resp=mysql_query($querySelect);
		return($resp);
	}//fin de funcion



function CodigoLote($IdLote){
	$querySelect="select Lote from farm_lotes where IdLote='$IdLote'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}


function ObtenerPrecioLote($Lote){
	$querySelect="select farm_lotes.PrecioLote
					from farm_lotes
					where farm_lotes.IdLote='$Lote'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerPrecioLote

function ObtenerExistenciaTotal($IdMedicina,$IdArea){
	$querySelect="select sum(farm_medicinaexistenciaxarea.Existencia) as TotalExistencia
				from farm_medicinaexistenciaxarea
				inner join farm_lotes
				on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
				where farm_medicinaexistenciaxarea.Existencia <> 0
				and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
				and farm_medicinaexistenciaxarea.IdArea='$IdArea'
				group by farm_medicinaexistenciaxarea.IdMedicina asc";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerExistenciaTotal

function ObtenerLotesExistencias($IdMedicina,$IdArea){
	$querySelect="select farm_medicinaexistenciaxarea.Existencia,year(farm_lotes.FechaVencimiento)as ano,
				monthName(farm_lotes.FechaVencimiento) as mes,farm_lotes.*
				from farm_medicinaexistenciaxarea
				inner join farm_lotes
				on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
				where farm_medicinaexistenciaxarea.Existencia <> 0
				and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
				and farm_medicinaexistenciaxarea.IdArea='$IdArea'
				order by farm_lotes.FechaVencimiento asc";
	$resp=mysql_query($querySelect);
	return($resp);
}//Obtener Lotes Existencias

function ObtenerLotesExistenciasTotal($IdMedicina,$Bandera){
    switch($Bandera){
    case 1:
        $querySelect="select farm_unidadmedidas.UnidadesContenidas, year(farm_lotes.FechaVencimiento)as ano,
                    monthName(farm_lotes.FechaVencimiento) as mes,farm_lotes.*
                    from farm_entregamedicamento
                    inner join farm_lotes
                    on farm_lotes.IdLote=farm_entregamedicamento.IdLote
                    inner join farm_catalogoproductos
                    on farm_catalogoproductos.IdMedicina=farm_entregamedicamento.IdMedicina
                    inner join farm_unidadmedidas
                    on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
                    where farm_entregamedicamento.Existencia <> 0
                    and farm_entregamedicamento.IdMedicina='$IdMedicina'
                    order by farm_lotes.FechaVencimiento asc";
    break;
    }
    $resp=mysql_query($querySelect);
    return($resp);
}//Obtener Lotes Existencias

function ExistenciaLotesTotal($IdLote,$Bandera){
    switch($Bandera){
        case 1:
            $querySelect="select Existencia
                        from farm_entregamedicamento
                        where IdLote='$IdLote'";    
        break;
        
        case 2:
            $querySelect=" select Existencia
                        from farm_medicinaexistenciaxarea
                        where IdLote='$IdLote'";
        break;
    }
    $resp=mysql_fetch_array(mysql_query($querySelect));
    return($resp[0]);
     
}//ExistenciaLotesTotal


function Transferencias($IdMedicina,$ConsumoTotal,$ExistenciaTotal){
$datos=array();
	$querySelectFecha="select FechaVencimiento
				from farm_lotes
				inner join farm_medicinaexistenciaxarea
				on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
				where Existencia <> '0'
                and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
				order by farm_lotes.FechaVencimiento desc
				limit 1";
	$respFecha=mysql_fetch_array(mysql_query($querySelectFecha));
	$FechaVencimiento=$respFecha[0];
	$querySelect="select month('$FechaVencimiento')-month(curdate()) as meses,year('$FechaVencimiento')-year(curdate()) as ano";
	$queryNecesidad="select sum(farm_medicinarecetada.Cantidad)
					from farm_medicinarecetada
					inner join farm_recetas
					on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
					where farm_medicinarecetada.IdMedicina='$IdMedicina'
					and farm_recetas.IdEstado='RE'
					and left(farm_recetas.Fecha,7) between left(curdate(),7) and left('$FechaVencimiento',7)";
	$respNecesidad=mysql_fetch_array(mysql_query($queryNecesidad));
	$resp=mysql_fetch_array(mysql_query($querySelect));
	if($resp[1]==0){
		$ConsumoAproximado=$ConsumoTotal*$resp[0];//consumo aproximado a la ultima fecha de vencimiento
		$ConsumoAproximado=$ConsumoAproximado+$respNecesidad[0];//Tomando en cuenta las recetas repetitivas entre ese periodo
		$Tranferencia=$ExistenciaTotal-$ConsumoAproximado;
        $CoberturaEstimada=$resp[0];
		$datos[0]=$ConsumoAproximado;
		$datos[1]=$Tranferencia;
		$datos[2]=$CoberturaEstimada;
	}else{
		if($resp[0]<0){
			$mes=$resp[0]*-1;
			$datos[0]=$ConsumoTotal*$mes;
		}else{
			$datos[0]=$ConsumoTotal*$resp[0];
		}
		
		
		$datos[1]='--';
		
		
		if($resp[0]<0){
			$mes=$resp[0]*-1;
			$datos[2]=$mes;
		}else{
			$datos[2]=$resp[0];
		}
	}
	return($datos);
}//Transferencias 


function TransferenciasTotales($IdMedicina,$ConsumoTotal,$ExistenciaTotal){
$datos=array();
    $querySelectFecha="select FechaVencimiento
                from farm_lotes
                inner join farm_entregamedicamento
                on farm_entregamedicamento.IdLote=farm_lotes.IdLote
                where Existencia <> '0'
                and farm_entregamedicamento.IdMedicina='$IdMedicina'
                order by farm_lotes.FechaVencimiento desc
                limit 1";
    $respFecha=mysql_fetch_array(mysql_query($querySelectFecha));
    $FechaVencimiento=$respFecha[0];
    $querySelect="select month('$FechaVencimiento')-month(curdate()) as meses,year('$FechaVencimiento')-year(curdate()) as ano";
    $queryNecesidad="select sum(farm_medicinarecetada.Cantidad)
                    from farm_medicinarecetada
                    inner join farm_recetas
                    on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
                    where farm_medicinarecetada.IdMedicina='$IdMedicina'
                    and farm_recetas.IdEstado='RE'
                    and left(farm_recetas.Fecha,7) between left(curdate(),7) and left('$FechaVencimiento',7)";
    
    $resp=mysql_fetch_array(mysql_query($querySelect));
    $respNecesidad=mysql_fetch_array(mysql_query($queryNecesidad));
    
    if($resp[1]==0){
        $ConsumoAproximado=$ConsumoTotal*$resp[0];//consumo aproximado a la ultima fecha de vencimiento
        $ConsumoAproximado=$ConsumoAproximado+$respNecesidad[0];//Tomando en cuenta las recetas repetitivas entre ese periodo
        $Tranferencia=$ExistenciaTotal-$ConsumoAproximado;
        $CoberturaEstimada=$resp[0];
        $datos[0]=$ConsumoAproximado;
        $datos[1]=$Tranferencia;
        $datos[2]=$CoberturaEstimada;
    }else{
        if($resp[0]<0){
            $mes=$resp[0]*-1;
            $ConsumoAproximado=$ConsumoTotal*$mes;
        }else{
            $ConsumoAproximado=$ConsumoTotal*$resp[0];
            
        }     
        
            $ConsumoAproximado=$ConsumoAproximado+$respNecesidad[0];
            
        $datos[0]=$ConsumoAproximado;
        $Transferencia=$ExistenciaTotal-$ConsumoAproximado;
        if($Transferencia <= 0){
            $datos[1]='--';
        }else{
            $datos[1]=$Transferencia;
        }
        
        
        
        if($resp[0]<0){
            $mes=$resp[0]*-1;
            $datos[2]=$mes;
        }else{
            $datos[2]=$resp[0];
        }
    }
    return($datos);
}//Transferencias Totales


//**********FIN CONTROL DE EXISTENCIAS

//*************DATOS MEDICINA***************
function ObtenerDatosMedicina($IdMedicina){
$queryMedicina="select farm_catalogoproductos.*,mnt_grupoterapeutico.GrupoTerapeutico as terapeutico
				from farm_catalogoproductos 
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
				where farm_catalogoproductos.IdMedicina='$IdMedicina'";

$info=mysql_query($queryMedicina);

return($info);
}//fin DatosMedicina

function ObtenerDatosMedicina2($IdMedicina,$IdArea){
$queryMedicina="select farm_catalogoproductos.*,mnt_grupoterapeutico.GrupoTerapeutico as terapeutico,
				farm_medicinaexistenciaxarea.*,farm_lotes.*, mnt_areafarmacia.IdArea
				from farm_catalogoproductos 
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
				inner join farm_medicinaexistenciaxarea
				on farm_medicinaexistenciaxarea.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join mnt_areafarmacia
				on mnt_areafarmacia.IdArea=farm_medicinaexistenciaxarea.IdArea
				inner join farm_lotes
				on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina' and farm_medicinaexistenciaxarea.IdArea='$IdArea'";

$info=mysql_query($queryMedicina);

return($info);
}//fin DatosMedicina2
/************************	LOTES EXISTENCIAS	***********************************/
function LotesExistencias($IdMedicina,$IdEstablecimiento,$IdModalidad){
	$selectLotes="select farm_lotes.IdLote, Lote,  date_format(FechaVencimiento,'%d-%m-%Y') as FechaVencimientoH,IdMedicina,FechaVencimiento
				from farm_lotes
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdLote=farm_lotes.IdLote
				where Existencia <> '0'
				and IdMedicina='$IdMedicina'
                                and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
                                and farm_entregamedicamento.IdModalidad=$IdModalidad
				and left(FechaVencimiento,7) >= left(curdate(),7)
				order by FechaVencimiento asc";
	$resp=mysql_query($selectLotes);
		$row=mysql_fetch_array($resp);
	$data='	<select id="Lote'.$IdMedicina.'" name="Lote'.$IdMedicina.'" onChange="javascript:Existencias(this.value,'.$row[3].');">
			<option value="0">[Seleccione ...]</option>';
	if($row[1]!=NULL and $row[1]!=""){
	   do{
		$data.='<option value="'.$row[0].'">'.$row[1].' -> '.$row[2].'</option>';
	   }while($row=mysql_fetch_array($resp));
	}
	$data.='</select>';
	return($data);	
}


function LotesExistenciasVencidas($IdMedicina){
    
	$selectLotes="select farm_lotes.IdLote, Lote,  date_format(FechaVencimiento,'%d-%m-%Y') as FechaVencimientoH,IdMedicina,FechaVencimiento
				from farm_lotes
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdLote=farm_lotes.IdLote
				where IdMedicina='$IdMedicina'
				and left(FechaVencimiento,7) < left(curdate(),7)
				order by FechaVencimiento asc";


	$resp=mysql_query($selectLotes);
		$row=mysql_fetch_array($resp);
	$data='	<select id="Lote'.$IdMedicina.'" name="Lote'.$IdMedicina.'">
			<option value="0">[Seleccione ...]</option>';
	
	if($row[1]!=NULL and $row[1]!=""){
	   do{
		$data.='<option value="'.$row[0].'">'.$row[1].' -> '.$row[2].'</option>';
	   }while($row=mysql_fetch_array($resp));
	}
	$data.='</select>';
	return($data);	
}

/***********************************************************/

//*******aumento de existencias por Area
function AumentaExistencias($IdArea,$IdMedicina,$cantidad,$ventto,$Lote,$Precio,$IdEstablecimiento,$IdModalidad){
/* AQUI $Lote ES UNA CADENA QUE IDENTIFICA EL CODIGO DEL LOTE */
/*$respuesta=queries::ConfirmaExistencia($IdMedicina,$IdArea,$Lote);
	if($row=mysql_fetch_array($respuesta)){
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		queries::ActualizarExistencias($IdArea,$IdMedicina,$cantidad,$Lote);
	}else{*/
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		queries::IntroducirExistencias($IdArea,$IdMedicina,$cantidad,$ventto,$Lote,
                                               $Precio,$IdEstablecimiento,$IdModalidad);
	//}
}//AumentaExistencias

function ObtenerUnidadMedida($IdMedicina){
	$querySelect="select farm_unidadmedidas.UnidadesContenidas
				from farm_unidadmedidas
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerUnidadMedida

function ConfirmaExistencia($IdMedicina,$IdArea,$Lote){
if($Lote!='0'){
$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.IdLote
			from farm_medicinaexistenciaxarea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote 
			where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina' 
			and farm_medicinaexistenciaxarea.IdArea='$IdArea' 
			and farm_lotes.IdLote='$Lote'";
}else{
//$querySelect="select * from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea'";
}
$resp=mysql_query($querySelect);
return($resp);
}//confirma existencia

//****Actualizacion de existencias
function ActualizarExistencias($IdArea,$IdMedicina,$cantidad,$Lote){
	$resp=queries::ConfirmaExistencia($IdMedicina,$IdArea,$Lote);
	$row=mysql_fetch_array($resp);

	
	if($Lote!='0'){
		$SelectExistencia="select Existencia
						from farm_entregamedicamento
						inner join farm_lotes
						on farm_lotes.IdLote=farm_entregamedicamento.IdLote
						where farm_lotes.IdLote='$Lote'";
		$rowExistencia=mysql_fetch_array(mysql_query($SelectExistencia));
		
		if($rowExistencia[0] <= $cantidad){
			
			$cantidad_old=$row["Existencia"];
			$cantidad_new=$cantidad_old+$rowExistencia[0];
				$DiferenciaLotes=$cantidad-$rowExistencia[0];
	
			$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='$cantidad_new' 
						where IdMedicina='$IdMedicina' and IdArea='$IdArea' and IdLote='$Lote'";

			   $IdEntrega=mysql_fetch_array(mysql_query("select IdExistencia from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea' and IdLote='$Lote'"));
				$IdEntrega=$IdEntrega[0];
			$queryInsertExistencia2="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso) values('$IdMedicina','$IdArea','$rowExistencia[0]','$IdEntrega','$Lote',now())";
				mysql_query($queryInsertExistencia2);

			$UpdateExistencia1="update farm_entregamedicamento set Existencia='0' where IdLote=".$Lote;
					
			mysql_query($queryUpdate1);
			mysql_query($UpdateExistencia1);
			
			$SelectExistencia2="select Existencia, farm_lotes.IdLote
							from farm_entregamedicamento
							inner join farm_lotes
							on farm_lotes.IdLote=farm_entregamedicamento.IdLote
							where Existencia <> '0'
							and IdMedicina='$IdMedicina'
							order by FechaVencimiento
							limit 1";
			$rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2));
			
			if($rowExistencia2[0]!=NULL and $rowExistencia2[0]!='' and $rowExistencia2[0]!='0'){
				$IdLote2=$rowExistencia2[1];
				$Existencia_new2=$rowExistencia2[0]-$DiferenciaLotes;
				
				$Verificacion="select Existencia,farm_lotes.IdLote
							from farm_medicinaexistenciaxarea
							inner join farm_lotes
							on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
							where IdArea='$IdArea' and farm_lotes.IdLote='$IdLote2'
							and Existencia <> '0'";
				if($rowVerifica=mysql_fetch_array(mysql_query($Verificacion))){
					$ExistenciaFarmacia_new2=$rowVerifica[0]+$DiferenciaLotes;
					$ExistenciaFarmacia="update farm_medicinaexistenciaxarea set Existencia='$ExistenciaFarmacia_new2' where IdArea='$IdArea' and IdLote=".$IdLote2;
				
					$IdEntrega=mysql_fetch_array(mysql_query("select IdExistencia from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea' and IdLote='$IdLote2'"));
					$IdEntrega=$IdEntrega[0];

				$queryInsertExistencia2="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso) values('$IdMedicina','$IdArea','$DiferenciaLotes','$IdEntrega','$IdLote2',now())";
				mysql_query($queryInsertExistencia2);
				
				}else{				
					$ExistenciaFarmacia="insert into farm_medicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote) values('$IdMedicina','$IdArea','$DiferenciaLotes','$IdLote2')";

					$IdEntrega=mysql_fetch_array(mysql_query("select IdExistencia from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea' and IdLote='$Lote2'"));
					$IdEntrega=$IdEntrega[0];

				$queryInsertExistencia2="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso) values('$IdMedicina','$IdArea','$DiferenciaLotes','$IdEntrega','$IdLote2',now())";
				mysql_query($queryInsertExistencia2);

				}
				
				$UpdateExistencia2="update farm_entregamedicamento set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
				mysql_query($ExistenciaFarmacia);
				mysql_query($UpdateExistencia2);
				
			}
			
			
		}else{
			/*	ACTUALIZACION DE EXISTENCIAS CON. EXT. DE LOTE 1	*/
			$cantidad_old=$row["Existencia"];
			$cantidad_new=$cantidad_old+$cantidad;
				$Existencia_new1=$rowExistencia[0]-$cantidad;
			$UpdateExistenciaFarmacia="update farm_medicinaexistenciaxarea set Existencia='$cantidad_new' where IdArea='$IdArea' and IdLote=".$Lote;

				$IdEntrega=mysql_fetch_array(mysql_query("select IdExistencia from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea' and IdLote='$Lote'"));
				$IdEntrega=$IdEntrega[0];

			$queryInsertExistencia2="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso) values('$IdMedicina','$IdArea','$cantidad','$IdEntrega','$Lote',now())";
				mysql_query($queryInsertExistencia2);

			$UpdateExistencia1="update farm_entregamedicamento set Existencia='$Existencia_new1' where IdLote=".$Lote;
			
			mysql_query($UpdateExistenciaFarmacia);
			mysql_query($UpdateExistencia1);
			
		}//Si la cantidad es menor de la existencia del lote	
	
	}//Lote != 0

}//Actualizacion Existencias

//*****Introduccion de Existencias por area
function IntroducirExistencias($IdArea,$IdMedicina,$cantidad,$fecha,$Lote,$Precio,$IdEstablecimiento,$IdModalidad){
    if($fecha=='Fecha Ventto.'){
        $Query="select FechaVencimiento 
                from farm_lotes 
                where IdLote=".$Lote." and IdEstablecimiento=".$IdEstablecimiento."
                and IdModalidad=$IdModalidad";
        $resp=mysql_query($Query);
        if($row=mysql_fetch_array($resp)){
            $fecha=$row[0];
        }
        
    }    
    
	if($Lote!='0' and $fecha!='Fecha Ventto.'){
		$SelectExistencia="select Existencia
						from farm_entregamedicamento
						inner join farm_lotes
						on farm_lotes.IdLote=farm_entregamedicamento.IdLote
						where farm_lotes.IdLote='$Lote'
                                                and farm_entregamedicamento.IdEstablecimiento=".$IdEstablecimiento." 
                                                and farm_entregamedicamento.IdModalidad=$IdModalidad";
		$rowExistencia=mysql_fetch_array(mysql_query($SelectExistencia));
		
		if($rowExistencia[0] < $cantidad){
			/*	ACTUALIZACION DE EXISTENCIAS	*/
				$DiferenciaLotes=$cantidad-$rowExistencia[0];
			
			$CantidadLote1=$rowExistencia[0];
			
			
			$UpdateExistencia1="update farm_entregamedicamento set Existencia='0' 
                                            where IdLote=".$Lote." 
                                            and IdEstablecimiento=".$IdEstablecimiento." 
                                            and IdModalidad=$IdModalidad";
				mysql_query($UpdateExistencia1);

			$queryInsertExistencia1="insert into farm_medicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                                                                   values('$IdMedicina','$IdArea','$CantidadLote1','$Lote',$IdEstablecimiento,$IdModalidad)";
				mysql_query($queryInsertExistencia1);

				//Ultimo IdExistencia ingresado
				$IdEntrega=mysql_insert_id();

			$queryInsertExistencia2="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso,IdEstablecimiento,IdModalidad) 
                                                                                           values('$IdMedicina','$IdArea','$CantidadLote1','$IdEntrega','$Lote',now(),$IdEstablecimiento,$IdModalidad)";
				mysql_query($queryInsertExistencia2);
			/*	USO DEL SEGUNDO LOTE	*/
			$SelectLote="select Existencia, farm_lotes.IdLote
						from farm_entregamedicamento
						inner join farm_lotes
						on farm_lotes.IdLote=farm_entregamedicamento.IdLote
						where Existencia <> '0'
						and IdMedicina='$IdMedicina'
                                                and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
                                                and farm_entregamedicamento.IdEstablecimiento=$IdModalidad
						order by FechaVencimiento
						limit 1";
			$rowExistencia2=mysql_fetch_array(mysql_query($SelectLote));
			$Existencia_new2=$rowExistencia2[0]-$DiferenciaLotes;
			$IdLote2=$rowExistencia2[1];
			
			if($IdLote2!='' and $IdLote2!='0' and $IdLote2!=NULL){
			
				$queryInsertExistencia2="insert into farm_medicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                                                                           values('$IdMedicina','$IdArea','$DiferenciaLotes','$IdLote2',$IdEstablecimiento,$IdModalidad)";
					mysql_query($queryInsertExistencia2);

					//Ultimo IdExistencia ingresado
					$IdEntrega=mysql_insert_id();

				$queryInsertExistencia3="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso,IdEstablecimiento,IdModalidad) 
                                                                                                   values('$IdMedicina','$IdArea','$DiferenciaLotes','$IdEntrega','$IdLote2',now(),$IdEstablecimiento,$IdModalidad)";
					
					mysql_query($queryInsertExistencia3);
					
				$UpdateExistencia2="update farm_entregamedicamento set Existencia='$Existencia_new2' 
                                                    where  IdLote=".$IdLote2." 
                                                    and IdEstablecimiento=".$IdEstablecimiento." 
                                                    and IdModalidad=$IdModalidad";
					mysql_query($UpdateExistencia2);
			}
		}else{
			$Existencia_new1=$rowExistencia[0]-$cantidad;
			$UpdateExistencia1="update farm_entregamedicamento set Existencia='$Existencia_new1' 
                                            where IdLote=".$Lote." 
                                            and IdEstablecimiento=".$IdEstablecimiento."
                                            and IdModalidad=$IdModalidad";
				mysql_query($UpdateExistencia1);
			
			$queryInsertExistencia1="insert into farm_medicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                                                                   values('$IdMedicina','$IdArea','$cantidad','$Lote',$IdEstablecimiento,$IdModalidad)";
				mysql_query($queryInsertExistencia1);	

				//Ultimo IdExistencia ingresado
				$IdEntrega=mysql_insert_id();

			$queryInsertExistencia3="insert into farm_bitacoramedicinaexistenciaxarea(IdMedicina,IdArea,Existencia,IdExistenciaOrigen,IdLote,FechaHoraIngreso,IdEstablecimiento,IdModalidad) 
                                                                                           values('$IdMedicina','$IdArea','$cantidad','$IdEntrega','$Lote',now(),$IdEstablecimiento,$IdModalidad)";
				
				mysql_query($queryInsertExistencia3);
				
		}
		
	}//Lote != NULL

}//Introducir Existencia

//********Obtencion de Reporte por GrupoTerapeutico
function QueryExterna($grupoTerapeutico, $medicina, $IdArea,$FechaInicio,$FechaFin){
//******todos los grupos terapeuticos
if($grupoTerapeutico=='0' and $medicina==0){
$querySelect="select distinct mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
	where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')

	and farm_recetas.IdArea='$IdArea'
	and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'

	order by farm_catalogoproductos.IdMedicina";
}elseif($grupoTerapeutico!='0' and $medicina==0){
//******un grupoterapeutico especifico pero todas sus medicinas
$querySelect="select distinct mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
	and farm_recetas.IdArea='$IdArea'
	and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'
			order by farm_catalogoproductos.IdMedicina";
}else{
//******un grupoterapeutico especifico y una medicina especifica
$querySelect="select distinct mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$medicina' 
			and farm_recetas.IdArea='$IdArea' 
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')	
	and farm_recetas.IdArea='$IdArea'
	and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'
			order by farm_catalogoproductos.IdMedicina";
}

$resp=mysql_query($querySelect);
return($resp);
}//queryExterna


function QueryExterna2($grupoTerapeutico, $medicina){
//******todos los grupos terapeuticos
if($grupoTerapeutico==0 and $medicina==0){
$querySelect="select mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico";

}else{
//******un grupoterapeutico especifico y una medicina especifica
$querySelect="select mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			where farm_catalogoproductos.IdMedicina='$medicina'";
}
$resp=mysql_query($querySelect);
return($resp);
}//queryExterna2

//*****datos Filtrados
function ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdArea){
//**Query para un GrupoTerapeutico especifico y una Medicina Especifica
//Del Query Elimine mnt_medicinarecetada.Cantidad, a la par de farm_medicinarecetada.*,

$selectQuery="select distinct farm_catalogoproductos.Codigo,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
			farm_catalogoproductos.FormaFarmaceutica,farm_recetas.*,farm_medicinarecetada.*, 
			farm_catalogoproductos.PrecioActual,farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' and farm_medicinarecetada.IdMedicina='$IdMedicina' 
			and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER')
			and farm_recetas.IdArea='$IdArea' order by farm_catalogoproductos.IdMedicina";
$resp=mysql_query($selectQuery);
return($resp);
}//fin de ObtenerReporteGrupoTerapeutico


function ObtenerReporteExistencias($GrupoTerapeutico,$IdArea,$IdMedicina,$FechaInicio,$FechaFin){
//**Query para un GrupoTerapeutico especifico y una Medicina Especifica
//Del Query Elimine mnt_medicinarecetada.Cantidad, a la par de farm_medicinarecetada.*,
switch($IdMedicina){
case 0:
$selectQuery="select farm_catalogoproductos.IdMedicina,
			farm_catalogoproductos.Codigo,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
			farm_catalogoproductos.FormaFarmaceutica,
			farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_catalogoproductos
			inner join mnt_areamedicina
			on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			where mnt_areamedicina.IdArea='$IdArea' and farm_catalogoproductos.IdTerapeutico='$GrupoTerapeutico'
			group by farm_catalogoproductos.IdMedicina";
break;
default:
$selectQuery="select farm_catalogoproductos.IdMedicina,
			farm_catalogoproductos.Codigo,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
			farm_catalogoproductos.FormaFarmaceutica,
			farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_catalogoproductos
			inner join mnt_areamedicina
			on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			where mnt_areamedicina.IdArea='$IdArea' and farm_catalogoproductos.IdTerapeutico='$GrupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina'
			group by farm_catalogoproductos.IdMedicina";
break;
}
//
$resp=mysql_query($selectQuery);
//
return($resp);
}//fin de ObtenerReporteExistencias

//**** REPORTE DE CONSUMO POR ESPECIALIDAD
function ObtenerReporteEspecialidades($GrupoTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$IdMedico,$IdArea){
if($IdMedico=='0'){
$querySelect="select farm_medicinarecetada.IdMedicina,
			farm_recetas.*,farm_medicinarecetada.*
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
			inner join mnt_subespecialidad
			on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
			
			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER')
			and mnt_subespecialidad.IdSubEspecialidad='$IdSubEspecialidad' 
			and farm_recetas.IdArea='$IdArea' 
			order by farm_catalogoproductos.IdMedicina";

}elseif($IdMedicina=='0'){
$querySelect="select farm_medicinarecetada.IdMedicina,
			farm_recetas.*,farm_medicinarecetada.*
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
			
			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER')
			and mnt_empleados.IdEmpleado='$IdMedico' 
			and farm_recetas.IdArea='$IdArea' 
			order by farm_catalogoproductos.IdMedicina";
}else{
$querySelect="select distinct farm_medicinarecetada.IdMedicina,
			farm_recetas.*,farm_medicinarecetada.*
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
			
			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER')
			and mnt_empleados.IdEmpleado='$IdMedico' 
			and farm_medicinarecetada.IdMedicina='$IdMedicina'";
}

$resp=mysql_query($querySelect);
return($resp);
}//fin reporte especialidades


function ObtenerInfomacionMedicina($Medicina){
	$querySelect="select farm_catalogoproductos.Codigo, farm_catalogoproductos.Nombre, farm_catalogoproductos.Concentracion,Presentacion,
				farm_catalogoproductos.FormaFarmaceutica,farm_unidadmedidas.UnidadesContenidas as Divisor,
				farm_unidadmedidas.Descripcion
				
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina=".$Medicina;
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp);
}


//****
function NombreTera($grupoTerapeutico){
if($grupoTerapeutico==0){
$querySelect="select distinct mnt_grupoterapeutico.* from mnt_grupoterapeutico
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta

			where GrupoTerapeutico <> '--'
			and (farm_recetas.IdEstado<>'D')
			order by mnt_grupoterapeutico.IdTerapeutico";
}else{
$querySelect="select * from mnt_grupoterapeutico where IdTerapeutico='$grupoTerapeutico'";
}//else
//
$resp=mysql_query($querySelect);
//
return($resp);
}//nombreTera


function NombreTeraEspecialidad($IdSubEspecialidad){

$querySelect="select distinct mnt_grupoterapeutico.* from mnt_grupoterapeutico
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado

			where GrupoTerapeutico <> '--'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and mnt_empleados.IdSubEspecialidad='$IdSubEspecialidad'
			and year(farm_recetas.Fecha)=year(curdate())
			order by mnt_grupoterapeutico.IdTerapeutico";

//
$resp=mysql_query($querySelect);
//
return($resp);
}//nombreTeraEspecialidad


//Para Satisfechas
function ObtenerRecetasSatisfechas($IdReceta,$IdMedicina,$FechaInicio,$FechaFin,$IdArea,$Bandera,$IdMedico){
/*Bandera = IdSubEspeacialidad utilizado en reporte por especialidad*/
if($Bandera==0){
$querySelect="select distinct count(farm_recetas.IdReceta) as TotalSatisfechas 
			  from farm_medicinarecetada
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and farm_medicinarecetada.IdEstado='S'
			  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			  and farm_recetas.IdArea='$IdArea'
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";
}else{

	if($IdMedico=='0'){
$querySelect="select distinct count(farm_recetas.IdReceta) as TotalSatisfechas 
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
			inner join mnt_subespecialidad
			on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
		  where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_medicinarecetada.IdEstado='S'
		  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
		  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
		  and farm_recetas.IdArea='$IdArea'
		  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";
    }else{
$querySelect="select distinct count(farm_recetas.IdReceta) as TotalSatisfechas 
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			inner join mnt_empleados
			on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
			inner join mnt_subespecialidad
			on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
		  where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_medicinarecetada.IdEstado='S'
		  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
		  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
		  and farm_recetas.IdArea='$IdArea'
		  and mnt_empleados.IdEmpleado='$IdMedico' 
		  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";	
	}
}
$resp=mysql_fetch_array(mysql_query($querySelect));

return($resp[0]);
}//satisfechas


//Para Insatisfechas
function ObtenerRecetasInsatisfechas($IdReceta,$IdMedicina,$FechaInicio,$FechaFin,$IdArea,$Bandera,$IdMedico){
if($Bandera==0){
$querySelect="select distinct count(farm_recetas.IdReceta) as TotalInsatisfechas 
			  from farm_medicinarecetada 
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_medicinarecetada.IdEstado='I'
			  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
			  and farm_recetas.IdArea='$IdArea'
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";}
else{

	if($IdMedico=='0'){
		$querySelect="select distinct count(farm_recetas.IdReceta) as TotalInsatisfechas 
					from farm_recetas
					inner join farm_medicinarecetada
					on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
					inner join mnt_grupoterapeutico
					on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
					inner join sec_historial_clinico
					on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
					inner join mnt_empleados
					on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
					inner join mnt_subespecialidad
					on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
				  where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_medicinarecetada.IdEstado='I'
				  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
				  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
				  and farm_recetas.IdArea='$IdArea'
				  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";
    }else{
		$querySelect="select distinct count(farm_recetas.IdReceta) as TotalInsatisfechas 
					from farm_recetas
					inner join farm_medicinarecetada
					on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
					inner join mnt_grupoterapeutico
					on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
					inner join sec_historial_clinico
					on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
					inner join mnt_empleados
					on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
					inner join mnt_subespecialidad
					on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
				  where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_medicinarecetada.IdEstado='I'
				  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
				  and farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin' 
				  and farm_recetas.IdArea='$IdArea' 
				  and mnt_empleados.IdEmpleado='$IdMedico' 
				  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')";
		}//else IF medico
}

$resp=mysql_fetch_array(mysql_query($querySelect));

return($resp[0]);
}//Insatisfechas


function ObtenerTotalRecetas($IdMedicina,$FechaInicio,$FechaFin,$IdArea){
	$querySelect="select count(*)
				from farm_recetas
				inner join farm_medicinarecetada
				on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.IdArea='$IdArea'
				and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//Obtener Total Recetas



function ObtenerTotalRecetasEspecialidad($IdSubEspecialidad,$IdMedico,$IdMedicina,$FechaInicio,$FechaFin,$IdArea){
$estado1="";
if($IdMedico!=0){$estado1="and mnt_empleado.IdEmpleado='$IdMedico'";}
	$querySelect="select count(farm_recetas.IdReceta)
				from farm_recetas
				inner join farm_medicinarecetada
				on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				inner join mnt_empleados
				on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
				inner join mnt_subespecialidad
				on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad

				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.IdArea='$IdArea'
				and mnt_subespecialidad.IdSubEspecialidad='$IdSubEspecialidad'
				$estado1
				and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//Obtener Total Recetas





//*****FIN REPORTE

function MedicinaEntregada($IdMedicina,$IdArea,$fechaInicio,$fechaFin){
$querySelect="select sum(farm_medicinarecetada.Cantidad) as suma
			from farm_medicinarecetada
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where farm_medicinarecetada.IdMedicina='$IdMedicina' and farm_recetas.IdArea='$IdArea'
			and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER') 
			and farm_recetas.Fecha between '$fechaInicio' and '$fechaFin'
			and farm_medicinarecetada.IdEstado='S'";
$resp=mysql_fetch_array(mysql_query($querySelect));
return($resp[0]);
}

function MedicinaEntregadaTotal($IdMedicina,$fechaInicio,$fechaFin){
$querySelect="select sum(farm_medicinarecetada.Cantidad) as ConsumoTotal
            from farm_medicinarecetada
            inner join farm_recetas
            on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
            where farm_medicinarecetada.IdMedicina='$IdMedicina' 
            and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='ER') 
            and farm_medicinarecetada.FechaEntrega between '$fechaInicio' and '$fechaFin'
            and farm_medicinarecetada.IdEstado='S'";
$resp=mysql_fetch_array(mysql_query($querySelect));
return($resp[0]);
}//MedicinaEntregadaTotal


function AreaDeFarmacia($IdFarmacia){
$querySelect="select mnt_areafarmacia.IdArea
			  from mnt_areafarmacia
			  inner join mnt_farmacia
			  on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
			  where mnt_farmacia.IdFarmacia='$IdFarmacia'";
//
$resp=mysql_query($querySelect);
//
return($resp);
}//AreaDeFarmacia

function MedicinaPorGrupoTotal($IdTerapeutico){
$querySelect="select farm_catalogoproductos.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,
            farm_unidadmedidas.Descripcion
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
            inner join farm_unidadmedidas
            on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
			where mnt_grupoterapeutico.IdTerapeutico='$IdTerapeutico'
            and farm_catalogoproductos.IdEstado='H'";
$resp=mysql_query($querySelect);
return($resp);
}//MedicinaPorGrupoTotal

function MedicinaPorArea($IdMedicina,$Bandera){
    switch($Bandera){
        case 1:
$querySelect="select sum(farm_entregamedicamento.Existencia)as TotalExistencia
            from farm_catalogoproductos
            inner join farm_entregamedicamento
            on farm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
            inner join farm_lotes
            on farm_lotes.IdLote=farm_entregamedicamento.IdLote
            where farm_catalogoproductos.IdMedicina='$IdMedicina'
            group by farm_entregamedicamento.IdMedicina";
        break;
            
        case 2:
$querySelect="select sum(farm_medicinaexistenciaxarea.Existencia)as TotalExistencia
            from farm_catalogoproductos
            inner join farm_medicinaexistenciaxarea
            on farm_medicinaexistenciaxarea.IdMedicina=farm_catalogoproductos.IdMedicina
            inner join farm_lotes
            on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
            where farm_catalogoproductos.IdMedicina='$IdMedicina'
            group by farm_medicinaexistenciaxarea.IdMedicina";  
        break;
    } //fin de switch
            
$resp=mysql_fetch_array(mysql_query($querySelect));
return($resp[0]);
}//MedicinaPorArea


function NombreTecnico($IdReceta){
		  $querySelect="select farm_usuarios.IdPersonal,farm_usuarios.Nombre as NombreTecnico
						from farm_usuarios
						inner join farm_recetas
						on farm_recetas.IdPersonal=farm_usuarios.IdPersonal
						where farm_recetas.IdReceta = '$IdReceta'";
		  $resp=mysql_query($querySelect);
return($resp);
}//NombreTecnico



#PETICIONES DE MEDICAMENTOS

function ExistenciaAlmacen($IdMedicina){
	$datos="";
	$querySelect="select Existencia,Lote,date_format(FechaVencimiento,'%d-%m-%Y') as FechaVencimiento, UnidadesContenidas, Descripcion
				from alm_existencias
				inner join farm_lotes
				on farm_lotes.IdLote=alm_existencias.IdLote
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdMedicina=alm_existencias.IdMedicina
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where alm_existencias.IdMedicina=".$IdMedicina."
				order by FechaVencimiento";
	$resp=mysql_query($querySelect);
	$tmp=mysql_query($querySelect);
	if($t=mysql_fetch_array($tmp)){
		$datos="<table>
				<tr><td colspan='2'><hr></td></tr>";
		while($row=mysql_fetch_array($resp)){
			$datos.="<tr class='FONDO2'><td>Existencias=</td><td>&nbsp;&nbsp;".$row["Existencia"]/$row["UnidadesContenidas"]."&nbsp;".$row["Descripcion"]."</td></tr>";
			$datos.="<tr class='FONDO2'><td>Lote=</td><td>&nbsp;&nbsp;".$row["Lote"]."</td></tr>";
			$datos.="<tr class='FONDO2'><td>Fecha de Vencimiento=</td><td>&nbsp;&nbsp;".$row["FechaVencimiento"]."</td></tr>";
			$datos.="<tr><td colspan='2'><hr></td></tr>";
		}//while
		$datos.="</table>";
	}else{
		$datos="ESTE MEDICAMENTO NO PRESENTA EXISTENCIAS EN ALMACEN";
	}
	
	return($datos);
	
}


function IntroducirPeticionMedicamento($IdMedicina,$Cantidad,$IdPersonal){
	$queryInsert="insert into alm_pedidos (IdEstado,Fecha,IdUsuarioReg,FechaHoraReg) values('X',CURDATE(),'$IdPersonal',CURRENT_TIMESTAMP)";
	mysql_query($queryInsert);
		
	$querySelect="select IdPedido from alm_pedidos where Fecha=curdate() and IdUsuarioReg='$IdPersonal'";
	$IdPedido=mysql_fetch_array(mysql_query($querySelect));
	
	
	$queryInsertMedicina="insert into alm_detallepedido (IdPedido,Cantidad,IdMedicina) values('".$IdPedido[0]."','$Cantidad','$IdMedicina')";
	mysql_query($queryInsertMedicina);
	
	return($IdPedido[0]);
}


function IntroducirPeticionMedicamentos($IdPedido,$IdMedicina,$Cantidad){
	
	$queryInsertMedicina="insert into alm_detallepedido (IdPedido,Cantidad,IdMedicina) values('$IdPedido','$Cantidad','$IdMedicina')";
	mysql_query($queryInsertMedicina);
	
}//introduccion de medicamento al detalle




function DesplegarPeticion($IdUsuarioReg,$Externo){
	if($Externo==0){
	$querySelect2="select alm_detallepedido.IdMedicina,IdDetallePedido,Nombre,Concentracion,Cantidad,Descripcion,UnidadesContenidas
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				inner join alm_detallepedido
				on alm_detallepedido.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join alm_pedidos
				on alm_pedidos.IdPedido=alm_detallepedido.IdPedido
				where Fecha=curdate() and alm_pedidos.IdEstado='X' and IdUsuarioReg='$IdUsuarioReg'";	
	}else{
	$querySelect2="select alm_detallepedido.IdMedicina,Nombre,Concentracion,Cantidad,Descripcion,UnidadesContenidas
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				inner join alm_detallepedido
				on alm_detallepedido.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join alm_pedidos
				on alm_pedidos.IdPedido=alm_detallepedido.IdPedido
				where Fecha=curdate() and alm_pedidos.IdEstado='X' and IdUsuarioReg='$IdUsuarioReg'";
	
	
	}
				
	$resp2=mysql_query($querySelect2);
	$resp3=mysql_query($querySelect2);

	
	$datos="<table align='center' width='100%'>";
	if($Externo==1){
		$datos.="
			<tr><td align='right' colspan='6'>Fecha:&nbsp;&nbsp;&nbsp;<strong>".date('d/m/Y')."</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>HOSPITAL NACIONAL ROSALES</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>FARMACIA CENTRAL</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>LISTADO DE MEDICAMENTOS SOLICITADOS</strong><br><br></td></tr>";
		
	}
	
	
	$datos.="<tr class='FONDO2'><td align='center'><strong>No.</strong></td><td align='center'><strong>Medicamento</strong></td><td align='center'><strong>Unidad de Medida</strong></td><td align='center'><strong>Cantidad Solicitada</strong></td><td align='center'><strong>Cantidad Entregada</strong></td>";
	if($Externo==1){
		$datos.="<tr><td colspan='6'><hr></td></tr>";
	}
	
	if($Externo!=1){
	$datos.="<td align='center'><strong>Eliminar</strong></td>";
	}else{
	//$datos.="<td align='center'><strong>&nbsp;</strong></td>";
	}
	$datos.="</tr>";
	
	
	if($row2=mysql_fetch_array($resp3)){
		$NoMovimiento=0;
	
	while($row=mysql_fetch_array($resp2)){

			if($row2["IdMedicina"]==$row["IdMedicina"]){
				$NoMovimiento++;
			}else{
				$NoMovimiento=1;
			}
			
						$row2=mysql_fetch_array($resp3);
			
		$datos.="<tr class='FONDO'><td align='center'>".$NoMovimiento."</td><td align='center'>".$row["Nombre"]." - ".$row["Concentracion"]."</td><td align='center'>".$row["Descripcion"]."</td><td align='center'>".$row["Cantidad"]/$row["UnidadesContenidas"]."</td><td align='center'>&nbsp;";
		
			/********	CODIGO PARA CELDA DE ENTREGADA		********/
				
		$datos.="</td>";
		
		if($Externo!=1){
		$datos.="<td align='center'>		
		<a onclick=\"javascript:EliminarTransferencia(".$row["IdDetallePedido"].");\" style=\"cursor:default;\"><img src=\"../images/papelera.gif\" /></a></td></tr>";
		}//If Externo
		else{
			
			//$datos.="<td align='center'><strong>&nbsp;</strong></td>";
		}
		if($Externo==1){
			$datos.="<tr><td colspan='6'><hr></td></tr>";
		}
		
	}//while resp2

}else{

$datos.="<tr class='FONDO'><td colspan='6' align='center'><h2>NO EXISTE(N) TRANSFERENCIA(S) A MOSTRAR</h2></td></tr>";
}
	$datos.="</table>";
	
	return($datos);	
	
}//Desplegar Pedidos



function EliminarDetalle($IdDetallePedido){
	$queryDelete="delete from alm_detallepedido where IdDetallePedido=".$IdDetallePedido;
	mysql_query($queryDelete);
	
}//Eliminar Medicamento de Pedido


function EliminarPedido($IdPedido){
	$querySelect="select IdDetallePedido from alm_detallepedido where IdPedido=".$IdPedido;
	$resp=mysql_query($querySelect);
	while($row=mysql_fetch_array($resp)){
		#ELIMINACION DEL DETALLE
		queries::EliminarDetalle($row["IdDetallePedido"]);
				
	}//while
	
	$queryDelete="delete from alm_pedidos where IdPedido=".$IdPedido;
	mysql_query($queryDelete);
	
}//cancelar el pedido completo


function FinalizarPedido($IdPedido){
	$queryUpdate="update alm_pedidos set IdEstado='R' where IdPedido=".$IdPedido;
	mysql_query($queryUpdate);
	
}//Feinalizar Pedido

function UnidadMedidas(){
	$query="select IdUnidadMedida, Descripcion
			from farm_unidadmedidas
			limit 2";
	$resp=mysql_query($query);
	return($resp);
}



}//fin class queries
 
 
class meses{
function NombreMes($mes){
		switch($mes){
			case 'January': $mes="Enero";
			break;
			case 'February': $mes="Febrero";
			break;
			case 'March': $mes="Marzo";
			break;
			case 'April': $mes="Abril";
			break;
			case 'May': $mes="Mayo";
			break;
			case 'June': $mes="Junio";
			break;
			case 'July': $mes="Julio";
			break;
			case 'August': $mes="Agosto";
			break;
			case 'September': $mes="Septiembte";
			break;
			case 'October': $mes="Octubre";
			break;
			case 'November': $mes="Noviembre";
			break;
			case 'December': $mes="Diciembre";
			break;
			default: $mes=" ____________ ";
			break;
		}//switch
return ($mes);
}//NombreMes

} //meses


class MedicamentoVencimiento{

	function ExisteVencimiento(){
conexion::conectar();
		$SQL="select farm_catalogoproductos.Nombre,farm_medicinaexistenciaxarea.Existencia, 
					farm_lotes.Lote,farm_lotes.FechaVencimiento,farm_unidadmedidas.Descripcion,
					farm_unidadmedidas.UnidadesContenidas as Divisor
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where left(farm_lotes.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)
					order by farm_lotes.FechaVencimiento";
		$resp=mysql_query($SQL);
conexion::desconectar();
		return($resp);		
	}
	
}

?>
