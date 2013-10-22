<?php

class ReporteVencimiento{

function Establecimientos($IdEstablecimiento,$FechaInicio,$FechaFin){
if($IdEstablecimiento!=0){$comp=" and IdEstablecimiento=".$IdEstablecimiento;}else{$comp="";}
$SQL="select distinct IdEstablecimiento,Nombre
	from mnt_establecimiento me
	inner join farm_transferenciashospitales fth
	on fth.IdEstablecimientoDestino = me.IdEstablecimiento
	
	where FechaTransferencia between '$FechaInicio' and '$FechaFin'
	".$comp."
	order by IdEstablecimiento";
	$resp=mysql_query($SQL);
	return($resp);
}


function ObtenerInformacionTransferencia($IdEstablecimiento,$IdTerapeutico,$IdMedicina,$FechaInicio,$FechaFin){

$SQL="select fcp.IdMedicina,
	Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion,Descripcion,UnidadesContenidas,Lote,PrecioLote,FechaVencimiento,sum(Cantidad) as Total,((sum(Cantidad)/UnidadesContenidas)*PrecioLote) as Costo, date_format(FechaTransferencia,'%d-%m-%Y') as FechaTransferencia
	from farm_transferenciashospitales fth
	inner join farm_catalogoproductos fcp
	on fcp.IdMedicina=fth.IdMedicina
	inner join farm_lotes fl
	on fl.IdLote=fth.IdLote
	inner join farm_unidadmedidas fum
	on fum.IdUnidadMedida=fcp.IdUnidadMedida
		
		where FechaTransferencia between '$FechaInicio' and '$FechaFin'
		and IdTerapeutico=".$IdTerapeutico."
		and fth.IdMedicina=".$IdMedicina."
		and fth.IdEstablecimientoDestino=".$IdEstablecimiento."
		group by fth.IdMedicina
		order by fcp.Codigo";

	$resp=mysql_query($SQL);
	return($resp);

}


function ObtenerVencimientoProximo($IdTerapeutico,$IdMedicina,$FechaInicio,$FechaFin){

if($IdMedicina!=0){$comp2="and farm_catalogoproductos.IdMedicina=".$IdMedicina;}else{$comp2="";}



		$querySelect="select farm_catalogoproductos.Nombre,Concentracion,FormaFarmaceutica,Presentacion,sum(farm_medicinaexistenciaxarea.Existencia) as Existencia, PrecioLote,
					farm_lotes.Lote,farm_lotes.FechaVencimiento,farm_unidadmedidas.Descripcion,
					farm_unidadmedidas.UnidadesContenidas as Divisor
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where farm_lotes.FechaVencimiento between '$FechaInicio' and '$FechaFin'
$comp2
					group by farm_catalogoproductos.IdMedicina
			union

			select Nombre,Concentracion,FormaFarmaceutica,Presentacion,sum(farm_entregamedicamento.Existencia) as Existencia, PrecioLote,
					farm_lotes.Lote,farm_lotes.FechaVencimiento,farm_unidadmedidas.Descripcion,
					farm_unidadmedidas.UnidadesContenidas as Divisor
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_entregamedicamento.IdMedicina
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
$comp2
					where farm_lotes.FechaVencimiento between '$FechaInicio' and '$FechaFin'


				group by farm_catalogoproductos.IdMedicina";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerVencimientoProximo


function GrupoTerapeutico($IdTerapeutico){
	if($IdTerapeutico!=0){$comp="and IdTerapeutico=".$IdTerapeutico;}else{$comp="";}
   $SQL="select IdTerapeutico,GrupoTerapeutico from mnt_grupoterapeutico where GrupoTerapeutico <> '--' ".$comp;
	$resp=mysql_query($SQL);
	return($resp);
}

function MedicinasGrupo($IdTerapeutico,$IdEstablecimiento,$IdEstablecimientoDestino,$IdMedicina,$FechaInicio,$FechaFin){

	if($IdMedicina!=0){$comp=" and fcp.IdMedicina=".$IdMedicina;}else{$comp="";}

   $SQL="select distinct fcp.*
	from farm_catalogoproductos fcp
	inner join farm_catalogoproductosxestablecimiento fcpe
	on fcpe.IdMedicina=fcp.IdMedicina
	inner join farm_transferenciashospitales fth
	on fth.IdMedicina=fcp.IdMedicina
	where IdTerapeutico=".$IdTerapeutico."
	".$comp."
	and IdEstablecimientoDestino=".$IdEstablecimientoDestino."
	and Condicion='H'
	and IdEstablecimiento=".$IdEstablecimiento."
	and FechaTransferencia between '$FechaInicio' and '$FechaFin'
	order by Codigo";
   $resp=mysql_query($SQL);
   return($resp);
}

function MedicinasGrupo1($IdTerapeutico,$IdEstablecimiento){
   $SQL="select distinct fcp.*
	from farm_catalogoproductos fcp
	inner join farm_catalogoproductosxestablecimiento fcpe
	on fcpe.IdMedicina=fcp.IdMedicina

	where IdTerapeutico=".$IdTerapeutico."
	and Condicion='H'
	and IdEstablecimiento=".$IdEstablecimiento."
	order by Codigo";
   $resp=mysql_query($SQL);
   return($resp);
}

function ObtenerLotes($IdMedicina,$FechaInicio,$FechaFin){
	$SQL="select Lote,FechaVencimiento
		from farm_lotes l
		inner join farm_entregamedicamento fem
		on fem.IdLote=l.IdLote
		where FechaVencimiento between '$FechaInicio' and  '$FechaFin'
		and IdMedicina=".$IdMedicina."
		order by FechaVencimiento";
	$resp=mysql_query($SQL);
	return($resp);
}

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
	
}//ReporteTransferencias
?>