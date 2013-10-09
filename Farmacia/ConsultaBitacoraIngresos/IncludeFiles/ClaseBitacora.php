<?php

class Bitacora{
   function ExisteBitacora($FechaInicio,$FechaFin){
	$SQL="select *
		from farm_bitacoraentregamedicamento
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function ObtenerGrupos($IdTeraputico,$FechaInicio,$FechaFin){
	if($IdTeraputico!=0){$comp="and fgt.IdTerapeutico=".$IdTeraputico;}else{$comp="";}

	$SQL="select distinct fgt.IdTerapeutico,GrupoTerapeutico
		from mnt_grupoterapeutico fgt
		inner join farm_catalogoproductos fcp
		on fcp.IdTerapeutico=fgt.IdTerapeutico
		inner join farm_bitacoraentregamedicamento fbem
		on fbem.IdMedicina=fcp.IdMedicina
		
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'
		".$comp;
	$resp=mysql_query($SQL);
	return($resp);
   }
	
   function ObtenerBitacora($IdTeraputico,$FechaInicio,$FechaFin){
	$SQL="select fcp.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion,fbem.Existencia,Lote,Descripcion,UnidadesContenidas,
		date_format(date(FechaHoraIngreso),'%d-%m-%Y') as FechaIngreso,
		date_format(FechaHoraIngreso,'%l:%i:%s %p') as HoraIngreso,IdEntregaOrigen
		from farm_catalogoproductos fcp
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida=fcp.IdUnidadMedida
		inner join farm_bitacoraentregamedicamento fbem
		on fbem.IdMedicina=fcp.IdMedicina
		inner join farm_lotes fl
		on fl.IdLote=fbem.IdLote
		left join farm_entregamedicamento fem
		on fem.IdEntrega=fbem.IdEntregaOrigen
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'
		and fcp.IdTerapeutico=".$IdTeraputico."
		order by Codigo,FechaIngreso";	
	$resp=mysql_query($SQL);
	return($resp);
   }


	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

}


?>
