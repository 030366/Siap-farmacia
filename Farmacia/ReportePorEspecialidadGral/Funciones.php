<?php 

function Farmacia($IdFarmaciaN){
  $SQL="select Farmacia from mnt_farmacia where IdFarmacia=".$IdFarmaciaN;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
}

function Servicios($IdSubEspecialidad,$IdTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdFarmacia){

	if($IdTerapeutico!=0){$comp="and fcp.IdTerapeutico=".$IdTerapeutico;}else{$comp="";}
	if($IdMedicina!=0){$comp2="and fcp.IdMedicina=".$IdMedicina;}else{$comp2="";}
	if($IdFarmacia!=0){$comp3="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp3="";}

	switch($IdSubEspecialidad){
		case 0:
		$querySelect="select distinct mnt_subservicio.IdSubServicio, NombreSubServicio
				from mnt_subservicio
				inner join sec_historial_clinico
				on sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
				inner join farm_recetas
				on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico

				inner join farm_medicinarecetada fmr
				on fmr.IdReceta=farm_recetas.IdReceta
				inner join farm_catalogoproductos fcp
				on fcp.IdMedicina=fmr.IdMedicina
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=fcp.IdMedicina

				where mnt_subservicio.IdServicio='CONEXT'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
				".$comp."
				".$comp2."
				".$comp3."
				order by NombreSubServicio";
		break;
		default:
		$querySelect="select distinct mnt_subservicio.IdSubServicio, NombreSubServicio
				from mnt_subservicio
				inner join sec_historial_clinico
				on sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
				inner join farm_recetas
				on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico

				inner join farm_medicinarecetada fmr
				on fmr.IdReceta=farm_recetas.IdReceta
				inner join farm_catalogoproductos fcp
				on fcp.IdMedicina=fmr.IdMedicina
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=fcp.IdMedicina

				where farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				".$comp."
				".$comp2."
				".$comp3."
				and mnt_subservicio.IdSubServicio=".$IdSubEspecialidad;
		break;
	}
	$resp=mysql_query($querySelect);
	return($resp);
}//Servicios


function NombreTera($grupoTerapeutico,$IdSubEspecialidad,$FechaInicio,$FechaFin,$IdFarmacia){
if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}
if($grupoTerapeutico==0){
$querySelect="select distinct mnt_grupoterapeutico.IdTerapeutico,GrupoTerapeutico 
			from mnt_grupoterapeutico
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico

			where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
			and sec_historial_clinico.IdSubServicio='$IdSubEspecialidad'
			".$comp."
			order by mnt_grupoterapeutico.IdTerapeutico";
}else{
$querySelect="select distinct mnt_grupoterapeutico.IdTerapeutico,GrupoTerapeutico
			from mnt_grupoterapeutico 
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
			and sec_historial_clinico.IdSubServicio='$IdSubEspecialidad'
			".$comp."";
}//else
//
$resp=mysql_query($querySelect);
//
return($resp);
}//nombreTera



function QueryExterna($IdTerapeutico,$IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin,$IdFarmacia){
//******todos los grupos terapeuticos
if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}
if($IdTerapeutico!=0){$comp2="and mnt_grupoterapeutico.IdTerapeutico=".$IdTerapeutico;}else{$comp2="";}
if($IdMedicina!=0){$comp3="and farm_catalogoproductos.IdMedicina=".$IdMedicina;}else{$comp3="";}

$querySelect="select distinct farm_catalogoproductos.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica, Presentacion
			from farm_catalogoproductos
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where IdSubServicio='$IdSubEspecialidad'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin'
			".$comp."
			".$comp2."
			".$comp3."
			order by farm_catalogoproductos.Codigo";


$resp=mysql_query($querySelect);
return($resp);
}//queryExterna


function ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad){
//**Query para un GrupoTerapeutico especifico y una Medicina Especifica
//Del Query Elimine mnt_medicinarecetada.Cantidad, a la par de farm_medicinarecetada.*,

$selectQuery="select distinct farm_recetas.IdReceta,farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
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
			and sec_historial_clinico.IdSubServicio='$IdSubEspecialidad' 
			order by farm_catalogoproductos.IdMedicina";
$resp=mysql_query($selectQuery);
return($resp);
}//fin de ObtenerReporteGrupoTerapeutico



function ObtenerConsumoTotalMedicamento($IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$IdFarmacia){
if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}

	$querySelect="select sum(Cantidad)as Total
			from farm_medicinarecetada
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			
			where IdSubServicio='$IdSubEspecialidad'
			and IdMedicina='$IdMedicina'
			and farm_recetas.Fecha between '$FechaInicio' and '$FechaFin'
			".$comp."";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);	
}//ObtenerConsumoTotalMedicamento




function ObtenerRecetasSatisfechas($IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$Bandera,$IdMedico,$IdFarmacia){
/*Bandera = IdSubEspeacialidad utilizado en reporte por especialidad*/
if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}

$querySelect="select  count(farm_recetas.IdReceta) as TotalSatisfechas 
			  from farm_medicinarecetada
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  inner join sec_historial_clinico
			  on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			  
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and IdSubServicio='$IdSubEspecialidad'
			  ".$comp." ";
$resp=mysql_fetch_array(mysql_query($querySelect));

return($resp[0]);
}//satisfechas


//Para Insatisfechas
function ObtenerRecetasInsatisfechas($IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$Bandera,$IdMedico,$IdFarmacia){

if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}

$querySelect="select count(farm_recetas.IdReceta) as TotalInsatisfechas 
			  from farm_medicinarecetada 
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  inner join sec_historial_clinico
			  on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
			  
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and farm_medicinarecetada.IdEstado='I'
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and IdSubServicio='$IdSubEspecialidad'
			  ".$comp."";
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
				where farm_medicinarecetada.IdMedicina='$IdMedicina'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}


function SumatoriaMedicamento($IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin,$IdFarmacia){

if($IdFarmacia!=0){$comp="and farm_recetas.IdFarmacia=".$IdFarmacia;}else{$comp="";}

	$querySelect="select  sum(CantidadDespachada)/UnidadesContenidas as TotalMedicamento,Lote,PrecioLote,(sum(CantidadDespachada)/UnidadesContenidas)*PrecioLote as Costo
			from farm_recetas 
			inner join farm_medicinarecetada
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico

			inner join farm_catalogoproductos fcp
			on fcp.IdMedicina = farm_medicinarecetada.IdMedicina
			inner join farm_medicinadespachada md
			on md.IdMedicinaRecetada = farm_medicinarecetada.IdMedicinaRecetada
			inner join farm_lotes l
			on l.IdLote = md.IdLote
			inner join farm_unidadmedidas um
			on um.IdUnidadMedida=fcp.IdUnidadMedida

			where (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			and sec_historial_clinico.IdSubServicio='$IdSubEspecialidad'
			and farm_medicinarecetada.IdMedicina='$IdMedicina'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin'
			".$comp."
			group by md.IdLote";
	$resp=mysql_query($querySelect);
	return($resp);
}

function ObtenerPrecioMedicina($IdMedicina,$Ano){
		$query="select Precio
				from farm_preciosxano
				where IdMedicina='$IdMedicina'
				and Ano	='$Ano'";
		$resp=mysql_fetch_array(mysql_query($query));
		if($resp[0]!=NULL){$Respuesta=$resp[0];}else{$Respuesta=0;}
		return($Respuesta);
}

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
?>