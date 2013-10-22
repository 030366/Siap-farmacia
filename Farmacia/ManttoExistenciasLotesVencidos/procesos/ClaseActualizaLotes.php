<?php
include('../Clases/class2.php');
class Actualiza{
function ObtenerMedicinaInformacion($IdMedicina,$Lote,$IdEstablecimiento){
	$querySelect="select farm_lotes.IdLote,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
				farm_lotes.PrecioLote,monthname(farm_lotes.FechaVencimiento) as mes,
				year(farm_lotes.FechaVencimiento) as ano, Existencia,
				farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
				from farm_catalogoproductos
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=farm_entregamedicamento.IdLote
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'
                                and IdEstablecimiento=$IdEstablecimiento
				and left(FechaVencimiento,7) > left(curdate(),7)
				and farm_lotes.Lote='$Lote'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp);
}//ObtenerMedicinaInformacion



}//clase Actualiza


?>