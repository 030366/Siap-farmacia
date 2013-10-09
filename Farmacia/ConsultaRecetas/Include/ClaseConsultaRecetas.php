<?php

class Cambios{
function ActulizarFecha($NuevaFecha,$IdReceta){
$queryUpdate="update farm_recetas set Fecha = '$NuevaFecha' where IdReceta = '$IdReceta'";
mysql_query($queryUpdate);


}//ActualizarFecha


}//clase Cambios

class ConsultaRecetas{
function ObtencionNombrePaciente($Expediente){
	$querySelect="select distinct mnt_expediente.IdPaciente,mnt_expediente.IdNumeroExp,
	concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),
	CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
	mnt_datospaciente.sexo,
	(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac
	from sec_historial_clinico
	inner join mnt_expediente
	on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
	inner join mnt_datospaciente
	on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
	where mnt_expediente.IdNumeroExp='$Expediente'";
$resp=mysql_query($querySelect);
return($resp);


}//NombrePaciente

function ObtencionDatosRecetas($Expediente){
	$querySelect="select distinct mnt_expediente.IdPaciente,mnt_expediente.IdNumeroExp,
	concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),
	CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
	mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
	sec_historial_clinico.FechaConsulta,farm_recetas.*,
	(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,
	dayname(farm_recetas.Fecha)as NombreDia,farm_catalogoproductos.Nombre,farm_medicinarecetada.Cantidad,
	case when farm_recetas.Fecha=curdate() then 1 else 0 end as respuesta,
	concat_ws('-',day(farm_recetas.Fecha),month(farm_recetas.Fecha),year(farm_recetas.Fecha))as FechaEntrega	
	from sec_historial_clinico
	inner join mnt_expediente
	on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
	inner join farm_recetas
	on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	inner join mnt_datospaciente
	on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
	inner join mnt_empleados
	on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
	inner join mnt_subservicio
	on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
	inner join farm_medicinarecetada
	on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
	inner join farm_catalogoproductos
	on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
	where farm_recetas.IdEstado='RE'
	and mnt_expediente.IdNumeroExp='$Expediente'
	and farm_recetas.Fecha >= curdate()
	order by farm_recetas.Fecha";
	
$resp=mysql_query($querySelect);	
return($resp);
}//ObtencionDatosRecetas


function ModificaFechaReceta($IdReceta,$NuevaFecha){
$queryUpdate="update farm_recetas set Fecha = '$NuevaFecha' where farm_recetas.IdReceta = '$IdReceta'";
$querySelect="select farm_recetas.Fecha from farm_recetas where farm_recetas.IdReceta='$IdReceta'";
mysql_query($queryUpdate);
$resp=mysql_query($querySelect);
return($resp);
}//DatosRecetaModifica


}//Fin de Clase

class NombreDia{
function CambiaNombre($NombreDia){
switch($NombreDia){
case "Monday":
	$Nombre = "(LUNES)";
break;
case "Tuesday":
	$Nombre = "(MARTES)";
break;
case "Wednesday":
	$Nombre = "(MIERCOLES)";
break;
case "Thursday":
	$Nombre = "(JUEVES)";
break;
case "Friday":
	$Nombre = "(VIERNES)";
break;
case "Saturday":
	$Nombre = "<div style=\"color:#FF0000\">(SABADO)</div>";
break;
case "Sunday":
	$Nombre = "<div style=\"color:#FF0000\">(DOMINGO)</div>";
break;

default:
 $Nombre = "---";
break;
}//switch
return ($Nombre);

}//CambiaNombre


}//ClaseNombreDia
?>
