<?php

class Classquery{

function ObtenerQuery($Bandera,$IdArea,$q){
	switch($Bandera){
	case 1: $sqlStr =  "select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
sec_historial_clinico.FechaConsulta,farm_recetas.*, year(mnt_datospaciente.FechaNacimiento)as year,
year(curdate()) as year2
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
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='X' or farm_recetas.IdEstado='RT' or farm_recetas.IdEstado='ER' or farm_recetas.IdEstado='T') 
and (mnt_expediente.IdNumeroExp LIKE '%$q%' OR farm_recetas.NumeroReceta='$q')
and farm_recetas.Fecha between adddate(curdate(),interval -1 month) and curdate()
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";
 break;
 
 case 0: $sqlStr = "select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
sec_historial_clinico.FechaConsulta,farm_recetas.*, year(mnt_datospaciente.FechaNacimiento)as year,
year(curdate()) as year2
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
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='X' or farm_recetas.IdEstado='RT' or farm_recetas.IdEstado='ER' or farm_recetas.IdEstado='T') 
and farm_recetas.Fecha between adddate(curdate(),interval -1 month) and curdate()
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";
 break;
      }//switch
 return ($sqlStr);
	}//ObtenerQueryLike
	
	
function ObtenerQueryTotal($Bandera,$IdArea,$q){
switch($Bandera){
case 1:
 $sqlStrAux = "select count(farm_recetas.IdReceta) as total
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

where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='X' or farm_recetas.IdEstado='RT' or farm_recetas.IdEstado='ER' or farm_recetas.IdEstado='T') 
and (mnt_expediente.IdNumeroExp LIKE '%$q%' OR farm_recetas.NumeroReceta='$q')
and farm_recetas.Fecha between adddate(curdate(),interval -1 month) and curdate()
and farm_recetas.IdArea='$IdArea'";
 break;
 
 case 0:
 $sqlStrAux = "select count(farm_recetas.IdReceta) as total
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

where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='X' or farm_recetas.IdEstado='RT' or farm_recetas.IdEstado='ER' or farm_recetas.IdEstado='T') 
and farm_recetas.Fecha between adddate(curdate(),interval -1 month) and curdate()";
 break;
}//switch
return($sqlStrAux);
}//ObtenerQueryTotal

function NombreMedicamento($IdReceta,$link){
$querySelect="select farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion
			from farm_catalogoproductos
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where farm_medicinarecetada.IdEstado='I' and farm_recetas.IdReceta='$IdReceta'";
$resp = mysql_query($querySelect,$link);

return($resp);

}//NombreMedicamento

function MedicinaReceta($IdReceta,$IdArea){
$querySelect="select farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis,farm_medicinarecetada.IdEstado,
			farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,farm_catalogoproductos.FormaFarmaceutica
			from farm_medicinarecetada
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where farm_medicinarecetada.IdReceta='$IdReceta'
			and farm_recetas.IdArea='$IdArea'
			order by farm_medicinarecetada.IdMedicina";
return($querySelect);

}//MedicinaReceta



}//clase query