<?php
class Repetitivas{
function ObtenerDatosPacienteRecetaProceso($IdReceta){
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
farm_recetas.*,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,
concat_ws('-',day(sec_historial_clinico.FechaConsulta),month(sec_historial_clinico.FechaConsulta),year(sec_historial_clinico.FechaConsulta))as FechaConsulta,
concat_ws('-',day(farm_recetas.Fecha),month(farm_recetas.Fecha),year(farm_recetas.Fecha))as FechaDeEntrega
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
where farm_recetas.IdReceta='$IdReceta'";
$resp=mysql_query($querySelect);
return($resp);
}

function ObtenerDatosPacienteReceta($bandera,$IdHistorialClinico,$IdArea){
if($bandera==1){
/*MOSTRAR TODAS LAS RECETAS*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
farm_recetas.*,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,
concat_ws('-',day(sec_historial_clinico.FechaConsulta),month(sec_historial_clinico.FechaConsulta),year(sec_historial_clinico.FechaConsulta))as FechaConsulta,
concat_ws('-',day(farm_recetas.Fecha),month(farm_recetas.Fecha),year(farm_recetas.Fecha))as FechaDeEntrega
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
where (farm_recetas.IdEstado='TT') 
and farm_recetas.Fecha = curdate()
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";

}else{
/*IMPRESION DE VINETAS*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subespecialidad.NombreSubEspecialidad,
farm_recetas.*,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,
concat_ws('-',day(sec_historial_clinico.FechaConsulta),month(sec_historial_clinico.FechaConsulta),year(sec_historial_clinico.FechaConsulta))as FechaConsulta,
concat_ws('-',day(farm_recetas.Fecha),month(farm_recetas.Fecha),year(farm_recetas.Fecha))as FechaDeEntrega
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
where (farm_recetas.IdEstado='TT') 
and farm_recetas.Fecha = curdate()
and sec_historial_clinico.IdHistorialClinico='$IdHistorialClinico'
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";
}
//Para consulta del mes en where month(FechaConsulta)=month(curdate())...Para despues
$resp=mysql_query($querySelect);
return($resp);
}//ObtenerDatosPacienteReceta

function datosReceta($IdReceta,$IdArea){
$querySelect="select farm_recetas.*, farm_catalogoproductos.Nombre as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica, farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis,farm_medicinarecetada.IdEstado,
farm_medicinarecetada.IdEstado as EstadoMedicina,
concat_ws('-',day(sec_historial_clinico.FechaConsulta),month(sec_historial_clinico.FechaConsulta),year(sec_historial_clinico.FechaConsulta))as FechaConsulta,
concat_ws('-',day(farm_recetas.Fecha),month(farm_recetas.Fecha),year(farm_recetas.Fecha))as FechaDeEntrega
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
where farm_recetas.IdReceta='$IdReceta'";
$respuesta=mysql_query($querySelect);
return($respuesta);
}//fin de datosReceta


function MedicinaReceta($IdReceta){
	$querySelect="select farm_medicinarecetada.*
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdReceta='$IdReceta'";
	$resp=mysql_query($querySelect);
	return($resp);
	
	
}//MedicinaReceta






}//Fin clase Repetitivas
?>