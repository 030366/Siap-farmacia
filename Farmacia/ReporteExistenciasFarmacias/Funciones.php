<?php 
/*	EN PRODUCCION SE DEBE FILTRAR POR IDAREA	*/

function NombreTera($grupoTerapeutico){
if($grupoTerapeutico==0){
$querySelect="select distinct mnt_grupoterapeutico.* from mnt_grupoterapeutico
			inner join farm_catalogoproductos fcp
			on fcp.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina

			where GrupoTerapeutico <> '--'
			
			order by mnt_grupoterapeutico.IdTerapeutico";
}else{
$querySelect="select * from mnt_grupoterapeutico where IdTerapeutico='$grupoTerapeutico'";
}//else
//
$resp=mysql_query($querySelect);
//
return($resp);
}//nombreTera


function QueryExterna($IdFarmacia,$IdArea,$grupoTerapeutico, $medicina){

if($IdFarmacia==0){
if($grupoTerapeutico!=0){$comp="where  mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico'";}else{$comp="";}
	if($medicina!=0){$comp2="and farm_catalogoproductos.IdMedicina='$medicina'";}else{$comp2="";}

$querySelect="select distinct farm_catalogoproductos.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica, Presentacion
				from farm_catalogoproductos
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
					
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
				".$comp."
				".$comp2."
				order by farm_catalogoproductos.Codigo";

}

if($IdFarmacia!=4 and $IdFarmacia!=0){

	if($IdFarmacia!=0){$comp3="where mf.IdFarmacia='$IdFarmacia'";}else{$comp3="";}
	if($IdArea!=0){$comp4="and maf.IdArea='$IdArea'";}else{$comp4="";}
	
	if($grupoTerapeutico!=0){$comp="and  mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico'";}else{$comp="";}
	if($medicina!=0){$comp2="and farm_catalogoproductos.IdMedicina='$medicina'";}else{$comp2="";}
	
	
	$querySelect="select distinct farm_catalogoproductos.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion
				from farm_catalogoproductos
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
					
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
					
				inner join farm_medicinaexistenciaxarea fmexa
				on fmexa.IdMedicina=fcpe.IdMedicina
				inner join mnt_areafarmacia maf
				on maf.IdArea=fmexa.IdArea
				inner join mnt_farmacia mf
				on mf.IdFarmacia=maf.IdFarmacia
				".$comp4."
				".$comp3."
				".$comp."
				".$comp2."
				order by farm_catalogoproductos.Codigo";

}
if($IdFarmacia==4){
// MEDICAMENTO EN BODEGA
if($grupoTerapeutico!=0){$comp="where  mnt_grupoterapeutico.IdTerapeutico='$grupoTerapeutico'";}else{$comp="";}
if($medicina!=0){$comp2="and farm_catalogoproductos.IdMedicina='$medicina'";}else{$comp2="";}


$querySelect="select distinct farm_catalogoproductos.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion
				from farm_catalogoproductos
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
					
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
					
				".$comp."
				".$comp2."
				order by farm_catalogoproductos.Codigo";


}


$resp=mysql_query($querySelect);
return($resp);
}//queryExterna


