<?php

class Bitacora{
   function ExisteBitacora($FechaInicio,$FechaFin){
	$SQL="select *
		from farm_bitacoramedicinaexistenciaxarea
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function ObtenerGrupos($IdTeraputico,$FechaInicio,$FechaFin,$IdFarmacia){
	if($IdTeraputico!=0){$comp="and fgt.IdTerapeutico=".$IdTeraputico;}else{$comp="";}
	if($IdFarmacia!=0){$comp2="and fbem.IdArea=".$IdFarmacia;}else{$comp2="";}

	$SQL="select distinct fgt.IdTerapeutico,GrupoTerapeutico
		from mnt_grupoterapeutico fgt
		inner join farm_catalogoproductos fcp
		on fcp.IdTerapeutico=fgt.IdTerapeutico
		inner join farm_bitacoramedicinaexistenciaxarea fbem
		on fbem.IdMedicina=fcp.IdMedicina
		
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'
		".$comp."
		".$comp2."";
	$resp=mysql_query($SQL);
	return($resp);
   }
	
   function ObtenerBitacora($IdMedicina,$IdTeraputico,$FechaInicio,$FechaFin,$IdFarmacia){
		if($IdFarmacia!=0){$comp="and fbem.IdArea=".$IdFarmacia;}else{$comp="";}
                if($IdMedicina!=0){$comp2="and fcp.IdMedicina=".$IdMedicina;}else{$comp2="";}
                
	$SQL="select fcp.IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion,fbem.Existencia,Lote,Descripcion,UnidadesContenidas,
		Area,date_format(date(FechaHoraIngreso),'%d-%m-%Y') as FechaIngreso,
		date_format(FechaHoraIngreso,'%l:%i:%s %p') as HoraIngreso,IdExistenciaOrigen,IdTransferencia
		from farm_catalogoproductos fcp
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida=fcp.IdUnidadMedida
		inner join farm_bitacoramedicinaexistenciaxarea fbem
		on fbem.IdMedicina=fcp.IdMedicina
		inner join farm_lotes fl
		on fl.IdLote=fbem.IdLote
		inner join mnt_areafarmacia maf
		on maf.IdArea=fbem.IdArea
		left join farm_medicinaexistenciaxarea fmexa
		on fmexa.IdExistencia=fbem.IdExistenciaOrigen
		where date(FechaHoraIngreso) between '$FechaInicio' and '$FechaFin'
		and fcp.IdTerapeutico=".$IdTeraputico."
		".$comp."
                ".$comp2."
		order by Codigo,FechaIngreso";	
	$resp=mysql_query($SQL);
	return($resp);
   }


	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

        
        function Medicinas($IdTerapeutico){
            $query="select fcp.IdMedicina,Nombre,Concentracion,FormaFarmaceutica
                    from farm_catalogoproductos fcp
                    inner join farm_catalogoproductosxestablecimiento fcpxe
                    on fcpxe.IdMedicina = fcp.IdMedicina
                    where fcp.IdTerapeutico=".$IdTerapeutico;
            
            $resp=mysql_query($query);
            return $resp;
            
        }
        
}


?>
