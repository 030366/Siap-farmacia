<?php
require('../Clases/class.php');

class Aviso{

	
function ObtenerInformacionVencimientoProximo($IdTerapeutico,$IdMedicina){

$SQL="select Codigo,farm_catalogoproductos.Nombre,Concentracion,FormaFarmaceutica,Presentacion, farm_unidadmedidas.Descripcion,
					farm_unidadmedidas.UnidadesContenidas as Divisor
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where left(farm_lotes.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)
and farm_catalogoproductos.IdMedicina=$IdMedicina
					group by farm_catalogoproductos.IdMedicina
			union

	select Codigo,farm_catalogoproductos.Nombre,Concentracion,FormaFarmaceutica,Presentacion, farm_unidadmedidas.Descripcion,
					farm_unidadmedidas.UnidadesContenidas as Divisor
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_entregamedicamento.IdMedicina
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida

					where left(farm_lotes.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)
and farm_catalogoproductos.IdMedicina=$IdMedicina

				group by farm_catalogoproductos.IdMedicina";

	$resp=mysql_query($SQL);
	return($resp);

}


	function ObtenerVencimientoProximo($IdTerapeutico,$IdMedicina){

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
					where left(farm_lotes.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)
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
					where left(farm_lotes.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)


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

function MedicinasGrupo($IdTerapeutico,$IdEstablecimiento){
   $SQL="select fcp.*
	from farm_catalogoproductos fcp
	inner join farm_catalogoproductosxestablecimiento fcpe
	on fcpe.IdMedicina=fcp.IdMedicina
	where IdTerapeutico=".$IdTerapeutico."
	and Condicion='H'
	and IdEstablecimiento=".$IdEstablecimiento;
   $resp=mysql_query($SQL);
   return($resp);
}


	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

	function ObtenerLotes($IdMedicina){
	$SQL="select distinct Lote
		from farm_lotes l
		inner join farm_entregamedicamento fem
		on fem.IdLote=l.IdLote
		where left(l.FechaVencimiento,7) between left(adddate(curdate(),interval 1 month),7) and left(adddate(curdate(),interval 4 month),7)
		and IdMedicina=".$IdMedicina."
                ";
	$resp=mysql_query($SQL);
	return($resp);
	}
	



}//Clase Aviso


?>