function ObtenerReporteGrupoTerapeutico($IdFarmacia,$IdArea,$GrupoTerapeutico,$IdMedicina){
//**Query para un GrupoTerapeutico especifico y una Medicina Especifica
//Del Query Elimine mnt_medicinarecetada.Cantidad, a la par de farm_medicinarecetada.*,
if($IdFarmacia==0){
$selectQuery="select fme.IdMedicina,sum(fme.Existencia)/UnidadesContenidas as Total, Descripcion
	
			from farm_medicinaexistenciaxarea fme
			inner join farm_catalogoproductos
			on fme.IdMedicina=farm_catalogoproductos.IdMedicina
		
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			inner join farm_lotes l
			on l.IdLote=fme.IdLote	
								
				
				inner join mnt_areafarmacia maf
				on maf.IdArea=fme.IdArea
				inner join mnt_farmacia mf
				on mf.IdFarmacia=maf.IdFarmacia

			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina' 
			and left(FechaVencimiento,7) >= left(curdate(),7)
			group by fme.IdMedicina

		union

select fem.IdMedicina,sum(Existencia)/UnidadesContenidas as Total, Descripcion
			from farm_entregamedicamento fem
			inner join farm_catalogoproductos
			on fem.IdMedicina=farm_catalogoproductos.IdMedicina
		
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida

			inner join farm_lotes l
			on l.IdLote=fem.IdLote

			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina' 

			and left(FechaVencimiento,7) >= left(curdate(),7)
			group by fem.IdMedicina";



}


if($IdFarmacia!=4 and $IdFarmacia!=0){

if($IdFarmacia!=0){$comp="and mf.IdFarmacia=".$IdFarmacia;}else{$comp="";}
if($IdArea!=0){$comp2="and maf.IdArea=".$IdArea;}else{$comp2="";}

$selectQuery="select fme.IdMedicina,sum(fme.Existencia)/UnidadesContenidas as Total, Descripcion
	
			from farm_medicinaexistenciaxarea fme
			inner join farm_catalogoproductos
			on fme.IdMedicina=farm_catalogoproductos.IdMedicina
		
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			inner join farm_lotes l
			on l.IdLote=fme.IdLote	
								
				
				inner join mnt_areafarmacia maf
				on maf.IdArea=fme.IdArea
				inner join mnt_farmacia mf
				on mf.IdFarmacia=maf.IdFarmacia

			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina' 
			".$comp."
			".$comp2."
			and left(FechaVencimiento,7) >= left(curdate(),7)
			group by fme.IdMedicina";

}

if($IdFarmacia==4){
//	EXISTENCIA EN BODEGA
$selectQuery="select fem.IdMedicina,sum(Existencia)/UnidadesContenidas as Total, Descripcion
			from farm_entregamedicamento fem
			inner join farm_catalogoproductos
			on fem.IdMedicina=farm_catalogoproductos.IdMedicina
		
			inner join mnt_grupoterapeutico
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_unidadmedidas
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida

			inner join farm_lotes l
			on l.IdLote=fem.IdLote

			where mnt_grupoterapeutico.IdTerapeutico='$GrupoTerapeutico' 
			and farm_catalogoproductos.IdMedicina='$IdMedicina' 

			and left(FechaVencimiento,7) >= left(curdate(),7)
			group by fem.IdMedicina";
}

$resp=mysql_query($selectQuery);
return($resp);
}//fin de ObtenerReporteGrupoTerapeutico


function FechaBase(){
   $SQL="select left(adddate(current_date,interval -1 month),7)";
   $resp=mysql_fetch_array(mysql_query($SQL));
   return($resp[0]);
}


function LotesMedicamento($IdFarmacia,$IdArea,$Medicina){
if($IdFarmacia==0){
   $SQL="select distinct l.* from farm_medicinaexistenciaxarea  fmexa
	inner join farm_lotes l
	on fmexa.IdLote=l.IdLote
	where IdMedicina='$Medicina'
	and left(FechaVencimiento,7) >= left(curdate(),7)
		
	union 

	select distinct l.* from farm_entregamedicamento  fmexa
	inner join farm_lotes l
	on fmexa.IdLote=l.IdLote
	where IdMedicina='$Medicina'
	and left(FechaVencimiento,7) >= left(curdate(),7)
		order by FechaVencimiento asc";
}

if($IdFarmacia!=4 and $IdFarmacia!=0){
	if($IdFarmacia!=0 and $IdArea==0){$comp=" inner join mnt_areafarmacia faf on faf.IdArea=fmexa.IdArea"; $comp2=" and faf.IdFarmacia= ".$IdFarmacia;}else{$comp="";$comp2="";}

	if($IdFarmacia!=0 and $IdArea!=0){$comp=" inner join mnt_areafarmacia faf on faf.IdArea=fmexa.IdArea"; $comp2=" and faf.IdArea= ".$IdArea;}

   $SQL="select distinct l.* from farm_medicinaexistenciaxarea  fmexa
	inner join farm_lotes l
	on fmexa.IdLote=l.IdLote
	".$comp."
	where IdMedicina=".$Medicina."
	and left(FechaVencimiento,7) >= left(curdate(),7)
	".$comp2."
		order by FechaVencimiento asc";
}

if($IdFarmacia==4){
   $SQL="select distinct l.* from farm_entregamedicamento  fmexa
	inner join farm_lotes l
	on fmexa.IdLote=l.IdLote
	where IdMedicina=".$Medicina."
	and left(FechaVencimiento,7) >= left(curdate(),7)
	and Existencia <> 0
		order by FechaVencimiento asc";
}

   $resp=mysql_query($SQL);
   return($resp);
}


