<?php 

function Servicios($IdSubEspecialidad,$FechaInicio,$FechaFin){
	switch($IdSubEspecialidad){
		case 0:
		$querySelect="select distinct mnt_subespecialidad.IdSubEspecialidad, NombreSubEspecialidad
					from mnt_subespecialidad
					inner join sec_historial_clinico
					on sec_historial_clinico.IdSubEspecialidad=mnt_subespecialidad.IdSubEspecialidad
					inner join farm_recetas
					on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
					where mnt_subespecialidad.IdEspecialidad=4
					and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'";
		break;
		default:
		$querySelect="select distinct mnt_subespecialidad.IdSubEspecialidad, NombreSubEspecialidad
					from mnt_subespecialidad
					inner join sec_historial_clinico
					on sec_historial_clinico.IdSubEspecialidad=mnt_subespecialidad.IdSubEspecialidad
					inner join farm_recetas
					on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
					where farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
					and mnt_subespecialidad.IdSubEspecialidad=".$IdSubEspecialidad;
		break;
	}
	$resp=mysql_query($querySelect);
	return($resp);
}//Servicios


function NombreTera($grupoTerapeutico,$IdSubEspecialidad,$FechaInicio,$FechaFin){
if($grupoTerapeutico==0){
$querySelect="select distinct mnt_grupoterapeutico.* from mnt_grupoterapeutico
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico

			where(farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
			and sec_historial_clinico.IdSubEspecialidad='$IdSubEspecialidad'
			order by mnt_grupoterapeutico.IdTerapeutico";
}else{
$querySelect="select * from mnt_grupoterapeutico 
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico

			where mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
			and sec_historial_clinico.IdSubEspecialidad='$IdSubEspecialidad'";
}//else
//
$resp=mysql_query($querySelect);
//
return($resp);
}//nombreTera



function QueryExterna($IdTerapeutico,$IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin){
//******todos los grupos terapeuticos
if($IdTerapeutico=='0' and $IdMedicina==0){
$querySelect="select distinct mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where IdSubEspecialidad='$IdSubEspecialidad'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin'
			order by farm_catalogoproductos.IdMedicina";
}elseif($IdTerapeutico!='0' and $IdMedicina==0){
//******un grupoterapeutico especifico pero todas sus medicinas
$querySelect="select distinct mnt_grupoterapeutico.*, farm_catalogoproductos.*
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where mnt_grupoterapeutico.IdTerapeutico='$IdTerapeutico'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and IdSubEspecialidad='$IdSubEspecialidad'
			and Fecha between '$FechaInicio' and '$FechaFin'
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
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where mnt_grupoterapeutico.IdTerapeutico='$IdTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina' 
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and IdSubEspecialidad='$IdSubEspecialidad' 
			and Fecha between '$FechaInicio' and '$FechaFin'
			order by farm_catalogoproductos.IdMedicina";
}

$resp=mysql_query($querySelect);
return($resp);
}//queryExterna


function ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad){
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
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_medicinarecetada.IdMedicina='$IdMedicina' 
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin' 
			and sec_historial_clinico.IdSubEspecialidad='$IdSubEspecialidad' 
			order by farm_catalogoproductos.IdMedicina";
$resp=mysql_query($selectQuery);
return($resp);
}//fin de ObtenerReporteGrupoTerapeutico



function ObtenerConsumosMedicamentoLote($IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin){
	/* FUNCION UTILIZADA EN REPORTE DE CONSUMO DE MEDCAMENTOS */

		$querySelect="select farm_catalogoproductos.IdMedicina,
					sum(farm_medicinarecetada.CantidadLote1) as TotalLote1,farm_medicinarecetada.Lote1,
					sum(farm_medicinarecetada.CantidadLote2)as TotalLote2, farm_medicinarecetada.Lote2
					from farm_medicinarecetada
					inner join farm_recetas
					on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
					inner join sec_historial_clinico
					on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				
				where farm_medicinarecetada.FechaEntrega between '$FechaInicio' and '$FechaFin'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and sec_historial_clinico.IdSubEspecialidad='$IdSubEspecialidad'
				and farm_medicinarecetada.CantidadLote1 is not NULL
				and farm_medicinarecetada.Lote1 is not null
				
				group by farm_medicinarecetada.IdMedicina, farm_medicinarecetada.Lote1,farm_medicinarecetada.Lote2";	
		$resp=mysql_query($querySelect);
		return($resp);
	}//fin de funcion




function ObtenerRecetasSatisfechas($IdReceta,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$Bandera,$IdMedico){
/*Bandera = IdSubEspeacialidad utilizado en reporte por especialidad*/

$querySelect="select distinct count(farm_recetas.IdReceta) as TotalSatisfechas 
			  from farm_medicinarecetada
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  inner join sec_historial_clinico
			  on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			  
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and IdSubEspecialidad='$IdSubEspecialidad'
			  ";
$resp=mysql_fetch_array(mysql_query($querySelect));

return($resp[0]);
}//satisfechas


//Para Insatisfechas
function ObtenerRecetasInsatisfechas($IdReceta,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$Bandera,$IdMedico){

$querySelect="select distinct count(farm_recetas.IdReceta) as TotalInsatisfechas 
			  from farm_medicinarecetada 
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  inner join sec_historial_clinico
			  on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			  
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and farm_medicinarecetada.IdEstado='I'
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and IdSubEspecialidad='$IdSubEspecialidad'
			  ";
$resp=mysql_fetch_array(mysql_query($querySelect));

return($resp[0]);
}//Insatisfechas


function verificaSatisfecha($IdMedicina,$IdReceta){
	if ($IdReceta==0){
		$querySelect="select * from farm_medicinarecetada where IdMedicina='$IdMedicina' and (IdEstado='S' or IdEstado='')";
	}else{
		$querySelect="select * from farm_medicinarecetada where IdReceta='$IdReceta' and IdMedicina='$IdMedicina' and (IdEstado='S' or IdEstado='')";
	}
	$resp=mysql_query($querySelect);
	return($resp);
}//verificaSatisfechos


function NumeroRecetasTotal($IdMedicina,$IdArea,$FechaInicio,$FechaFin){
	$querySelect="select  count(farm_recetas.IdReceta)as TotalRecetas
				from farm_recetas 
				inner join farm_medicinarecetada
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where IdArea='$IdArea'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}


function SumatoriaMedicamento($IdMedicina,$IdArea,$FechaInicio,$FechaFin){
	$querySelect="select  sum(Cantidad)as TotalMedicamento
			from farm_recetas 
			inner join farm_medicinarecetada
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			and IdArea='$IdArea'
			and farm_medicinarecetada.IdMedicina='$IdMedicina'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}


?>