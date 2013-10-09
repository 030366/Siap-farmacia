<?php
include('../Clases/class.php');
class Obtencion{
function ObtenerPersonalReceta($IdPersonal){
$querySelect="select distinct farm_usuarios.Nombre
from farm_recetas
inner join farm_usuarios
on (farm_usuarios.IdPersonal=farm_recetas.IdPersonal or farm_usuarios.IdPersonal=farm_recetas.IdPersonalIntro)
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='T') and farm_recetas.IdPersonal='$IdPersonal'";
	  $resp=mysql_query($querySelect);
return($resp);
}//ObtenerPersonalReceta


function ObtenerDatosRecetasLimit($IdPersonal,$limit){
$querySelect="select farm_usuarios.Nombre,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NombrePaciente, farm_recetas.*, mnt_empleados.NombreEmpleado
from farm_recetas
inner join farm_usuarios
on farm_usuarios.IdPersonal=farm_recetas.IdPersonal
inner join sec_historial_clinico
on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='T' OR farm_recetas.IdEstado='ER' OR farm_recetas.IdEstado='RT') 
and (farm_recetas.IdPersonal='$IdPersonal' or farm_recetas.IdPersonalIntro='$IdPersonal')
and year(farm_recetas.Fecha)=year(curdate())order by farm_recetas.Fecha desc".$limit;
	  $resp=mysql_query($querySelect);
return($resp);
}//ObtenerDatosRecetasLimit


function ObtenerDatosRecetasTotal($IdPersonal){//Still Unused
$querySelect="select count(*) as total
from farm_recetas
inner join farm_usuarios
on farm_usuarios.IdPersonal=farm_recetas.IdPersonal
inner join sec_historial_clinico
on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='T' OR farm_recetas.IdEstado='ER' OR farm_recetas.IdEstado='RT') 
and farm_recetas.IdPersonal='$IdPersonal'
and year(farm_recetas.Fecha)=year(curdate())";
	  $resp=mysql_query($querySelect);
return($resp);
}//ObtenerDatosRecetas


function DetalleReceta($IdReceta){
$querySelect="select sec_historial_clinico.FechaConsulta,farm_recetas.*, farm_catalogoproductos.Nombre as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica,year(mnt_datospaciente.FechaNacimiento)as year,
year(curdate()) as year2,farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis,
farm_medicinarecetada.IdEstado
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
where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='T' OR farm_recetas.IdEstado='ER' OR farm_recetas.IdEstado='RT') 
and farm_recetas.IdReceta='$IdReceta'";
$resp=mysql_query($querySelect);
return($resp);
}//DetalleReceta





}//clase obtencion


/**********************
CODIGO ELIMINADO:
QUERY ObtenerDatosRecetaTotal():
					and month(farm_recetas.Fecha) between month(adddate(curdate(),interval -1 month)) and month(curdate())


***********************/
?>