function ObtenerRecetasSatisfechas($IdReceta,$IdMedicina,$FechaInicio,$FechaFin,$IdArea,$Bandera,$IdMedico){
/*Bandera = IdSubEspeacialidad utilizado en reporte por especialidad*/
if($Bandera==0){
$querySelect="select count( farm_recetas.IdReceta) as TotalSatisfechas 
			  from farm_medicinarecetada
			  inner join farm_recetas
			  on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and farm_recetas.IdArea='$IdArea'
			  ";
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
		  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
		  and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
		  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
		  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
		  and Fecha between '$FechaInicio' and '$FechaFin' 
		  and farm_recetas.IdArea='$IdArea'
		  ";
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
		  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
		  and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
		  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
		  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
		  and Fecha between '$FechaInicio' and '$FechaFin' 
		  and farm_recetas.IdArea='$IdArea'
		  and mnt_empleados.IdEmpleado='$IdMedico' 
		  ";	
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
			  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
			  and farm_medicinarecetada.IdEstado='I'
			  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			  and Fecha between '$FechaInicio' and '$FechaFin' 
			  and farm_recetas.IdArea='$IdArea'
			  ";}
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
				  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
				  and farm_medicinarecetada.IdEstado='I'
				  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
				  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				  and Fecha between '$FechaInicio' and '$FechaFin' 
				  and farm_recetas.IdArea='$IdArea'
				 ";
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
				  where farm_medicinarecetada.IdMedicina='$IdMedicina' 
				  and farm_medicinarecetada.IdEstado='I'
				  and mnt_subespecialidad.IdSubEspecialidad='$Bandera'
				  and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				  and Fecha between '$FechaInicio' and '$FechaFin' 
				  and farm_recetas.IdArea='$IdArea' 
				  and mnt_empleados.IdEmpleado='$IdMedico' 
				  ";
		}//else IF medico
}

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
				where farm_recetas.IdArea='$IdArea'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and Fecha between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}



function SumatoriaMedicamento($IdMedicina,$FechaInicio,$FechaFin){
	$querySelect="select  (sum(Cantidad)/UnidadesContenidas) as TotalMedicamento, PrecioLote,
		    ((sum(Cantidad)/UnidadesContenidas)*PrecioLote) as Costo,Lote,PrecioLote
			from farm_recetas 
			inner join farm_medicinarecetada
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			inner join farm_medicinadespachada md
			on md.IdMedicinaRecetada = farm_medicinarecetada.IdMedicinaRecetada
			inner join farm_lotes l
			on l.IdLote = md.IdLote
			inner join farm_catalogoproductos cp
			on cp.IdMedicina = farm_medicinarecetada.IdMedicina
			inner join farm_unidadmedidas um
			on um.IdUnidadMedida = cp.IdUnidadMedida
			where (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
			and farm_medicinarecetada.IdMedicina='$IdMedicina'
			and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
			and Fecha between '$FechaInicio' and '$FechaFin'
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

function ObtenerAreasFarmacia($IdFarmacia,$IdArea,$FechaInicio,$FechaFin){

	if($IdFarmacia=='0'){
		$query="select distinct mnt_areafarmacia.IdArea,Area 
				from mnt_areafarmacia 
				inner join farm_recetas
				on farm_recetas.IdArea=mnt_areafarmacia.IdArea
				where Fecha  between '$FechaInicio' and '$FechaFin'
				and Habilitado='S'";
	
	}
	if($IdFarmacia!=0 and $IdArea==0){
		$query="select distinct mnt_areafarmacia.IdArea,Area 
				from mnt_areafarmacia 
				inner join farm_recetas
				on farm_recetas.IdArea=mnt_areafarmacia.IdArea
				where Fecha  between '$FechaInicio' and '$FechaFin'
				and farm_recetas.IdFarmacia='$IdFarmacia'
				and Habilitado='S'";
	}

	if($IdArea!=0){
		$query="select IdArea,Area from mnt_areafarmacia where IdArea=".$IdArea;
	}
	
	$resp=mysql_query($query);
	return($resp);
}

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

?>